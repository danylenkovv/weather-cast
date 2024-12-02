<a href="/">
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
            <p><?= \app\core\Session::get('city') ?></p>
        </div>
        <div><i class="fa-solid fa-magnifying-glass"></i></div>
    </div>
    <div class="calendar"><i class="fa-solid fa-calendar-days"></i></div>
</div>
