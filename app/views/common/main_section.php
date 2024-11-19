<?php if (!empty($current)): ?>
    <div class="other-params">
        <h2 class="city"><?= $location['name'] . ', ' . $location['region'] . ", " . $location['country'] ?></h2>
        <p class="date">
            <?= $current['date'] ?>
            <?php if (isset($_GET['action']) && $_GET['action'] != 'yesterday' && $_GET['action'] != 'specific_day') : ?>
        <div>Updated at: <?= $last_updated['last_updated'] ?></div>
    <?php endif; ?>
    </p>
    <div class="weather-params">
        <div class="col">
            <span><i class="fa-solid fa-wind"></i> <?= $current['current']['wind_kph'] ?> kph</span>
            <span><i class="fa-solid fa-droplet"></i><?= $current['current']['humidity'] ?>%</span>
        </div>
        <div class="col">
            <span><i class="fa-solid fa-cloud-rain"></i><?= $current['current']['chance_of_rain'] ?>%</span>
            <span><i class="fa-regular fa-snowflake"></i><?= $current['current']['chance_of_snow'] ?>%</span>
        </div>
    </div>
    </div>
    <div class="temperature">
        <div class="icon">
            <img src="<?= $current['current']['icon'] ?>" alt="Weatger Icon" />
        </div>
        <div class="temp-value"><?= $current['current']['temp_c'] ?>&deg; C</div>
        <div class="temp-interval"><?= $current['daily_temps']['mintemp_c'] ?>&deg; C - <?= $current['daily_temps']['maxtemp_c'] ?>&deg; C</div>
    </div>
<?php else: ?>
    <div class="error-code"><?= $status_code . ' ' . $error ?></div>
<?php endif; ?>
