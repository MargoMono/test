<?php

namespace App\Rabbit;

use App\Log\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use PhpAmqpLib\Message\AMQPMessage;

class Producer extends RabbitMQAbstract
{
    /**
     * @param array $config
     * @return AMQPStreamConnection
     */
    protected function connect(array $config)
    {
        try {
            $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
//            Logger::getJornalInfo(parent::CONNECT_EVENT, $config);
            return $connection;
        } catch(AMQPRuntimeException $e) {
            Logger::getLogInfo(parent::CONNECT_ERROR_EVENT, $e->getMessage());
        } catch(\RuntimeException $e) {
            Logger::getLogInfo(parent::CONNECT_ERROR_EVENT, $e->getMessage());
        } catch(\Exception $e) {
            Logger::getLogInfo(parent::CONNECT_ERROR_EVENT, $e->getMessage());
        }
    }

    /**
     * @param string $message
     */
    public function sendMessage(string $message)
    {
        try {
            $this->channel->basic_publish(new AMQPMessage($message), '', $this->queueName);
//             Logger::getJornalInfo(self::SEND_EVENT, $message);
        } catch(AMQPChannelClosedException $e) {
            Logger::getLogInfo(self::SEND_ERROR_EVENT, $e->getMessage());
        } catch(\Exception $e) {
            Logger::getLogInfo(self::SEND_ERROR_EVENT, $e->getMessage());
        }
    }
}
