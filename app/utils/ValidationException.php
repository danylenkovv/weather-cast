<?php

namespace app\utils;

use Exception;

class ValidationException extends Exception
{
    private $description;

    public function __construct($message = "No errors", $code = 0, Exception $previous = null, $description = '')
    {
        $this->description = $description;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns error's description
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}
