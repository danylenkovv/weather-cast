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
        $model = new Forecast();
        $currentWeather = $model->getCurrent($city);
        $hourlyWeather = $model->getAllDayHours($city);

        App::render('daily', [
            'current' => $currentWeather,
            'hours' => $hourlyWeather
        ]);
    }
}
