<?php

namespace App\Service;

use App\Service\Logger as L;
use App\Service\Logger\Adapter;
use Exception;

class AppLogger
{

    // 日志类型映射，之后可以增加 regist 方法追加日志类型
    protected static array $types = [
        'log4php' => L\Log4php::class, // 第1项作为默认
        'think-log' => L\ThinkLog::class,
    ];

    // 日志适配器对象
    private static L\Adapter $logger;

    // 执行方法
    public static function __callStatic($name, $arguments)
    {
        if (!method_exists(self::$logger, $name)) {
            call_user_func_array([self::$logger, $name], $arguments);
        }
    }

    // 工厂方法
    public static function factory(string $type): Adapter
    {
        if (!array_key_exists($type, self::$types)) {
            $type = self::$types[0];
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
