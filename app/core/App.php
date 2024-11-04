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
}
