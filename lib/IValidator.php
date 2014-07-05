<?php
/**
 * 声明模型的属性值验证器规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

/**
 * 模型的属性值验证器规范。
 *
 * @package Zen\Model
 * @version 0.1.0
 * @since   0.1.0
 */
interface IValidator
{
    /**
     * 验证指定值是否满足要求。
     *
     * @param  mixed $value 待测试地值
     * @return bool
     */
    public function verify($value);
}
