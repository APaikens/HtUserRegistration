<?php

namespace HtUserRegistration\Factory;

use Laminas\ServiceManager\Factory\FactoryInterface;

class SetPasswordFormFactory implements FactoryInterface {

    public function __invoke(\Interop\Container\ContainerInterface $container, $requestedName, array $options = null) {
        $changePasswordForm = $container->get('zfcuser_change_password_form');
        $form = clone $changePasswordForm;
        foreach (array('identity', 'credential') as $field) {
            $form->remove($field);
            $form->getInputFilter()->remove($field);
        }

        return $form;
    }

}
