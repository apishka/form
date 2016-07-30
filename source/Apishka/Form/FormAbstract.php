<?php

/**
 * Apishka form form abstract
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
     * Process structure
     */

    protected function processStructure()
    {
        $signature = $this->processSignatureField();
        if ($signature)
            $this->addField($signature);
    }

    /**
     * Is valid
     *
     * @return bool
     */

    public function isValid()
    {
        if (!$this->isSent())
            return false;

        $valid = true;
        foreach ($this->_fields as $field)
        {
            if (!$field->isValid())
                $valid = false;
        }

        if (!$valid)
            return false;

        $this->validate();

        return true;
    }

    /**
     * Validate
     */

    protected function validate()
    {
    }

    /**
     * Is sent
     *
     * @return bool
     */

    public function isSent()
    {
        if ($this->hasField('signature'))
            return $this->getField('signature')->isValid();

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
        $method = '__get' . $name;
        if (method_exists($this, $method))
            return $this->$method();

        if ($this->hasField($name))
            return $this->getField($name);

        throw new Exception('Property ' . var_export($name, true) . ' not available in ' . var_export(get_class($this), true));
    }

    /**
     * Get validator
     *
     * @return \Apishka\Validator\Validator
     */

    public function getValidator()
    {
        if ($this->_validator === null)
            $this->_validator = \Apishka\Validator\Validator::apishka();

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

    /**
     * Get unique id
     *
     * @return string
     */

    public function getUniqueId()
    {
        return get_class($this);
    }

    /**
     * Process signature field
     *
     * @return Apishka_Form_Field_Signature
     */

    protected function processSignatureField()
    {
        return Apishka_Form_Field_Signature::apishka('signature');
    }

    /**
     * Returns signature params
     *
     * @return array
     */

    public function getSignatureParams()
    {
        return array();
    }
}
