<?php

namespace App\Log;

class Logger
{
    static public function getJornalInfo(string $event = null, $data = null): void
    {
//        работа с бд
    }

    static public function getLogInfo(string $event = null, $data = null): void
    {
        $entry = date("Y-m-d H:i:s") . ": '$event' event info: '" . json_encode($data) . "'\n";
        file_put_contents(__DIR__ . "/log.txt", $entry, FILE_APPEND);
    }
}

