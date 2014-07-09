<?php
/**
 * 定义当验证值无效时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

/**
 * 当验证值无效时抛出地异常。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 *
 * @method void __construct(mixed $value, string $class, \Exception $prev = null) 构造函数
 */
final class ExIllegalInstance extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '值“%value$s”不是类“%class$s”的实例。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('value', 'class');
}
