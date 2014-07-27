<?php
/**
 * 定义当验证值过大时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

/**
 * 当验证值过大时抛出地异常。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(string $attribute, float $value, float $max, \Exception $prev = null) 构造函数
 */
final class ExRangedNumberTooLarge extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '数值“%value$.1f”超出最大限度“%max$.1f”。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('attribute', 'value', 'max');
}
