<?php

namespace HtUserRegistrationTest\Factory;

use Laminas\ServiceManager\ServiceManager;
use HtUserRegistration\Factory\SetPasswordFormFactory;
use Laminas\Form\Form;

class SetPasswordFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $form = new Form;
        $serviceManager = new ServiceManager;
        $serviceManager->setService('zfcuser_change_password_form', $form);
        $factory = new SetPasswordFormFactory;
        $this->assertInstanceOf('Laminas\Form\Form', $factory->createService($serviceManager));
    }
}
