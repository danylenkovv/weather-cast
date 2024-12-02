<?php

try {
    require_once '../app/bootstrap.php';
} catch (\Exception $e) {
    app\utils\Helpers::handleException($e);
}
