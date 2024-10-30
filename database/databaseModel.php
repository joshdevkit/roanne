<?php

include_once 'conn.php';

function baseUrl()
{
    define('base_url', 'http://localhost/web_base_profiling_and_mapping/');
}


function getSession()
{
    baseUrl();
    $sessionKeys = [
        'authenticated' => true,
        'user_id' => 'user_id',
        'user_type' => 'account_type'
    ];

    $sessionData = [];

    foreach ($sessionKeys as $key => $sessionVariable) {
        if (isset($_SESSION[$sessionVariable])) {
            $sessionData[$key] = $_SESSION[$sessionVariable];
        }
    }

    if (!empty($sessionData)) {
        return $sessionData;
    } else {
        header('Location: ' . base_url . '');
        exit();
    }
}


function endSession()
{
    baseUrl();
    $_SESSION = [];

    session_destroy();
    header('Location: ' . base_url);
    exit();
}

function insert($conn, $table, $data)
{
    try {
        $columns = array_keys($data);
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $columns);
        $stmt = $conn->prepare("INSERT INTO $table (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")");
        foreach ($data as $key => &$val) {
            $stmt->bindParam(":$key", $val);
        }
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}



function insertNew($conn, $table, $data)
{
    try {
        $columns = array_keys($data);
        $placeholders = array_map(function ($key) {
            return ":$key";
        }, $columns);
        $stmt = $conn->prepare("INSERT INTO $table (" . implode(",", $columns) . ") VALUES (" . implode(",", $placeholders) . ")");
        foreach ($data as $key => &$val) {
            $stmt->bindParam(":$key", $val);
        }
        $stmt->execute();
        if ($stmt) {
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function retrieve($conn, $table)
{
    try {
        $stmt = $conn->query("SELECT * FROM $table");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function getOne($conn, $table, $tableColumn, $id)
{
    try {
        $stmt = $conn->prepare("SELECT * FROM $table WHERE $tableColumn = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function getJoinTables($conn, $tables, $joins, $conditions = [], $fields = ['*'])
{
    try {
        $fieldsPart = implode(", ", $fields);
        $sql = "SELECT $fieldsPart FROM " . array_shift($tables);
        foreach ($tables as $table) {
            $joinType = isset($joins[$table]['type']) ? strtoupper($joins[$table]['type']) : 'INNER';
            $onCondition = isset($joins[$table]['on']) ? $joins[$table]['on'] : '';
            $sql .= " $joinType JOIN $table ON $onCondition";
        }

        $params = [];
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionsPart = [];
            foreach ($conditions as $field => $value) {
                $paramName = ":" . str_replace('.', '_', $field); // Adjust parameter name
                $conditionsPart[] = "$field = $paramName"; // Use parameter name in the condition
                $params[$paramName] = $value; // Define the parameter using adjusted name
            }
            $sql .= implode(" AND ", $conditionsPart);
        }

        $stmt = $conn->prepare($sql);
        $stmt->execute($params); // Pass the parameters to execute
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function getJoinedData($conn, $tables, $joins, $fields, $conditions = [])
{
    $sql = 'SELECT ' . implode(', ', $fields) . ' FROM ' . $tables[0];
    foreach ($joins as $table => $join) {
        $sql .= ' ' . $join['type'] . ' JOIN ' . $table . ' ON ' . $join['on'];
    }
    if (!empty($conditions)) {
        $sql .= ' WHERE ' . implode(' AND ', $conditions);
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function delete($conn, $table, $id)
{
    try {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}


function update($conn, $table, $primaryKey, $idValue, $data)
{
    try {
        $updateString = "";
        foreach ($data as $key => $value) {
            $updateString .= "$key=:$key, ";
        }
        $updateString = rtrim($updateString, ', ');

        $sql = "UPDATE $table SET $updateString WHERE $primaryKey = :id";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        foreach ($data as $key => &$value) { // Use reference &$value for bindParam
            $stmt->bindParam(":$key", $value);
        }
        $stmt->bindParam(':id', $idValue, PDO::PARAM_INT);

        // Execute statement
        $stmt->execute();

        return true; // Return true on successful update
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}



function incrementColumn($conn, $table, $id, $column, $incrementBy)
{
    try {
        $stmt = $conn->prepare("UPDATE $table SET $column = $column + :incrementBy WHERE owner_id = :id");
        $stmt->bindParam(':incrementBy', $incrementBy, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt) {
            return true;
        }
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}
