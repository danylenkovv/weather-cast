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
            <span><i class="fa-solid fa-wind"></i> <?= $hour['wind_kph']; ?> kph,</span>
            <span><i class="fa-solid fa-cloud-rain"></i> <?= $hour['chance_of_rain']; ?>%</span>
        </div>
    </div>
<?php endforeach; ?>
