<?php

namespace core;

class Model
{
    protected $fieldsArray;
    protected static $primaryKey = 'id';
    protected static $tableName = '';

    public function __construct()
    {
        $this->fieldsArray = [];
    }

    public function __set($name, $value)
    {
        $this->fieldsArray[$name] = $value;
    }

    public function __get($name)
    {
        return $this->fieldsArray[$name] ?? null;
    }

    public function save()
    {
        $value = $this->{static::$primaryKey};
        if (empty($value)) // insert
        {
            var_dump($this->fieldsArray);
            Core::get()->db->insert(static::$tableName, $this->fieldsArray);
        } else // update
        {
            Core::get()->db->update(static::$tableName, $this->fieldsArray,
                [
                    static::$primaryKey => $value
                ]);
        }
    }

    public static function deleteById($id)
    {
        Core::get()->db->delete(static::$tableName, [static::$primaryKey => $id]);
    }

    public static function deleteByCondition($conditionAssocArr)
    {
        Core::get()->db->delete(static::$tableName, $conditionAssocArr);
    }

    public static function findById($id)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', [static::$primaryKey => $id]);
        if (count($arr) > 0){
            $user = new \Models\Users();
            $user->fieldsArray = $arr[0];
            return $user;
        } else {
            return null;
        }
    }

    public static function findByCondition($conditionAssocArr)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', $conditionAssocArr);
        if(count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public static function findAll()
    {
        $arr = Core::get()->db->select(static::$tableName);
        if(count($arr) > 0)
            return $arr;
        else
            return null;
    }

    public static function findRowsByCondition($rows, $conditionAssocArr = null)
    {
        $arr = Core::get()->db->select(static::$tableName, $rows, $conditionAssocArr);
        if(count($arr) > 0)
            return $arr;
        else
            return null;
    }

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

    public static function findByLimitAndOffset($limit, $offset)
    {
        $arr = Core::get()->db->select(static::$tableName, '*', null, $limit, $offset);
        if(count($arr) > 0)
            return $arr;
        else
            return [];
    }
}