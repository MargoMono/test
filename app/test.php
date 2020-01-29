<?php

require __DIR__ . '/vendor/autoload.php';

use App\Rabbit\Producer;

$config = [
    'host' => 'rabbitmq',
    'port' => 5672,
    'user' => 'guest',
    'password' => 'guest',
];

$queueName = 'fxtm';

$producer = new Producer($config, $queueName);

while (1) {
    $message = generateMessage(10);
    $producer->sendMessage(generateMessage(10));
}

function generateMessage($length)
{
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';

    for ($i = 0; $i < $length; $i++) {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }

    return $string;
}
