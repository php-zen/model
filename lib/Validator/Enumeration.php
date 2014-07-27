<?php
/**
 * 定义枚举值验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 枚举值验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class Enumeration extends Validator
{
    /**
     * 枚举值集合。
     *
     * @var scalar[]
     */
    protected $items;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     * @param scalar $...       枚举值
     */
    public function __construct($attribute)
    {
        $a_args = func_get_args();
        parent::__construct(array_shift($a_args));
        $this->items = a_args();
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $value 待验证地值
     * @return bool
     *
     * @throws ExIllegalEnumItem 当验证值无效时
     */
    public function verify($value)
    {
        if (!in_array($value, $this->items)) {
            throw new ExIllegalEnumItem($this->attribute, $value);
        }

        return true;
    }
}
