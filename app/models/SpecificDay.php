<?php

namespace app\models;

use app\models\Model;
use app\utils\Helpers;

class SpecificDay extends Model
{
    private string $endpointHistory = 'history.json';
    private string $endpointFuture = 'future.json';

    /**
     * Fetches future weather forecast data for a specific city and date.
     *
     * @param string $city The name of the city.
     * @param string $date The date for which to retrieve the forecast.
     * @return array The future forecast data.
     */
    public function getFutureForecast(string $city, string $date): array
    {
        $url = $this->getApiUrl($this->endpointFuture, $city, $date);
        return $this->fetchData($url);
    }

    /**
     * Fetches historical weather forecast data for a specific city and date.
     *
     * @param string $city The name of the city.
     * @param string $date The date for which to retrieve the forecast.
     * @return array The historical forecast data.
     */
    public function getHistoryForecast(string $city, string $date): array
    {
        $url = $this->getApiUrl($this->endpointHistory, $city, $date);
        return $this->fetchData($url);
    }

    /**
     * Extracts and organizes specific weather data for a day, including location, daily average, and hourly data.
     *
     * @param array $forecast The forecast data.
     * @return array The organized weather data including location, daily average, and hourly data.
     */
    public function getSpecificDay($forecast): array
    {
        $current = $this->extractDailyAvgData($forecast['forecast']);
        $hours = $this->extractAllHours($forecast['forecast']['forecastday'][0]['hour'] ?? []);

        $avgData = Helpers::getAvgValue(['chance_of_rain', 'chance_of_snow'], $hours);
        $current['current']['chance_of_rain'] = $current['current']['chance_of_rain'] ?? $avgData['chance_of_rain'];
        $current['current']['chance_of_snow'] = $current['current']['chance_of_snow'] ?? $avgData['chance_of_snow'];

        return [
            'location' => $this->extractLocation($forecast['location']),
            'current' => $current,
            'hours' => $hours
        ];
    }

    /**
     * Retrieves specific forecast data based on the date comparison.
     *
     * @param SpecificDay $model The model instance to fetch forecast data.
     * @param string $city The city for which the forecast is retrieved.
     * @param string $date The date for the forecast in 'Y-m-d' format.
     * @return array|null Returns the forecast data or null if the date is within the current range.
     */
    public function getSpecificForecastData(SpecificDay $model, string $city, string $date): ?array
    {
        $dateComparison = Helpers::compareDates($date);

        return match ($dateComparison) {
            'past' => $model->getHistoryForecast($city, $date),
            'future' => $model->getFutureForecast($city, $date),
            default => null
        };
    }
}
