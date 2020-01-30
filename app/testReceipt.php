<?php

require __DIR__ . '/vendor/autoload.php';

use App\Http\WebSender;
use App\Log\Logger;
use App\Rabbit\Consumer;

$argv = getopt("f:", [
    "host::",
    "port::",
    "user::",
    "password::",
    "queue::",
    "batch-size::",
    "url::",
]);

$config = [
    'host' => $argv['host'],
    'port' => $argv['port'],
    'user' => $argv['user'],
    'password' => $argv['password'],
    'queue' => $argv['queue'],
    'batch-size' => $argv['batch-size'],
    'url' => $argv['url'],
];

foreach ($config as $key => $value) {
    if (empty($value)) {
        Logger::error('Incorrect script argument => ' . $key);
        exit;
    }
}

$consumer = new Consumer($config);

while (1) {
    $limitCounters = $config['batch-size'];
    $webSender = new WebSender($config['url']);

    for ($i = 0; $i < $limitCounters; $i++) {
        $message = $consumer->getMessage();

        if (empty($message)) {
            var_dump('queue is empty, stop');
            sleep(1);
            break;
        }

        try {
            $webSender->sendRequest($message->body);
            Logger::info('Message send' . $message->body);
        } catch (Exception $e) {
            var_dump('===============> oops, I found a problem');
            Logger::error($e->getMessage());
            break;
        }

        $consumer->ack($message->delivery_info['delivery_tag']);
        var_dump('===============>  ' . $message->body);
        sleep(0.01);
    }
    sleep(1);
}
