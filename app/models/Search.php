<?php

class Search extends Forecast
{
    private string $endpoint = 'search.json';

    public function getSearchResults(string $query): array
    {
        $url = $this->getApiUrl($this->endpoint, $query);
        return $this->fetchData($url);
    }

    public function getCities(array $results): array
    {
        $cities = [];
        foreach ($results as $result) {
            $cities[] = $this->extractLocation($result);
        }

        return $cities;
    }
}
