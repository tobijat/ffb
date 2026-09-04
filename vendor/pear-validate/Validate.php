<?php
/**
 * Minimal PEAR Validate-compatible helpers used by FFB.
 * Replaces the missing PEAR Validate package for PHP 8.
 */
class Validate
{
    public static function string($string, $options = array())
    {
        if (!is_string($string) && !is_numeric($string)) {
            return false;
        }
        $string = (string) $string;
        if (isset($options['min_length']) && strlen($string) < (int) $options['min_length']) {
            return false;
        }
        if (isset($options['max_length']) && strlen($string) > (int) $options['max_length']) {
            return false;
        }
        return true;
    }

    public static function email($email)
    {
        return (bool) filter_var((string) $email, FILTER_VALIDATE_EMAIL);
    }

    public static function number($number, $options = array())
    {
        if (!is_numeric($number)) {
            return false;
        }
        $number = $number + 0;
        if (isset($options['min']) && $number < $options['min']) {
            return false;
        }
        if (isset($options['max']) && $number > $options['max']) {
            return false;
        }
        return true;
    }
}
