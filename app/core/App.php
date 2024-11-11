<?php

class App
{

    /**
     * Renders the specified view.
     *
     * @param string $page The name of the view to render.
     * @param array $data Data to pass to the view.
     * @param string $layout The name of the layouts to use.
     * @return void
     */
    public static function render(string $page, array $data = [], string $layout = 'default'): void
    {
        extract($data);
        require "app/views/layouts/{$layout}.php";
    }

    /**
     * Index action that render current weather
     *
     * @return void
     */
    public function index(): void
    {
        (new WeatherController())->current(Session::get('city'));
    }
    /**
     * Weekly action that render current weather and weekly forecast
     *
     * @return void
     */
    public function weekly(): void
    {
        //TODO Accessable only 7 days
        (new WeatherController())->weekly(Session::get('city'), 7);
    }
    /**
     * twoWeeks action that render current weather and two weeks forecast
     *
     * @return void
     */
    public function two_weeks(): void
    {
        //TODO Accessable only 14 days
        (new WeatherController())->weekly(Session::get('city'), 14);
    }

    /**
     * Daily action that render weather for a specified date
     *
     * @return void
     */
    public function daily(): void
    {
        (new WeatherController())->daily(Session::get('city'), Helpers::getUrlParam('date'), 14);
    }

    /**
     * Yesterday action tnat render forecast for the previous day
     *
     * @return void
     */
    public function yesterday(): void
    {
        (new WeatherController())->yesterday(Session::get('city'));
    }

    /**
     * Specific day action thet render forecast for a specific date.
     * The date is retrieved from the URL parameters.
     *
     * @return void
     */
    public function specific_day(): void
    {
        (new WeatherController())->specificDay(Session::get('city'), Helpers::getUrlParam('date'));
    }

    /**
     * Search city action that invokes the searchCity method - get all search results
     * The query is retrieved from the POST data.
     *
     * @return void
     */
    public function searchCity(): void
    {
        (new WeatherController())->searchCity(Helpers::getPostData('query'));
    }

    /**
     * Set city action that invokes the setCity method - change city in the session
     * The query is retrieved from the POST data.
     *
     * @return void
     */
    public function setCity(): void
    {
        (new WeatherController())->setCity(Helpers::getPostData('city'));
    }
}
