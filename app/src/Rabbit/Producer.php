<?php

namespace App\Rabbit;

use App\Log\Logger;
use Exception;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Message\AMQPMessage;

class Producer extends RabbitMQAbstract
{
    /**
     * @param string $message
     */
    public function sendMessage(string $message)
    {
        try {
            $this->channel->basic_publish(new AMQPMessage($message), '', $this->queueName);
        } catch(AMQPChannelClosedException $e) {
            Logger::getLogInfo(parent::SEND_ERROR_EVENT, $e->getMessage());
        } catch(Exception $e) {
            Logger::getLogInfo(parent::SEND_ERROR_EVENT, $e->getMessage());
        }
    }
}
