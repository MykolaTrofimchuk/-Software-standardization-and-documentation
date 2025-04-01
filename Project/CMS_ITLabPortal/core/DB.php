<?php

namespace core;
/**
 * Клас для роботи з базою даних через PDO.
 *
 * Цей клас забезпечує основні методи для виконання SQL запитів: вибірки, вставки, оновлення та видалення.
 */
class DB
{
    /**
     * Об'єкт PDO для підключення до бази даних.
     *
     * @var \PDO
     */
    public $pdo;

    /**
     * Конструктор для ініціалізації з'єднання з базою даних.
     *
     * @param string $host Хост бази даних.
     * @param string $name Назва бази даних.
     * @param string $login Логін для підключення.
     * @param string $password Пароль для підключення.
     */
    public function __construct($host, $name, $login, $password)
    {
        $this->pdo = new \PDO("mysql:host={$host};dbname={$name}", $login, $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]
        );
    }

    /**
     * Створює SQL умовний вираз для WHERE.
     *
     * @param mixed $where Умова для фільтрації.
     * @return string
     */
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

    /**
     * Виконує SQL запит SELECT для отримання даних з таблиці.
     *
     * @param string $table Назва таблиці.
     * @param mixed $fields Поля для вибірки.
     * @param mixed $where Умова фільтрації.
     * @param int|null $limit Ліміт на кількість записів.
     * @param int $offset Зсув.
     * @return array
     */
    public function select($table, $fields = "*", $where = null, $limit = null, $offset = 0)
    {
        if (is_array($fields)) {
            $fields_string = implode(', ', $fields);
        } elseif (is_string($fields)) {
            $fields_string = $fields;
        } else {
            $fields_string = "*";
        }

        // Якщо $where порожнє або null, не додаємо WHERE в SQL запит
        $where_string = '';
        if ($where !== null && !empty($where)) {
            $where_string = $this->where($where);
        }

        $limit_string = $limit !== null ? "LIMIT {$limit}" : '';
        $offset_string = $offset !== null && $offset > 0 ? "OFFSET {$offset}" : '';

        $sql = "SELECT {$fields_string} FROM {$table} {$where_string} {$limit_string} {$offset_string}";

        $sth = $this->pdo->prepare($sql);

        if ($where !== null && !empty($where)) {
            foreach ($where as $key => $value) {
                if (strpos($key, ':') !== false) {
                    $sth->bindValue($key, $value);
                } else {
                    $field = explode(' ', $key)[0];
                    $sth->bindValue(":{$field}_unique", $value);
                }
            }
        }
        $sth->execute();
        return $sth->fetchAll();
    }

    /**
     * Виконує SQL запит INSERT для вставки даних у таблицю.
     *
     * @param string $table Назва таблиці.
     * @param array $row_to_insert Дані для вставки.
     * @return int Кількість змінених рядків.
     */
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

    /**
     * Виконує SQL запит DELETE для видалення даних з таблиці.
     *
     * @param string $table Назва таблиці.
     * @param mixed $where Умова фільтрації.
     * @return int Кількість видалених рядків.
     */
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

    /**
     * Виконує SQL запит UPDATE для оновлення даних у таблиці.
     *
     * @param string $table Назва таблиці.
     * @param array $row_to_update Дані для оновлення.
     * @param mixed $where Умова фільтрації.
     * @return int Кількість змінених рядків.
     */
    public function update($table, $row_to_update, $where)
    {
        $where_string = $this->where($where);
        $set_array = [];
        foreach ($row_to_update as $key => $value) {
            $set_array[] = "{$key} = :{$key}";
        }
        $set_string = implode(", ", $set_array);
        $sql = "UPDATE {$table} SET {$set_string} {$where_string}";
        $sth = $this->pdo->prepare($sql);
        foreach ($row_to_update as $key => $value) {
            $sth->bindValue(":{$key}", $value);
        }
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $value = $value[0];
            }
            $sth->bindValue(":{$key}_unique", $value);
        }
        $sth->execute();
        return $sth->rowCount();
    }

    /**
     * Виконує SQL запит SELECT з використанням оператора LIKE.
     *
     * @param string $table Назва таблиці.
     * @param mixed $fields Поля для вибірки.
     * @param mixed $where Умова фільтрації.
     * @param int|null $limit Ліміт на кількість записів.
     * @param int $offset Зсув.
     * @return array
     */
    public function select_like($table, $fields = "*", $where = null, $limit = null, $offset = 0)
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
            $where_string = $this->where_like($where);
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
                    if ($value === null) {
                        $sth->bindValue(":{$field}_unique", NULL);
                    } else {
                        $sth->bindValue(":{$field}_unique", "%{$value}%");
                    }
                }
            }
        }
        $sth->execute();
        return $sth->fetchAll();
    }

    /**
     * Створює SQL умовний вираз для WHERE з оператором LIKE.
     *
     * @param mixed $where Умова для фільтрації.
     * @return string
     */
    protected function where_like($where)
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
                    $operator = isset($split[1]) ? $split[1] : 'LIKE';
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

}