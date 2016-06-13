<?php
/**
 * DDL语句解析组件。
 *
 * @author    Yao <yaogaoyu@gmail.com>
 * @copyright © 2016 BiGood.com
 * @license   GPL-3.0+
 */

namespace Zen\Model;

use Exception;

/**
 * 根据DDL语句生成Model，ModelSet，Dao的内容
 */
class ModelGenerator
{
    /**
     * Model模板
     *
     * @var string
     */
    private $modelTemplate;

    /**
     * ModelSet模板
     *
     * @var string
     */
    private $modelSetTemplate;

    /**
     * Dao模板
     *
     * @var string
     */
    private $daoTemplate;

    /**
     * ddl语句的解析结果
     *
     * @var array
     */
    private $ormInfo;

    /**
     * Model所属包
     *
     * @var string
     */
    private $package;


    /**
     * 构造函数
     */
    public function __construct($ddl, $package)
    {
        $this->genModelTemplate();
        $this->genModelSetTemplate();
        $this->genDaoTemplate();
        $this->ormInfo = $this->parseDDL($ddl);
        $this->package = $package;
    }

    /**
     * 生成Model内容
     *
     * @return string Model文件内容
     */
    public function genModel()
    {
        $modelContent = str_replace('#{package}', $this->package, $this->modelTemplate);
        $modelContent = str_replace('#{namespace}', $this->package.'\Model', $modelContent);
        $modelContent = str_replace('#{clsName}', $this->ormInfo['modelName'], $modelContent);
        $propertiesAnnotation = $fields = $fieldCases = $validatorHash = '';
        foreach ($this->ormInfo['fields'] as $index => $field) {
            $propertiesAnnotation .=
                $this->genModelPropAnnotation(substr($field['varName'], 1), $field['varType']);
            $fields .= $this->genModelProp($field['varName'], $field['varType']);
            $fieldCases .= "            case '".substr($field['varName'], 1)."':\n";
            $validatorHash .= empty($field['varRange']) ?
            '' :
            ('            ' . substr($field['varName'], 1) . ' => ' . $field['varRange'] . ",\n");
        }
        $modelContent = str_replace('#{propertiesAnnotation}', $propertiesAnnotation, $modelContent);
        $modelContent = str_replace('#{fields}', substr($fields, 0, strlen($fields) - 2), $modelContent);
        $modelContent = str_replace('#{fieldCases}', substr($fieldCases, 0, strlen($fieldCases) - 1), $modelContent);
        $modelContent =
            str_replace('#{validatorHash}', substr($validatorHash, 0, strlen($validatorHash) - 2), $modelContent);
        return $modelContent;
    }

    /**
     * 生成ModelSet内容
     *
     * @return string ModelSet文件内容
     */
    public function genModelSet()
    {
        $modelSetContent = str_replace('#{package}', $this->package, $this->modelSetTemplate);
        $modelSetContent = str_replace('#{namespace}', $this->package.'\Model', $modelSetContent);
        $modelSetContent = str_replace('#{clsName}', $this->ormInfo['modelName'], $modelSetContent);
        return $modelSetContent;
    }

    /**
     * 生成Dao内容
     *
     * @return string Dao文件内容
     */
    public function genDao()
    {
        $daoContent = str_replace('#{package}', $this->package, $this->daoTemplate);
        $daoContent = str_replace('#{namespace}', $this->package.'\Model', $daoContent);
        $daoContent = str_replace('#{clsName}', $this->ormInfo['modelName'], $daoContent);
        $daoContent = str_replace('#{tableName}', $this->ormInfo['tableName'], $daoContent);
        $daoContent = str_replace('#{pkField}', $this->ormInfo['pkField'], $daoContent);
        $daoContent = str_replace('#{pkVarName}', substr($this->ormInfo['pkVarName'], 1), $daoContent);
        $fieldsMap = '';
        foreach ($this->ormInfo['fields'] as $index => $field) {
            $fieldsMap .= "        '" . $field['name'] . "' => '" . substr($field['varName'], 1) . "',\n";
        }
        $daoContent = str_replace('#{fieldsMap}', substr($fieldsMap, 0, strlen($fieldsMap)-2), $daoContent);
        return $daoContent;
    }

    /**
     * 获取表名
     *
     * @return string ddl创建的表名
     */
    public function getTableName()
    {
        return $this->ormInfo['tableName'];
    }

    /**
     * 获取Model名
     *
     * @return string 表对应的Model名
     */
    public function getModelName()
    {
        return $this->ormInfo['modelName'];
    }

