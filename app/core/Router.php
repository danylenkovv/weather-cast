<?php

namespace app\core;

use app\controllers\WeatherController;
use app\core\View;
use app\core\Session;
use app\utils\Helpers;
use app\models\IpLookup;

class Router
{
    private WeatherController $controller;
    private array $urlComponents = [];

    public function __construct()
    {
        $this->controller = new WeatherController();
        $this->parseUrl();
    }

    /**
     * Init the app, handle URL.
     *
     * @return void
     */
    public function init(): void
    {
        Session::start();
        if (!Session::get('city')) {
            Session::set('city', (new IpLookup())->getLocationByIp(Helpers::getIp()));
        }

        $action = $this->getAction();

        if (method_exists($this->controller, $action)) {
            $this->controller->$action($this->getParams());
        } else {
            $this->notFound();
        }
    }

    /**
     * Parse URL into components.
     *
     * @return void
     */
    private function parseUrl(): void
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $this->urlComponents = explode('/', $url);

        if (!empty($this->urlComponents[0]) && $this->urlComponents[0] === 'index.php') {
            array_shift($this->urlComponents);
        }
    }

    /**
     * Get action from URL.
     *
     * @return string
     */
    private function getAction(): string
    {
        return empty($this->urlComponents[0]) ? 'current' : $this->urlComponents[0];
    }

    /**
     * Get params from URL.
     *
     * @return array
     */
    private function getParams(): array
    {
        return array_slice($this->urlComponents, 1);
    }

    /**
     * Generate URL for action.
     *
     * @param string $action
     * @param array $params - Some params, like date.
     * @return string Generated URL.
     */
    public static function url(string $action = 'current', array $params = []): string
    {
        $url = '/' . $action;
        if (!empty($params)) {
            $url .= '/' . implode('/', $params);
        }
        return $url;
    }

    /**
     * Redirect to specified URL.
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
     * Show 404 page.
     *
     * @return void
     */
    private function notFound(): void
    {
        http_response_code(404);
        View::render('errors', [
            'status_code' => 404,
            'error' => 'Not found',
            'description' => 'The requested URL was not found'
        ]);
    }
}
