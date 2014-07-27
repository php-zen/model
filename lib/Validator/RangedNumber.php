<?php
/**
 * 定义限区间数值验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 限区间数值验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class RangedNumber extends Validator
{
    /**
     * 最小值。
     *
     * @internal
     *
     * @var int
     */
    protected $minValue;

    /**
     * 最大值。
     *
     * @internal
     *
     * @var int
     */
    protected $maxValue;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     * @param int    $minValue  最小值要求
     * @param int    $maxValue  最大值要求
     */
    public function __construct($attribute, $minValue, $maxValue = 0)
    {
        parent::__construct($attribute);
        $this->minValue = (float) $minValue;
        $this->maxValue = 0 < $maxValue
            ? max($this->minValue, $maxValue)
            : 0;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar $value 待验证地值
     * @return bool
     *
     * @throws ExRangedNumberTooSmall 当验证值过小时
     * @throws ExRangedNumberTooLarge 当验证值过大时
     */
    public function verify($value)
    {
        $f_value = (float) $value;
        if ($f_value < $this->minValue) {
            throw new ExRangedNumberTooSmall($this->attribute, $value, $this->minValue);
        }
        if ($this->maxValue && $f_value > $this->maxValue) {
            throw new ExRangedNumberTooLarge($this->attribute, $value, $this->maxValue);
        }

        return true;
    }
}
