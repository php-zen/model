<?php
/**
 * 定义模式字符串验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 模式字符串验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class Pattern extends Validator
{
    /**
     * 模式（正则表达式）。
     *
     * @internal
     *
     * @var string
     */
    protected $pattern;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     * @param int    $pattern   模式
     */
    public function __construct($attribute, $pattern)
    {
        parent::__construct($attribute);
        $this->pattern = $pattern;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $value 待验证地值
     * @return bool
     *
     * @throws ExPatternDismatched 当模式不匹配时
     */
    public function verify($value)
    {
        if (!preg_match($this->pattern, $value)) {
            throw new ExPatternDismatched($this->attribute, $value, $this->pattern);
        }

        return true;
    }
}
