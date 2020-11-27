<?php
namespace HtUserRegistration\Service;

use HtUserRegistration\Mapper\UserRegistrationMapperInterface;
use Laminas\EventManager\EventInterface;
use ZfcUser\Entity\UserInterface;
use DateTime;
use HtUserRegistration\Entity\UserRegistrationInterface;
use HtUserRegistration\Mailer\MailerInterface;
use Laminas\Crypt\Password\Bcrypt;
use HtUserRegistration\Options\ModuleOptions;
use ZfcUser\Options\ModuleOptions as ZfcUserOptions;
use ZfcUser\Mapper\UserInterface as UserMapperInterface;

class UserRegistrationService implements UserRegistrationServiceInterface
{
    
    /**
     * @var UserRegistrationMapperInterface
     */
    protected $userRegistrationMapper;

    /**
     * @var ModuleOptions
     */
    protected $options;
    
    /**
     *
     * @var MailerInterface 
     */
    protected $mailer;

    /**
     * @var ZfcUserOptions
     */
    protected $zfcUserOptions;

    /**
     * @var UserMapperInterface
     */
    protected $userMapper;

    /**
     * Constructor
     *
     * @param UserRegistrationMapperInterface $userRegistrationMapper
     * @param ModuleOptions $options
     * @param ZfcUserOptions $zfcUserOptions
     * @paramUserMapperInterface $userMapper
     */
    public function __construct(
        UserRegistrationMapperInterface $userRegistrationMapper,
        ModuleOptions $options,
        MailerInterface $mailer,
        UserMapperInterface $userMapper,
        ZfcUserOptions $zfcUserOptions
    )
    {       
        $this->userRegistrationMapper = $userRegistrationMapper;
        $this->options                = $options;
        $this->mailer                 = $mailer;
        $this->zfcUserOptions         = $zfcUserOptions;
        $this->userMapper             = $userMapper;
    }

    /**
     * {@inheritDoc}
     */
    public function onUserRegistration(EventInterface $e)
    {
        $user = $e->getParam('user');
        if ($this->getOptions()->getSendVerificationEmail() && $this->getZfcUserOptions()->getEnableRegistration()) {
            $this->sendVerificationEmail($user);
        } elseif ($this->getOptions()->getSendPasswordRequestEmail() && !$this->getZfcUserOptions()->getEnableRegistration()) {
            $this->sendPasswordRequestEmail($user);
        }

        // do nothing
    }

    /**
     * {@inheritDoc}
     */
    public function sendVerificationEmail(UserInterface $user)
    {
        $registrationRecord = $this->createRegistrationRecord($user);
        $this->mailer->sendVerificationEmail($registrationRecord);
    }

    /**
     * {@inheritDoc}
     */
    public function sendPasswordRequestEmail(UserInterface $user)
    {
        $registrationRecord = $this->createRegistrationRecord($user);
        $this->mailer->sendPasswordRequestEmail($registrationRecord);
    }

    /**
     * Stored user registration record to database
     *
     * @return UserInterface             $user
     * @return UserRegistrationInterface
     */
    protected function createRegistrationRecord(UserInterface $user)
    {
        $entityClass = $this->getOptions()->getRegistrationEntityClass();
        $entity = new $entityClass($user);
        $entity->generateToken();
        $this->getUserRegistrationMapper()->insert($entity);
        return $entity;
    }

    /**
     * {@inheritDoc}
     */
    public function verifyEmail(UserInterface $user, $token)
    {
        $record = $this->getUserRegistrationMapper()->findByUser($user);
        if (!$record || !$this->isTokenValid($user, $token, $record)) {
            return false;
        }
        if (!$record->isResponded()) {
            $record->setResponded(UserRegistrationInterface::EMAIL_RESPONDED);
            $this->getUserRegistrationMapper()->update($record);
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isTokenValid(UserInterface $user, $token, UserRegistrationInterface $record)
    {
        if ($record->getToken() !== $token) {
            return false;
        } elseif ($this->getOptions()->getEnableRequestExpiry() && $this->isTokenExpired($record)) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isTokenExpired(UserRegistrationInterface $record)
    {
        $expiryDate = new DateTime($this->getOptions()->getRequestExpiry() . ' seconds ago');

        return $record->getRequestTime() < $expiryDate;
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword(array $data, UserRegistrationInterface $registrationRecord)
    {
        $newPass = $data['newCredential'];
        $user = $registrationRecord->getUser();

        $bcrypt = new Bcrypt;
        $bcrypt->setCost($this->getZfcUserOptions()->getPasswordCost());

        $pass = $bcrypt->create($newPass);
        $user->setPassword($pass);

        $this->getUserMapper()->update($user);
        $registrationRecord->setResponded(UserRegistrationInterface::EMAIL_RESPONDED);
        $this->getUserRegistrationMapper()->update($registrationRecord);
    }

    /**
     * Gets userRegistrationMapper
     *
     * @return UserRegistrationMapperInterface
     */
    protected function getUserRegistrationMapper()
    {
        return $this->userRegistrationMapper;
    }

    /**
     * Gets moduleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * Gets zfcUserOptions
     */
    protected function getZfcUserOptions()
    {
        return $this->zfcUserOptions;
    }

    /**
     * Gets userMapper
     */
    protected function getUserMapper()
    {
        return $this->userMapper;
    }
}
