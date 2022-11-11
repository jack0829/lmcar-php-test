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

        date_default_timezone_set('PRC');

        echo '全部日志类型：';
        AppLogger::printAllTypes(); // 打印支持的日志类型
    }

    // 工厂方式 log4php
    public function testLogFactoryLog4php()
    {

        $logger = AppLogger::factory('log4php');

        $info = sprintf('This is info message (%x) log4php', rand(0, 0xffff));
        $debug = sprintf('This is debug message (%x) log4php', rand(0, 0xffff));
        $error = sprintf('This is error message (%x) log4php', rand(0, 0xffff));

        $logger->info($info);
        $logger->debug($debug);
        $logger->error($error);

        $path = $logger->getPath();
        $this->assertFileExists($path, '日志没写进去');

        $content = file_get_contents($path);

        $this->assertStringContainsString($info, $content, 'log4php info 没写进去');
        $this->assertStringContainsString($debug, $content, 'log4php debug 没写进去');
        $this->assertStringContainsString($error, $content, 'log4php error 没写进去');
    }


    // 工厂方式 log4php
    public function testLogFactoryThinkLog()
    {

        $logger = AppLogger::factory('think-log');

        $info = sprintf('This is info message (%x) think-log', rand(0, 0xffff));
        $debug = sprintf('This is debug message (%x) think-log', rand(0, 0xffff));
        $error = sprintf('This is error message (%x) think-log', rand(0, 0xffff));

        $logger->info($info);
        $logger->debug($debug);
        $logger->error($error);

        $path = $logger->getPath();
        $this->assertFileExists($path, '日志没写进去');

        $content = file_get_contents($path);

        // 检查转换大写
        $this->assertStringContainsString(strtoupper($info), $content, 'think-log info 没写进去');
        $this->assertStringContainsString(strtoupper($debug), $content, 'think-log debug 没写进去');
        $this->assertStringContainsString(strtoupper($error), $content, 'think-log error 没写进去');
    }


    // 静态代理方式
    public function testLogStatic()
    {

        // default
        $info = sprintf('This is info message {%x} (static default)', rand(0, 0xffff));
        AppLogger::info($info);

        $path = AppLogger::getPath();
        $this->assertFileExists($path, '日志没写进去');

        $message = file_get_contents($path);
        $this->assertStringContainsStringIgnoringCase($info, $message, 'default info 没写进去');

        // log4php
        AppLogger::using('log4php');
        $debug = sprintf('This is debug message {%x} (static log4php)', rand(0, 0xffff));
        AppLogger::debug($debug);

        $path = AppLogger::getPath();
        $this->assertFileExists($path, '日志没写进去');

        $message = file_get_contents($path);
        $this->assertStringContainsString($debug, $message, 'log4php debug 没写进去');

        // think-log
        AppLogger::using('think-log');
        $error = sprintf('This is error message {%x} (static think-log)', rand(0, 0xffff));
        AppLogger::error($error);

        $path = AppLogger::getPath();
        $this->assertFileExists($path, '日志没写进去');

        $message = file_get_contents($path);
        $this->assertStringContainsString(strtoupper($error), $message, 'think-log error 没写进去');
    }
}
