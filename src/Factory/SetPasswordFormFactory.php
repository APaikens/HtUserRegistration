<?php

namespace HtUserRegistration\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class SetPasswordFormFactory implements FactoryInterface
{
    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null)
    {
        $changePasswordForm = $container->get('zfcuser_change_password_form');
        $form = clone $changePasswordForm;
        foreach (array('identity', 'credential') as $field) {
            $form->remove($field);
            $form->getInputFilter()->remove($field);
        }

        return $form;
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
