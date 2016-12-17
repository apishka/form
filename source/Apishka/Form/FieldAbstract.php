<?php

/**
 * Apishka form field abstract
 */

abstract class Apishka_Form_FieldAbstract
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Error
     *
     * @var Throwable
     */

    private $_error = null;

    /**
     * Form
     *
     * @var Apishka_Form_FormAbstract
     */

    private $_form = null;

    /**
     * Options
     *
     * @var array
     */

    private $_options = array();

    /**
     * Value
     *
     * @var mixed
     */

    private $_value = null;

    /**
     * Value validated
     *
     * @var bool
     */

    private $_value_validated = false;

    /**
     * Values
     *
     * @var array
     */

    private $_values = null;

    /**
     * Is initialized
     *
     * @var bool
     */

    private $_is_initialized = false;

    /**
     * Construct
     * @param mixed $name
     */

    public function __construct($name)
    {
        $this->setName($name);
        $this->_options = array_replace_recursive(
            $this->_options,
            $this->getDefaultOptions()
        );
    }

    /**
     * Call static element
     *
     * @param array  $data
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */

    protected static function __apishkaElement(array $data, $name, array $arguments)
    {
        return new $data['class']('element');
    }

    /**
     * Returns static prefixes
     *
     * @return string
     */

    public function __apishkaGetPrefixes()
    {
        return 'apishka|element';
    }

    /**
     * Initialize
     *
     * @param Apishka_Form_FormAbstract $form
     *
     * @return Admin_ElementAbstract this
     */

    public function initialize(Apishka_Form_FormAbstract $form)
    {
        $this->_is_initialized = true;
        $this->_form = $form;

        return $this;
    }

    /**
     * Get is initialized
     *
     * @return bool
     */

    public function isInitialized()
    {
        return $this->_is_initialized;
    }

    /**
     * Get default options
     *
     * @return array
     */

    protected function getDefaultOptions()
    {
        return array(
            'value'             => null,
            'values'            => null,
            'default_value'     => null,
            'required'          => false,
            'transformations'   => $this->getDefaultTransformations(),
        );
    }

    /**
     * Get form
     *
     * @return Apishka_Form_FormAbstract
     */

    protected function getForm()
    {
        if ($this->_form === null)
            throw new LogicException('Field is not initialized');

        return $this->_form;
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

        throw new \Exception('Property ' . var_export($name, true) . ' not available in ' . var_export(get_class($this), true));
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

        return false;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setName($name)
    {
        if (!$this->hasOption('structure_name') || !$this->getOption('structure_name'))
            $this->setOption('structure_name', (string) $name);

        return $this
            ->setOption('name', (string) $name)
        ;
    }

    /**
     * Get name
     *
     * @return string
     */

    public function getName()
    {
        return $this->getOption('name');
    }

    /**
     * Get name
     *
     * @return string
     */

    protected function __getName()
    {
        return $this->getName();
    }

    /**
     * Set ID
     *
     * @param string $id
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setId($id)
    {
        return $this->setOption('id', $id);
    }

    /**
     * Get ID
     *
     * @return string
     */

    public function getId()
    {
        return $this->getOption('id');
    }

    /**
     * Get ID
     *
     * @return string
     */

    protected function __getId()
    {
        if ($this->getId())
            return $this->getId();

        return $this->getName() . '_' . md5($this->getName() . $this->getForm()->getUniqueId());
    }

    /**
     * Set structure name
     *
     * @param string $name
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setStructureName($name)
    {
        return $this->setOption('structure_name', $name);
    }

    /**
     * Get structure name
     *
     * @return string
     */

    public function getStructureName()
    {
        if (!$this->hasOption('structure_name'))
            return $this->getName();

        return $this->getOption('structure_name');
    }

    /**
     * Get structure name
     *
     * @return string
     */

    protected function __getStructure_name()
    {
        return $this->getStructureName();
    }

    /**
     * Set required
     *
     * @param bool $required
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setRequired($required)
    {
        return $this->setOption('required', (bool) $required);
    }

    /**
     * Get required
     *
     * @return bool
     */

    public function getRequired()
    {
        return $this->getOption('required');
    }

    /**
     * Get required
     *
     * @return bool
     */

    protected function __getRequired()
    {
        return $this->getRequired();
    }

    /**
     * Get is valid
     *
     * @return array
     */

    public function isValid()
    {
        if (!$this->getForm()->isSent())
            return true;

        $this->validate();

        return $this->getError() === null;
    }

    /**
     * Set value
     *
     * @param mixed $value
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setValue($value)
    {
        return $this->setOption('value', $value);
    }

    /**
     * Get value
     *
     * @return mixed
     */

    public function getValue()
    {
        return $this->getOption('value');
    }

    /**
     * Get value
     *
     * @return mixed
     */

    protected function __getValue()
    {
        if ($this->getForm()->isSent())
            return $this->validate();

        return $this->getValue() ?? $this->getDefault();
    }

    /**
     * Set default
     *
     * @param mixed $value
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setDefault($value)
    {
        return $this->setOption('default_value', $value);
    }

    /**
     * Get value
     *
     * @return mixed
     */

    public function getDefault()
    {
        return $this->getOption('default_value');
    }

    /**
     * Get value
     *
     * @return mixed
     */

    protected function __getDefault()
    {
        return $this->getDefault();
    }

    /**
     * Validate
     *
     * @return mixed
     */

    protected function validate()
    {
        if (!$this->_value_validated)
        {
            try
            {
                $this->_value = $this->runValidations();
            }
            catch (\Apishka\Transformer\Exception $e)
            {
                $this->_value = $this->setError($e)->getValueFromRequest();
            }
            finally
            {
                $this->_value_validated = true;
            }
        }

        return $this->_value;
    }

    /**
     * Run validations
     *
     * @return mixed
     * @param  null|mixed $value
     */

    public function runValidations($value = null)
    {
        return $this->getForm()->getValidator()->validate(
            $value ?? $this->getValueFromRequest(),
            $this->getTransformations()
        );
    }

    /**
     * Get default transformations
     *
     * @return array
     */

    protected function getDefaultTransformations()
    {
        return array();
    }

    /**
     * Set transformations
     *
     * @param mixed $validate
     * @param mixed $transformations
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function setTransformations($transformations)
    {
        return $this->setOption('transformations', $transformations);
    }

    /**
     * Get transformations
     *
     * @param bool $before
     *
     * @return Admin_FieldAbstract this
     */

    public function getTransformations()
    {
        return $this->getOption('transformations');
    }

    /**
     * Push transformation
     *
     * @param string $transformation
     * @param array  $options
     *
     * @return Admin_FieldAbstract this
     */

    public function pushTransformation($transformation, array $options = array())
    {
        $transformations = $this->getTransformations();
        $transformations[$transformation] = $options;

        return $this->setTransformations($transformations);
    }

    /**
     * Del transformation
     *
     * @param string $transformation
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function delTransformation($transformation)
    {
        $transformations = $this->getTransformations();
        unset($transformations[$transformation]);

        return $this->setTransformations($transformations);
    }

    /**
     * Unshift transformation
     *
     * @param string $transformation
     * @param array  $options
     *
     * @return Admin_FieldAbstract this
     */

    public function unshiftTransformation($transformation, array $options = array())
    {
        return $this->setTransformations(
            array(
                $transformation => $options,
            )
            +
            $this->getTransformations()
        );
    }

    /**
     * Get error
     *
     * @return \Apishka\Transformer\Exception
     */

    public function getError()
    {
        return $this->_error;
    }

    /**
     * Get error message
     *
     * @return string|null
     */

    protected function __getErrorMessage()
    {
        if ($this->isValid())
            return;

        return $this->getError()->getMessage();
    }

    /**
     * Get error code
     *
     * @return int|null
     */

    protected function __getErrorCode()
    {
        if ($this->isValid())
            return;

        return $this->getError()->getCode();
    }

    /**
     * Set error
     *
     * @param Throwable $exception
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function setError(Throwable $exception)
    {
        $this->_error = $exception;

        return $this;
    }

    /**
     * Get value from request
     *
     * @return mixed
     */

    public function getValueFromRequest()
    {
        $func = $this->getRequestGetter();
        if ($func)
        {
            if ($func instanceof \Closure)
                return $func($this);

            throw new UnexpectedValueException('Option request_getter is not function');
        }

        if (array_key_exists($this->getName(), $_POST))
            return $_POST[$this->getName()];

        if (array_key_exists($this->getName(), $_GET))
            return $_GET[$this->getName()];

        return $this->getDefault();
    }

    /**
     * Set values
     *
     * @param mixed $values
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setValues($values)
    {
        if (!is_array($values) && !($values instanceof \Closure))
            throw new \UnexpectedValueException();

        // Restore cached values
        $this->_values = null;

        return $this->setOption('values', $values);
    }

    /**
     * Get values
     *
     * @return array
     */

    public function getValues()
    {
        return $this->getOption('values');
    }

    /**
     * Get values
     *
     * @return array
     */

    protected function __getValues()
    {
        if ($this->_values === null)
        {
            $this->_values = is_array($this->getValues())
                ? $this->getValues()
                : call_user_func($this->getValues())
            ;

            $this->_values = $this->getForm()->processValues($this->_values, $this);
        }

        return $this->_values;
    }

    /**
     * Set request getter
     *
     * @param mixed $getter
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function setRequestGetter($getter)
    {
        return $this->setOption('request_getter', $getter);
    }

    /**
     * Get request getter
     *
     * @return mixed
     */

    public function getRequestGetter()
    {
        return $this->getOption('request_getter');
    }

    /**
     * Set option
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return Apishka_Form_FieldAbstract this
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
