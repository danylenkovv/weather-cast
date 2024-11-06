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
        //TODO Dynamic city change
        (new WeatherController())->current('Chernihiv');
    }
    /**
     * Weekly action that render current weather and weekly forecast
     *
     * @return void
     */
    public function weekly(): void
    {
        //TODO Accessable only 7 days
        (new WeatherController())->weekly('Chernihiv');
    }
    /**
     * twoWeeks action that render current weather and two weeks forecast
     *
     * @return void
     */
    public function twoWeeks(): void
    {
        //TODO Accessable only 14 days
        (new WeatherController())->weekly('Chernihiv', 14);
    }

    /**
     * Daily action that render weather for a specified date
     *
     * @return void
     */
    public function daily(): void
    {
        //TODO Accessable only 14 days
        (new WeatherController())->daily('Chernihiv', Helpers::getUrlParam('date'));
    }
}
