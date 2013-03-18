<?php

class Date
{
    public static function unixToSql($stamp) {
        if(!empty($stamp))
            $stamp = date("Y-m-j H:i", $stamp);
        else
            $stamp = "";
        return $stamp;
    }

    public static function get_ago_time($date)
    {
        // $date = "2011-12-17 17:45"
        // year-month-day hour:minute
        // echo $result = Agotime($date); // 2 days ago

        if(empty($date))
            return "No date provided";

        if(strlen($date) == 10)
            $date = Date::unixToSql($date);

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date      = strtotime($date);

        // check validity of date
        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        if($periods[$j] != 'seconds' && $periods[$j] != 'second')
            return "$difference $periods[$j] {$tense}";
        else
            return "just now";
    }
}