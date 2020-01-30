<?php

namespace App\Log;

class Logger
{
    static public function getLogInfo(string $event = null, $data = null): void
    {
        $entry = date("Y-m-d H:i:s") . ": '$event' event info: '" . json_encode($data) . "'\n";
        file_put_contents('./log/' . date("Y.m.d") . '.log', $entry, FILE_APPEND);
    }
}

