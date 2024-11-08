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

    /**
     * Displays the weather forecast for the previous day.
     *
     * @param string $city The city for which the forecast is to be retrieved.
     */
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

    /**
     * Displays the weather forecast for a specific date, adjusting based on whether the date is past, future, or near-future.
     *
     * @param string $city The city for which the forecast is to be retrieved.
     * @param string $date The specific date for the forecast in 'Y-m-d' format.
     */
    public function specificDay(string $city, string $date)
    {
        $model = new SpecificDay();
        $currentDate = new DateTime();
        $selectedDate = new DateTime($date);
        $dateDifference = $currentDate->diff($selectedDate)->days;

        if ($selectedDate < $currentDate) {
            $forecast = $model->getHistoryForecast($city, $date);
        } elseif ($dateDifference >= 13) {
            $forecast = $model->getFutureForecast($city, $date);
        } else {
            Router::redirect("daily&date={$date}");
            return;
        }

        $specificDayWeather = $model->getSpecificDay($forecast);
        unset($forecast);

        App::render('daily', [
            'location' => $specificDayWeather['location'],
            'current' => $specificDayWeather['current'],
            'hours' => $specificDayWeather['hours']
        ]);
    }
}
