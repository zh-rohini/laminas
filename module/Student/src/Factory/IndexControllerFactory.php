<?php 
namespace Student\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

use Student\Controller\IndexController;
use Student\Model\Student;
use Student\Form\StudentForm;

class IndexControllerFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {   
        $formManager = $container->get('FormElementManager');
        return new IndexController(
            $container->get(Student::class),
            $formManager->get(StudentForm::class)
        );
    }
}