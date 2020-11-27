<?php

namespace HtUserRegistration\Controller\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use HtUserRegistration\Controller\UserRegistrationController;

class UserRegistrationFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        $userRegistrationService = $container->get('HtUserRegistration\UserRegistrationService');
        $passwordForm = $container->get('HtUserRegistration\SetPasswordForm');
        $userMapper = $container->get('zfcuser_user_mapper');
        $userRegistrationMapper = $container->get('HtUserRegistration\UserRegistrationMapper');
        $moduleOptions = $container->get('HtUserRegistration\ModuleOptions');

        return new UserRegistrationController($userRegistrationService, $passwordForm, $userMapper, $userRegistrationMapper, $moduleOptions);
    }

}
