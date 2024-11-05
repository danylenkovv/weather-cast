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

    /**
     * Displays the current weather and daily forecast for the specified city and days.
     *
     * @param string $city The name of the city for which to display weather data.
     * @param int $days The number of days for forecast - 7 or 14.
     * @return void
     */
    public function weekly(string $city, int $days = 7): void
    {
        $model = new Forecast();
        $currentWeather = $model->getCurrent($city);
        $weeklyWeather = $model->getWeekly($city, $days);

        App::render('weekly', [
            'current' => $currentWeather,
            'weekly' => $weeklyWeather
        ]);
    }
}
