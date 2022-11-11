<?php

namespace Test\App;

use PHPUnit\Framework\TestCase;
use App\App\Demo;
use App\Service\AppLogger;
use App\Service\Logger\ThinkLog;
use App\Util\HttpRequest;

class DemoTest extends TestCase
{

    // public function test_foo()
    // {
    // }

    // 接口能正常返回数据的情况
    public function test_get_user_info_ok()
    {
        $ok = [
            'error' => 0,
            'data' => [
                'id' => 1,
                'username' => 'hello world',
            ],
        ];

        $req = $this->createStub(HttpRequest::class);
        $req->method('get')->with(Demo::URL)->willReturn(json_encode($ok));

        $demo = new Demo(AppLogger::factory(), $req);
        $userinfo = $demo->get_user_info();

        $this->assertIsArray($userinfo, '返回类型不为 array');
        $this->assertArrayHasKey('id', $userinfo, '返回结果没有 id 字段');
        $this->assertArrayHasKey('username', $userinfo, '返回结果没有 username 字段');
        // $this->assertGreaterThan(0, $userinfo['id'], '用户 id 字段值应该大于 0');
        // $this->assertIsString($userinfo['username'], '用户 username 字段类型错误');
        $this->assertEquals($ok['data']['id'], $userinfo['id'], '返回结果 id 值错误');
        $this->assertSame($ok['data']['username'], $userinfo['username'], '返回结果 username 值错误');
    }

    // 接口不能正常返回数据的情况
    public function test_get_user_info_error()
    {

        $req = $this->createStub(HttpRequest::class);
        $req->method('get')->with(Demo::URL)->willReturn('{"error":40400}');

        $message = '';
        $setMessage = function ($s) use (&$message) {
            $message = $s;
        };

        $log = $this->createStub(ThinkLog::class);
        $log->expects($this->atLeastOnce())->method('error')->will($this->returnCallback($setMessage));

        $demo = new Demo($log, $req);
        $userinfo = $demo->get_user_info();

        $this->assertNull($userinfo, '返回值应该为 null');
        $this->assertNotEmpty($message, '返回错误码时没有写日志');
    }
}
