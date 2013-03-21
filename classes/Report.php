<?php

class Report {
    public static function write($type, $message, $to)
    {
        mail($to, $type, $message);
    }
}