<?php

namespace HtUserRegistration\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use HtUserRegistration\Service\UserRegistrationService;

class UserRegistrationServiceFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        return new UserRegistrationService(
                $container->get('HtUserRegistration\UserRegistrationMapper'),
                $container->get('HtUserRegistration\ModuleOptions'),
                $container->get('HtUserRegistration\Mailer\Mailer'),
                $container->get('zfcuser_user_mapper'),
                $container->get('zfcuser_module_options')
        );
    }

}
