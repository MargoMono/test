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
     */
    public function __construct(array $config)
    {
        $this->connection = $this->connect($config);
        $this->queueName = $config['queue'];
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
            Logger::info($config);
            return $connection;
        } catch (AMQPRuntimeException $e) {
            Logger::error($e->getMessage());
        } catch (RuntimeException $e) {
            Logger::error($e->getMessage());
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }

    /**
     * @param AMQPStreamConnection $connection
     * @return AMQPChannel
     */
    protected function channel(AMQPStreamConnection $connection): AMQPChannel
    {
        try {
            $channel = $connection->channel();
            $channel->queue_declare($this->queueName, false, false, false, false);
            return $channel;
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }

    function cleanupConnection()
    {
        try {
            if ($this->connection !== null) {
                $this->connection->close();
                Logger::info('cleanupConnection');
            }
        } catch (Exception $e) {
            Logger::error($e->getMessage());
        }
    }
}
