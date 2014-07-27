<?php
/**
 * 定义当验证值过长时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

/**
 * 当验证值过长时抛出地异常。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(string $attribute, string $value, int $length, \Exception $prev = null) 构造函数
 */
final class ExSizedCharsTooLong extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '字符串“%value$s”过长，多于%length$d个字符。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('attribute', 'value', 'length');
}
