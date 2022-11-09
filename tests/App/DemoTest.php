<?php

namespace Test\App;

use PHPUnit\Framework\TestCase;
use App\App\Demo;
use App\Service\AppLogger;
use App\Util\HttpRequest;

class DemoTest extends TestCase
{

    // public function test_foo()
    // {
    // }

    public function test_get_user_info()
    {
        $demo = new Demo(new AppLogger(), new HttpRequest);
        $userinfo = $demo->get_user_info();

        $this->assertIsArray($userinfo, '返回类型不为 array');
        $this->assertArrayHasKey('id', $userinfo, '返回结果没有 id 字段');
        $this->assertArrayHasKey('username', $userinfo, '返回结果没有 username 字段');
        $this->assertGreaterThan(0, $userinfo['id'], '用户 id 字段值应该大于 0');
        $this->assertIsString($userinfo['username'], '用户 username 字段类型错误');
    }
}
