<div class="weather-items">
    <?php foreach ($hours as $hour): ?>
        <div class="item <?= date('H:00') === $hour['time'] ? 'active' : ''; ?>">
            <div class="icon">
                <img src="<?= $hour['icon']; ?>" alt="Weather Icon" />
            </div>
            <div class="time">
                <div class="weather-type"><?= $hour['condition']; ?></div>
                <div class="date-time"><?= $hour['time']; ?></div>
            </div>
            <div class="temp-value">
                <?= $hour['temp_c']; ?>&deg;
                <span><?= $hour['feelslike_c']; ?></span>
            </div>
            <div class="other-values">
                <div><i class="fa-solid fa-wind"></i> <?= $hour['wind_kph']; ?> kph,</div>
                <div><i class="fa-solid fa-cloud-rain"></i> <?= $hour['chance_of_rain']; ?> %,</div>
                <div><i class="fa-regular fa-snowflake"></i> <?= $hour['chance_of_snow']; ?> %</div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
