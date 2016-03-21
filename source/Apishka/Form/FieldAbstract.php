<?php

/**
 * Apishka form field abstract
 *
 * @abstract
 *
 * @author Evgeny Reykh <evgeny@reykh.com>
 */

abstract class Apishka_Form_FieldAbstract
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Options
     *
     * @var array
     */

    private $_options = array();

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
        return array(
            'default'   => null,
            'required'  => false,
        );
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
     * Set default
     *
     * @param mixed $value
     *
     * @return Apishka_Form_FieldAbstract this
     */

    public function setDefault($value)
    {
        return $this->setOption('default', $value);
    }

    /**
     * Get value
     */

    public function getDefault()
    {
        return $this->getOption('default');
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
