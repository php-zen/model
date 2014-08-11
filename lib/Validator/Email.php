<?php
/**
 * 定义电子邮箱验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 电子邮箱验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class Email extends Pattern
{
    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     */
    public function __construct($attribute)
    {
        parent::__construct($attribute, '#^[\w\.\-+]+@[\w\-]+(\.[\w\-]+)+$#');
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $value 待验证地值
     * @return bool
     *
     * @throws ExIllegalEmail 当电子邮箱不合法时
     */
    public function verify($value)
    {
        try {
            return parent::verify($value);
        } catch (\Exception $ee) {
            throw new ExIllegalEmail($this->attribute, $value);
        }
    }
}
