<?php
/**
 * 定义辅助模型组件工作地特殊空数据访问对象组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Dao;

/**
 * 辅助模型组件工作地特殊空数据访问对象组件。
 *
 * @package    Zen\Model
 * @subpackage Dao
 * @version    0.1.0
 * @since      0.1.0
 */
final class DummyDao extends Dao
{
    /**
     * {@inheritdoc}
     *
     * @param  mixed[] $fields 实体属性值集合
     * @return 0
     */
    public function create($fields)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar  $id 编号
     * @return mixed[]
     */
    public function read($id)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar  $id     编号
     * @param  mixed[] $fields 新的属性值集合
     * @return true
     */
    public function update($id, $fields)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar $id 编号
     * @return true
     */
    public function delete($id)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @return int
     */
    public function count($conditions)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @param  array[] $conditions 条件
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return array[]
     */
    public function query($conditions, $limit = 0, $offset = 0)
    {
        return array();
    }
}
