<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Weather Cast</title>
    <link rel="icon" href="<?= IMG_PATH . 'moon.png'; ?>" type="image/png">
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="<?= CSS_PATH . 'styles.css'; ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
</head>

<body>
    <header>
        <a href="index.php">
            <div class="full-logo">
                <div class="logo">
                    <img src="<?= IMG_PATH . 'moon.png'; ?>" alt="Moon Logo" />
                </div>
                <div class="logo-text"><span>WEATHER</span> <span>CAST</span></div>
            </div>
        </a>
        <div class="options">
            <div class="search">
                <div class="location">
                    <i class="fa-regular fa-paper-plane"></i>
                    <p><?= $current['location']['name'] ?></p>
                </div>
                <div><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>
            <div class="calendar"><i class="fa-solid fa-calendar-days"></i></div>
        </div>
    </header>
    <main>
        <div class="current">
            <div class="other-params">
                <h2 class="city"><?= $current['location']['name'] . ', ' . $current['location']['region'] . ", " . $current['location']['country'] ?></h2>
                <p class="date">
                    <?= $current['current']['formatted_date'] ?> <span>Updated at: <?= $current['current']['time'] ?></span>
                </p>
                <div class="weather-params">
                    <div class="col">
                        <span><i class="fa-solid fa-wind"></i> <?= $current['hour_forecast']['wind_kph'] ?> kph</span>
                        <span><i class="fa-solid fa-compass"></i><?= $current['hour_forecast']['wind_dir'] ?></span>
                        <span><i class="fa-solid fa-droplet"></i><?= $current['hour_forecast']['humidity'] ?>%</span>
                    </div>
                    <div class="col">
                        <span><i class="fa-solid fa-cloud"></i><?= $current['hour_forecast']['cloud'] ?>%</span>
                        <span><i class="fa-solid fa-cloud-rain"></i><?= $current['hour_forecast']['chance_of_rain'] ?>%</span>
                        <span><i class="fa-regular fa-snowflake"></i><?= $current['hour_forecast']['chance_of_snow'] ?>%</span>
                    </div>
                </div>
            </div>
            <div class="temperature">
                <div class="icon">
                    <img src="<?= $current['hour_forecast']['condition']['icon'] ?>" alt="Weatger Icon" />
                </div>
                <div class="temp-value"><?= $current['hour_forecast']['temp_c'] ?>&deg; C</div>
                <div class="temp-interval"><?= $current['daily']['mintemp_c'] ?>&deg; C - <?= $current['daily']['maxtemp_c'] ?>&deg; C</div>
            </div>
        </div>
        <div class="next">
            <div class="navigation">
                <div class="links">
                    <a href="#">Yesterday</a>
                    <a href="#" class="active">Today</a>
                    <a href="#">Weekly</a>
                    <a href="#">Two weeks</a>
                </div>
                <div class="buttons">
                    <i class="fa-solid fa-arrow-left" id="prev"></i>
                    <i class="fa-solid fa-arrow-right" id="next"></i>
                </div>
            </div>
            <div class="weather-items">
                <?php include_once(PAGES_PATH . $page . '.php'); ?>
            </div>
        </div>
    </main>
    <footer>
        <p>Powered by</p>
        <a href="https://www.weatherapi.com/">WeatherAPI.com</a>
    </footer>
    <div id="modalCalendar" class="modal"><?php include_once(MODALS_PATH . 'calendar.php'); ?></div>
    <div id="modalSearch" class="modal"><?php include_once(MODALS_PATH . 'search.php'); ?></div>
    <script src="<?= JS_PATH . 'scripts.js'; ?>"></script>
</body>

</html>
