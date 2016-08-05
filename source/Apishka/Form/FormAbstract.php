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
     * Global error key
     *
     * @type string
     */

    const GLOBAL_ERROR_KEY = '#structure';

    /**
     * Form error
     *
     * @var Throwable
     */

    private $_error = null;

    /**
     * Fields
     *
     * @var array
     */

    private $_fields = null;

    /**
     * Validator
     *
     * @var \Apishka\Transformer\Validator
     */

    private $_validator = null;

    /**
     * Process structure
     */

    protected function processStructure()
    {
        $signature = $this->getSignatureField();
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
        if ($this->getFieldErrors())
            return false;

        $this->validate(
            $this->toArray()
        );

        if ($this->getFieldErrors())
            return false;

        if ($this->getError())
            return false;

        return true;
    }

    /**
     * Validate
     *
     * @param array $data
     */

    protected function validate(array $data)
    {
    }

    /**
     * Converts form data to array
     *
     * @return array
     */

    public function toArray()
    {
        $result = array();
        foreach ($this->getFields() as $field)
            $result[$field->getStructureName()] = $field->value;

        return $result;
    }

    /**
     * Is sent
     *
     * @return bool
     */

    public function isSent()
    {
        if ($this->hasField('signature'))
            return $this->getField('signature')->getValueFromRequest() !== null;

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
     * @return \Apishka\Transformer\Validator
     */

    public function getValidator()
    {
        if ($this->_validator === null)
            $this->_validator = \Apishka\Transformer\Validator::apishka();

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
        $this->initializeFields();

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
        return array_key_exists($name, $this->getFields());
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
        return $this->getFields()[$name];
    }

    /**
     * Get fields
     *
     * @return array
     */

    public function getFields()
    {
        $this->initializeFields();

        return $this->_fields;
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
        $this->initializeFields();

        unset($this->_fields[$name]);

        return $this;
    }

    /**
     * Initialize fields
     *
     * @return Apishka_Form_FormAbstract this
     */

    protected function initializeFields()
    {
        if ($this->_fields === null)
        {
            $this->_fields = array();
            $this->processStructure();
        }

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

    protected function getSignatureField()
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

    /**
     * Draw
     *
     * @param string $tpl
     * @param array  $params
     * @param bool   $ajax
     * @param array  $result
     *
     * @return mixed|null
     */

    public function draw($tpl, array $params, $ajax = false, $result = array())
    {
        if ($ajax && $this->isSent())
            return $this->getArrayResponse($result);

        $this->drawTpl($tpl, $params);
    }

    /**
     * Get array response
     *
     * @param array $result
     *
     * @return array
     */

    protected function getArrayResponse($result)
    {
        if (!is_array($result))
            $result = array();

        $result['valid'] = $this->isValid();

        if (!$this->isValid())
        {
            $result['errors'] = array();
            foreach ($this->getFieldErrors() as $name => $error)
                $result['errors'][$name] = $this->getFieldError($name, $error);

            if ($this->getError() !== null)
            {
                $result['errors'][self::GLOBAL_ERROR_KEY] = $this->getFieldError(
                    static::GLOBAL_ERROR_KEY,
                    $this->getError()
                );
            }
        }

        return $result;
    }

    /**
     * Get field error data
     *
     * @param string    $field_name
     * @param Throwable $exception
     *
     * @return array
     */

    protected function getFieldError($field_name, $exception)
    {
        return array(
            'field'     => $name,
            'code'      => $exception->getCode(),
            'message'   => $exception->getMessage(),
        );
    }

    /**
     * Set field error
     *
     * @param string $field
     * @param array|string|Localizer_Translation $message
     *
     * @return void
     */

    protected function setFieldError($field, $message, $params, $code = 0)
    {
        $object = $field
            ? $this->getField($field)
            : $this
        ;

        $object->setError(
            \Apishka\Transformer\FriendlyException::apishka(
                array(
                    'message'   => $message,
                    'code'      => $code,
                ),
                $params
            )
        );
    }

    /**
     * Get field errors
     *
     * @return array
     */

    protected function getFieldErrors()
    {
        $errors = array();
        foreach ($this->getFields() as $field)
        {
            if (!$field->isValid())
                $errors[$field->structure_name] = $field->getError();
        }

        return $errors;
    }

    /**
     * Set error
     *
     * @param Throwable $exception
     *
     * @return Apishka_Form_FormAbstract
     */

    public function setError(Throwable $exception)
    {
        $this->_error = $exception;

        return $this;
    }

    /**
     * Get error
     *
     * @return Throwable
     */

    public function getError()
    {
        return $this->_error;
    }
}
