<?php
namespace HtUserRegistration;

use Laminas\EventManager\EventInterface;
use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;
use Laminas\ModuleManager\Feature\BootstrapListenerInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\ModuleManager\Feature\ServiceProviderInterface;

class Module implements 
    BootstrapListenerInterface,
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBootstrap(EventInterface $e)
    {
        /** @var \Laminas\Mvc\Application */
        $application    = $e->getParam('application');
        $eventManager   = $application->getEventManager();
        $sharedManager  = $eventManager->getSharedManager();
        $sharedManager->attach('ZfcUser\Service\User', 'register.post', function (EventInterface $event) use ($application) {
            $serviceLocator = $application->getServiceManager();
            $userRegistrationService = $serviceLocator->get('HtUserRegistration\UserRegistrationService');
            $userRegistrationService->onUserRegistration($event);
        });
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceConfig()
    {
        return [
            'factories' => [
                'HtUserRegistration\ModuleOptions' => 'HtUserRegistration\Factory\ModuleOptionsFactory',
                'HtUserRegistration\UserRegistrationMapper' => 'HtUserRegistration\Factory\UserRegistrationMapperFactory',
                'HtUserRegistration\UserRegistrationService' => 'HtUserRegistration\Factory\UserRegistrationServiceFactory',
                'HtUserRegistration\SetPasswordForm' => 'HtUserRegistration\Factory\SetPasswordFormFactory',
                'HtUserRegistration\Authentication\Adapter\EmailVerification' => 'HtUserRegistration\Authentication\Adapter\Factory\EmailVerificationFactory',
                'HtUserRegistration\Mailer\Mailer' => 'HtUserRegistration\Factory\MailerFactory',
            ],
            'aliases' => [
                'HtUserRegistration\DbAdapter' => 'Laminas\Db\Adapter\Adapter'
            ],
            'invokables' => [
                'HtUserRegistration\UserRegistrationHydrator' => 'HtUserRegistration\Stdlib\Hydrator\UserRegistrationHydrator'
            ]
        ];
    }
}
