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
     * @param int|null $days The number of days for forecast (optional).
     * @param string|null $date The specific date for weather data in YYYY-MM-DD format (optional).
     * @return string The constructed API URL.
     */
    public function getApiUrl(string $endpoint, string $city, ?int $days = null, ?string $date = null): string
    {
        $url = $this->apiUrl . $endpoint . '?key=' . $this->apiKey . '&q=' . $city;

        if ($date) {
            $url .= '&dt=' . $date;
        } elseif ($days) {
            $url .= '&days=' . $days;
        }

        return $url;
    }
}
