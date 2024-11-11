<?php

class Forecast extends Model
{
    private string $endpoint = 'forecast.json';

    /**
     * Retrieves forecast data for a specific city and number of days.
     *
     * @param string $city The name of the city to retrieve the forecast for.
     * @param int $days The number of days to retrieve the forecast for.
     * @return array The forecast data as an associative array.
     */
    public function getForecast(string $city, int $days = 1): array
    {
        $url = $this->getApiUrl($this->endpoint, $city, $days);
        return $this->fetchData($url);
    }

    /**
     * Retrieves current weather details from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The current weather details.
     */
    public function getCurrent(array $forecast): array
    {
        return [
            'location' => $this->extractLocation($forecast['location']),
            'last_updated' => $this->extractLastUpdated($forecast),
            'current' => $this->extractCurrentWeather($forecast),
            'hours' => $this->extractAllHours($forecast['forecast']['forecastday'][0]['hour'])
        ];
    }

    /**
     * Retrieves weekly forecast details from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The weekly forecast details.
     */
    public function getWeekly(array $forecast): array
    {
        return [
            'location' => $this->extractLocation($forecast['location']),
            'last_updated' => $this->extractLastUpdated($forecast),
            'current' => $this->extractCurrentWeather($forecast),
            'days' => $this->extractAllDays($forecast['forecast']['forecastday'])
        ];
    }

    /**
     * Retrieves forecast details for a specific date from forecast data.
     *
     * @param array $forecast The forecast data.
     * @param string $date The date for which to retrieve forecast details.
     * @return array The forecast details for the specified date.
     */
    public function getForecastByDate(array $forecast, string $date): array
    {
        $dayForecast = $this->findDayForecast($forecast['forecast']['forecastday'] ?? [], $date);
        return [
            'location' => $this->extractLocation($forecast['location']),
            'last_updated' => $this->extractLastUpdated($forecast),
            'current' => $this->extractDailyAvgData($dayForecast),
            'hours' => $this->extractAllHours($dayForecast['forecastday'][0]['hour'] ?? [])
        ];
    }

    /**
     * Extracts location information from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The location details.
     */
    protected function extractLocation(array $forecast): array
    {
        return [
            'name' => $forecast['name'],
            'region' => $forecast['region'],
            'country' => $forecast['country']
        ];
    }

    /**
     * Extracts the last updated time from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The last updated time.
     */
    protected function extractLastUpdated(array $forecast): array
    {
        return ['last_updated' => Helpers::getTime($forecast['current']['last_updated'])];
    }

    /**
     * Extracts current weather information from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The current weather details.
     */
    protected function extractCurrentWeather(array $forecast): array
    {
        return [
            'date' => Helpers::getFormattedDate($forecast['forecast']['forecastday'][0]['date']),
            'daily_temps' => Helpers::roundValues([
                'mintemp_c' => $forecast['forecast']['forecastday'][0]['day']['mintemp_c'],
                'maxtemp_c' => $forecast['forecast']['forecastday'][0]['day']['maxtemp_c']
            ], ['mintemp_c', 'maxtemp_c']),
            'current' => $this->extractCurrentHour($forecast)
        ];
    }

    /**
     * Extracts current hour weather information from forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The current hour weather details.
     */
    protected function extractCurrentHour(array $forecast): array
    {
        $currentHour = $forecast['forecast']['forecastday'][0]['hour'][date('G')];
        return Helpers::roundValues([
            'temp_c' => $currentHour['temp_c'],
            'wind_kph' => $currentHour['wind_kph'],
            'condition' => $currentHour['condition']['text'],
            'icon' => $currentHour['condition']['icon'],
            'humidity' => $currentHour['humidity'],
            'chance_of_rain' => $currentHour['chance_of_rain'],
            'chance_of_snow' => $currentHour['chance_of_snow']
        ], ['temp_c', 'wind_kph']);
    }

    /**
     * Extracts hourly weather details from forecast data.
     *
     * @param array $hourlyData The hourly forecast data.
     * @return array The formatted hourly weather details.
     */
    protected function extractAllHours(array $hourlyData): array
    {
        $formattedHours = [];
        foreach ($hourlyData as $hour) {
            $formattedHours[] = Helpers::roundValues([
                'time' => Helpers::getTime($hour['time']),
                'temp_c' => $hour['temp_c'],
                'condition' => $hour['condition']['text'],
                'icon' => $hour['condition']['icon'],
                'wind_kph' => $hour['wind_kph'],
                'humidity' => $hour['humidity'],
                'feelslike_c' => $hour['feelslike_c'],
                'chance_of_rain' => $hour['chance_of_rain'],
                'chance_of_snow' => $hour['chance_of_snow']
            ], ['temp_c', 'wind_kph', 'feelslike_c']);
        }
        return $formattedHours;
    }

    /**
     * Extracts daily weather details from forecast data.
     *
     * @param array $dailyData The daily forecast data.
     * @return array The formatted daily weather details.
     */
    protected function extractAllDays(array $dailyData): array
    {
        $formattedDays = [];
        foreach ($dailyData as $day) {
            $formattedDays[] = Helpers::roundValues([
                'date' => $day['date'],
                'maxtemp_c' => $day['day']['maxtemp_c'],
                'mintemp_c' => $day['day']['mintemp_c'],
                'maxwind_kph' => $day['day']['maxwind_kph'],
                'daily_chance_of_rain' => $day['day']['daily_chance_of_rain'],
                'daily_chance_of_snow' => $day['day']['daily_chance_of_snow'],
                'condition' => $day['day']['condition']['text'],
                'icon' => $day['day']['condition']['icon']
            ], ['maxtemp_c', 'mintemp_c', 'maxwind_kph']);
        }
        return $formattedDays;
    }

    /**
     * Extracts daily average weather data from a specified day's forecast.
     *
     * @param array $forecast The specific day's forecast data.
     * @return array The average daily weather details.
     */
    public function extractDailyAvgData(array $forecast): array
    {
        $dayData = $forecast['forecastday'][0];
        return [
            'date' => Helpers::getFormattedDate($dayData['date']),
            'current' => Helpers::roundValues([
                'temp_c' => $dayData['day']['avgtemp_c'],
                'wind_kph' => $dayData['day']['maxwind_kph'],
                'humidity' => $dayData['day']['avghumidity'],
                'chance_of_rain' => $dayData['day']['daily_chance_of_rain'],
                'chance_of_snow' => $dayData['day']['daily_chance_of_snow'],
                'condition' => $dayData['day']['condition']['text'],
                'icon' => $dayData['day']['condition']['icon']
            ], ['temp_c', 'wind_kph']),
            'daily_temps' => Helpers::roundValues([
                'maxtemp_c' => $dayData['day']['maxtemp_c'],
                'mintemp_c' => $dayData['day']['mintemp_c']
            ], ['maxtemp_c', 'mintemp_c'])
        ];
    }

    /**
     * Finds forecast data for a specific date.
     *
     * @param array $forecastDays The forecast days data.
     * @param string $date The date to look for.
     * @return array The forecast for the specified date.
     */
    protected function findDayForecast(array $forecastDays, string $date): array
    {
        foreach ($forecastDays as $day) {
            if ($day['date'] === $date) {
                return ['forecastday' => [$day]];
            }
        }
        return [];
    }
}
