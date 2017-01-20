<?php
/**
 * 定义模型组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2017 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model;

use Zen\Core;

/**
 * 模型组件。
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
     * @param scalar $offset 属性名
     *
     * @return bool
     */
    final public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param scalar $property
     *
     * @return bool
     */
    protected function zenIsset($property)
    {
        return !in_array($property, $this->listNonAttributes());
    }

    /**
     * 获取属性值。
     *
     * @internal
     *
     * @param scalar $offset 属性名
     *
     * @return mixed
     */
    final public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * 设置属性值。
     *
     * @internal
     *
     * @param scalar $offset 属性名
     * @param mixed  $value  新值
     */
    final public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);
    }

    /**
     * 删除属性值。
     *
     * @internal
     *
     * @param scalar $offset 属性名
     */
    final public function offsetUnset($offset)
    {
        $this->__unset($offset);
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
            'zenStaging',
        );
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
        return (string) @$this->zenStaging['id'];
    }

    /**
     * {@inheritdoc}
     *
     * @return mixed[]
     */
    public function toArray()
    {
        return $this->zenStaging;
    }

    /**
     * {@inheritdoc}
     *
     * @param scalar[] $attributes 属性集合
     *
     * @return self
     */
    final public static function create($attributes)
    {
        $s_class = get_called_class();
        if (!is_array(self::$zenEntities)) {
            self::$zenEntities = array();
        }
        $o_entity = new static();
        $a_attrs = get_object_vars($o_entity);
        foreach ($o_entity->listNonAttributes() as $ii) {
            unset($a_attrs[$ii]);
        }
        foreach ($attributes as $ii => $jj) {
            if (array_key_exists($ii, $a_attrs)) {
                $o_entity->zenStaging[$ii] = null;
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
     * 构造函数.
     */
    final protected function __construct()
    {
        $this->dao = $this->newDao();
        $this->zenStaging = array('id' => '');
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
     * @param scalar $id 编号
     *
     * @return self
     */
    final public static function load($id)
    {
        $s_class = get_called_class();
        if (!is_array(self::$zenEntities)) {
            self::$zenEntities = array();
        }
        if (!isset(self::$zenEntities[$s_class][$id])) {
            $o_entity = new static();
            $o_entity->zenStaging['id'] = $id;
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
     * @param scalar[] $attributes 属性集合
     *
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
            $o_entity = new static();
            $o_entity->zenStaging['id'] = $s_id;
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
    protected $zenStaging;

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param mixed[] $attributes 属性集合
     *
     * @return self
     *
     * @throws ExAttributeMissing 当属性缺失时
     */
    final public function reload($attributes)
    {
        if (is_array($attributes) &&
            isset($this->zenStaging['id'], $attributes['id']) &&
            $this->zenStaging['id'] == $attributes['id']
        ) {
            $a_attrs = get_object_vars($this);
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
                $a_attrs = array_keys($a_attrs);
                throw new ExAttributeMissing($this, $a_attrs[0]);
            }
            $this->zenStaging = $a_stage;
            foreach ($a_stage as $ii => $jj) {
                $this->$ii = $jj;
            }
        }

        return $this;
    }

    /**
     * 实体载入事件。
     *
     * @param scalar[] $attributes 无法被直接映射地属性集合
     *
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
        $a_stage = $this->zenStaging;
        $this->onSave();
        $a_diff = array();
        foreach ($a_stage as $ii => $jj) {
            if ($jj != (string) $this->$ii) {
                $a_diff[$ii] = (string) $this->$ii;
            }
        }
        if (empty($a_diff)) {
            return $this;
        }
        $s_class = get_class($this);
        if (isset($a_stage['id']) && $a_stage['id']) {
            $this->dao->update($a_stage['id'], $a_diff);
            if ($a_stage['id'] != $this->id) {
                unset(self::$zenEntities[$s_class][$a_stage['id']]);
                self::$zenEntities[$s_class][$this->id] = $this;
            }
            $this->zenStaging = array_merge($a_stage, $a_diff);
        } else {
            $this->zenStaging['id'] =
            $this->id = $this->dao->create($a_diff);
            $this->reload($this->dao->read($this->id));
            self::$zenEntities[$s_class][$this->id] = $this;
        }

        return $this;
    }

    /**
     * 实体保存事件。
     */
    protected function onSave()
    {
    }

    /**
     * {@inheritdoc}
     */
    final public function destroy()
    {
        if (!isset($this->zenStaging['id'])) {
            return;
        }
        $s_id = $this->zenStaging['id'];
        $this->onDestroy();
        foreach ($this->zenStaging as $ii => $jj) {
            $this->$ii = null;
        }
        $this->zenStaging = array();
        $this->dao = $this->newDummyDao();
        $s_class = get_class($this);
        unset(self::$zenEntities[$s_class][$s_id]);
    }

    /**
     * 实体（持久化数据）销毁事件。
     */
    protected function onDestroy()
    {
        $this->dao->delete($this->zenStaging['id']);
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
     * @param string $attribute 属性名
     * @param mixed  $value     值
     * @param string $op        运算符
     *
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
            case ISet::OP_BT:
                return $m_value > $value[0] && $m_value < $value[1];
            case ISet::OP_LK:
                return (bool) preg_match(
                    str_replace(array('\\\\\\*', '\\*'), array('*', '.+'), preg_quote($value)),
                    $m_value
                );
            case ISet::OP_NE:
                return $m_value != $value;
            case ISet::OP_NI:
                return is_array($value) && !in_array($m_value, $value);
            case ISet::OP_GE:
                return $m_value >= $value;
            case ISet::OP_LE:
                return $m_value <= $value;
            case ISet::OP_NB:
                return $m_value <= $value[0] || $m_value >= $value[1];
            case ISet::OP_NL:
                return !preg_match(
                    str_replace(array('\\\\\\*', '\\*'), array('*', '.+'), $value),
                    $m_value
                );
        }

        return false;
    }

    /**
     * 验证器集合。
     *
     * @internal
     *
     * @var array[]
     */
    protected static $zenValidators = array();

    /**
     * 初始化验证器事件。
     *
     * @return IValidator[]
     */
    protected function onInitValidators()
    {
        return array();
    }

    /**
     * 验证给定值是否符合属性要求。
     *
     * @param string $property 属性名
     * @param mixed  $value    待设置地值
     *
     * @return bool
     */
    final protected function validate($property, $value)
    {
        $s_class = get_class($this);
        if (!isset(self::$zenValidators[$s_class])) {
            self::$zenValidators[$s_class] = $this->onInitValidators();
        }
        if (!isset(self::$zenValidators[$s_class][$property])) {
            return true;
        }
        if (!self::$zenValidators[$s_class][$property] instanceof IValidator) {
            return false;
        }

        return self::$zenValidators[$s_class][$property]->verify($value);
    }
}
