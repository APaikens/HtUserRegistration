<?php

namespace HtUserRegistration\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;
use HtUserRegistration\Mailer\Mailer;

class MailerFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        return new Mailer(
                $container->get('HtUserRegistration\ModuleOptions'),
                $container->get('MtMail\Service\Mail')
        );
    }

}
