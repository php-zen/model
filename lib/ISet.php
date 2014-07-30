<?php
/**
 * 声明模型集合组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

use Countable;
use Iterator;

/**
 * 模型集合组件规范。
 *
 * @package Zen\Model
 * @version 0.1.0
 * @since   0.1.0
 */
interface ISet extends Countable, Iterator
{
    /**
     * 转化为数组。
     *
     * @return array[]
     */
    public function toArray();

    /**
     * 创建映射全部实体以用于过滤地模型集合组件实例。
     *
     * @return self
     */
    public static function all();

    /**
     * 等于运算符。
     *
     * @var string
     */
    const OP_EQ = '=';

    /**
     * 在集合内地运算符。
     *
     * @var string
     */
    const OP_IN = 'in';

    /**
     * 大于运算符。
     *
     * @var string
     */
    const OP_GT = '>';

    /**
     * 小于运算符。
     *
     * @var string
     */
    const OP_LT = '<';

    /**
     * 在区域内地运算符。
     *
     * @var string
     */
    const OP_BT = 'between';

    /**
     * 不等于运算符。
     *
     * @var string
     */
    const OP_NE = '<>';

    /**
     * 不在集合内地运算符。
     *
     * @var string
     */
    const OP_NI = 'not in';

    /**
     * 大于或等于运算符。
     *
     * @var string
     */
    const OP_GE = '>=';

    /**
     * 小于或等于运算符。
     *
     * @var string
     */
    const OP_LE = '<=';

    /**
     * 不在区域内地运算符。
     *
     * @var string
     */
    const OP_NB = 'not between';

    /**
     * 过滤保留属性值等于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  string $value     期望值
     * @return self
     */
    public function filterEq($attribute, $value);

    /**
     * 过滤保留属性值在预期值集合内地实体。
     *
     * @param  string   $attribute 属性名
     * @param  string[] $value     期望值
     * @return self
     */
    public function filterIn($attribute, $value);

    /**
     * 过滤保留属性值大于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $value     期望值
     * @return self
     */
    public function filterGt($attribute, $value);

    /**
     * 过滤保留属性值小于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $value     期望值
     * @return self
     */
    public function filterLt($attribute, $value);

    /**
     * 过滤保留属性值在区域内地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $min       最小值
     * @param  number $max       最大值
     * @return self
     */
    public function filterBetween($attribute, $min, $max);

    /**
     * 过滤掉属性值等于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  string $value     期望值
     * @return self
     */
    public function excludeEq($attribute, $value);

    /**
     * 过滤掉属性值在预期值集合内地实体。
     *
     * @param  string   $attribute 属性名
     * @param  string[] $value     期望值
     * @return self
     */
    public function excludeIn($attribute, $value);

    /**
     * 过滤掉属性值大于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $value     期望值
     * @return self
     */
    public function excludeGt($attribute, $value);

    /**
     * 过滤掉属性值小于预期值地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $value     期望值
     * @return self
     */
    public function excludeLt($attribute, $value);

    /**
     * 过滤掉属性值在区域内地实体。
     *
     * @param  string $attribute 属性名
     * @param  number $min       最小值
     * @param  number $max       最大值
     * @return self
     */
    public function excludeBetween($attribute, $min, $max);

    /**
     * 正向排序。
     *
     * @var bool
     */
    const SORT_ASC = true;

    /**
     * 逆向排序。
     *
     * @var bool
     */
    const SORT_DESC = false;

    /**
     * 按照指定属性值排序。
     *
     * @param  string $attribute 属性名
     * @param  bool   $ascading  可选。是否正向排序
     * @return self
     */
    public function sortBy($attribute, $ascading = true);

    /**
     * 按指定位置截取实体集合。
     *
     * @param  int  $offset 起始位置
     * @param  int  $size   数量限制
     * @return self
     */
    public function crop($offset, $size);
}
