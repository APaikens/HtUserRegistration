<?php

namespace HtUserRegistration\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use HtUserRegistration\Mapper\UserRegistrationMapper;

class UserRegistrationMapperFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('HtUserRegistration\ModuleOptions');
        $mapper = new UserRegistrationMapper();
        $mapper->setTableName($options->getRegistrationTableName());
        $entityPrototypeClass = $options->getRegistrationEntityClass();
        $mapper->setEntityPrototype(new $entityPrototypeClass);
        $mapper->setHydrator($container->get('HtUserRegistration\UserRegistrationHydrator'));
        $mapper->setDbAdapter($container->get('HtUserRegistration\DbAdapter'));

        return $mapper;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->__Invoke($serviceLocator,null);
    }
}
