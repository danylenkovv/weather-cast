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

    /**
     * Retrieves data from the POST request.
     *
     * @param string $param The name of the parameter to retrieve.
     * @return string|null The value of the parameter if it exists, otherwise null.
     */
    public static function getPostData(string $data): string|null
    {
        return isset($_POST[$data]) ? $_POST[$data] : null;
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
            $result = [];
            foreach ($data as $d) {
                $result[] = $d[$field];
            }
            $average[$field] = round(array_sum($result) / count($result));
        }
        return $average;
    }
}
