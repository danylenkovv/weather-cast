<?php if (isset($_GET['action']) && $_GET['action'] === 'daily'): ?>
    <div class="other-params">
        <h2 class="city"><?= $current['location']['name'] . ', ' . $current['location']['region'] . ", " . $current['location']['country'] ?></h2>
        <p class="date">
            <?= $current['date'] ?>
        </p>
        <div class="weather-params">
            <div class="col">
                <span><i class="fa-solid fa-wind"></i> <?= $current['day']['maxwind_kph'] ?> kph</span>
                <span><i class="fa-solid fa-droplet"></i><?= $current['day']['avghumidity'] ?>%</span>
            </div>
            <div class="col">
                <span><i class="fa-solid fa-cloud-rain"></i><?= $current['day']['daily_chance_of_rain'] ?>%</span>
                <span><i class="fa-regular fa-snowflake"></i><?= $current['day']['daily_chance_of_snow'] ?>%</span>
            </div>
        </div>
    </div>
    <div class="temperature">
        <div class="icon">
            <img src="<?= $current['day']['condition']['icon'] ?>" alt="Weatger Icon" />
        </div>
        <div class="temp-value"><?= $current['day']['avgtemp_c'] ?>&deg; C</div>
        <div class="temp-interval"><?= $current['day']['mintemp_c'] ?>&deg; C - <?= $current['day']['maxtemp_c'] ?>&deg; C</div>
    </div>
<?php else: ?>
    <div class="other-params">
        <h2 class="city"><?= $current['location']['name'] . ', ' . $current['location']['region'] . ", " . $current['location']['country'] ?></h2>
        <p class="date">
            <?= $current['current']['formatted_date'] ?> <span>Updated at: <?= $current['current']['time'] ?></span>
        </p>
        <div class="weather-params">
            <div class="col">
                <span><i class="fa-solid fa-wind"></i> <?= $current['hour_forecast']['wind_kph'] ?> kph</span>
                <span><i class="fa-solid fa-droplet"></i><?= $current['hour_forecast']['humidity'] ?>%</span>
            </div>
            <div class="col">
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
<?php endif; ?>
