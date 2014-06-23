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
}
