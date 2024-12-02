<?php

namespace app\utils;

use DateTime;
use app\utils\ValidationException;

class Validators
{
    /**
     * Validates the city name format.
     *
     * @param string $city The city name to validate.
     * @throws ValidationException If the city name is empty or contains invalid characters.
     */
    public static function validateCity(string $city): void
    {
        if (empty($city) || !preg_match('/^[a-zA-Z\s]+$/', $city)) {
            throw new ValidationException("Bad Request", 400, null, 'Incorrect city name used');
        }
    }

    /**
     * Validates the date input format and range.
     *
     * @param string $date The date to validate.
     * @throws ValidationException If the date is invalid or out of the allowed range.
     */
    public static function validateDate(string $date): void
    {
        $startDate = date('Y-m-d', strtotime('-1 year'));
        $endDate = date('Y-m-d', strtotime('+300 days'));
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateTime || $dateTime->format('Y-m-d') !== $date) {
            throw new ValidationException("Bad Request", 400, null, "Incorrect date input. Date must be existing and in yyyy-mm-dd format");
        }
        if ($date < $startDate || $date > $endDate) {
            throw new ValidationException("Bad Request", 400, null, "Incorrect date input. Date must be in range: 1 year ago - 300 days ahead");
        }
    }
}
