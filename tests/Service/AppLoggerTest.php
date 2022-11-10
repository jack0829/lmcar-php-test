<?php

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use App\Service\AppLogger;

/**
 * Class ProductHandlerTest
 */
class AppLoggerTest extends TestCase
{

    public static function setUpBeforeClass(): void
    {
        // 我本机没改时区，其实一般这句都放在应用的入口，调用一次即可
        // date_default_timezone_set('PRC');

        echo '全部日志类型：';
        AppLogger::printAllTypes(); // 打印支持的日志类型
    }


    // 工厂方式
    public function testLogFactory()
    {

        // log4php
        $logger = AppLogger::factory('log4php');
        $logger->info('This is info log message (log4php)');
        $logger->debug('This is debug log message (log4php)');
        $logger->error('This is error log message (log4php)');

        // think-log
        $logger = AppLogger::factory('think-log');
        $logger->info('This is info log message (think-log)');
        $logger->debug('This is debug log message (think-log)');
        $logger->error('This is error log message (think-log)');

        $this->assertTrue(true);
    }

    // 静态方式
    public function testLogStatic()
    {

        // default
        AppLogger::info('This is info log message (static default)');

        // log4php
        AppLogger::using('log4php');
        AppLogger::info('This is info log message (staticlog4php)');
        AppLogger::debug('This is debug log message (static log4php)');

        // think-log
        AppLogger::using('think-log');
        AppLogger::info('This is info log message (static think-log)');
        AppLogger::debug('This is debug log message (static think-log)');

        $this->assertTrue(true);
    }
}
