<?php

namespace App\CoreBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MediaMonks\Doctrine\Mapping\Annotation as MediaMonks;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @MediaMonks\Transformable(name="encrypt")
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     * @MediaMonks\Transformable(name="hash")
     */
    protected $emailCanonical;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $usernameCanonical;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $locale;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $timezone;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jwtVerifier;

    /**
     * @ORM\ManyToMany(targetEntity="Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $locked;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $expired;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $expiresAt;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $credentialsExpired;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $credentialsExpireAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twoStepVerificationCode;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookUid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebookName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $facebookData;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitterUid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitterName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $twitterData;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gplusUid;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gplusName;

    /**
     * @ORM\Column(type="json_array", nullable=true)
     */
    protected $gplusData;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $token;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $avatar;

    public function __construct()
    {
        parent::__construct();

        $this->updateToken();
    }

    public function updateToken($length = 20)
    {
        $this->token = sha1(random_bytes($length));
    }

    public function updateJwtVerifier()
    {
        $this->jwtVerifier = time();
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        $this->setEmailCanonical($email);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     * @return User
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * @param mixed $expired
     * @return User
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookData()
    {
        return $this->facebookData;
    }

    /**
     * @param mixed $facebookData
     * @return User
     */
    public function setFacebookData($facebookData)
    {
        $this->facebookData = $facebookData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookName()
    {
        return $this->facebookName;
    }

    /**
     * @param mixed $facebookName
     * @return User
     */
    public function setFacebookName($facebookName)
    {
        $this->facebookName = $facebookName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFacebookUid()
    {
        return $this->facebookUid;
    }

    /**
     * @param mixed $facebookUid
     * @return User
     */
    public function setFacebookUid($facebookUid)
    {
        $this->facebookUid = $facebookUid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGplusData()
    {
        return $this->gplusData;
    }

    /**
     * @param mixed $gplusData
     * @return User
     */
    public function setGplusData($gplusData)
    {
        $this->gplusData = $gplusData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGplusName()
    {
        return $this->gplusName;
    }

    /**
     * @param mixed $gplusName
     * @return User
     */
    public function setGplusName($gplusName)
    {
        $this->gplusName = $gplusName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGplusUid()
    {
        return $this->gplusUid;
    }

    /**
     * @param mixed $gplusUid
     * @return User
     */
    public function setGplusUid($gplusUid)
    {
        $this->gplusUid = $gplusUid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     * @return User
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $timezone
     * @return User
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return User
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitterData()
    {
        return $this->twitterData;
    }

    /**
     * @param mixed $twitterData
     * @return User
     */
    public function setTwitterData($twitterData)
    {
        $this->twitterData = $twitterData;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitterName()
    {
        return $this->twitterName;
    }

    /**
     * @param mixed $twitterName
     * @return User
     */
    public function setTwitterName($twitterName)
    {
        $this->twitterName = $twitterName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwitterUid()
    {
        return $this->twitterUid;
    }

    /**
     * @param mixed $twitterUid
     * @return User
     */
    public function setTwitterUid($twitterUid)
    {
        $this->twitterUid = $twitterUid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTwoStepVerificationCode()
    {
        return $this->twoStepVerificationCode;
    }

    /**
     * @param mixed $twoStepVerificationCode
     * @return User
     */
    public function setTwoStepVerificationCode($twoStepVerificationCode)
    {
        $this->twoStepVerificationCode = $twoStepVerificationCode;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsernameCanonical()
    {
        return $this->usernameCanonical;
    }

    /**
     * @param mixed $usernameCanonical
     * @return User
     */
    public function setUsernameCanonical($usernameCanonical)
    {
        $this->usernameCanonical = $usernameCanonical;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }

    /**
     * @return array
     */
    public function getRealRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     *
     * @return User
     */
    public function setRealRoles(array $roles)
    {
        $this->setRoles($roles);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJwtVerifier()
    {
        return $this->jwtVerifier;
    }

    /**
     * @param mixed $jwtVerifier
     * @return User
     */
    public function setJwtVerifier($jwtVerifier)
    {
        $this->jwtVerifier = $jwtVerifier;
        return $this;
    }
}
