<?php

namespace App\Service\Logger;

use App\Service\Logger\Adapter;
use think\facade\Log;

/**
 * ThinkLog 适配器
 */
class ThinkLog implements Adapter
{

    // Think 框架很多方法封装的不好
    // 如果不用单文件模式，getMasterLogFile 是个 protected，不能使用
    // 所以拿不到真实的日志文件 path
    // 为了过单元测试，先用 single 模式吧
    const SINGLE_LOG_NAME = 'think-log';

    // 单例
    private static ?ThinkLog $instance = null;

    public function __construct(string $path)
    {
        Log::init([
            'default'    =>    'file',
            'channels'    =>    [
                'file'    =>    [
                    'type'    =>    'file',
                    'path' => $path,
                    'single' => self::SINGLE_LOG_NAME,

                    // 使用 ThinkLog 本身的处理器
                    'processor' => function (array $event, $type) {
                        foreach ($event as $level => &$logs) {

                            // if ($level == 'debug') {
                            //     return $event;
                            // }

                            array_walk($logs, function (&$s) {
                                $s = strtoupper($s); // 全转大写
                            });
                        }
                        unset($logs);
                        return $event;
                    },
                ],
            ],
        ]);
    }

    /**
     * @overwrite Adapter
     */
    public static function inst(): Adapter
    {
        if (self::$instance === null) {
            self::$instance = new self(Config::BASE_DIR, 'think-log');
        }

        return self::$instance;
    }

    /**
     * @overwrite Adapter
     */
    public function info(string $message = '')
    {
        Log::info($message);
    }

    /**
     * @overwrite Adapter
     */
    public function debug(string $message = '')
    {
        Log::debug($message);
    }

    /**
     * @overwrite Adapter
     */
    public function error(string $message = '')
    {
        Log::error($message);
    }

    /**
     * @overwrite Adapter
     */
    public function getPath(): string
    {
        $cli = Log::runningInConsole();

        return Config::BASE_DIR . DIRECTORY_SEPARATOR . self::SINGLE_LOG_NAME . ($cli ? '_cli' : '') . '.log';
    }
}
