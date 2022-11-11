<?php

namespace App\Service\Logger;

use App\Service\Logger\Adapter;

/**
 * Log4php 适配器
 */
class Log4php implements Adapter
{
    private $logger;
    private string $path;

    // 单例
    private static ?Log4php $instance = null;

    public function __construct(string $path)
    {

        $this->path = $path . DIRECTORY_SEPARATOR . 'log4php.log';
        // echo 'Log4PHP: ', $this->path, PHP_EOL;

        // 配置太多，不封装了，这个测试目的应该也不是为了看这些 ^_^
        \Logger::configure(array(
            'rootLogger' => array(
                'appenders' => array('default'),
            ),
            'appenders' => array(
                'default' => array(
                    'class' => 'LoggerAppenderFile',
                    'layout' => array(
                        'class' => 'LoggerLayoutSimple'
                    ),
                    'params' => array(
                        'file' => $this->path,
                        'append' => true
                    )
                )
            )
        ));
        $this->logger = \Logger::getLogger('default');
    }

    /**
     * @overwrite Adapter
     */
    public static function inst(): Adapter
    {
        if (self::$instance === null) {
            self::$instance = new self(Config::BASE_DIR);
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

    /**
     * @overwrite Adapter
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
