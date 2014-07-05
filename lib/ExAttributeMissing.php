<?php
/**
 * 定义当属性缺失时抛出地异常。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

/**
 * 当属性缺失时抛出地异常。
 *
 * @package Zen\Model
 * @version 0.1.0
 * @since   0.1.0
 *
 * @method void __construct(IModel $entity, string $attribute, \Exception $prev = null) 构造函数
 */
final class ExAttributeMissing extends Exception
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected static $template = '实体“%entity$s”找不到属性“%attribute$s”的值。';

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @var string[]
     */
    protected static $contextSequence = array('entity', 'attribtue');
}
