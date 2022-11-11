<?php

namespace App\Service\Logger;

// 日志适配器
interface Adapter
{

    // 强制单例
    static function inst(): Adapter;

    // Info
    function info(string $message = '');

    // Debug
    function debug(string $message = '');

    // Error
    function error(string $message = '');

    // 日志目录
    function getPath(): string;
}
