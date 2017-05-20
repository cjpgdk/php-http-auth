<?php

namespace CJPGDK\PhpHttpAuth\Hash;

/**
 * Provides PHP methods for SHA1 hashing
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
trait Sha
{

    /**
     * format username and password array to a '.htpasswd' line
     * @param array|string $user as array ['username' => , 'password'=>], or a user name with passwd
     * @param null|string $hashedpwd
     * @return string
     */
    public function getShaHtpasswdRow($user, $hashedpwd = null)
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
    public function createUserSha($user, $pwd)
    {
        return array('username' => $user, 'password' => $this->getShaHash($pwd));
    }

    /**
     * Get an sha hash
     * @param string $plainpasswd
     * @return string
     */
    public function getShaHash($plainpasswd)
    {
        return "{SHA}".base64_encode(sha1($plainpasswd, TRUE));
    }

    /**
     * Validate an sha hash
     * @param string $plain
     * @param string $hash
     * @return boolean
     */
    public function matchShaHash($plain, $hash)
    {
        return $this->getShaHash($plain) === $hash;
    }
}