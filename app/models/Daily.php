<?php

class Daily extends Forecast
{
    /**
     * Retrieves the weather forecast for a specific date and city.
     *
     * @param string $date The date for the forecast in 'YYYY-MM-DD' format.
     * @param string $city The city for which the forecast is requested.
     * @return array The formatted forecast data, including location, date, daily, and hourly information.
     */
    public function getForecastByDate(string $date, string $city): array
    {
        $forecast = $this->getForecast($city, 14);
        $result = [];

        $result['location'] = $forecast['location'] ?? [];
        $result['date'] = Helpers::getFormattedDate($date);

        $dayForecast = $this->findDayForecast($forecast['forecast']['forecastday'] ?? [], $date);

        if (!empty($dayForecast)) {
            $result['day'] = $this->getRoundedDayValues($dayForecast['day']);
            $result['hour'] = $this->getRoundedHourlyValues($dayForecast['hour']);
        }

        return $result;
    }

    /**
     * Finds the forecast for a specific day from an array of forecast data.
     *
     * @param array $forecastDays The array of forecast data for multiple days.
     * @param string $date The date to find the forecast for.
     * @return array The forecast data for the specified date or an empty array if not found.
     */
    private function findDayForecast(array $forecastDays, string $date): array
    {
        $dayForecast = array_filter($forecastDays, fn($day) => $day['date'] === $date);
        return !empty($dayForecast) ? array_values($dayForecast)[0] : [];
    }

    /**
     * Rounds specified values in the daily forecast data.
     *
     * @param array $day The daily forecast data with various weather metrics.
     * @return array The daily forecast data with rounded values for specified keys.
     */
    private function getRoundedDayValues(array $day): array
    {
        return Helpers::roundValues($day, ['maxwind_kph', 'maxtemp_c', 'mintemp_c', 'avgtemp_c']);
    }

    /**
     * Rounds specified values in each hourly forecast entry.
     *
     * @param array $hours The hourly forecast data with temperature and wind metrics.
     * @return array The hourly forecast data with rounded values for specified keys.
     */
    private function getRoundedHourlyValues(array $hours): array
    {
        return array_map(
            function ($hour) {
                $roundedValues = Helpers::roundValues($hour, ['temp_c', 'feelslike_c', 'wind_kph']);
                $roundedValues['time'] = Helpers::getTime($hour['time']);
                return $roundedValues;
            },
            $hours
        );
    }
}
