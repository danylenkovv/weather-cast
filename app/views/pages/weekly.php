<?php foreach ($weekly as $day): ?>
    <div class="item <?= date('Y-m-d') === $day['date'] ? 'active' : ''; ?>">
        <div class="icon">
            <img src="<?= $day['day']['condition']['icon']; ?>" alt="Weather Icon" />
        </div>
        <div class="time">
            <div class="weather-type"><?= $day['day']['condition']['text']; ?></div>
            <div class="date-time"><?= $day['date']; ?></div>
        </div>
        <div class="temp-value">
            <?= $day['day']['maxtemp_c']; ?>&deg;
            <span><?= $day['day']['mintemp_c']; ?></span>
        </div>
        <div class="other-values">
            <span><i class="fa-solid fa-wind"></i> <?= $day['day']['maxwind_kph']; ?> kph,</span>
            <span><i class="fa-solid fa-cloud-rain"></i> <?= $day['day']['daily_chance_of_rain']; ?>%</span>
        </div>
    </div>
<?php endforeach; ?>
