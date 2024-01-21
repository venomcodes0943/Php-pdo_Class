<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Crud
{
    private $conn;
    private $defaultDb;

    public function __construct($defaultDb = null, $host = 'localhost', $dbuser = 'root', $dbpassword = '')
    {
        if ($defaultDb !== null) {
            $this->defaultDb = $defaultDb;
        } else {
            $this->defaultDb = 'Test_database';
        }
        $this->establishConnection($host, $dbuser, $dbpassword);
        $this->createDatabase($this->defaultDb);
        $this->useDatabase($this->defaultDb);
    }

    private function establishConnection($host, $dbuser, $dbpassword)
    {
        try {
            $this->conn = new PDO("mysql:host=$host", $dbuser, $dbpassword);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }
    }
    public function createDatabase($dbName)
    {
        try {
            if (empty($dbName)) {
                throw new InvalidArgumentException("Database name is required.");
            }

            $query = "CREATE DATABASE IF NOT EXISTS $dbName";
            $this->conn->exec($query);
        } catch (PDOException $e) {
            echo "Error creating database: " . $e->getMessage();
        }
    }

    public function useDatabase($dbName)
    {
        try {
            if (empty($dbName)) {
                throw new InvalidArgumentException("Database name is required.");
            }

            $this->conn->exec("USE $dbName");

        } catch (PDOException $e) {
            echo "Error switching to database: " . $e->getMessage();
        }
    }
    public function createTable($tableName, $fields)
    {
        try {
            if (empty($tableName) || empty($fields)) {
                throw new InvalidArgumentException("Table Name And Fields Are Required.");
            }
            //create table query
            $query = "CREATE TABLE IF NOT EXISTS `$tableName`(";
            foreach ($fields as $feildName => $fieldType) {
                // $dataType = ($fieldType === 'string') ? 'VARCHAR(255) NOT NULL' : $fieldType;
                if ($fieldType === 'string') {
                    $dataType = 'VARCHAR(255) NOT NULL';
                } elseif ($fieldType === 'int') {
                    $dataType = 'INT(11) NOT NULL';
                } else {
                    $dataType = $fieldType;
                }
                $query .= "$feildName $dataType ,";

            }
            $query .= "update_at VARCHAR(255) DEFAULT CURRENT_TIMESTAMP";
            $query .= ")";

            $this->conn->exec($query);
            return true;

        } catch (PDOException $e) {
            echo "Error Creating Tbale :" . $e;
        }
    }

    public function insertRecord($tableName, $data_array, $location)
    {
        try {
            if (empty($tableName) || empty($data_array)) {
                throw new InvalidArgumentException("Table Name, DataArray, and Location are required.");
            }
            array_pop($data_array);
            $columns = implode(', ', array_keys($data_array));
            $placeholders = ':' . implode(', :', array_keys($data_array));

            $query = "INSERT INTO `$tableName` ($columns, update_at) VALUES ($placeholders, NOW())";

            $stmt = $this->conn->prepare($query);

            foreach ($data_array as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->execute();
            if (!empty($location)) {
                header("Location: $location");
            } else {
                echo "Data Inserted Successfully :)";
            }

            exit;
        } catch (PDOException $e) {
            echo "Error Inserting Record" . $e->getMessage();
        }
    }
    public function showRecord($tableName, $numRecord, $condition)
    {
        try {
            if (empty($tableName) || empty($numRecord)) {
                throw new InvalidArgumentException("Table Name and NumRecord are required.");
            }

            $query = "SELECT $numRecord FROM `$tableName`";
            if (!empty($condition)) {
                $query .= " WHERE $condition";
            }
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $record = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $record;
        } catch (PDOException $e) {
            echo "Error Retrieving Records: " . $e->getMessage();
        }
    }
    public function updateRecord($tableName, $formData, $condition, $location)
    {
        try {
            if (empty($tableName) || empty($formData) || empty($condition)) {
                throw new InvalidArgumentException("Table Name, DataArray, and Location are required.");
            }
            array_pop($formData);

            $setClause = '';
            foreach ($formData as $column => $value) {
                $setClause .= "`$column` = :$column,";
            }
            $setClause = rtrim($setClause, ', ');

            $query = "UPDATE `$tableName` SET $setClause WHERE $condition";

            $stmt = $this->conn->prepare($query);

            foreach ($formData as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }
            if ($stmt->execute()) {
                header('Location:' . $location);
            } else {
                echo "Error in Update Query";
            }
            if (!empty($location)) {
                header("Location: $location");
            } else {
                echo "Your Record Updated Successfully :)";
            }

        } catch (PDOException $e) {
            echo "Error in UpdateFunction Record" . $e->getMessage();
        }
    }
    public function deleteRecord($tableName, $condition, $location)
    {
        try {
            if (empty($tableName) || empty($condition)) {
                throw new InvalidArgumentException("Table Name, DataArray, and Location are required.");
            }
            $query = "DELETE FROM `$tableName` WHERE $condition";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            if (!empty($location)) {
                header("Location: $location");
            } else {
                echo "Your Record Deleted Successfully :)";
            }
            exit;
        } catch (PDOException $e) {
            echo "Error Inserting Record" . $e->getMessage();
        }
    }

}
