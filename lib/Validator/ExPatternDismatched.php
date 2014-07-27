<?php
/**
 * 定义当模式不匹配时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

/**
 * 当模式不匹配时抛出地异常。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(string $attribute, string $value, string $pattern, \Exception $prev = null) 构造函数
 */
final class ExPatternDismatched extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '“%value$s”不匹配模式“%pattern$s”。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('attribute', 'value', 'pattern');
}
