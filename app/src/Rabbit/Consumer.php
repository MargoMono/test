<?php

namespace App\Rabbit;

use App\Log\Logger;
use Exception;

class Consumer extends RabbitMQAbstract
{
    /**
     * @return mixed
     */
    public function getMessage()
    {
        try {
            $message = $this->channel->basic_get($this->queueName);
            Logger::info('Get message => ' . $message->body . ' from queue ' . $this->queueName);
            return $message;
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }

    public function ack($delivery_tag): void
    {
        try {
            $this->channel->basic_ack($delivery_tag);
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }
}
