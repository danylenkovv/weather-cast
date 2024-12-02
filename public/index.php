<?php

try {
    require_once '../app/config/bootstrap.php';
} catch (ValidationException $e) {
    Helpers::handleException($e);
} catch (\Exception $e) {
    Helpers::handleException($e);
}
