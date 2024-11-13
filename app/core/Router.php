<?php

class Router
{
    private App $app;

    public function __construct()
    {
        $this->app = new App();
    }

    /**
     * Initializes the application by handling the request action.
     *
     * @return void
     */
    public function init(): void
    {
        Session::start();
        if (!Session::get('city')) {
            Session::set('city', 'Chernihiv');
        }
        $action = $this->getAction();

        if (method_exists($this->app, $action)) {
            $this->app->$action();
        } else {
            http_response_code(404);
            $this->app::render('errors', [
                'status_code' => 404,
                'error' => 'Not found',
                'description' => 'The requested URL was not found'
            ]);
        }
    }

    /**
     * Retrieves the action from the query parameters or defaults to 'index'.
     *
     * @return string The action name.
     */
    private function getAction(): string
    {
        return $_GET['action'] ?? 'index';
    }

    /**
     * Generates a URL for the specified action.
     *
     * @param string $action The action for which to generate the URL.
     * @return string The generated URL.
     */
    public static function url(string $action = 'index'): string
    {
        return "index.php?action=" . $action;
    }

    /**
     * Redirects the user to the specified URL.
     *
     * @param string $url The URL to redirect to.
     * @return never
     */
    public static function redirect(string $url): never
    {
        $url = self::url($url);
        header('Location: ' . $url);
        exit();
    }
}
