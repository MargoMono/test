<?php

namespace App\Rabbit;

use App\Log\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;

class Consumer extends RabbitMQAbstract
{
    const WAIT_BEFORE_RECONNECT = 10;

    /**
     * @param array $config
     * @return AMQPStreamConnection
     */
    protected function connect(array $config): AMQPStreamConnection
    {
        $connection = null;

        while (true) {
            try {
                $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
//                Logger::getJornalInfo(parent::CONNECT_EVENT, $config);
                return $connection;
            } catch (AMQPRuntimeException $e) {
                Logger::getLogInfo(parent::SEND_ERROR_EVENT, $e->getMessage());
                $this->cleanupConnection();
                usleep(self::WAIT_BEFORE_RECONNECT);
            } catch (\RuntimeException $e) {
                Logger::getLogInfo(parent::SEND_ERROR_EVENT, $e->getMessage());
                $this->cleanupConnection();
                usleep(self::WAIT_BEFORE_RECONNECT);
            } catch (\Exception $e) {
                Logger::getLogInfo(parent::SEND_ERROR_EVENT, $e->getMessage());
                $this->cleanupConnection();
                usleep(self::WAIT_BEFORE_RECONNECT);
            }
        }
    }

    public function getMessage()
    {
        return $this->channel->basic_get($this->queueName);
    }

    public function ack($delivery_tag): void
    {
        $this->channel->basic_ack($delivery_tag);
     }
}
