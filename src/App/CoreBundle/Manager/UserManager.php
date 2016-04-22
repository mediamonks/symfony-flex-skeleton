<?php

namespace App\CoreBundle\Manager;

use FOS\UserBundle\Doctrine\UserManager as BaseUserManager;
use FOS\UserBundle\Model\UserInterface;
use Zend\Crypt\Hash;

class UserManager extends BaseUserManager
{
    /**
     * Finds a user by email
     *
     * @param string $email
     *
     * @return UserInterface
     */
    public function findUserByEmail($email)
    {
        return $this->findUserBy(['emailCanonical' => Hash::compute('sha256', $email)]);
    }

    /**
     * @param UserInterface $user
     */
    public function updateCanonicalFields(UserInterface $user)
    {
        return;
    }
}
