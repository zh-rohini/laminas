<?php
namespace Student\Form;

use Laminas\Form\Form;

class StudentForm extends Form
{
    public function init()
    {
        $this->add([
            'name' => 'student',
            'type' => StudentFieldset::class,
            'options' => [
                'use_as_base_fieldset' => true,
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'submit',
            'attributes' => [
                'value' => 'Add',
            ],
        ]);
    }
}