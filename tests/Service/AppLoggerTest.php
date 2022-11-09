<?php

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use App\Service\AppLogger;

/**
 * Class ProductHandlerTest
 */
class AppLoggerTest extends TestCase
{

    public function testInfoLog()
    {
        // 我本机没改时区，其实一般这句都放在应用的入口，调用一次即可
        // date_default_timezone_set('PRC');

        AppLogger::printAllTypes(); // 打印支持的日志类型

        $logger = AppLogger::factory('log4php');

        $logger->info('This is info log message (log4php)');
        $logger->debug('This is debug log message (log4php)');
        $logger->error('This is error log message (log4php)');

        $logger = AppLogger::factory('think-log');
        $logger->info('This is info log message (think-log)');
        $logger->debug('This is debug log message (think-log)');
        $logger->error('This is error log message (think-log)');

        $this->assertTrue(true);
    }
}
