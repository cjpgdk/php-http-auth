<?php

namespace CJPGDK\PhpHttpAuth\Database;

/**
 * Provides methods for PDO integration
 *
 * This file is only added to show how you may added your own.
 * PDO provides many db backends but this file only supports sqlite and mysql
 *
 * @package CJPGDK
 * @subpackage PhpHttpAuth
 * @author Christian M. Jensen <cmj@cjpg.dk>
 * @version 1.0.0
 * @since 1.0.0
 */
class PDODB extends DB
{
    /** @var \PDO */
    protected $dbh;

    public function __construct($dsn, $username="", $password="")
    {
        $this->dbh = new \PDO($dsn, $username, $password);
    }

    /**
     * Delete a user from database
     * @param string $name
     * @return boolean
     */
    public function deleteUser($name)
    {
        $query = "DELETE FROM {$this->usersTableName}
                  WHERE {$this->tableColumns['username']} = {$this->getDb()->quote($name)}";
        return $this->getDb()->exec($query);
    }

    /**
     * Get the current PDO object
     * @return \PDO
     */
    public function getDb()
    {
        return $this->dbh;
    }

    /**
     * Get user from database
     * @return object[]
     */
    public function getUsers()
    {
        $query = "SELECT {$this->tableColumns['id']} as id,
                {$this->tableColumns['username']} as name,
                {$this->tableColumns['password']} as password
                FROM {$this->usersTableName}";
        $res   = array();
        if ($rows  = $this->getDb()->query($query)) {
            foreach ($rows as $row) {
                $res[] = (object) array(
                        'id'       => $row['id'],
                        'name'     => $row['name'],
                        'password' => $row['password'],
                );
            }
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
        $query = "SELECT {$this->tableColumns['id']} as id,
                {$this->tableColumns['username']} as name,
                {$this->tableColumns['password']} as password
                FROM {$this->usersTableName} WHERE
                {$this->tableColumns['username']}={$this->getDb()->quote($name)} LIMIT 1";
        $res   = null;
        if ($rows  = $this->getDb()->query($query)) {
            foreach ($rows as $row) {
                $res           = new \stdClass();
                $res->id       = $row['id'];
                $res->name     = $row['name'];
                $res->password = $row['password'];
            }
        }
        return $res;
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
                SET {$this->tableColumns['password']} = {$this->getDb()->quote($passwd)}
                WHERE {$this->tableColumns['id']} = {$user->id};";
        } else {
            $query = "INSERT INTO {$this->usersTableName}
                ({$this->tableColumns['username']}, {$this->tableColumns['password']})
                VALUES ({$this->getDb()->quote($name)}, {$this->getDb()->quote($passwd)})";
        }
        return $this->getDb()->exec($query);
    }
}