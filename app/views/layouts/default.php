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
                    <p><?= $location['name'] ?></p>
                </div>
                <div><i class="fa-solid fa-magnifying-glass"></i></div>
            </div>
            <div class="calendar"><i class="fa-solid fa-calendar-days"></i></div>
        </div>
    </header>
    <main>
        <div class="current">
            <?php include_once(COMMON_PATH . 'main_section.php'); ?>
        </div>
        <div class="next">
            <div class="navigation">
                <?php include_once(COMMON_PATH . 'navigation.php'); ?>
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
