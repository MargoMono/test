<?php

require __DIR__ . '/vendor/autoload.php';

use App\Http\WebSender;
use App\Rabbit\Consumer;

$config = [
    'host' => 'rabbitmq',
    'port' => 5672,
    'user' => 'guest',
    'password' => 'guest',
];

$queueName = 'fxtm';

$consumer = new Consumer($config, $queueName);

while (1) {
    $limitCounters = 100;

    for ($i = 0; $i < $limitCounters; $i++) {

        $message = $consumer->getMessage();

        if (empty($message)) {
            var_dump('queue is empty, stop');
            break;
        }

        $consumer->ack($message->delivery_info['delivery_tag']);

        //Отправка сообщения
//        $send = new WebSender('url');
//        $send->send($message->body);

        var_dump('===============>  ' . $message->body);
    }

    sleep(1);
}
