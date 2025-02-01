<?php

namespace core;

class DB
{
    public $pdo;

    public function __construct($host, $name, $login, $password)
    {
        $this->pdo = new \PDO("mysql:host={$host};dbname={$name}", $login, $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }

    protected function where($where)
    {
        if (is_array($where)) {
            $where_string = "WHERE ";
            $where_fields = array_keys($where);
            $parts = [];
            foreach ($where_fields as $field) {
                if (strpos($field, ':') !== false) {
                    $parts[] = $field;
                } else {
                    $split = explode(' ', $field, 2);
                    $field = $split[0];
                    $operator = isset($split[1]) ? $split[1] : '=';
                    $parts[] = "{$field} {$operator} :{$field}_unique";
                }
            }
            $where_string .= implode(' AND ', $parts);
        } elseif (is_string($where)) {
            $where_string = "WHERE {$where}";
        } else {
            $where_string = '';
        }
        return $where_string;
    }

    public function select($table, $fields = "*", $where = null, $limit = null, $offset = 0)
    {
        if (is_array($fields)) {
            $fields_string = implode(', ', $fields);
        } elseif (is_string($fields)) {
            $fields_string = $fields;
        } else {
            $fields_string = "*";
        }

        $where_string = '';
        if ($where !== null) {
            $where_string = $this->where($where);
        }

        $limit_string = $limit !== null ? "LIMIT {$limit}" : '';
        $offset_string = $offset !== null && $offset > 0 ? "OFFSET {$offset}" : '';

        $sql = "SELECT {$fields_string} FROM {$table} {$where_string} {$limit_string} {$offset_string}";

        $sth = $this->pdo->prepare($sql);

        if ($where !== null) {
            foreach ($where as $key => $value) {
                if (strpos($key, ':') !== false) {
                    $sth->bindValue($key, $value);
                } else {
                    $field = explode(' ', $key)[0];
                    if ($value === null) { // Якщо значення null, тоді прив'яжемо параметр без значення
                        $sth->bindValue(":{$field}_unique", NULL, );
                    } else {
                        $sth->bindValue(":{$field}_unique", $value);
                    }
                }
            }
        }
        $sth->execute();
        return $sth->fetchAll();
    }

    public function insert($table, $row_to_insert)
    {
        $fields_list = implode(", ", array_keys($row_to_insert));
        $params_array = [];
        foreach ($row_to_insert as $key => $value){
            $params_array[] = ":{$key}";
        }
        $params_list = implode(", ", $params_array);

        $sql = "INSERT INTO {$table} ({$fields_list}) VALUES ({$params_list})";
        $sth = $this->pdo->prepare($sql);
        foreach ($row_to_insert as $key => $value)
            $sth->bindValue(":{$key}", $value);
        $sth->execute();
        return $sth->rowCount();
    }

    public function delete($table, $where)
    {
        $where_string = $this->where($where);

        $sql = "DELETE FROM {$table} {$where_string}";
        $sth = $this->pdo->prepare($sql);
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $value = $value[0];
            }
            $sth->bindValue(":{$key}_unique", $value);
        }
        $sth->execute();
        return $sth->rowCount();
    }

    public function update($table, $row_to_update, $where)
    {
        $where_string = $this->where($where);
        $set_array = [];
        foreach ($row_to_update as $key => $value){
            $set_array[] = "{$key} = :{$key}";
        }
        $set_string = implode(", ", $set_array);
        $sql = "UPDATE {$table} SET {$set_string} {$where_string}";
        $sth = $this->pdo->prepare($sql);
        foreach ($where as $key => $value)
            $sth->bindValue(":{$key}", $value);
        foreach ($row_to_update as $key => $value)
            $sth->bindValue(":{$key}", $value);
        $sth->execute();
        return $sth->rowCount();
    }
}