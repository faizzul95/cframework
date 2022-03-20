<?php

class Model
{
    public function __construct()
    {
        $this->db = db();
        $this->serversideDt = serverSideDT($this->db);
        $this->getInstanceDB = $this->db->getInstance();
    }

    // all() takes all data in a model. If no matching model exist, it returns null
    public static function all()
    {
        $className = get_called_class();
        $obj = new $className;

        $data = db()->get($obj->table);
        return (!empty($data)) ? $data : NULL;
    }

    // find($id) takes an id and returns a single model. If no matching model exist, it returns null
    public static function find($id = NULL, $columnName = NULL)
    {
        $id = escape($id);

        $className = get_called_class();
        $obj = new $className;

        $columnName = (!empty($columnName)) ? escape($columnName) : $obj->primaryKey;

        $data = db()->where($columnName, $id)->fetchRow($obj->table);
        return (!empty($data)) ? $data : NULL;
    }

    // findOrFail($id) takes an id and returns a single model. If no matching model exist, it throws an error1
    public static function findOrFail($id = NULL)
    {
        $id = escape($id);

        $className = get_called_class();
        $obj = new $className;

        try {
            $data = db()->where($obj->primaryKey, $id)->fetchRow($obj->table);
            if (empty($data)) {
                throw new Exception("Data $id not found.");
            }
        } catch (Exception $e) {
            $data = $e->getMessage();
        }

        return $data;
    }

    // where($coloumName, $value) takes the condition and returns all data related in model
    public static function where($coloumName = NULL, $value = NULL, $con = NULL)
    {
        $value = escape($value);
        $className = get_called_class();
        $obj = new $className;
        return (empty($con)) ? db()->where($coloumName, $value)->get($obj->table) : db()->where($coloumName, $value, $con)->get($obj->table);
    }

    // first() returns the first record found in the database. If no matching model exist, it returns null
    public static function first()
    {
        $className = get_called_class();
        $obj = new $className;
        $data = db()->rawQuery("SELECT * FROM $obj->table ORDER BY $obj->primaryKey ASC LIMIT 1");
        return (!empty($data)) ? $data : NULL;
    }

    // last() returns the last record found in the database. If no matching model exist, it returns null
    public static function last()
    {
        $className = get_called_class();
        $obj = new $className;
        $data = db()->rawQuery("SELECT * FROM $obj->table ORDER BY $obj->primaryKey DESC LIMIT 1");
        return (!empty($data)) ? $data : NULL;
    }

    public static function save($data = array())
    {
        $className = get_called_class();
        $obj = new $className;

        if (isset($obj->fillable)) {
            $id = escape($data[$obj->primaryKey]);
            $dataArr = array(); // reset array

            foreach ($obj->fillable as $columnName) {
                if (isset($data[$columnName])) {
                    $dataArr[$columnName] = escape($data[$columnName]);
                }
            }

            $dataArr[$obj->primaryKey] = $id; // add id PK

            $data = $dataArr;
        }

        return save($obj->table, $data);
    }

    public static function insert($data = array())
    {
        $className = get_called_class();
        $obj = new $className;

        if (isset($obj->fillable)) {
            $dataInsert = array(); // reset array
            foreach ($obj->fillable as $columnName) {
                if (isset($data[$columnName])) {
                    $dataInsert[$columnName] = escape($data[$columnName]);
                }
            }
            return insert($obj->table, $dataInsert, true);
            exit();
        }

        return updateOrInsert($obj->table, $data);
    }

    public static function updateOrInsert($data = array())
    {
        $className = get_called_class();
        $obj = new $className;

        if (isset($obj->fillable)) {
            $id = escape($data[$obj->primaryKey]);
            $dataArr = array(); // reset array

            foreach ($obj->fillable as $columnName) {
                if (isset($data[$columnName])) {
                    $dataArr[$columnName] = escape($data[$columnName]);
                }
            }

            $dataArr[$obj->primaryKey] = $id; // add id PK

            $data = $dataArr;
        }

        return updateOrInsert($obj->table, $data);
    }

    public static function update($data = array())
    {
        $className = get_called_class();
        $obj = new $className;

        if (isset($obj->fillable)) {
            $id = escape($data[$obj->primaryKey]);
            $dataUpdate = array(); // reset array
            foreach ($obj->fillable as $columnName) {
                if (isset($data[$columnName])) {
                    $dataUpdate[$columnName] = escape($data[$columnName]);
                }
            }
            $dataUpdate[$obj->primaryKey] = $id; // add id PK
            return update($obj->table, $dataUpdate, $id);
            exit();
        }

        return save($obj->table, $data);
    }

    public static function bulkData($data = array())
    {
        $className = get_called_class();
        $obj = new $className;
        return insertMulti($obj->table, $data, true);
    }

    public static function delete($id = NULL, $pkTable = NULL)
    {
        $id = escape($id);
        $className = get_called_class();
        $obj = new $className;
        return delete($obj->table, $id, $pkTable);
    }

    public static function bulkDeleteData($data = NULL, $pkTable = NULL)
    {
        $className = get_called_class();
        $obj = new $className;

        $countError = $countSuccess = 0;

        foreach ($data as $bulkData) {
            foreach ($bulkData as $id) {
                $delete = delete($obj->table, $id, $pkTable);
                ($delete == 200) ? $countSuccess++ : $countError++;
            }
        }

        return ($countError < 1) ? 200 : 400;
    }

    public static function countData($dataToCount = NULL, $columnToCount = NULL)
    {
        $className = get_called_class();
        $obj = new $className;
        return countValue($obj->table, $columnToCount, $dataToCount);
    }
}
