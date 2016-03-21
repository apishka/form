# Draft

```
<?php

/**
 * Main modules user form common
 *
 * @uses Apishka_Form_FormAbstract
 *
 * @author Evgeny Reykh <evgeny@reykh.com>
 */

class Main_Modules_User_Form_Common extends Apishka_Form_FormAbstract
{
    protected function processStructure()
    {
        $this->add(
            Apishka_Form_Field_Int::jihad('make_id')
                ->setRequired(true)
        );

        $this->add(
            Apishka_Form_Field_Int::jihad('model_id')
                ->setValues(
                    function()
                    {
                        if (!$this->__get('make_id')->isValid())
                            return array();

                        if (!$this->__get('make_id')->value)
                            return array();

                        $models = Vehicle_Models::prepareByMakeId($this->__get('make_id')->value);

                        $result = array();
                        foreach ($models = as $model)
                            $result[$model->use_id] = $model->name;

                        return $result;
                    }
                )
        );
    }
}

class Controller
{

    // ...

    /**
     * Method form
     *
     * @param mixed $params
     * @param mixed $vehicle_id
     *
     * @return void
     */

    public function methodForm($params, $vehicle_id)
    {
        $vehicle = Vehicle::prepareById($vehicle_id);

        $form = Main_Modules_User_Form_Common::jihad();

        // Set mass defaults
        $form->setValues(
            array(
                'make_id'   => $vehicel->make_id,
                'model_id'  => $vehicle->model_id,
            )
        );

        // Same as
        $form->make_id->setValue($vehicle->make_id);
        $form->model_id->setValue($vehicle->model_id);

        if ($form->isValid())
        {
            Order::performCreate(
                $form->make_id->value,
                $form->model->value
            );
        }

        return $form->draw(
            array(
                'vehicle'   => $vehicle,
            )
        );
    }
}
```
