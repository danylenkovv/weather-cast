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
        <?php include_once(COMMON_PATH . 'header.php'); ?>
    </header>
    <main>
        <div class="current">
            <?php include_once(COMMON_PATH . 'main_section.php'); ?>
        </div>
        <div class="next">
            <?php include_once(COMMON_PATH . 'navigation.php'); ?>
            <?php include_once(PAGES_PATH . $page . '.php'); ?>
        </div>
    </main>
    <footer>
        <p>Powered by</p>
        <a href="https://www.weatherapi.com/">WeatherAPI.com</a>
    </footer>
    <div id="modalCalendar" class="modal"><?php include_once(MODALS_PATH . 'calendar.php'); ?></div>
    <div id="modalSearch" class="modal"><?php include_once(MODALS_PATH . 'search.php'); ?></div>
    <script src="<?= JS_PATH . 'scripts.js'; ?>"></script>
    <script src="<?= JS_PATH . 'app.js'; ?>"></script>
</body>

</html>
