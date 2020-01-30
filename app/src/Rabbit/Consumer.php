<?php

namespace App\Rabbit;

class Consumer extends RabbitMQAbstract
{
    public function getMessage()
    {
        return $this->channel->basic_get($this->queueName);
    }

    public function ack($delivery_tag): void
    {
        $this->channel->basic_ack($delivery_tag);
     }
}
