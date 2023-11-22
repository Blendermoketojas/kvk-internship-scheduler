<?php

namespace App\Exceptions;

use Exception;

class DateNotInRangeException extends Exception
{
    /**
     * Constructor for the DateNotInRangeException.
     *
     * @param string $date The date that caused the exception.
     * @param string $startDate The start date of the range.
     * @param string $endDate The end date of the range.
     * @param int $code The error code (optional).
     * @param Exception|null $previous Previous exception (optional).
     */
    public function __construct($date, $startDate, $endDate, $code = 0, Exception $previous = null)
    {
        $message = "The date $date is not between $startDate and $endDate.";
        parent::__construct($message, $code, $previous);
        // Additional initialization can be done here
    }
}
