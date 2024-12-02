<?php

namespace app\utils;

use DateTime;
use app\core\App;

class Helpers
{
    /**
     * Rounds specified numeric fields.
     *
     * @param array $data The data array with numeric fields.
     * @param array $fields The list of fields to round.
     * @return array The data with rounded fields.
     */
    public static function roundValues(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $data[$field] = round($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Extracts time (HH:MM) from a datetime string.
     *
     * @param string $datetime The datetime string.
     * @return string The time in HH:MM format.
     */
    public static function getTime(string $datetime): string
    {
        return explode(' ', $datetime)[1];
    }

    /**
     * Formats a datetime string to a readable date format.
     *
     * @param string $datetime The datetime string.
     * @return string The formatted date in "Day, Month DD, YYYY" format.
     */
    public static function getFormattedDate(string $datetime): string
    {
        $date = new DateTime($datetime);
        return $date->format('l, F j, Y');
    }
    /**
     * Retrieves a URL parameter from the GET request.
     *
     * @param string $param The name of the parameter to retrieve.
     * @return string|null The value of the parameter if it exists, otherwise null.
     */
    public static function getUrlParam(string $param): ?string
    {
        return isset($_GET[$param]) ? filter_input(INPUT_GET, $param, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;
    }

    /**
     * Retrieves data from the POST request.
     *
     * @param string $param The name of the parameter to retrieve.
     * @return string|null The value of the parameter if it exists, otherwise null.
     */
    public static function getPostData(string $data): string|null
    {
        return isset($_POST[$data]) ? filter_input(INPUT_POST, $data, FILTER_SANITIZE_FULL_SPECIAL_CHARS) : null;
    }

    /**
     * Calculate the average values for specified fields from an array of data.
     * @param array $fields The list of fields for which the average needs to be calculated.
     * @param array $data The data array containing the values to be averaged.
     * @return array An associative array where each key is a field and the value is its average.
     */
    public static function getAvgValue(array $fields, array $data): array
    {
        $average = [];
        foreach ($fields as $field) {
            $values = array_column($data, $field);
            $average[$field] = round(array_sum($values) / count($values));
        }
        return $average;
    }

    /**
     * Handles exceptions by rendering an error view.
     *
     * @param \Exception $e The exception to handle.
     */
    public static function handleException(\Exception $e): void
    {
        http_response_code($e->getCode());
        App::render('errors', [
            'status_code' => $e->getCode(),
            'error' => $e->getMessage(),
            'description' => $e->getDescription()
        ]);
    }

    /**
     * Compares a given date with the current date and determines its relative position.
     *
     * @param string $date The date to compare in 'Y-m-d' format.
     * @return string Returns 'past', 'future', or 'current' based on the comparison.
     */
    public static function compareDates(string $date): string
    {
        $currentDate = new DateTime();
        $selectedDate = new DateTime($date);
        $dateDifference = $currentDate->diff($selectedDate)->days;

        if ($selectedDate < $currentDate) {
            return 'past';
        }

        if ($dateDifference >= 13) {
            return 'future';
        }

        return 'current';
    }
    /**
     * Get real client IP
     * @return string
     */
    public static function getIp(): string
    {
        $ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
