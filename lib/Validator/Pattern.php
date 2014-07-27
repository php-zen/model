<?php
/**
 * 定义模式字符串验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Core;
use Zen\Model;

/**
 * 模式字符串验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class Pattern extends Core\Component implements Model\IValidator
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
     * @param int $pattern 模式
     */
    public function __construct($pattern)
    {
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
            throw new ExPatternDismatched($value, $this->pattern);
        }

        return true;
    }
}
