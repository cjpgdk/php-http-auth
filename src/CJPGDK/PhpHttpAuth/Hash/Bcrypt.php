<?php

namespace CJPGDK\PhpHttpAuth\Hash;

/**
 * Provides PHP methods for Bcrypt hashing (PHP version 5.5 and up)
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
trait Bcrypt
{
    /**
     * format username and password array to a '.htpasswd' line
     * @param array|string $user as array ['username' => , 'password'=>], or a user name with passwd
     * @param null|string $hashedpwd
     * @return string
     */
    public function getBcryptHtpasswdRow($user, $hashedpwd = null)
    {
        if (is_array($user)) {
            return "{$user['username']}:{$user['password']}";
        }
        return "{$user}:{$hashedpwd}";
    }

    /**
     * Create a user and password row.
     * @param string $user
     * @param string $pwd
     * @return array ['username' => , 'password'=>]
     */
    public function createUserBcrypt($user, $pwd)
    {
        return array('username' => $user, 'password' => $this->getBcryptHash($pwd));
    }

    /**
     * Get an bcrypt hash
     * @param string $plainpasswd
     * @return string
     */
    public function getBcryptHash($plainpasswd)
    {
        return password_hash($plainpasswd, PASSWORD_BCRYPT);
    }

    /**
     * Validate an bcrypt hash
     * @param string $plain
     * @param string $hash
     * @return boolean
     */
    public function matchBcryptHash($plain, $hash)
    {
        return password_verify($plain, $hash);
    }
}