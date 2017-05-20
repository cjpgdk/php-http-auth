<?php

namespace CJPGDK\PhpHttpAuth\Hash;

/**
 * Provides PHP methods for MD5 hashing
 *
 * this type of hasing is not supported by real htpasswd authentication
 * added as a sample, and because it was need in an other project.
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
trait Md5
{

    /**
     * format username and password array to a '.htpasswd' line
     * @param array|string $user as array ['username' => , 'password'=>], or a user name with passwd
     * @param null|string $hashedpwd
     * @return string
     */
    public function getMd5HtpasswdRow($user, $hashedpwd = null)
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
    public function createUserMd5($user, $pwd)
    {
        return array('username' => $user, 'password' => $this->getMd5Hash($pwd));
    }

    /**
     * Get an md5 hash
     * @param string $plainpasswd
     * @return string
     */
    public function getMd5Hash($plainpasswd)
    {
        return md5($plainpasswd);
    }

    /**
     * Validate an md5 hash
     * @param string $plain
     * @param string $hash
     * @return boolean
     */
    public function matchMd5Hash($plain, $hash)
    {
        return $this->getMd5Hash($plain) === $hash;
    }
}