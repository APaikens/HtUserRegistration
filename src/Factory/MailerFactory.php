<?php
namespace HtUserRegistration\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use HtUserRegistration\Mailer\Mailer;

class MailerFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        return new Mailer(
            $container->get('HtUserRegistration\ModuleOptions'),
            $container->get('MtMail\Service\Mail')
        );
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
