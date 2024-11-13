<div class="weather-items">
    <?php foreach ($days as $day): ?>
        <div class="item <?= date('Y-m-d') === $day['date'] ? 'active' : ''; ?>">
            <a href="<?= Router::url('daily&date=' . $day['date']) ?>" class="item-link">
                <div class="icon">
                    <img src="<?= $day['icon']; ?>" alt="Weather Icon" />
                </div>
                <div class="time">
                    <div class="weather-type"><?= $day['condition']; ?></div>
                    <div class="date-time"><?= $day['date']; ?></div>
                </div>
                <div class="temp-value">
                    <?= $day['maxtemp_c']; ?>&deg;
                    <span><?= $day['mintemp_c']; ?></span>
                </div>
                <div class="other-values">
                    <span><i class="fa-solid fa-wind"></i> <?= $day['maxwind_kph']; ?> kph,</span>
                    <span><i class="fa-solid fa-cloud-rain"></i> <?= $day['daily_chance_of_rain']; ?>%</span>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
