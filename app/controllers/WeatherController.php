<?php

namespace app\controllers;

use app\models\Forecast;
use app\utils\Validators;
use app\core\Session;
use app\models\SpecificDay;
use app\models\Search;
use app\core\Router;
use app\utils\Helpers;
use app\core\View;

class WeatherController
{
    protected ?string $city;
    protected Forecast $forecastModel;
    protected Search $searchModel;
    protected SpecificDay $specificDayModel;
    protected View $view;

    public function __construct()
    {
        Session::start();
        $this->city = Session::get('city');
        Validators::validateCity($this->city);
        $this->forecastModel = new Forecast();
        $this->searchModel = new Search();
        $this->specificDayModel = new SpecificDay();
        $this->view = new View();
    }

    /**
     * Displays the current weather and hourly forecast for the specified city.
     * @return void
     */
    public function current(): void
    {
        $forecast = $this->forecastModel->getForecast($this->city);

        $this->view::render('daily', $this->forecastModel->getCurrent($forecast));
    }

    /**
     * Displays the current weather and weekly forecast for the specified city.
     * @return void
     */
    public function weekly(): void
    {
        $forecast = $this->forecastModel->getForecast($this->city, FORECAST_DAYS[0]);

        $this->view::render('weekly', $this->forecastModel->getWeekly($forecast));
    }

    /**
     * Displays the current weather and two-weeks forecast for the specified city.
     * @return void
     */
    public function two_weeks(): void
    {
        $forecast = $this->forecastModel->getForecast($this->city, FORECAST_DAYS[1]);

        $this->view::render('weekly', $this->forecastModel->getWeekly($forecast));
    }

    /**
     * Displays the average weather and hourly forecast for the specified city and date.
     * @param array $params The date for the forecast in 'Y-m-d' format.
     * @return void
     */
    public function daily(array $params): void
    {
        $date = $params[0];
        Validators::validateDate($date);

        if (Helpers::compareDates($date) != 'current') {
            Router::redirect("/specific_day/{$date}");
        }

        $forecast = $this->forecastModel->getForecast($this->city, FORECAST_DAYS[1]);

        $this->view::render('daily', $this->forecastModel->getForecastByDate($forecast, $date));
    }

    /**
     * Displays the weather forecast for the previous day.
     * @return void
     */
    public function yesterday(): void
    {
        $date = date('Y-m-d', strtotime('-1 day'));
        $forecast = $this->specificDayModel->getHistoryForecast($this->city, $date);

        $this->view::render('daily', $this->specificDayModel->getSpecificDay($forecast));
    }

    /**
     * Displays the weather forecast for a specific date, adjusting based on whether the date is past, future, or near-future.
     * @param array $params The specific date for the forecast in 'Y-m-d' format.
     * @return void
     */
    public function specific_day(array $params): void
    {
        $date = $params[0];
        Validators::validateDate($date);

        $forecast = $this->specificDayModel->getSpecificForecastData($this->specificDayModel, $this->city, $date);

        if (!$forecast) {
            Router::redirect("/daily/{$date}");
        }
        $this->view::render('daily', $this->specificDayModel->getSpecificDay($forecast));
    }

    /**
     * Handles the search for a city based on the provided query string.
     * Outputs the search results as a JSON response.
     * @return void
     */
    public function searchCity(): void
    {
        header('Content-Type: application/json');
        $query = Helpers::getPostData('query');

        if (empty($query)) {
            echo json_encode([]);
            exit();
        }

        $result = $this->searchModel->getSearchResults($query);

        echo json_encode($this->searchModel->getCities($result));
        exit();
    }

    /**
     * Sets the specified city in the session.
     * Returns an HTTP status code of 200 if successful, or 400 if the city parameter is incorrect.
     * @return void
     */
    public function setCity(): void
    {
        $city = Helpers::getPostData('city');
        Validators::validateCity($city);
        Session::destroy();
        Session::start();
        Session::set('city', $city);
        http_response_code(200);
    }
}
