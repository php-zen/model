<?php
/**
 * 定义限区间整数值验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

/**
 * 限区间整数值验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class RangedInt extends RangedNumber
{
    /**
     * {@inheritdoc}
     *
     * @param  scalar $value 待验证地值
     * @return bool
     *
     * @throws ExValueNotInt 当验证值不是整数时
     */
    public function verify($value)
    {
        $i_value = (int) $value;
        if ($i_value != $value) {
            throw new ExValueNotInt($this->attribute, $value);
        }

        return parent::verify($value);
    }
}