    /**
     * 生成Model模板
     */
    private function genModelTemplate()
    {
        $this->modelTemplate = <<<'MODEL_TEMPLATE'
<?php
/**
 * <Model Descriptions>
 * @author    <ORMGenerator>
 * @copyright © 2016 BiGood.com
 * @license   GPL-3.0+
 */

namespace #{namespace};

use Zen\Model as zenModel;

/**
 * # Class description
 *
 * @package    #{package}
 * @since      v0.1.0
 *
#{propertiesAnnotation}
 */

class #{clsName} extends Core\Model {
#{fields}

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  string $property 属性名
     * @return mixed
     */
    protected function zenGet($property)
    {
        switch ($property) {
#{fieldCases}
                break;
            default:
                return;
        }

        return $this->$property;
    }

    /**
     * {@inheritdoc}
     *
     * @internal
     *
     * @param  string $property 属性名
     * @param  mixed  $value    新值
     * @return void
     */
    protected function zenSet($property, $value)
    {
        parent::zenSet($property, $value);
        switch ($property) {
#{fieldCases}
                $this->$property = $value;
                break;
            default:
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return Dao\Work
     */
    protected function newDao()
    {
        return Dao\#{clsName}::singleton();
    }

    /**
     * {@inheritdoc}
     *
     * @return IValidator[]
     */
    protected function onInitValidators()
    {
        return array(
#{validatorHash}
        );
    }
}

MODEL_TEMPLATE;
    }

    /**
     * 生成ModelSet模板
     */
    private function genModelSetTemplate()
    {
        $this->modelSetTemplate = <<< 'MODEL_SET_TEMPLATE'
<?php
/**
 * <Model Descriptions>集合
 * @author    <ORMGenerator>
 * @copyright © 2016 BiGood.com
 * @license   GPL-3.0+
 */

namespace #{namespace};

use Zen\Model as ZenModel;

/**
 * <Model Descriptions>集合。
 *
 * @package    #{package}
 * @since      v0.1.0
 */
class #{clsName}Set extends ZenModel\Set
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    const MODEL_CLASS = '#{namespace}\#{clsName}';

    /**
     * {@inheritdoc}
     *
     * @return Dao\#{clsName}
     */
    protected function newDao()
    {
        return Dao\#{clsName}::singleton();
    }
}

MODEL_SET_TEMPLATE;
    }

    /**
     * 生成Dao模板
     */
    private function genDaoTemplate()
    {
        $this->daoTemplate = <<< 'DAO_TEMPLATE'
<?php
/**
 * <Dao Descriptions>
 *
 * @author    <ORMGenerator>
 * @copyright © 2016 BiGood.com
 * @license   GPL-3.0+
 */

namespace #{namespace}\Dao;

use snakevil\zen;

/**
 * <Dao Descriptions>数据访问对象。
 *
 * @package    #{package}
 * @since      v0.1.0
 */
class #{clsName} extends zen\Model\Dao
{
    /**
     * {@inheritdoc}
     *
     * @var string
     */
    const TABLE = '#{tableName}';

    /**
     * {@inheritdoc}
     *
     * @var string
     */
    const PK = '#{pkField}';

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected static $map = array(
#{fieldsMap}
    );

    /**
     * {@inheritdoc}
     *
     * @var string[]
     */
    protected static $types = array(
        '#{pkVarName}' => self::TYPE_STRING
    );
}

DAO_TEMPLATE;
    }

