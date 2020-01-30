<?php

namespace App\Rabbit;

use App\Log\Logger;
use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPRuntimeException;
use RuntimeException;

abstract class RabbitMQAbstract
{
    const CONNECT_EVENT = 'Event:connect';
    const CONNECT_ERROR_EVENT = 'Event:connectError';
    const SEND_EVENT = 'Event:send';
    const SEND_ERROR_EVENT = 'Event:sendError';
    const CLOSE_EVENT = 'Event:close';
    const CLOSE_ERROR_EVENT = 'Event:closeError';

    /**
     * @param AMQPStreamConnection
     */
    protected $connection;

    /**
     * @param AMQPChannel
     */
    protected $channel;

    /**
     * @param string
     */
    protected $queueName;

    /**
     * @param array $config
     * @param string $queueName
     */
    public function __construct(array $config, string $queueName)
    {
        $this->connection = $this->connect($config);
        $this->queueName = $queueName;
        $this->channel = $this->channel($this->connection);
    }

    /**
     * @param array $config
     * @return AMQPStreamConnection
     */
    protected function connect(array $config)
    {
        try {
            $connection = new AMQPStreamConnection($config['host'], $config['port'], $config['user'], $config['password']);
            Logger::getLogInfo(self::CONNECT_EVENT, $config);
            return $connection;
        } catch(AMQPRuntimeException $e) {
            Logger::getLogInfo(self::CONNECT_ERROR_EVENT, $e->getMessage());
        } catch(RuntimeException $e) {
            Logger::getLogInfo(self::CONNECT_ERROR_EVENT, $e->getMessage());
        } catch(Exception $e) {
            Logger::getLogInfo(self::CONNECT_ERROR_EVENT, $e->getMessage());
        }
    }

    /**
     * @param AMQPStreamConnection $connection
     * @return AMQPChannel
     */
    protected function channel(AMQPStreamConnection $connection): AMQPChannel
    {
        $channel = $connection->channel();
        $channel->queue_declare($this->queueName, false, false, false, false);

        return $channel;
    }

    function cleanupConnection()
    {
        try {
            if ($this->connection !== null) {
                $this->connection->close();
                Logger::getLogInfo(self::CLOSE_EVENT);
            }
        } catch (Exception $e) {
            Logger::getLogInfo(self::CLOSE_ERROR_EVENT, $e->getMessage());
        }
    }
}
