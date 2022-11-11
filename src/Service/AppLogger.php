<?php

namespace App\Service;

use App\Service\Logger as L;
use App\Service\Logger\Adapter;
use Exception;

class AppLogger
{
    // 默认目录
    const BASE_PATH = './logs';

    // 日志类型映射，之后可以增加 regist 方法追加日志类型
    protected static array $types = [
        'log4php' => L\Log4php::class,
        'think-log' => L\ThinkLog::class,
    ];

    // 静态模式 默认日志类型
    protected static string $type = 'log4php';

    // 静态模式 日志适配器对象
    private static ?L\Adapter $logger = null;

    // 静态模式 执行方法
    public static function __callStatic($name, $arguments)
    {
        if (self::$logger === null) {
            self::$logger = self::factory(); // 工厂背后是个单例
        }

        if (!method_exists(self::$logger, $name)) {
            throw new Exception("日志方法不存在：$name");
        }

        return call_user_func_array([self::$logger, $name], $arguments);
    }

    // 静态模式 选择日志类型
    public static function using(string $type)
    {
        if (!array_key_exists($type, self::$types)) {
            throw new Exception("日志类型错误：$type");
        }

        self::$type = $type;
        self::$logger = self::factory();
    }

    // 工厂方法
    public static function factory(string $type = ''): Adapter
    {
        if (!array_key_exists($type, self::$types)) {
            $type = self::$type;
        }

        $class = self::$types[$type];

        return $class::inst();
    }

    /**
     * 注册新日志类型
     * @param string $class 日志类
     * @param string $name  日志类型名称
     */
    public static function regist(string $class, string $name)
    {

        if (!class_exists($class)) {
            throw new Exception("$class 不存在");
        }

        if (!is_subclass_of($class, L\Adapter::class)) {
            throw new Exception("$class 不是 Adapter 类型");
        }

        if (!array_key_exists($name, self::$types)) {
            self::$types[$name] = $class;
        }
    }

    public static function printAllTypes()
    {
        print_r(self::$types);
    }
}
