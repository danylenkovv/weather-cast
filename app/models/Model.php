<?php

class Model
{
    private string $apiUrl = BASE_URL;
    private string $apiKey = API_KEY;

    /**
     * Fetches data from the provided URL using cURL.
     *
     * @param string $url The URL to fetch data from.
     * @return array|null The decoded JSON response as an associative array, or null on failure.
     */
    protected function fetchData(string $url): ?array
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * Constructs the API URL with required query parameters.
     *
     * @param string $endpoint The API endpoint to access.
     * @param string $city The city name for which to fetch weather data.
     * @param mixed $dayOrDate The number of days for forecast or data in YYYY-MM-DD format (optional).
     * @return string The constructed API URL.
     */
    public function getApiUrl(string $endpoint, string $city, mixed $daysOrDate = null): string
    {
        $city = urlencode($city);
        $url = $this->apiUrl . $endpoint . '?key=' . $this->apiKey . '&q=' . $city;

        if (is_string($daysOrDate)) {
            $url .= '&dt=' . $daysOrDate;
        } elseif (is_int($daysOrDate)) {
            $url .= '&days=' . $daysOrDate;
        }

        return $url;
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
    protected function extractDailyAvgData(array $forecast): array
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
}
