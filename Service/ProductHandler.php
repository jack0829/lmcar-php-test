<?php

namespace App\Service;

use SebastianBergmann\CodeCoverage\Report\PHP;

class ProductHandler
{

    // 原始产品数据
    protected array $products;

    /**
     * 构造器
     * @param array ...$products
     */
    public function __construct(array ...$products)
    {
        $this->products = $products;
    }

    public function result(): array
    {
        // print_r($this->products);
        return $this->products;
    }

    /**
     * 计算产品总价
     * @param array ...$products
     * @return int
     */
    public function getTotalPrice(): int
    {
        return array_reduce($this->products, function ($total, array $product) {
            $total += $product['price'] ?: 0;
            return $total;
        }, 0);
    }

    /**
     * 筛选
     * @param array $kv 字段 k/v
     */
    public function filter(array $kv): ProductHandler
    {
        $this->products = array_filter($this->products, function (array $p) use ($kv) {

            // 对每个条件做判断
            foreach ($kv as $k => $v) {

                // 没有筛选字段的，过滤
                if (!isset($p[$k])) {
                    return false;
                }

                // 字段值不一样的，过滤
                if ($p[$k] != $v) {
                    return false;
                }
            }

            // 每个条件都符合，保留
            return true;
        });
        return $this;
    }

    /**
     * 按某字段排序
     * @param string $field 排序字段
     * @param bool $desc 是否为降序，默认 false
     */
    public function sortBy(string $field, bool $desc = false): ProductHandler
    {
        usort($this->products, function ($a, $b) use ($field, $desc) {
            if ($desc) {
                return $a[$field] < $b[$field];
            }
            return $a[$field] > $b[$field];
        });

        return $this;
    }

    /**
     * 转换日期
     * @param string ...$timeField 时间字段名
     */
    public function convertUnixTime(string ...$timeFields): ProductHandler
    {
        // 没传时间字段就不遍历了
        if (count($timeFields) == 0) {
            return $this;
        }

        array_walk($this->products, function (&$v) use ($timeFields) {
            foreach ($timeFields as $field) {

                if (!isset($v[$field]) || gettype($v[$field]) != 'string') {
                    continue;
                }

                $v[$field] = strtotime($v[$field]);
            }
        });

        return $this;
    }
}
