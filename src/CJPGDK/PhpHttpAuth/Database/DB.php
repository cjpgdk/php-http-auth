<?php

namespace CJPGDK\PhpHttpAuth\Database;

/**
 * Provides generic database methods
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
abstract class DB
{
    protected $dbConnectionSettings = array(
        'host'        => null,
        'user'        => null,
        'passwd'      => null,
        'databsename' => null,
        'port'        => null,
        'socket'      => null,
    );

    /**
     * Name of the table that holds the users
     * @var string
     */
    public $usersTableName = "users";

    /**
     * An array that indicates the names of the columns
     * in the table that contains the users
     * @var array
     */
    public $tableColumns = array(
        'id'       => 'id_users',
        'username' => 'username',
        'password' => 'password'
    );

    /**
     * Delete a user from database
     * @param string $name
     * @return boolean
     */
    abstract function deleteUser($name);

    /**
     * Get users from database
     * @return object[]
     */
    abstract function getUsers();

    /**
     * Get user from database
     * @param string $name
     * @return object
     */
    abstract function getUser($name);

    /**
     * Save or update a user in the database
     * @param string $name
     * @param string $passwd
     * @return boolean
     */
    abstract function saveUser($name, $passwd);

    /**
     * Get database connection
     * @return \mysqli|\PDO
     */
    abstract function getDb();

    /**
     * Set the database connection socket
     * @param mixed $socket
     * @param mixed $default
     */
    protected function setConnectionSocket($socket, $default = null)
    {
        if (is_null($socket) && !is_null($default)) {
            $this->dbConnectionSettings['socket'] = $default;
        } else {
            $this->dbConnectionSettings['socket'] = $socket;
        }
    }

    /**
     * Get the database connection socket
     * @return mixed
     */
    protected function getConnectionSocket()
    {
        return $this->dbConnectionSettings['socket'];
    }

    /**
     * Set the database connection port
     * @param mixed $port
     * @param mixed $default
     */
    protected function setConnectionPort($port, $default = null)
    {
        if (is_null($port) && !is_null($default)) {
            $this->dbConnectionSettings['port'] = $default;
        } else {
            $this->dbConnectionSettings['port'] = $port;
        }
    }

    /**
     * Get the database connection port
     * @return mixed
     */
    protected function getConnectionPort()
    {
        return $this->dbConnectionSettings['port'];
    }

    /**
     * Set the databsename to use when connecting to the database
     * @param mixed $dbName
     * @param mixed $default
     */
    protected function setConnectionDatabseName($dbName, $default = null)
    {
        if (is_null($dbName) && !is_null($default)) {
            $this->dbConnectionSettings['databsename'] = $default;
        } else {
            $this->dbConnectionSettings['databsename'] = $dbName;
        }
    }

    /**
     * Get the databsename to use when connecting to the database
     * @return mixed
     */
    protected function getConnectionDatabseName()
    {
        return $this->dbConnectionSettings['databsename'];
    }

    /**
     * Set the password to use when connecting to the database
     * @param mixed $passwd
     * @param mixed $default
     */
    protected function setConnectionPassword($passwd, $default = null)
    {
        if (is_null($passwd) && !is_null($default)) {
            $this->dbConnectionSettings['passwd'] = $default;
        } else {
            $this->dbConnectionSettings['passwd'] = $passwd;
        }
    }

    /**
     * Get the password to use when connecting to the database
     * @return mixed
     */
    protected function getConnectionPassword()
    {
        return $this->dbConnectionSettings['passwd'];
    }

    /**
     * Set the username to use when connecting to the database
     * @param mixed $user
     * @param mixed $default
     */
    protected function setConnectionUser($user, $default = null)
    {
        if (is_null($user) && !is_null($default)) {
            $this->dbConnectionSettings['user'] = $default;
        } else {
            $this->dbConnectionSettings['user'] = $user;
        }
    }

    /**
     * Get the username to use when connecting to the database
     * @return mixed
     */
    protected function getConnectionUser()
    {
        return $this->dbConnectionSettings['user'];
    }

    /**
     * Set the host or path to the database
     * @param mixed $host
     * @param mixed $default
     */
    protected function setConnectionHost($host, $default = null)
    {
        if (is_null($host) && !is_null($default)) {
            $this->dbConnectionSettings['host'] = $default;
        } else {
            $this->dbConnectionSettings['host'] = $host;
        }
    }

    /**
     * Get the host or path to the database
     * @return mixed
     */
    protected function getConnectionHost()
    {
        return $this->dbConnectionSettings['host'];
    }
}