<?php

class Form {
    /**
     * set the value to SQL database for time_apply_to
     * 
     * @param string $time_apply_to - time before the start of the lesson when user is able to apply
     * 
     * @return string value written into SQL
     */

     public static function setTimeApplyToDB($time_apply_to) {

        if ($time_apply_to === 'min30') {
            return '+30 minutes';
        } elseif ($time_apply_to === 'hour1') {
            return '+1 hour';
        } elseif ($time_apply_to === 'hour2') {
            return '+2 hours';
        } elseif ($time_apply_to === 'hour6') {
            return '+6 hours';
        } elseif ($time_apply_to === 'hour12') {
            return '+12 hours';
        } elseif ($time_apply_to === 'hour24') {
            return '+24 hours';
        } else {
            return '+0 hours';
        }
     }

    /**
     * get the value from SQL database for form
     * 
     * @param string $time_apply_to - time before the start of the lesson when user is able to apply
     * 
     * @return string value for form
     */

     public static function getTimeApplyToFromDB($time_apply_to) {

        if ($time_apply_to === '+30 minutes') {
            return 'min30';
        } elseif ($time_apply_to === '+1 hour') {
            return 'hour1';
        } elseif ($time_apply_to === '+2 hours') {
            return 'hour2';
        } elseif ($time_apply_to === '+6 hours') {
            return 'hour6';
        } elseif ($time_apply_to === '+12 hours') {
            return 'hour12';
        } elseif ($time_apply_to === '+24 hours') {
            return 'hour24';
        } else {
            return '';
        }
     }
}