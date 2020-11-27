<?php

namespace HtUserRegistration\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use HtUserRegistration\Mapper\UserRegistrationMapper;

class UserRegistrationMapperFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        $moduleOptions = $container->get('HtUserRegistration\ModuleOptions');
        $mapper = new UserRegistrationMapper();
        $mapper->setTableName($moduleOptions->getRegistrationTableName());
        $entityPrototypeClass = $moduleOptions->getRegistrationEntityClass();
        $mapper->setEntityPrototype(new $entityPrototypeClass);
        $mapper->setHydrator($container->get('HtUserRegistration\UserRegistrationHydrator'));
        $mapper->setDbAdapter($container->get('HtUserRegistration\DbAdapter'));

        return $mapper;
    }

}
