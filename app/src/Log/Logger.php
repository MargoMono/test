<?php

namespace App\Log;

use Monolog\Logger as LoggerBase;
use Monolog\Handler\StreamHandler;

class Logger
{
    const INFO_EVENT = 'info';
    const DEBUG_EVENT = 'debug';
    const ERROR_EVENT = 'error';

    /**
     * @param mixed $message
     */
    public static function info($message)
    {
        self::getLogger(LoggerBase::DEBUG, self::DEBUG_EVENT)->info(json_encode($message));
    }

    /**
     * @param mixed $message
     */
    public static function debug($message)
    {
        self::getLogger(LoggerBase::DEBUG, self::DEBUG_EVENT)->debug(json_encode($message));
    }

    /**
     * @param mixed $message
     */
    public static function error($message)
    {
        self::getLogger(LoggerBase::ERROR, self::ERROR_EVENT)->error(json_encode($message));
    }

    /**
     * @param int $level
     * @param string $event
     * @return LoggerBase $logger
     */
    private static function getLogger(int $level, string $event): LoggerBase
    {
        $logger = new LoggerBase($event);
        $logger->pushHandler(new StreamHandler('logs/' . date("Y_m_d") . '.log', $level, false));
        return $logger;
    }
}
