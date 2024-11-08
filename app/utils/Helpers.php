<?php

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
    public static function getUrlParam(string $param): string|null
    {
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }
}
