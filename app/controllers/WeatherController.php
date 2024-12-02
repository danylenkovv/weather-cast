<?php

class WeatherController
{
    /**
     * Displays the current weather and hourly forecast for the specified city.
     *
     * @param string $city The name of the city for which to display weather data.
     * @return void
     */
    public function current(string $city): void
    {
        Validators::validateCity($city);

        $model = new Forecast();
        $forecast = $model->getForecast($city);

        App::render('daily', $model->getCurrent($forecast));
    }

    /**
     * Displays the current weather and daily forecast for the specified city and days.
     *
     * @param string $city The name of the city for which to display weather data.
     * @param int $days The number of days for forecast - 7 or 14.
     * @return void
     */
    public function weekly(string $city, int $days): void
    {
        Validators::validateCity($city);
        Validators::validateDays($days);

        $model = new Forecast();
        $forecast = $model->getForecast($city, $days);

        App::render('weekly', $model->getWeekly($forecast));
    }

    /**
     * Displays the average weather and hourly forecast for the specified city and date.
     *
     * @param string $city The name of the city for which to display weather data.
     * @param string $date The date for which to display detailed weather data.
     * @param int $days The number of days for forecast - 14.
     * @return void
     */
    public function daily(string $city, string $date): void
    {
        Validators::validateDate($date);
        Validators::validateCity($city);

        $model = new Forecast();
        $forecast = $model->getForecast($city, FORECAST_DAYS[1]);

        App::render('daily', $model->getForecastByDate($forecast, $date));
    }

    /**
     * Displays the weather forecast for the previous day.
     *
     * @param string $city The city for which the forecast is to be retrieved.
     */
    public function yesterday(string $city): void
    {
        Validators::validateCity($city);

        $model = new SpecificDay();
        $date = date('Y-m-d', strtotime('-1 day'));
        $forecast = $model->getHistoryForecast($city, $date);

        App::render('daily', $model->getSpecificDay($forecast));
    }

    /**
     * Displays the weather forecast for a specific date, adjusting based on whether the date is past, future, or near-future.
     *
     * @param string $city The city for which the forecast is to be retrieved.
     * @param string $date The specific date for the forecast in 'Y-m-d' format.
     */
    public function specificDay(string $city, string $date): void
    {
        Validators::validateDate($date);
        Validators::validateCity($city);

        $model = new SpecificDay();
        $forecast = $model->getSpecificForecastData($model, $city, $date);

        if (!$forecast) {
            Router::redirect("/daily/{$date}");
        }
        App::render('daily', $model->getSpecificDay($forecast));
    }

    /**
     * Handles the search for a city based on the provided query string.
     * Outputs the search results as a JSON response.
     *
     * @param string $query The city name or keyword to search for.
     * @return void
     */
    public function searchCity(string $query): void
    {
        header('Content-Type: application/json');

        if (empty($query)) {
            echo json_encode([]);
            exit();
        }

        $model = new Search();
        $result = $model->getSearchResults($query);

        echo json_encode($model->getCities($result));
        exit();
    }

    /**
     * Sets the specified city in the session.
     * Returns an HTTP status code of 200 if successful, or 400 if the city parameter is incorrect.
     *
     * @param string $city The name of the city to set in the session.
     * @return void
     */
    public function setCity(string $city): void
    {
        Validators::validateCity($city);
        Session::destroy();
        Session::start();
        Session::set('city', $city);
        http_response_code(200);
    }
}
