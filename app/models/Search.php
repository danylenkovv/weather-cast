<?php

class Search extends Model
{
    private string $endpoint = 'search.json';

    /**
     * Retrieves search results from the weather API based on the provided query.
     *
     * @param string $query The search query string, usually a city name or location keyword.
     * @return array An array containing the raw search results from the API.
     */
    public function getSearchResults(string $query): array
    {
        $url = $this->getApiUrl($this->endpoint, $query);
        return $this->fetchData($url);
    }

    /**
     * Extracts city information from the search results.
     *
     * @param array $results An array of search result data returned by the API.
     * @return array An array of formatted city information extracted from the search results.
     */
    public function getCities(array $results): array
    {
        $cities = [];
        foreach ($results as $result) {
            $cities[] = $this->extractLocation($result);
        }

        return $cities;
    }
}
