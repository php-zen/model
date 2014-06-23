<?php
/**
 * 声明模型的数据访问对象组件规范。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

use Zen\Core;

/**
 * 模型的数据访问对象组件规范。
 *
 * @package    Zen\Model
 * @version    0.1.0
 * @since      0.1.0
 */
interface IDao extends Core\ISingleton
{
    /**
     * 创建新的实体记录并获取编号。
     *
     * @param  mixed[] $fields 实体属性值集合
     * @return scalar
     */
    public function create($fields);

    /**
     * 读取指定实体记录。
     *
     * @param  scalar        $id 编号
     * @return mixed[]|false
     */
    public function read($id);

    /**
     * 更新实体记录。
     *
     * @param  scalar  $id     编号
     * @param  mixed[] $fields 新的属性值集合
     * @return bool
     */
    public function update($id, $fields);

    /**
     * 删除实体记录。
     *
     * @param  scalar $id 编号
     * @return bool
     */
    public function delete($id);

    /**
     * 统计符合条件地实体记录数量。
     *
     * @param  array[] $conditions 条件
     * @return int
     */
    public function count($conditions);

    /**
     * 获取符合条件地实体记录集合。
     *
     * @param  array[] $conditions 条件
     * @param  int     $limit      可选。集合大小限制
     * @param  int     $offset     可选。集合起始偏移量
     * @return array[]
     */
    public function query($conditions, $limit = 0, $offset = 0);
}
