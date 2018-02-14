<?php

/**
 * Apishka form form abstract
 *
 * @method static static apishka()
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
     * @var string
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
     * Is valid
     *
     * @var bool
     */
    private $_is_valid = null;

    /**
     * Options
     *
     * @var array
     */
    private $_options = [];

    /**
     * Validator
     *
     * @var \Apishka\Transformer\Validator
     */
    private $_validator = null;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->_options = $this->getDefaultOptions();
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'use_default_on_error' => false,
        ];
    }

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
        if ($this->_is_valid === null)
        {
            $this->_is_valid = false;

            do
            {
                if (!$this->isSent())
                    break;

                if ($this->getFieldErrors())
                    break;

                $this->validate(
                    $this->toArray()
                );

                if ($this->getFieldErrors())
                    break;

                if ($this->getError())
                    break;

                $this->_is_valid = true;
            }
            while (false);
        }

        return $this->_is_valid;
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
        $result = [];
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
     * Isset
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $method = '__get' . $name;
        if (method_exists($this, $method))
            return true;

        if ($this->hasField($name))
            return true;

        return false;
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
     * Set values
     *
     * @param array $values
     * @param bool  $ignore
     *
     * @return $this
     */
    public function setValues($values, $ignore = false)
    {
        foreach ($values as $field => $value)
        {
            if (!$this->hasField($field))
            {
                if ($ignore)
                    continue;

                throw new Exception('Field ' . var_export($field, true) . ' not found in structure');
            }

            $this->getField($field)->setValue($value);
        }

        return $this;
    }

    /**
     * Add
     *
     * @param Apishka_Form_FieldAbstract $field
     *
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    protected function initializeFields()
    {
        if ($this->_fields === null)
        {
            $this->_fields = [];
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
        return [];
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
    public function draw($tpl, array $params, $ajax = false, $result = [])
    {
        if ($ajax && $this->isSent())
            return $this->getArrayResponse($result);

        $this->drawTpl($tpl, $params);
    }

    /**
     * Draw tpl
     *
     * @param string $tpl
     * @param array  $params
     */
    abstract protected function drawTpl($tpl, array $params);

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
            $result = [];

        $result['valid'] = $this->isValid();

        if (!$this->isValid())
        {
            $result['errors'] = [];
            foreach ($this->getFieldErrors() as $name => $error)
                $result['errors'][$name] = $this->getFieldError($this->getField($name)->name, $error);

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
        return [
            'field'     => $field_name,
            'code'      => $exception->getCode(),
            'message'   => $exception->getMessage(),
        ];
    }

    /**
     * Set field error
     *
     * @param string                             $field
     * @param array|string|Localizer_Translation $message
     * @param array                              $params
     * @param int                                $code
     */
    protected function setFieldError($field, $message, array $params = [], $code = 0)
    {
        $object = $field
            ? $this->getField($field)
            : $this
        ;

        $object->setError(
            $this->getFieldException($message, $params, $code)
        );
    }

    /**
     * Get field exception
     *
     * @param array|string|Localizer_Translation $message
     * @param array                              $params
     * @param int                                $code
     *
     * @return Throwable
     */
    protected function getFieldException($message, array $params = [], $code = 0)
    {
        return \Apishka\Transformer\FriendlyException::apishka(
            [
                'message'   => $message,
                'code'      => $code,
            ],
            $params
        );
    }

    /**
     * Get field errors
     *
     * @return array
     */
    protected function getFieldErrors()
    {
        $errors = [];
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

    /**
     * Process values
     *
     * @param array                      $values
     * @param Apishka_Form_FieldAbstract $field
     *
     * @return array
     */
    public function processValues(array $values, $field)
    {
        return $values;
    }

    /**
     * Set use default on error
     *
     * @param bool $flag
     *
     * @return $this
     */
    public function setUseDefaultOnError($flag)
    {
        return $this->setOption('use_default_on_error', (bool) $flag);
    }

    /**
     * Get use default on error
     *
     * @return bool
     */
    public function getUseDefaultOnError()
    {
        return $this->getOption('use_default_on_error');
    }

    /**
     * Set option
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return $this
     */
    protected function setOption($name, $value)
    {
        $this->_options[$name] = $value;

        return $this;
    }

    /**
     * Get option
     *
     * @param string $name
     *
     * @return mixed
     */
    protected function getOption($name)
    {
        if (!$this->hasOption($name))
            return;

        return $this->_options[$name];
    }

    /**
     * Has option
     *
     * @param string $name
     *
     * @return bool
     */
    protected function hasOption($name)
    {
        return array_key_exists($name, $this->_options);
    }
}
