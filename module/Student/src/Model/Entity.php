<?php 
namespace Student\Model;

class Entity{

    private $id;
	private $name;
	private $address;
	private $age;

	public function __construct($name, $address, $age, $id = null)
    {
        $this->name = $name;
        $this->address = $address;
        $this->age = $age;
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getAge(){
        return $this->age;
    }

    public function getAddress(){
        return $this->address;
    }
}