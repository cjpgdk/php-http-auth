<?php

namespace CJPGDK\PhpHttpAuth\Hash;

/**
 * Provides PHP methods for APR1-MD5 hashing
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
trait Apr1Md5
{
    private $base64Alphabet  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    private $apr1Md5Alphabet = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * format username and password array to a '.htpasswd' line
     * @param array|string $user as array ['username' => , 'password'=>], or a user name with passwd
     * @param null|string $hashedpwd
     * @return string
     */
    public function getApr1Md5HtpasswdRow($user, $hashedpwd = null)
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
    public function createUserApr1Md5($user, $pwd)
    {
        return array('username' => $user, 'password' => $this->getApr1Md5Hash($pwd));
    }

    /**
     * Get an apr1 md5 hash
     * @param string $plainpasswd
     * @param string $salt
     * @return string
     */
    public function getApr1Md5Hash($plainpasswd, $salt = null)
    {
        if (is_null($salt)) {
            $salt = $this->getApr1Salt(8);
        }
        $len  = strlen($plainpasswd);
        $text = $plainpasswd.'$apr1$'.$salt;
        $bin  = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $plainpasswd{0};
        }
        $bin = pack("H32", md5($text));
        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3) {
                $new .= $salt;
            }
            if ($i % 7) {
                $new .= $plainpasswd;
            }
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        $tmp=null;
        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16) {
                $j = 5;
            }
            $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
        }
        $tmp = chr(0).chr(0).$bin[11].$tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)), $this->base64Alphabet, $this->apr1Md5Alphabet);

        return "\$apr1\${$salt}\${$tmp}";
    }

    /**
     * Get salt
     * @param integer $length
     * @return string
     */
    private function getApr1Salt($length = 8)
    {
        $alphabet = $this->apr1Md5Alphabet;
        $salt     = '';
        for ($i = 0; $i < intval($length); $i++) {
            $orpb   = openssl_random_pseudo_bytes(1);
            $offset = hexdec(bin2hex($orpb)) % 64;
            $salt   .= $alphabet[$offset];
        }
        return $salt;
    }

    /**
     * Validate an apr1 md5 hash
     * @param string $plain
     * @param string $hash
     * @return boolean
     */
    public function matchApr1Md5Hash($plain, $hash)
    {
        if (strpos($hash, '$apr1') === 0) {
            $passParts = explode('$', $hash);
            $salt      = $passParts[2];
            $hashed    = $this->getApr1Md5Hash($plain, $salt);
            return $hashed == $hash;
        }
        return false;
    }
}