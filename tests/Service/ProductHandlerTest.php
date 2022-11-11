<?php

namespace Test\Service;

use PHPUnit\Framework\TestCase;
use App\Service\ProductHandler;

/**
 * Class ProductHandlerTest
 */
class ProductHandlerTest extends TestCase
{

    private $products = [
        [
            'id' => 1,
            'name' => 'Coca-cola',
            'type' => 'Drinks',
            'price' => 10,
            'create_at' => '2021-04-20 10:00:00',
        ],
        [
            'id' => 2,
            'name' => 'Persi',
            'type' => 'Drinks',
            'price' => 5,
            'create_at' => '2021-04-21 09:00:00',
        ],
        [
            'id' => 3,
            'name' => 'Ham Sandwich',
            'type' => 'Sandwich',
            'price' => 45,
            'create_at' => '2021-04-20 19:00:00',
        ],
        [
            'id' => 4,
            'name' => 'Cup cake',
            'type' => 'Dessert',
            'price' => 35,
            'create_at' => '2021-04-18 08:45:00',
        ],
        [
            'id' => 5,
            'name' => 'New York Cheese Cake',
            'type' => 'Dessert',
            'price' => 40,
            'create_at' => '2021-04-19 14:38:00',
        ],
        [
            'id' => 6,
            'name' => 'Lemon Tea',
            'type' => 'Drinks',
            'price' => 8,
            'create_at' => '2021-04-04 19:23:00',
        ],
    ];

    // 1.1
    public function testGetTotalPrice()
    {
        $handle = new ProductHandler(...$this->products);
        $total = $handle->getTotalPrice();

        $this->assertEquals(143, $total, '总价错误');
    }

    // 1.2 and 1.3
    public function testProductHandler()
    {
        $handle = new ProductHandler(...$this->products);
        $handle->filter(['type' => 'Dessert'])->sortBy('price', true)->convertUnixTime('create_at');

        $products = $handle->result();

        // 1.2
        $this->assertEquals(2, count($products), '筛选结果数量错误');
        $this->assertTrue($products[0]['price'] >= $products[1]['price'], '排序不正确');

        // 1.3
        $this->assertEquals(
            mktime(14, 38, 0, 4, 19, 2021), // 2021-04-19 14:38:00
            $products[0]['create_at'],
            '时间转换错误'
        );
        $this->assertEquals(
            mktime(8, 45, 0, 4, 18, 2021), // 2021-04-18 08:45:00
            $products[1]['create_at'],
            '时间转换错误'
        );
    }
}
