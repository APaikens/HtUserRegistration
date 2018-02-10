<?php
namespace HtUserRegistration\Controller\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use HtUserRegistration\Controller\UserRegistrationController;

class UserRegistrationFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $userRegistrationService = $container->get('HtUserRegistration\UserRegistrationService');
        $passwordForm = $container->get('HtUserRegistration\SetPasswordForm');
        $userMapper = $container->get('zfcuser_user_mapper');
        $userRegistrationMapper = $container->get('HtUserRegistration\UserRegistrationMapper');
        $moduleOptions = $container->get('HtUserRegistration\ModuleOptions');

        return new UserRegistrationController($userRegistrationService, $passwordForm, $userMapper, $userRegistrationMapper, $moduleOptions);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->__invoke($serviceLocator,null);
    }
}
