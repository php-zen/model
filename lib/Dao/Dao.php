<?php
/**
 * 定义数据访问对象组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Dao;

use Zen\Core;
use Zen\Model;

/**
 * 数据访问对象组件。
 *
 * @package    Zen\Model
 * @subpackage Dao
 * @version    0.1.0
 * @since      0.1.0
 */
abstract class Dao extends Core\Component implements Model\IDao
{
    /**
     * 数据访问对象组件实例池。
     *
     * @internal
     *
     * @var Dao[]
     */
    protected static $instances;

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    final public static function singleton()
    {
        if (!is_array(self::$instances)) {
            self::$instances = array();
        }
        $s_class = get_called_class();
        if (!isset(self::$instances[$s_class])) {
            self::$instances[$s_class] = new static;
        }

        return self::$instances[$s_class];
    }

    /**
     * 构造函数
     */
    final protected function __construct()
    {
    }

    /**
     * 数据字段对实体属性的映射表。
     *
     * @var string[]
     */
    protected static $map = array(
        'Id' => 'id'
    );

    /**
     * 将数据记录转化为实体属性集合。
     *
     * @param  mixed[] $records 单行或多行数据记录
     * @return mixed[]
     */
    final protected function map($records)
    {
        if (isset($records[0])) {
            $b_multi = true;
        } else {
            $b_multi = false;
            $records = array($records);
        }
        $a_ret = array();
        for ($ii = 0, $jj = count($records); $ii < $jj; $ii++) {
            $a_tmp = array();
            foreach ($records[$ii] as $kk => $ll) {
                $a_tmp[static::$map[$kk]] = $ll;
            }
            $a_ret[] = $a_tmp;
        }

        return $b_multi
            ? $a_ret
            : $a_ret[0];
    }

    /**
     * 将实体属性转化为数据记录数组。
     *
     * @param  mixed[] $entities 单个或多个实体
     * @return mixed[]
     */
    final protected function reverseMap($entities)
    {
        $a_map = array_flip(static::$map);
        if (isset($entities[0])) {
            $b_multi = true;
        } else {
            $b_multi = false;
            $entities = array($entities);
        }
        $a_ret = array();
        for ($ii = 0, $jj = count($entities); $ii < $jj; $ii++) {
            $a_tmp = array();
            foreach ($entities[$ii] as $kk => $ll) {
                $a_tmp[$a_map[$kk]] = $ll;
            }
            $a_ret[] = $a_tmp;
        }

        return $b_multi
            ? $a_ret
            : $a_ret[0];
    }
}
