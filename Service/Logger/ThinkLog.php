<?php

namespace App\Service\Logger;

use App\Service\Logger\Adapter;
use think\facade\Log;

/**
 * ThinkLog 适配器
 */
class ThinkLog implements Adapter
{

    // 单例
    private static ?ThinkLog $instance = null;

    public function __construct(string $path)
    {
        Log::init([
            'default'    =>    'file',
            'channels'    =>    [
                'file'    =>    [
                    'type'    =>    'file',
                    'path'    =>    $path,

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
            self::$instance = new self('think-log');
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
}
