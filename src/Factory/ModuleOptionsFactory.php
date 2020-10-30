<?php

namespace HtUserRegistration\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use HtUserRegistration\Options\ModuleOptions;

class ModuleOptionsFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        return new ModuleOptions($container->get('config')['ht_user_registration']);
    }

}
