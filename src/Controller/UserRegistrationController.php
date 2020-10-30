<?php
namespace HtUserRegistration\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use HtUserRegistration\Service\UserRegistrationServiceInterface;
use Laminas\View\Model\ViewModel;
use HtUserRegistration\Mapper\UserRegistrationMapperInterface;

class UserRegistrationController extends AbstractActionController
{
    /**
     * @var UserRegistrationServiceInterface
     */
    protected $userRegistrationService;

    /**
     * @var \ZfcUser\Mapper\UserInterface
     */
    protected $userMapper;

    /**
     * @var UserRegistrationMapperInterface
     */
    protected $userRegistrationMapper;

    /**
     * @var \HtUserRegistration\Options\ModuleOptions
     */
    protected $options;


    protected $setPasswordForm;

    /**
     * Constructor
     *
     * @param UserRegistrationServiceInterface $userRegistrationService
     */
    public function __construct(UserRegistrationServiceInterface $userRegistrationService, 
            $passwordForm, $userMapper, $userRegistrationMapper, $moduleOptions)
    {
        $this->userRegistrationService = $userRegistrationService;
        $this->setPasswordForm = $passwordForm;
        $this->userMapper = $userMapper;
        $this->userRegistrationMapper = $userRegistrationMapper;
        $this->options = $moduleOptions;
    }

    /**
     * Verifies user`s email address and redirects to login route
     */
    public function verifyEmailAction()
    {
        $userId = $this->params()->fromRoute('userId', null);
        $token = $this->params()->fromRoute('token', null);

        if ($userId === null || $token === null) {
            return $this->notFoundAction();
        }

        $user = $this->getUserMapper()->findById($userId);

        if (!$user) {
            return $this->notFoundAction();
        }

        if ($this->userRegistrationService->verifyEmail($user, $token)) {
            // email verified
            return $this->redirectToPostVerificationRoute();
        }

        // email not verified, probably invalid token
        $vm = new ViewModel();
        $vm->setTemplate('ht-user-registration/user-registration/verify-email-error.phtml');

        return $vm;

    }

    /**
     * Allows users to set their account password
     */
    public function setPasswordAction()
    {
        $userId = $this->params()->fromRoute('userId', null);
        $token = $this->params()->fromRoute('token', null);

        if ($userId === null || $token === null) {
            return $this->notFoundAction();
        }

        $user = $this->getUserMapper()->findById($userId);

        if (!$user) {
            return $this->notFoundAction();
        }

        $record = $this->getUserRegistrationMapper()->findByUser($user);

        if (!$record ||  !$this->userRegistrationService->isTokenValid($user, $token, $record)) {
            // Invalid Token, Lets surprise the attacker
            return $this->notFoundAction();
        }

        if ($record->isResponded()) {
            // old link, password is already set by the user
            return $this->redirectToPostVerificationRoute();
        }

        $form = $this->setPasswordForm;

        if ($this->getRequest()->isPost()) {
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $this->userRegistrationService->setPassword($form->getData(), $record);

                return $this->redirectToPostVerificationRoute();
            }
        }

        return array(
            'user' => $user,
            'form' => $form
        );
    }

    /**
     * Gets userMapper
     */
    protected function getUserMapper()
    {
        return $this->userMapper;
    }

    /**
     * Gets userRegistrationMapper
     */
    protected function getUserRegistrationMapper()
    {
        return $this->userRegistrationMapper;
    }

    protected function getOptions()
    {
        return $this->options;
    }

    protected function redirectToPostVerificationRoute()
    {
        return $this->redirect()->toRoute($this->getOptions()->getPostVerificationRoute());
    }
}
