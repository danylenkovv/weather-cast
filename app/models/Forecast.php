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
     * Finds forecast data for a specific date.
     *
     * @param array $forecastDays The forecast days data.
     * @param string $date The date to look for.
     * @return array The forecast for the specified date.
     */
    private function findDayForecast(array $forecastDays, string $date): array
    {
        foreach ($forecastDays as $day) {
            if ($day['date'] === $date) {
                return ['forecastday' => [$day]];
            }
        }
        return [];
    }
}
