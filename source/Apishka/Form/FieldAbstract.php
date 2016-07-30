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
     * @var Exception
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
     * Construct
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
     * Initialize
     *
     * @param Apishka_Form_FormAbstract $form
     *
     * @return Admin_ElementAbstract this
     */

    public function initialize(Apishka_Form_FormAbstract $form)
    {
        $this->_form = $form;

        return $this;
    }

    /**
     * Get default options
     *
     * @return array
     */

    protected function getDefaultOptions()
    {
        return array(
            'value'         => null,
            'required'      => false,
            'validations'   => array(),
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
     * Set name
     *
     * @param string $name
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setName($name)
    {
        return $this->setOption('name', (string) $name);
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
        if (!$this->getOption('structure_name'))
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
        // triggers validations
        $value = $this->__getValue();

        return $this->getError() === null;
    }

    /**
     * Set default
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

        return $this->getValue();
    }

    /**
     * Validate
     *
     * @return mixed
     */

    protected function validate()
    {
        try
        {
            return $this->getForm()->getValidator()->validate(
                $this->getValueFromRequest(),
                $this->getMergedValidations()
            );
        }
        catch (\Apishka\Validator\Exception $e)
        {
            $this->_error = $e;

            return $this->getValueFromRequest();
        }
    }

    /**
     * Get default validations
     *
     * @return array
     */

    protected function getDefaultValidations()
    {
        return array();
    }

    /**
     * Get merged validations
     *
     * @return array
     */

    protected function getMergedValidations()
    {
        $validations = $this->getValidations();
        $validations = array_reverse($validations, true);

        $default_validations = $this->getDefaultValidations();
        $default_validations = array_reverse($default_validations, true);

        $result = array_replace($default_validations, $validations);
        $result = array_reverse($result, true);

        return $result;
    }

    /**
     * Set validations
     *
     * @param mixed $validate
     *
     * @return Apishka_Form_FieldAbstract
     */

    public function setValidations($validations)
    {
        return $this->setOption('validations', $validations);
    }

    /**
     * Get validations
     *
     * @return Admin_FieldAbstract this
     */

    public function getValidations()
    {
        return $this->getOption('validations');
    }

    /**
     * Add validations
     *
     * @param array $validations
     *
     * @return Admin_FieldAbstract this
     */

    public function addValidations($validations)
    {
        $current = $this->getValidations();
        $current += $validations;

        return $this->setValidations($current);
    }

    /**
     * Get error
     *
     * @return \Apishka\Validator\Exception
     */

    public function getError()
    {
        return $this->_error;
    }

    /**
     * Get value from request
     *
     * @return mixed
     */

    public function getValueFromRequest()
    {
        if (array_key_exists($this->getName(), $_REQUEST))
            return $_REQUEST[$this->getName()];

        return null;
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
        return $this->getValues();
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
        return $this->_options[$name];
    }
}
