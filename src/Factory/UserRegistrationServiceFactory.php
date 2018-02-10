<?php
namespace HtUserRegistration\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use HtUserRegistration\Service\UserRegistrationService;

class UserRegistrationServiceFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        return new UserRegistrationService(
            $container->get('HtUserRegistration\UserRegistrationMapper'),
            $container->get('HtUserRegistration\ModuleOptions'),
            $container->get('HtUserRegistration\Mailer\Mailer'),
            $container->get('zfcuser_user_mapper'),
            $container->get('zfcuser_module_options')
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
