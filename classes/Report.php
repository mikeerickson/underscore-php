<?php

// I use this to report errors from exceptions to myself.  or just about general alerts.  a barbaric logging system.

class Report {
    public static function write($type, $message, $to)
    {
        mail($to, $type, $message);
    }
}