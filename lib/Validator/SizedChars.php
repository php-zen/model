<?php
/**
 * 定义限长（多字节）字符串验证器组件。
 *
 * @author    Snakevil Zen <zsnakevil@gmail.com>
 * @copyright © 2014 SZen.in
 * @license   LGPL-3.0+
 */

namespace Zen\Model\Validator;

use Zen\Model;

/**
 * 限长（多字节）字符串验证器组件。
 *
 * @package    Zen\Model
 * @subpackage Validator
 * @version    0.1.0
 * @since      0.1.0
 */
class SizedChars extends Validator
{
    /**
     * 最小长度。
     *
     * @internal
     *
     * @var int
     */
    protected $minLength;

    /**
     * 最大长度。
     *
     * @internal
     *
     * @var int
     */
    protected $maxLength;

    /**
     * 构造函数
     *
     * @param string $attribute 属性名
     * @param int    $minLength 最小长度要求
     * @param int    $maxLength 最大长度要求
     */
    public function __construct($attribute, $minLength, $maxLength = 0)
    {
        parent::__construct($attribute);
        $this->minLength = max(0, $minLength);
        $this->maxLength = 0 < $maxLength
            ? max($this->minLength, $maxLength)
            : 0;
    }

    /**
     * {@inheritdoc}
     *
     * @param  string $value 待验证地值
     * @return bool
     *
     * @throws ExSizedCharsTooShort 当验证值过短时
     * @throws ExSizedCharsTooLong  当验证值过长时
     */
    public function verify($value)
    {
        $i_len = mb_strlen($value, 'UTF-8');
        if ($i_len < $this->minLength) {
            throw new ExSizedCharsTooShort($this->attribute, $value, $this->minLength);
        }
        if ($this->maxLength && $i_len > $this->maxLength) {
            throw new ExSizedCharsTooLong($this->attribute, $value, $this->maxLength);
        }

        return true;
    }
}
