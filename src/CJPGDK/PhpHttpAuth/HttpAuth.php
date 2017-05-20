<?php

namespace CJPGDK\PhpHttpAuth;

use CJPGDK\PhpHttpAuth\Hash\Apr1Md5;
use CJPGDK\PhpHttpAuth\Hash\Sha;
//use CJPGDK\PhpHttpAuth\Hash\Md5;
use CJPGDK\PhpHttpAuth\Hash\Bcrypt;
use CJPGDK\PhpHttpAuth\Database\DB;

/**
 * PHP Library to make use of HTTP Authentication
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @copyright (c) 2017, Christian M. Jensen <cmj@cjpg.dk>
 * @license https://cjpg.dk/the-mit-license/ The MIT License
 * @version 1.0.0
 */
class HttpAuth
{

    use Bcrypt;
    use Sha;
    /* use Md5; */
    use Apr1Md5;
    /**
     * users available for authentication in a database free enviroment.
     * @var array
     */
    private $users = array();

    /**
     * Path to an htpasswd file
     * @var string
     */
    private $htpasswdFile;

    /**
     * Allow login by plain text passwords
     * @var boolean
     */
    public $allowPlainPassword = false;

    /**
     * Holds the username of the currently login user
     * @var string
     */
    private $username = "no one";

    /**
     * @var HttpAuth 
     */
    private static $instance;

    /** @var DB */
    private $db;

    public function __construct(DB $db = null)
    {
        $this->db = $db;
    }

    /**
     * Update a users password.
     * @param string $name
     * @param string $passwd
     * @param boolean $save save changes to users backend
     */
    public function updateUser($name, $passwd, $save = false)
    {
        $this->users[$name] = $passwd;
        if (!is_null($this->db) && $save) {
            $this->db->saveUser($name, $passwd);
        }
        if (!is_null($this->htpasswdFile) && $save) {
            $this->savePasswdFile();
        }
    }

    /**
     * Delete a user from the users store
     * @param string $name
     * @param boolean $save save changes to users backend
     */
    public function deleteUser($name, $save = false)
    {
        unset($this->users[$name]);
        if (!is_null($this->db) && $save) {
            $this->db->deleteUser($name);
        }
        if (!is_null($this->htpasswdFile) && $save) {
            $this->savePasswdFile();
        }
    }

    /**
     * Get all users. (paswords are replaced with 'hidden')
     * @return array
     */
    public function getUsers()
    {
        if (!is_null($this->db)) {
            $users = $this->db->getUsers();
            foreach ($users as $user) {
                $this->users[$user->name] = $user->password;
            }
        }
        return array_map(function($v) {
            return "hidden";
        }, $this->users);
    }

    /**
     * Get an instance of this class, and cache the object for future use.
     * @param boolean $new get as new instance or use existing.
     * @param DB $db
     * @return HttpAuth
     */
    public static function getInstance($new = false, DB $db = null)
    {
        if ($new) {
            return new static($db);
        }
        if (is_null(static::$instance)) {
            static::$instance = new static($db);
        }
        return static::$instance;
    }

