<?php
/**
 * 定义类实例验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 类实例验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class Instance extends Validator
{
    /**
     * 类实例集合。
     *
     * @var string
     */
    protected $prototype;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     * @param string $class     类名
     */
    public function __construct($attribute, $class)
    {
        parent::__construct($attribute);
        $this->prototype = $class;
    }

    /**
     * {@inheritdoc}
     *
     * @param  object $value 待验证地值
     * @return bool
     *
     * @throws ExIllegalInstance 当验证值无效时
     */
    public function verify($value)
    {
        if (!is_a($value, $this->prototype)) {
            throw new ExIllegalInstance($this->attribute, $value, $this->prototype);
        }

        return true;
    }
}
