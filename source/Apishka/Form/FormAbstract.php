<?php

/**
 * Apishka form form abstract
 *
 * @abstract
 *
 * @author Evgeny Reykh <evgeny@reykh.com>
 */

abstract class Apishka_Form_FormAbstract
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Fields
     *
     * @var array
     */

    private $_fields = array();

    /**
     * Validator
     *
     * @var \Apishka\Validator\Validator
     */

    private $_validator = null;

    /**
     * Is valid
     *
     * @return bool
     */

    public function isValid()
    {
        if (!$this->isSent())
            return false;

        foreach ($this->_fields as $field)
        {
            if (!$field->isValid())
                return false;
        }

        return true;
    }

    /**
     * Is sent
     *
     * @return bool
     */

    public function isSent()
    {
        return true;
    }

    /**
     * Get
     *
     * @param string $name
     *
     * @return mixed
     */

    public function __get($name)
    {
        if ($this->hasField($name))
            return $this->getField($name);

        $method = '__get' . $name;
        if (method_exists($this, $method))
            return $this->$method();

        throw new \Exception('Property ' . var_export($name, true) . ' not available in ' . var_export(get_class($this), true));
    }

    /**
     * Get validator
     *
     * @return \Apishka\Validator\Validator
     */

    public function getValidator()
    {
        if ($this->_validator === null)
            $this->_validator = new \Apishka\Validator\Validator();

        return $this->_validator;
    }

    /**
     * Add
     *
     * @param Apishka_Form_FieldAbstract $field
     *
     * @return Apishka_Form_FormAbstract this
     */

    public function addField(Apishka_Form_FieldAbstract $field)
    {
        $name = $field->getStructureName();

        if ($this->hasField($name))
            throw new LogicException('Field ' . var_export($name, true) . ' already exists in structure');

        $this->_fields[$name] = $field->initialize($this);

        return $this;
    }

    /**
     * Has field
     *
     * @param string $name
     *
     * @return bool
     */

    public function hasField($name)
    {
        return array_key_exists($name, $this->_fields);
    }

    /**
     * Get field
     *
     * @param string $name
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function getField($name)
    {
        return $this->_fields[$name];
    }

    /**
     * Del field
     *
     * @param string $name
     *
     * @return Apishka_Form_FormAbstract this
     */

    public function delField($name)
    {
        unset($this->_fields[$name]);

        return $this;
    }
}
