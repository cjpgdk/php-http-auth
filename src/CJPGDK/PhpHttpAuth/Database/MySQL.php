<?php

namespace CJPGDK\PhpHttpAuth\Database;

/**
 * Provides methods for MySQL integration
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
class MySQL extends DB
{
    /** @var \mysqli */
    protected $db;

    public function __construct($host, $username, $passwd, $dbname = "", $port = 3306, $socket = null)
    {
        $this->setConnectionSettings($host, $username, $passwd, $dbname, $port, $socket);
    }

    /**
     * Delete a user from database
     * @param string $name
     * @return boolean
     */
    public function deleteUser($name)
    {
        $query = "DELETE FROM {$this->usersTableName}
                  WHERE {$this->tableColumns['username']} = '{$this->getDb()->escape_string($name)}'";
        return $this->getDb()->query($query);
    }

    /**
     * Save or update a user in the database
     * @param string $name
     * @param string $passwd
     * @return boolean
     */
    public function saveUser($name, $passwd)
    {
        if ($user = $this->getUser($name)) {
            $query = "UPDATE {$this->usersTableName}
                SET {$this->tableColumns['password']} = '{$this->getDb()->escape_string($passwd)}'
                WHERE {$this->tableColumns['id']} = {$user->id};";
        } else {
            $query = "INSERT INTO {$this->usersTableName}
                ({$this->tableColumns['username']}, {$this->tableColumns['password']})
                VALUES ('{$this->getDb()->escape_string($name)}', '{$this->getDb()->escape_string($passwd)}')";
        }
        return $this->getDb()->query($query);
    }

    /**
     * Get user from database
     * @return object[]
     */
    public function getUsers()
    {
        $query  = "SELECT {$this->tableColumns['id']} as id,
                {$this->tableColumns['username']} as name,
                {$this->tableColumns['password']} as password
                FROM {$this->usersTableName}";
        $res    = array();
        if ($result = $this->getDb()->query($query)) {
            while ($obj = $result->fetch_object()) {
                $res[] = $obj;
            }
            $result->close();
        }
        return $res;
    }

    /**
     * Get user from database
     * @param string $name
     * @return object
     */
    public function getUser($name)
    {
        $query  = "SELECT {$this->tableColumns['id']} as id,
                {$this->tableColumns['username']} as name,
                {$this->tableColumns['password']} as password
                FROM {$this->usersTableName} WHERE 
                {$this->tableColumns['username']}='{$this->getDb()->escape_string($name)}' LIMIT 1";
        $res    = null;
        if ($result = $this->getDb()->query($query)) {
            while ($obj = $result->fetch_object()) {
                $res = $obj;
            }
            $result->close();
        }
        return $res;
    }

    /**
     * Get the current mysqli object
     * @return \mysqli
     * @throws Exception
     */
    public function getDb()
    {
        if (!is_null($this->db) && $this->db->ping()) {
            return $this->db;
        }
        $this->db = new \mysqli(
            $this->getConnectionHost(), $this->getConnectionUser(), $this->getConnectionPassword(), $this->getConnectionDatabseName(),
            $this->getConnectionPort(), $this->getConnectionSocket()
        );
        if ($this->db->connect_errno) {
            throw new Exception($this->db->connect_error, $this->db->connect_errno);
        }
        return $this->db;
    }

    /**
     * Set the MySQL connection settings
     * @param type $host The MySQL hostname or ip
     * @param type $username The MySQL user name.
     * @param string $passwd the password to use when connection to MySQL
     * @param string $dbname [optional] If provided will specify the default database to be used when performing queries.
     * @param int $port [optional] Specifies the port number to attempt to connect to the MySQL server.
     * @param string $socket [optional] Specifies the socket or named pipe that should be used.
     */
    public function setConnectionSettings($host, $username, $passwd, $dbname = "", $port = 3306, $socket = null)
    {
        $this->setConnectionHost($host, ini_get("mysqli.default_host"));
        $this->setConnectionUser($username, ini_get("mysqli.default_user"));
        $this->setConnectionPassword($passwd, ini_get("mysqli.default_pw"));
        $this->setConnectionDatabseName($dbname, "");
        $this->setConnectionPort($port, ini_get("mysqli.default_port"));
        $this->setConnectionSocket($socket, ini_get("mysqli.default_socket"));
    }

    public function __destruct()
    {
        if (!is_null($this->db) && $this->db->ping()) {
            $this->db->close();
        }
    }
}