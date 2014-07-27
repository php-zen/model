<?php
/**
 * 定义抽象验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Core;
use Zen\Model;

/**
 * 抽象验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
abstract class Validator extends Core\Component implements Model\IValidator
{
    /**
     * 属性名。
     *
     * @internal
     *
     * @var string
     */
    protected $attribute;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     */
    public function __construct($attribute)
    {
        $this->attribute = $attribute;
    }
}
