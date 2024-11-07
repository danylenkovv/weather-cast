<?php

class SpecificDay extends Forecast
{
    private string $endpointHistory = 'history.json';
    private string $endpointFuture = 'future.json';

    public function getFutureForecast(string $city, string $date): array
    {
        $url = $this->getApiUrl($this->endpointFuture, $city, $date);
        return $this->fetchData($url);
    }

    public function getHistoryForecast(string $city, string $date): array
    {
        $url = $this->getApiUrl($this->endpointHistory, $city, $date);
        return $this->fetchData($url);
    }

    public function getSpecificDay($forecast)
    {
        return [
            'location' => $this->extractLocation($forecast),
            'current' => $this->extractDailyAvgData($forecast['forecast']),
            'hours' => $this->extractAllHours($forecast['forecast']['forecastday'][0]['hour'] ?? [])
        ];
    }
}
