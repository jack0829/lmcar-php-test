<?php

namespace App\Service\Logger;

use App\Service\Logger\Adapter;

/**
 * Log4php 适配器
 */
class Log4php implements Adapter
{
    private $logger;

    // 单例
    private static ?Log4php $instance = null;

    public function __construct(string $name)
    {
        $this->logger = \Logger::getLogger($name);
    }

    /**
     * @overwrite Adapter
     */
    public static function inst(): Adapter
    {
        if (self::$instance === null) {
            self::$instance = new self('log4php');
        }
        return self::$instance;
    }

    /**
     * @overwrite Adapter
     */
    public function info(string $message = '')
    {
        $this->logger->info($message);
    }

    /**
     * @overwrite Adapter
     */
    public function debug(string $message = '')
    {
        $this->logger->debug($message);
    }

    /**
     * @overwrite Adapter
     */
    public function error(string $message = '')
    {
        $this->logger->error($message);
    }
}