    /**
     * set the full/relative path to htpasswd file, to use for
     * authenticating users.
     * @param string $htpasswd
     */
    public function setUsersFile($htpasswd)
    {
        $this->htpasswdFile = $htpasswd;
        if (!file_exists($this->htpasswdFile)) {
            return;
        }

        $handle = fopen($this->htpasswdFile, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, ':') !== false) {
                    list($user, $passwd) = explode(':', $line);
                    $this->users[$user] = trim($passwd);
                }
            }
            fclose($handle);
        }
    }

    /**
     * Save all users to a password file.
     * @param string $htpasswd [optional] path to password file
     */
    public function savePasswdFile($htpasswd = null)
    {
        if (is_null($htpasswd)) {
            $htpasswd = $this->htpasswdFile;
        }
        $handle = fopen($this->htpasswdFile, "w");
        if ($handle) {
            foreach ($this->users as $name => $passwd) {
                if (!empty($name) && !empty($passwd)) {
                    fwrite($handle, "{$name}:{$passwd}\n");
                }
            }
            fclose($handle);
        }
    }

    /**
     * Append new users to the existing users table.
     * @param array $users
     * @param boolean $save save changes to users backend
     */
    public function appendUsers(array $users, $save = false)
    {
        $this->users = array_merge($this->users, $users);

        if (!is_null($this->htpasswdFile) && $save) {
            $this->savePasswdFile();
        }

        if (is_null($this->db)) {
            return;
        }
        if (!$save) {
            return;
        }
        foreach ($this->users as $name => $passwd) {
            $this->db->saveUser($name, $passwd);
        }
    }

    /**
     * Set available users
     * @param array $users
     * @param boolean $save save changes to users backend
     */
    public function setUsers(array $users, $save = false)
    {
        $this->users = $users;

        if (!is_null($this->htpasswdFile) && $save) {
            $this->savePasswdFile();
        }

        if (is_null($this->db)) {
            return;
        }
        if (!$save) {
            return;
        }
        foreach ($this->users as $name => $passwd) {
            $this->db->saveUser($name, $passwd);
        }
    }

    /**
     * Add a new user to the user table
     * @param string $name
     * @param string $passwd
     * @param boolean $save save changes to users backend
     */
    public function addUser($name, $passwd, $save = false)
    {
        $this->users[$name] = $passwd;

        if (!is_null($this->htpasswdFile) && $save) {
            $this->savePasswdFile();
        }

        if (!is_null($this->db) && $save) {
            $this->db->saveUser($name, $passwd);
        }
    }

    /**
     * Get password for a user.
     * @param string $username
     * @return string
     */
    protected function getUserPassword($username)
    {
        if (isset($this->users[$username])) {
            return $this->users[$username];
        }
        if (is_null($this->db)) {
            return '';
        }
        if ($user = $this->db->getUser($username)) {
            $this->users[$user->name] = $user->password;
        }
        return isset($this->users[$username]) ? $this->users[$username] : '';
    }

    /**
     * Get the username of username if it exists in the user table. if no user is found returns an empty string
     * @param string $username
     * @return string
     */
    protected function getUsername($username)
    {
        if (isset($this->users[$username])) {
            return $username;
        }
        if (is_null($this->db)) {
            return '';
        }
        if ($user = $this->db->getUser($username)) {
            $this->users[$user->name] = $user->password;
        }
        return isset($this->users[$username]) ? $username : '';
    }

    /**
     * get the username of the currently authenticated user
     * @return string
     */
    public function whoAmI()
    {
        return $this->username;
    }

    /**
     * Check if the visitor has send us some credentials
     * @param string $realm [optional]
     * @return boolean
     */
    public function hasValidCredentials($realm = 'Restricted area')
    {
        $user     = $this->getServerVariableValue('PHP_AUTH_USER');
        $pw       = $this->getServerVariableValue('PHP_AUTH_PW');
        $username = $this->getUsername($user);
        if (empty($username)) {
            return false;
        }
        $password = $this->getUserPassword($username);
        if ($this->matchPasswd($pw, $password, $this->allowPlainPassword)) {
            $this->username = $username;
            return true;
        }
        return $this->validateAuthDigestResponse($realm);
    }

    /**
     * Send headers requesting http auth basic,
     * if the user hits cancel the text from
     * $message will be displayed and the script dies
     *
     * @param string $realm [optional] the realm the visitor need valid credentials to roam
     * @param string $message [optional] message to show the visitor if the visitor did not authticate
     * @return boolean true if the visitor has used a valid authtication
     */
    public function authBasic($realm = 'Restricted area', $message = 'Restricted area')
    {
        $user = $this->getServerVariableValue('PHP_AUTH_USER');
        $pw   = $this->getServerVariableValue('PHP_AUTH_PW');
        if (empty($user) || empty($pw)) {
            $this->requestReAuthBasic($realm, $message);
            return false;
        }

        $username = $this->getUsername($user);
        if (empty($username)) {
            return false;
        }
        $password = $this->getUserPassword($username);
        if ($this->matchPasswd($pw, $password, $this->allowPlainPassword)) {
            $this->username = $username;
            return true;
        }
        return false;
    }

    /**
     * request the visitor to authenticate again, ignoring the current user and password set
     * @param string $realm [optional] the realm the visitor need valid credentials to roam
     * @param string $message [optional] 
     */
    public function requestReAuthBasic($realm = 'Restricted area', $message = 'Restricted area')
    {
        $this->send401Unauthorized();
        $this->sendHeader("WWW-Authenticate: Basic realm=\"{$realm}\"");
        die($message);
    }

    /**
     * check the password matches APR1-MD5, SHA1, Bcrypt or if allowed plain text
     * @param string $plain plain text password
     * @param string $hash hashed password.
     * @param boolean $allowPlain [optional] allow plain text passwords.
     * @return boolean
     */
    public function matchPasswd($plain, $hash, $allowPlain = false)
    {
        if (strpos($hash, '$apr1') === 0) {
            return static::getInstance()->matchApr1Md5Hash($plain, $hash);
        } elseif (strpos($hash, '{SHA}') === 0) {
            return static::getInstance()->matchShaHash($plain, $hash);
        } elseif (strpos($hash, '$2y$') === 0) {
            return static::getInstance()->matchBcryptHash($plain, $hash);
        }
//        else if (strlen($hash) == 32) {
//            return static::getInstance()->matchMd5Hash($plain, $hash);
//        }
        return $allowPlain && $plain == $hash;
    }

    /**
     * Validate an wuthentication request using auth digest
     * @param string $realm the realm the visitor need valid credentials to roam
     * @param array|null $data [optional]
     * @return boolean
     */
    public function validateAuthDigestResponse($realm = 'Restricted area', $data = null)
    {
        if (is_null($data)) {
            $data = $this->authDigestGetUserDetails();
        }
        if (!$data) {
            return false;
        }
        $username = $this->getUsername($data['username']);
        if (empty($username)) {
            return false;
        }
        $password       = $this->getUserPassword($username);
        $start          = md5("{$username}:{$realm}:{$password}");
        $requestMethod  = $this->getServerVariableValue('REQUEST_METHOD');
        $end            = md5("{$requestMethod}:{$data['uri']}");
        $valid_response = md5("{$start}:{$data['nonce']}:{$data['nc']}:{$data['cnonce']}:{$data['qop']}:{$end}");
        if (($data['response'] == $valid_response)) {
            $this->username = $username;
            return true;
        }
        return false;
    }

    /**
     * Get user details from auth digest request
     * @return boolean|array returns boolean false on authentication error
     */
    public function authDigestGetUserDetails()
    {
        $subject = $this->getServerVariableValue('PHP_AUTH_DIGEST');
        if (empty($subject)) {
            return false;
        }
        $pattern = '@(nonce|nc|cnonce|qop|username|uri|response)=(?:([\'"])([^\2]+?)\2|([^\s,]+))@';
        //int , int
        $matches = array();
        $flags   = PREG_SET_ORDER;
        $offset  = 0;
        preg_match_all($pattern, $subject, $matches, $flags, $offset);

        $result = array();
        foreach ($matches as $match) {
            $result[$match[1]] = $match[3] ? $match[3] : $match[4];
        }
        return count($result) != 7 ? false : $result;
    }

    /**
     * Send headers requesting http auth digest,
     * if the user hits cancel the text from
     * $message will be displayed and the script dies
     *
     * @param string $realm [optional] the realm the visitor need valid credentials to roam
     * @param string $message [optional]
     * @return void No value is returned.
     */
    public function requestReAuthDigest($realm = 'Restricted area', $message = 'Restricted area')
    {
        $this->send401Unauthorized();
        $uniqid        = uniqid();
        $md5           = md5($realm);
        $wwwAuthHeader = "WWW-Authenticate: Digest realm=\"{$realm}\",qop=\"auth\",nonce=\"{$uniqid}\",opaque=\"{$md5}'\"";
        $this->sendHeader($wwwAuthHeader);
        die($message);
    }

    /**
     * Send headers requesting http auth digest,
     * if the user hits cancel the tekst from
     * $message will be displayed and the script dies
     * 
     * @param string $realm [optional] the realm the visitor need valid credentials to roam
     * @param string $message [optional]
     * @return void No value is returned.
     */
    public function authDigest($realm = 'Restricted area', $message = 'Restricted area')
    {
        $auth = $this->getServerVariableValue('PHP_AUTH_DIGEST');
        if (empty($auth)) {
            $this->requestReAuthDigest($realm, $message);
        }
    }

    /**
     * Get the value of _SERVER variable by name
     * 
     * @param string $name a name to find in the server variable.
     * @param mixed $default [optional] default value to return if server variable is not set
     * @return mixed
     */
    public function getServerVariableValue($name, $default = null)
    {
        return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
    }

    /**
     * Send a 401 Unauthorized header
     *
     * @return void No value is returned.
     */
    public function send401Unauthorized()
    {
        $this->sendHeader('HTTP/1.1 401 Unauthorized');
    }

    /**
     * Send a raw HTTP header
     * 
     * @link http://php.net/manual/en/function.header.php
     * @param string $string <p>The header string.</p>
     * @param bool $replace [optional]
     * @param int $http_response_code [optional]
     * @return void No value is returned.
     */
    public function sendHeader($string, $replace = true, $http_response_code = null)
    {
        header($string, $replace, $http_response_code);
    }
}