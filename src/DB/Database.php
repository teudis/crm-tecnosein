<?php

namespace TLMK\DB;
use PDO;

class Database
{
    public $isConnected;
    protected $database;

    public function __construct($username = 'root', $password = 'admin', $host = 'localhost',
                                $dbname = 'crm_tecnosein', $options = [])
    {
        $this->isConnected = true;
        try {

            $this->database = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
            $this->database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            $this->isConnected = false;
            throw new Exception($e->getMessage());
        }
    }

    public function disconnect()
    {
        $this->database = null;
        $this->isConnected = false;
    }

    public function getRow($query, $params = [])
    {
        try {
            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getRows($query, $params = [])
    {
        try {
            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function insertRow($query, $params = [])
    {
        try {
            $stmt = $this->database->prepare($query);
            $stmt->execute($params);
            return true;

        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateRow($query, $params = [])
    {
        $this->insertRow($query, $params);
    }

    public function deleteRow($query, $params = [])
    {
        $this->insertRow($query, $params);
    }
}