    /**
     * 解析DDL语句
     *
     * @param $ddl DDL语句（create table语句）
     * @return array 解析后的table-model映射信息
     */
    private function parseDDL($ddl)
    {
        $info = array();
        // 获取表名
        $reg = '/create table (\S+)\s*(?:(?X)\()(.*)(?:\)$|\);$)/isU';
        preg_match($reg, $ddl, $result);
        $info['tableName'] = $result[1];
        // 根据表名生成model名
        $tableNameSplits = split('[-_]', $info['tableName']);
        if (sizeof($tableNameSplits) == 1) {
            $info['modelName'] = strtoupper($info['tableName'][0]).strtoLower(substr($info['tableName'], 1));
        } else {
            foreach ($tableNameSplits as $index => $value) {
                $info['modelName'] .= (strtoupper($value[0]).strtoLower(substr($value, 1)));
            }
        }
        // $info['fieldsStr'] = preg_replace('/(?:^\s|\s)*((?:\S\s?)+(?:,|\S))(?:\s*|\s*$)/ms', '${1}', $result[2]);
        // 解析字段语句
        $fieldsStr = preg_replace('/[^\S]+\s+[^\S]*/', '', $result[2]);
        $fieldsStr = preg_replace('/\s*,\s*/', ',', $fieldsStr);
        // 分割字段，使字段变为一个数组
        $preSplitStr = preg_replace('/(\S+|\)),((?U)[^\)]+\s\S*)/', '$1@$2', $fieldsStr);
        $fieldStrArr = split("@", $preSplitStr);
        $info['fields'] = array();
        $info['pk'] = '';
        foreach ($fieldStrArr as $index => $fieldStr) {
            $fieldName = substr($fieldStr, 0, strpos($fieldStr, " "));
            $fieldType = preg_replace('/\s+((?U)\S\s*)+/', '', substr($fieldStr, strpos($fieldStr, " ")+1));
            $info['fields'][$index]['name'] = $fieldName;
            $info['fields'][$index]['type'] = $fieldType;
            // 根据字段名生成model名
            $fieldNameSplits = split('[-_]', $fieldName);
            if (sizeof($fieldNameSplits) == 1) {
                $info['fields'][$index]['varName'] = '$'.strtolower($fieldName[0]).substr($fieldName, 1);
            } else {
                foreach ($fieldNameSplits as $fieldIndex => $value) {
                    $info['fields'][$index]['varName'] .= $fieldIndex == 0 ?
                        ('$' . strtolower($value[0])) :
                        strtoupper($value[0]) . strtolower(substr($value, 1));
                }
            }
            // 根据字段类型生成model属性类型
            $var = $this->genVar($info['fields'][$index]['varName'], $fieldType);
            $info['fields'][$index]['varType'] = $var['type'];
            if (!empty($var['range'])) {
                $info['fields'][$index]['varRange'] = $var['range'];
            }
            $isPK = strpos($fieldStr, "primary key");
            if ($isPK) {
                $info['pkField'] = $fieldName;
                $info['pkVarName'] = $info['fields'][$index]['varName'];
            }
        }
        if (empty($info['pkField']) || empty($info['pkVarName'])) {
            throw new Exception('表 '.$info['tableName'].' 未设置主键字段。');
        }
        return $info;
    }

    /**
     * 生成字段类型及范围（char或varchar类型才有范围）
     *
     * @param $varName 字段对应的属性名
     * @param $fieldType 字段类型
     * @return 字段对应的属性类型
     */
    private function genVar($varName, $fieldType)
    {
        $type = substr($fieldType, 0, strpos($fieldType, '(') ? strpos($fieldType, '(') : strlen($fieldType));
        // var_dump($fieldType.strpos($fieldType, '('));
        if (strpos($fieldType, '(')) {
            $rangeStr = preg_replace('/(\S+\()|(\)\S*)/', '', $fieldType);
            $rageArr = split(',', $rangeStr);
            $min = sizeof($rageArr) > 1 ? $rageArr[0] : 0;
            $max = sizeof($rageArr) > 1 ? $rageArr[1] : $rageArr[0];
            $range = $min.', '.$max;
        }
        switch ($type) {
            case 'char':
            case 'varchar':
                $var['type'] = 'string';
                $var['range'] = 'new ZenModel\Validator\SizedChars(\''.substr($varName, 1).'\', '.$range.')';
                break;
            case 'int':
                $var['type'] = 'int';
                break;
            case 'datetime':
                $var['type'] = 'Zen\Core\Type\DateTime';
                break;
            default:
                $var['type'] = 'string';
                break;
        }
        return $var;
    }

    /**
     * 生成关于字段映射的注释内容
     *
     * @param $varName 字段对应的属性名
     * @param $fieldType 字段类型
     * @return 字段映射的注释内容
     */
    private function genModelPropAnnotation($varName, $fieldType)
    {
        return " * @property    ".$fieldType."    ".$varName."\n";
    }

    /**
     * 生成字段定义及注释内容
     *
     * @param $varName 字段对应的属性名
     * @param $varType 字段对应的属性类型
     * @return string model中的属性注释及属性定义
     */
    private function genModelProp($varName, $varType)
    {
        $fieldTemplate = <<<'FIELD_TEMPLATE'
    /**
     * <FIELD DESCRIPTION>
     *
     * @internal
     *
     * @var #{varType}
     */
    protected #{varName};
FIELD_TEMPLATE;
        $field = str_replace('#{varType}', $varType, $fieldTemplate);
        $field = str_replace('#{varName}', $varName, $field);
        return $field."\n\n";
    }
}
