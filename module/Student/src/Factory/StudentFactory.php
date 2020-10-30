<?php 
namespace Student\Factory;

use Interop\Container\ContainerInterface;
use Student\Model\Student;
use Student\Model\Entity;
use Laminas\Hydrator\ReflectionHydrator;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class StudentFactory implements FactoryInterface
{
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new Student(
            $container->get(AdapterInterface::class),
            new ReflectionHydrator(),
            new Entity('', '','')
        );
    }
}