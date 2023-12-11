<?php 
/**
 * Luminova Framework
 *
 * @package Luminova
 * @author Ujah Chigozie Peter
 * @copyright (c) Nanoblock Technology Ltd
 * @license See LICENSE file
*/
namespace Luminova\ExtraUtils\Payment;

class Helper {

    /**
     * Format a mixed value as a currency with two decimal places.
     *
     * @param mixed $amount The value to be formatted as currency.
     *
     * @return float The formatted currency value.
    */
    public static function formatCurrency(mixed $amount): float 
    {
        return (float) number_format((float) $amount, 2, '.', '');
    }

   /**
     * Convert a mixed value to cents.
     *
     * @param mixed $amount The value to be converted to cents.
     *
     * @return float The value in cents.
    */
    public static function toCent(mixed $amount): float 
    {
        return (float) ($amount * 100);
    }

    
}
