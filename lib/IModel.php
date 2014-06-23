<?php
/**
 * 声明模型组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

use ArrayAccess;

/**
 * 模型组件规范。
 *
 * @package    Zen\Model
 * @version    0.1.0
 * @since      0.1.0
 */
interface IModel extends ArrayAccess
{
    /**
     * 创建实体及其持久化数据。
     *
     * @param  scalar[] $attributes 属性集合
     * @return self
     */
    public static function create($attributes);

    /**
     * 准备实体以创建。
     *
     * @param  scalar[] $attributes 可选。
     * @return self
     */
    public static function prepare($attributes = array());

    /**
     * 加载相应的实体（模型组件实例）。
     *
     * @param  scalar $id 编号
     * @return self
     */
    public static function load($id);

    /**
     * 基于属性值加载实体（模型组件实例）。
     *
     * @internal
     *
     * @param  scalar[] $attributes 属性集合
     * @return self
     */
    public static function loadFromAttributes($attributes);

    /**
     * 根据指定属性重载当前实体（模型组件实例）。
     *
     * @internal
     *
     * @param  mixed[] $attributes 属性集合
     * @return self
     */
    public function reload($attributes);

    /**
     * 保存当前实体（模型组件实例）的变更。
     *
     * @return self
     */
    public function save();

    /**
     * 销毁当前实体（的持久化数据）。
     *
     * @return void
     */
    public function destroy();

    /**
     * 测试属性值是否达标。
     *
     * @internal
     *
     * @param  string $attribute 属性名
     * @param  mixed  $value     值
     * @param  string $op        运算符
     * @return bool
     */
    public function assert($attribute, $value, $op);
}
