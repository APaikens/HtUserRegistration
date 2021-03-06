<?php
namespace HtUserRegistration\Mailer;

use HtUserRegistration\Entity\UserRegistrationInterface;
use HtUserRegistration\Options\ModuleOptions;

class Mailer implements MailerInterface
{
    /**
     * @var ModuleOptions
     */
    protected $options;

    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * Constructor
     *
     * @param ModuleOptions $options
     * @param MailService $mailService
     */
    public function __construct(ModuleOptions $options, $mailService)
    {
        $this->options      = $options;
        $this->mailService  = $mailService;
    }

    /**
     * {@inheritDoc}
     */
    public function sendVerificationEmail(UserRegistrationInterface $registrationRecord)
    {
        $this->sendMail(
            $registrationRecord, 
            $this->options->getVerificationEmailSubject(), 
            $this->options->getVerificationEmailTemplate()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function sendPasswordRequestEmail(UserRegistrationInterface $registrationRecord)
    {
        $this->sendMail(
            $registrationRecord, 
            $this->options->getPasswordRequestEmailSubject(), 
            $this->options->getPasswordRequestEmailTemplate()
        );        
    }

    /**
     * Sends mail
     *
     * @param UserRegistrationInterface $registrationRecord
     * @param string $subject
     * @param string $template
     */
    protected function sendMail(UserRegistrationInterface $registrationRecord, $subject, $template)
    {
        $user = $registrationRecord->getUser();
        $fromEmail = $this->options->getEmailFromAddress();
        //$from = '';
        //if(!empty($fromEmail)){
//            $from = (isset($fromEmail[0]))?$fromEmail[0]:'';
//        }
        $to = $user->getEmail();
        $data = ['user' => $user, 'registrationRecord' => $registrationRecord];
        $message = $this->mailService->createHtmlMessage($fromEmail, $to, $subject, $template, $data);
        return $this->mailService->send($message);
    }
}
