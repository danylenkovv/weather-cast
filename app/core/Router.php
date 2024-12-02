<?php

namespace app\core;

use app\core\Session;
use app\utils\Helpers;
use app\models\IpLookup;

class Router
{
    private App $app;
    private array $urlComponents = [];

    public function __construct()
    {
        $this->app = new App();
        $this->parseUrl();
    }

    /**
     * Ініціалізує додаток, обробляючи URL.
     *
     * @return void
     */
    public function init(): void
    {
        Session::start();

        // Встановлюємо місто за IP, якщо ще не вибрано.
        if (!Session::get('city')) {
            Session::set('city', (new IpLookup())->getLocationByIp(Helpers::getIp()));
        }

        $action = $this->getAction();

        if (method_exists($this->app, $action)) {
            // Виклик методу `App`.
            $this->app->$action($this->getParams());
        } else {
            $this->notFound();
        }
    }

    /**
     * Розбирає URL на компоненти.
     *
     * @return void
     */
    private function parseUrl(): void
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $this->urlComponents = explode('/', $url);

        // Видаляємо перший рівень, якщо це підкаталог.
        if (!empty($this->urlComponents[0]) && $this->urlComponents[0] === 'index.php') {
            array_shift($this->urlComponents);
        }
    }

    /**
     * Отримує дію (action) з URL.
     *
     * @return string
     */
    private function getAction(): string
    {
        return empty($this->urlComponents[0]) ? 'index' : $this->urlComponents[0];
    }

    /**
     * Отримує параметри з URL.
     *
     * @return array
     */
    private function getParams(): array
    {
        return array_slice($this->urlComponents, 1);
    }

    /**
     * Генерує URL для дії.
     *
     * @param string $action Дія (action).
     * @param array $params Параметри.
     * @return string Сформований URL.
     */
    public static function url(string $action = 'index', array $params = []): string
    {
        $url = '/' . $action;
        if (!empty($params)) {
            $url .= '/' . implode('/', $params);
        }
        return $url;
    }

    /**
     * Перенаправляє користувача на вказаний URL.
     *
     * @param string $url
     * @return never
     */
    public static function redirect(string $url = '/'): never
    {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Показує сторінку 404.
     *
     * @return void
     */
    private function notFound(): void
    {
        http_response_code(404);
        $this->app::render('errors', [
            'status_code' => 404,
            'error' => 'Not found',
            'description' => 'The requested URL was not found'
        ]);
    }
}
