<?php

namespace App\Util;

class HttpRequest
{
    function get($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2); // 加个超时测试网络不能能快一点
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2); // 加个超时测试网络不能能快一点
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
