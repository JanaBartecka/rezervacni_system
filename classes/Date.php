<?php

class Date {

    public static function DateFromDBlessonStart($day, $time_start) {
        
        return new DateTime( $day . $time_start);

    }

    public static function DateFromDBapplyTo($day, $time_start, $time_apply_to) {
        
        $lessonStart = new DateTime( $day . $time_start);
        $nowMinusTimeToApply = new DateTime('now');
        $nowMinusTimeToApply->modify($time_apply_to);

        return $nowMinusTimeToApply;

    }

    public static function DateFromDBdate($day) {

        $date = new DateTime($day);
        $dateFormat = $date->format('j\.n\.');

        return $dateFormat;

    }

    public static function DateFromDBtimeStart($time_start) {

        $timeStart = new DateTime($time_start);
        $timeStartFormat = $timeStart->format('H:i');

        return $timeStartFormat;

    }

    public static function DateFromDBtimeEnd($time_end) {

        $timeEnd = new DateTime($time_end);
        $timeEndFormat = $timeEnd->format('H:i');

        return $timeEndFormat;

    }


}

?>