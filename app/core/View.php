<?php

namespace app\core;

class View
{
    const VIEWS_DIR = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;

    public static $layout = 'default';

    public function __construct(string $layout = null)
    {
        if ($layout) {
            $this->layout = $layout;
        }
    }

    /**
     * Renders the specified view.
     *
     * @param string $page The name of the view to render.
     * @param array $data Data to pass to the view.
     * @return void
     */
    public static function render(string $page, array $data = []): void
    {
        extract($data);
        include_once self::VIEWS_DIR . 'layouts' . DIRECTORY_SEPARATOR . self::$layout . '.php';
        exit();
    }
}
