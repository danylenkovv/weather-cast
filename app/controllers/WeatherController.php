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
        $forecast = $model->getForecast($city);
        $currentWeather = $model->getCurrent($forecast);
        unset($forecast);

        App::render('daily', [
            'location' => $currentWeather['location'],
            'last_updated' => $currentWeather['last_updated'],
            'current' => $currentWeather['current'],
            'hours' => $currentWeather['hours']
        ]);
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
        $model = new Forecast();
        $forecast = $model->getForecast($city, $days);
        $weeklyWeather = $model->getWeekly($forecast);
        unset($forecast);

        App::render('weekly', [
            'location' => $weeklyWeather['location'],
            'last_updated' => $weeklyWeather['last_updated'],
            'current' => $weeklyWeather['current'],
            'days' => $weeklyWeather['days']
        ]);
    }

    /**
     * Displays the average weather and hourly forecast for the specified city and date.
     *
     * @param string $city The name of the city for which to display weather data.
     * @param string $date The date for which to display detailed weather data.
     * @param int $days The number of days for forecast - 14.
     * @return void
     */
    public function daily(string $city, string $date, int $days): void
    {
        $model = new Forecast();
        $forecast = $model->getForecast($city, $days);
        $dailyWeather = $model->getForecastByDate($forecast, $date);
        unset($forecast);

        App::render('daily', [
            'location' => $dailyWeather['location'],
            'last_updated' => $dailyWeather['last_updated'],
            'current' => $dailyWeather['current'],
            'hours' => $dailyWeather['hours']
        ]);
    }

    public function yesterday(string $city)
    {
        $model = new SpecificDay();
        $date = date('Y-m-d', strtotime('-1 day'));
        $forecast = $model->getHistoryForecast($city, $date);
        $specificDayWeather = $model->getSpecificDay($forecast);
        unset($forecast);

        App::render('daily', [
            'location' => $specificDayWeather['location'],
            'current' => $specificDayWeather['current'],
            'hours' => $specificDayWeather['hours']
        ]);
    }
}
