<?php

namespace App\Rabbit;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

abstract class RabbitMQAbstract
{
    const CONNECT_EVENT = 'Event:connect';
    const CONNECT_ERROR_EVENT = 'Event:connectError';
    const SEND_EVENT = 'Event:send';
    const SEND_ERROR_EVENT = 'Event:sendError';

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
    abstract protected function connect(array $config);

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
            }
        } catch (\Exception $e) {
        }
    }

}