<?php
/**
 * 定义模型组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

use Zen\Core;

/**
 * 模型组件。
 *
 * @package    Zen\Model
 * @version    0.1.0
 * @since      0.1.0
 *
 * @property-read scalar $id 编号
 */
abstract class Model extends Core\Component implements IModel
{
    /**
     * 判断属性是否存在。
     *
     * @internal
     *
     * @param  scalar $offset 属性名
     * @return bool
     */
    final public function offsetExists($offset)
    {
        if (in_array($offset, $this->listNonAttributes())) {
            return false;
        }
        $s_class = get_class($this);

        return isset(self::$zenPropsTable[$s_class][$offset]);
    }

    /**
     * 列举类功能性属性（非实体持久化属性）。
     *
     * @return string[]
     */
    protected function listNonAttributes()
    {
        return array(
            'dao',
            'staging'
        );
    }

    /**
     * 获取属性值。
     *
     * @internal
     *
     * @param  scalar $offset 属性名
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->__get($offset);
        }
    }

    /**
     * 设置属性值。
     *
     * @internal
     *
     * @param  scalar $offset 属性名
     * @param  mixed  $value  新值
     * @return void
     */
    final public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->__set($offset, $value);
        }
    }

    /**
     * 删除属性值。
     *
     * @internal
     *
     * @param  scalar $offset 属性名
     * @return void
     */
    final public function offsetUnset($offset)
    {
    }

    /**
     * 实体池。
     *
     * @internal
     *
     * @var array[]
     */
    protected static $zenEntities;

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    final public function __toString()
    {
        return @$this->staging['id'];
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed[]
     */
    final public function toArray()
    {
        return $this->staging;
    }

    /**
     * {@inheritdoc}
     *
     * @param  scalar[] $attributes 属性集合
     * @return self
     */
    final public static function create($attributes)
    {
        $s_class = get_called_class();
        if (!is_array(self::$zenEntities)) {
            self::$zenEntities = array();
        }
        $o_entity = new static;
        if (!isset(self::$zenPropsTable[$s_class])) {
            $o_entity->zenMeasureProperty('id');
        }
        $a_attrs = self::$zenPropsTable[$s_class];
        foreach ($o_entity->listNonAttributes() as $ii) {
            unset($a_attrs[$ii]);
        }
        foreach ($attributes as $ii => $jj) {
            if (isset($a_attrs[$ii])) {
                $o_entity->$ii = $jj;
            }
        }

        return $o_entity->save();
    }

    /**
     * 数据访问对象组件实例。
     *
     * @var IDao
     */
    protected $dao;

    /**
     * 构造函数
     */
    final protected function __construct()
    {
        $this->dao = $this->newDao();
        $this->staging = array();
    }

    /**
     * 创建新的数据访问对象组件实例。
     *
     * @return IDao
     */
    abstract protected function newDao();

    /**
     * {@inheritdoc}
     *
     * @param  scalar $id 编号
     * @return self
     */
    final public static function load($id)
    {
        $s_class = get_called_class();
        if (!is_array(self::$zenEntities)) {
            self::$zenEntities = array();
        }
        if (!isset(self::$zenEntities[$s_class][$id])) {
            $o_entity = new static;
            $o_entity->staging['id'] = $id;
            $o_entity->reload($o_entity->dao->read($id));
            self::$zenEntities[$s_class][$id] = $o_entity;
        }

        return self::$zenEntities[$s_class][$id];
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  scalar[] $attributes 属性集合
     * @return self
     */
    final public static function loadFromAttributes($attributes)
    {
        $s_class = get_called_class();
        if (!is_array(self::$zenEntities)) {
            self::$zenEntities = array();
        }
        if (!isset($attributes['id'])) {
            return static::prepare($attributes);
        }
        $s_id = $attributes['id'];
        if (!isset(self::$zenEntities[$s_class][$s_id])) {
            $o_entity = new static;
            $o_entity->staging['id'] = $s_id;
            self::$zenEntities[$s_class][$s_id] = $o_entity;
        }
        self::$zenEntities[$s_class][$s_id]->reload($attributes);

        return self::$zenEntities[$s_class][$s_id];
    }

    /**
     * 唯一编号。
     *
     * @var scalar
     */
    protected $id;

    /**
     * 属性原始值表。
     *
     * @var scalar[]
     */
    protected $staging;

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  mixed[] $attributes 属性集合
     * @return self
     *
     * @throws ExAttributeMissing 当属性缺失时
     */
    final public function reload($attributes)
    {
        if (is_array($attributes) &&
            isset($this->staging['id'], $attributes['id']) &&
            $this->staging['id'] == $attributes['id']
        ) {
            $s_class = get_class($this);
            if (!isset(self::$zenPropsTable[$s_class])) {
                $this->zenMeasureProperty('id');
            }
            $a_attrs = self::$zenPropsTable[$s_class];
            foreach ($this->listNonAttributes() as $ii) {
                unset($a_attrs[$ii]);
            }
            $a_stage = array();
            foreach ($a_attrs as $ii => $jj) {
                if (isset($attributes[$ii])) {
                    $a_stage[$ii] = $attributes[$ii];
                    unset($a_attrs[$ii], $attributes[$ii]);
                }
            }
            $attributes = $this->onLoad($attributes);
            if (is_array($attributes)) {
                foreach ($a_attrs as $ii => $jj) {
                    if (isset($attributes[$ii])) {
                        $a_stage[$ii] = $attributes[$ii];
                        unset($a_attrs[$ii], $attributes[$ii]);
                    }
                }
            }
            if (!empty($a_attrs)) {
                throw new ExAttributeMissing($this, array_shift(array_keys($a_attrs)));
            }
            $this->staging = $a_stage;
            foreach ($a_stage as $ii => $jj) {
                $this->$ii = $jj;
            }
        }

        return $this;
    }

    /**
     * 实体载入事件。
     *
     * @param  scalar[] $attributes 无法被直接映射地属性集合
     * @return scalar[]
     */
    protected function onLoad($attributes)
    {
        return array();
    }

    /**
     * {@inheritdoc}
     *
     * @return self
     */
    final public function save()
    {
        $a_stage = $this->staging;
        $this->onSave();
        $a_diff = array();
        foreach ($a_stage as $ii => $jj) {
            if ($jj != (string) $this->$ii) {
                $a_diff[$ii] = (string) $jj;
            }
        }
        if (empty($a_diff)) {
            return $this;
        }
        $s_class = get_class($this);
        if (isset($a_stage['id'])) {
            $this->dao->update($a_stage['id'], $a_diff);
            if ($a_stage['id'] != $this->id) {
                unset(self::$zenEntities[$s_class][$a_stage['id']]);
                self::$zenEntities[$s_class][$this->id] = $this;
            }
            $this->staging = array_merge($a_stage, $a_diff);
        } else {
            $this->staging['id'] =
            $this->id = $this->dao->create($a_stage);
            self::$zenEntities[$s_class][$this->id] = $this;
        }

        return $this;
    }

    /**
     * 实体保存事件。
     *
     * @return void
     */
    protected function onSave()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    final public function destroy()
    {
        if (!isset($this->staging['id'])) {
            return;
        }
        $s_id = $this->staging['id'];
        $this->onDestroy();
        foreach ($this->staging as $ii => $jj) {
            $this->$ii = null;
        }
        $this->staging = array();
        $this->dao = $this->newDummyDao();
        $s_class = get_class($this);
        unset(self::$zenEntities[$s_class][$s_id]);
    }

    /**
     * 实体（持久化数据）销毁事件。
     *
     * @return void
     */
    protected function onDestroy()
    {
        $this->dao->delete($this->staging['id']);
    }

    /**
     * 创建空数据访问对象组件实例。
     *
     * @internal
     *
     * @return Dao\DummyDao
     */
    protected function newDummyDao()
    {
        return Dao\DummyDao::singleton();
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  string $attribute 属性名
     * @param  mixed  $value     值
     * @param  string $op        运算符
     * @return bool
     */
    final public function assert($attribute, $value, $op)
    {
        $m_value = $this->offsetGet($attribute);
        switch ($op) {
            case ISet::OP_EQ:
                return $m_value == $value;
            case ISet::OP_IN:
                return is_array($value) && in_array($m_value, $value);
            case ISet::OP_GT:
                return $m_value > $value;
            case ISet::OP_LT:
                return $m_value < $value;
            case ISet::OP_NE:
                return $m_value != $value;
            case ISet::OP_NI:
                return is_array($value) && !in_array($m_value, $value);
            case ISet::OP_GE:
                return $m_value >= $value;
            case ISet::OP_LE:
                return $m_value <= $value;
        }
    }
}
