<?php 
namespace Student\Form;

use Student\Model\Entity;
use Laminas\Form\Fieldset;
use Laminas\Hydrator\ReflectionHydrator;

class StudentFieldset extends Fieldset
{

	public function init()
    {
    	$this->setHydrator(new ReflectionHydrator());
	    $this->setObject(new Entity('', '',''));

        $this->add([
            'type' => 'hidden',
            'name' => 'id',
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'name',
            'options' => [
                'label' => 'Name',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'age',
            'options' => [
                'label' => 'Age',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'address',
            'options' => [
                'label' => 'Address',
            ],
        ]);
    }

}