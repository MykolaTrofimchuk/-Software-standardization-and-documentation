<?php

namespace core;

/**
 * Базовий клас для моделей, що працюють з таблицями бази даних.
 *
 * Цей клас надає базові методи для взаємодії з базою даних, включаючи збереження, пошук, видалення та інші операції.
 */
class Model
{
    /**
     * Масив для зберігання значень полів об'єкта.
     *
     * @var array
     */
    protected $fieldsArray;

    /**
     * Назва первинного ключа таблиці.
     *
     * @var string
     */
    protected static $primaryKey = 'id';

    /**
     * Назва таблиці в базі даних.
     *
     * @var string
     */
    protected static $tableName = '';

    /**
     * Конструктор класу.
     *
     * Ініціалізує масив для полів моделі.
     */
    public function __construct()
    {
        $this->fieldsArray = [];
    }

    /**
     * Встановлює значення для властивості.
     *
     * @param string $name Назва властивості.
     * @param mixed $value Значення для встановлення.
     */
    public function __set($name, $value)
    {
        $this->fieldsArray[$name] = $value;
    }

    /**
     * Отримує значення властивості.
     *
     * @param string $name Назва властивості.
     * @return mixed Значення властивості або null, якщо не знайдено.
     */
    public function __get($name)
    {
        return $this->fieldsArray[$name] ?? null;
    }

    /**
     * Зберігає об'єкт в базі даних (використовує insert або update).
     */
    public function save()
    {
        $value = $this->{static::$primaryKey};
        if (empty($value)) { // insert
            var_dump($this->fieldsArray);
            Core::get()->db->insert(static::$tableName, $this->fieldsArray);
        } else { // update
            Core::get()->db->update(static::$tableName, $this->fieldsArray,
                [
                    static::$primaryKey => $value
                ]);
        }
    }

    /**
     * Видаляє запис за його ID.
     *
     * @param int $id ID запису.
     */
    public static function deleteById($id)
    {
        Core::get()->db->delete(static::$tableName, [static::$primaryKey => $id]);
    }

    /**
     * Видаляє записи за умовою.
     *
     * @param array $conditionAssocArr Асоціативний масив умов.
     */
    public static function deleteByCondition($conditionAssocArr)
    {
        Core::get()->db->delete(static::$tableName, $conditionAssocArr);
    }

    /**
     * Знаходить запис за його ID.
     *
     * @param int $id ID запису.
     * @return Model|null Повертає об'єкт моделі або null, якщо не знайдено.
     */
    public static function findById($id)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0) {
            $user = new \Models\Users();
            $user->fieldsArray = $arr[0];
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Знаходить записи за умовою.
     *
     * @param array $conditionAssocArr Асоціативний масив умов.
     * @return array|null Повертає масив записів або null, якщо не знайдено.
     */
    public static function findByCondition($conditionAssocArr)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', $conditionAssocArr);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    /**
     * Знаходить усі записи таблиці.
     *
     * @return array|null Повертає масив усіх записів або null, якщо таблиця порожня.
     */
    public static function findAll()
    {
        $arr = Core::get()->db->select(static::$tableName);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    /**
     * Знаходить певні рядки за умовою.
     *
     * @param mixed $rows Список полів для вибірки.
     * @param array|null $conditionAssocArr Умова для фільтрації.
     * @return array|null Повертає масив знайдених рядків або null, якщо не знайдено.
     */
    public static function findRowsByCondition($rows, $conditionAssocArr = null)
    {
        $arr = Core::get()->db->select(static::$tableName, $rows, $conditionAssocArr);
        if (count($arr) > 0)
            return $arr;
        else
            return null;
    }

    /**
     * Знаходить одну строку по ID та класу.
     *
     * @param int $id ID запису.
     * @param string $className Назва класу для створення об'єкта.
     * @return Model|null Повертає об'єкт моделі або null, якщо не знайдено.
     */
    public static function selectRowById($id, $className)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0) {
            $obj = new $className();
            foreach ($arr[0] as $key => $value) {
                $obj->{$key} = $value;
            }
            return $obj;
        } else {
            return null;
        }
    }

    /**
     * Знаходить записи з обмеженням на кількість та зсув.
     *
     * @param int $limit Ліміт на кількість записів.
     * @param int $offset Зсув.
     * @return array Масив знайдених записів.
     */
    public static function findByLimitAndOffset($limit, $offset)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', null, $limit, $offset);
        if (count($arr) > 0)
            return $arr;
        else
            return [];
    }
}
