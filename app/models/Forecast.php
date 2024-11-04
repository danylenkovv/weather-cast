<?php

class Forecast extends Model
{
    private string $endpoint = 'forecast.json';

    /**
     * Retrieves weather forecast data for a specific city and number of days.
     *
     * @param string $city The name of the city.
     * @param int $days The number of forecast days.
     * @return array The forecast data.
     */
    public function getForecast(string $city, int $days = 1): array
    {
        $url = $this->getApiUrl($this->endpoint, $city, $days);
        return $this->fetchData($url);
    }

    /**
     * Retrieves the current weather details for the specified city.
     *
     * @param string $city The city name.
     * @return array The current weather data, including location, hourly forecast, and daily high/low temperatures.
     */
    public function getCurrent(string $city): array
    {
        $forecast = $this->getForecast($city, 1);

        return [
            'location' => $this->extractLocation($forecast),
            'current' => $this->formatCurrentWeather($forecast),
            'hour_forecast' => $this->getCurrentHourForecast($forecast),
            'daily' => $this->extractDailyForecast($forecast)
        ];
    }

    /**
     * Retrieves all hourly forecasts for the day for a specific city.
     *
     * @param string $city The city name.
     * @return array All hourly forecasts for the current day.
     */
    public function getAllDayHours(string $city): array
    {
        $forecast = $this->getForecast($city, 1);
        return $this->formatHourlyData($forecast['forecast']['forecastday'][0]['hour']);
    }

    /**
     * Extracts and formats location details from the forecast data.
     *
     * @param array $forecast The forecast data.
     * @return array The location information.
     */
    private function extractLocation(array $forecast): array
    {
        return [
            'name' => $forecast['location']['name'],
            'region' => $forecast['location']['region'],
            'country' => $forecast['location']['country']
        ];
    }

    /**
     * Formats the current weather data, including date and time.
     *
     * @param array $forecast The forecast data.
     * @return array Formatted current weather details.
     */
    private function formatCurrentWeather(array $forecast): array
    {
        $lastUpdated = $forecast['current']['last_updated'];
        return [
            'last_updated' => $lastUpdated,
            'time' => Helpers::getTime($lastUpdated),
            'formatted_date' => Helpers::getFormattedDate($lastUpdated)
        ];
    }

    /**
     * Retrieves the forecast for the current hour, rounded to the nearest values.
     *
     * @param array $forecast The forecast data.
     * @return array The current hour's weather details.
     */
    private function getCurrentHourForecast(array $forecast): array
    {
        $currentHour = date('H');
        $hourData = $forecast['forecast']['forecastday'][0]['hour'][$currentHour];

        return Helpers::roundValues($hourData, ['temp_c', 'wind_kph']);
    }

    /**
     * Extracts daily high and low temperatures, rounded to the nearest integer.
     *
     * @param array $forecast The forecast data.
     * @return array The daily high and low temperatures.
     */
    private function extractDailyForecast(array $forecast): array
    {
        $dayData = $forecast['forecast']['forecastday'][0]['day'];
        return [
            'maxtemp_c' => round($dayData['maxtemp_c']),
            'mintemp_c' => round($dayData['mintemp_c'])
        ];
    }

    /**
     * Formats and rounds all hourly data.
     *
     * @param array $hourlyData The hourly forecast data.
     * @return array The formatted hourly data with rounded values.
     */
    private function formatHourlyData(array $hourlyData): array
    {
        foreach ($hourlyData as &$hour) {
            $hour['time'] = Helpers::getTime($hour['time']);
            $hour = Helpers::roundValues($hour, ['temp_c', 'feelslike_c', 'wind_kph']);
        }
        return $hourlyData;
    }
}
