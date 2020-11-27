<?php

namespace HtUserRegistration\Authentication\Adapter\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;

class EmailVerificationFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        return new \HtUserRegistration\Authentication\Adapter\EmailVerification(
                $container->get('HtUserRegistration\UserRegistrationMapper'),
                $container->get('zfcuser_user_mapper')
        );
    }

}
