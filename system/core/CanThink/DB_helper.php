<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

function db()
{
    if (empty($db)) {
        $db = new Database;
    } else {
        return Database::getInstance();
    }

    return $db;
}

function db_name($debug = false)
{
    $environment = $_ENV['ENVIRONMENT'];

    if ($environment == 'production') {
        $dbName = $_ENV['server.db'];
    } else if ($environment == 'staging') {
        $dbName = $_ENV['test.db'];
    } else {
        $dbName = $_ENV['local.db'];
    }

    return $dbName;
}

function escape($str)
{
    return db()->escape_data($str);
}

function insert($table, $data, $returnID = false)
{
    $data['created_at'] = timestamp();

    if ($returnID) {
        return db()->insert($table, sanitizeInput($data)) ?? 400;
    } else {
        return (db()->insert($table, sanitizeInput($data))) ? 200 : 400;
    }
}

function update($table, $data, $pkValue, $pkTable = NULL)
{
    if (empty($pkTable)) {
        $getPKTable = db()->rawQuery("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
        $pkTable = $getPKTable[0]['Column_name'];
    }

    $data['updated_at'] = timestamp();
    return (db()->where($pkTable, escape($pkValue))->update($table, sanitizeInput($data))) ? 200 : 400;
}

function delete($table, $pkValue, $pkTable = NULL)
{
    if (empty($pkTable)) {
        $getPKTable = db()->rawQuery("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
        $pkTable = $getPKTable[0]['Column_name'];
    }

    return (db()->where($pkTable, escape($pkValue))->delete($table)) ? 200 : 400;
}

// The updateOrInsert method will attempt to locate a matching database record using the first argument's column and value pairs. If the record exists, it will be updated with the values in the second argument. If the record can not be found, a new record will be inserted with the merged attributes of both arguments
function updateOrInsert($table, $data = array(), $debug = false)
{
    $db =  db(); // initiate database
    $dataInsert = [];
    $pkValue = $pkColumnName = $id = NULL; // set to NULL

    // get primary key
    $getPKTable = primary_field($table, $debug);
    $pkTable = $getPKTable[0]['COLUMN_NAME'];

    // check if data is associative or sequential array
    if (isAssociative($data)) {
        if (isset($data[$pkTable])) {
            if (!empty($data[$pkTable])) {
                $db->where($pkTable, $data[$pkTable]); // get condition value from primary key
            } else {
                $db->where($pkTable, 0); // set to 0 
            }
        } else {
            $db->where(key($data), reset($data)); // get condition value from first key
        }
    } else {
        foreach ($data[0] as $key => $value) {
            $db->where("$key", "$value");
        }
    }

    $exist = $db->fetchRow($table);

    $dataInsert = (isAssociative($data)) ?  $data : ((!empty($exist)) ? $data[1] : merge($data[0], $data[1]));

    // remove all column field that does't exist in db
    foreach ($dataInsert as $key => $value) {
        if (!isColumnExist($table, $key)) {
            unset($dataInsert[$key]);
        }
    }

    if (isset($dataInsert[$pkTable])) {
        unset($dataInsert[$pkTable]); // auto increment, no need to update or insert
    }

    if (!empty($exist)) {
        $id = $exist[$pkTable]; // get pk from table
        $resCode = update($table, $dataInsert, $id, $pkTable);
        $message =  message($resCode, 'update');
    } else {
        $result = insert($table, $dataInsert, true);
        $id = ($result !== 400) ? $result : NULL;
        $resCode = ($result !== 400) ? 200 : 400;
        $message =  message($resCode, 'create');
    }

    $result = array(
        "resCode" => $resCode,
        "message" => $message,
        "id" => $id,
        "data" => sanitizeInput($dataInsert)
    );

    return $result;
}

function save($table, $data)
{
    $db =  db(); // initiate database
    $dataInsert = [];
    $pkValue = $pkColumnName = NULL;

    // get primary key
    $getPKTable = primary_field($table);
    $pkTable = $getPKTable[0]['COLUMN_NAME'];

    // search if data exist using PK
    $db->where($pkTable, $data[$pkTable]);
    $exist = $db->fetchRow($table);

    $dataInsert = (isAssociative($data)) ?  $data : ((!empty($exist)) ? $data[1] : merge($data[0], $data[1]));
    $timestamp = (!empty($exist)) ? 'updated_at' : 'created_at';

    $dataInsert[$timestamp] = timestamp();

    // remove all column field that does't exist in db
    foreach ($dataInsert as $key => $value) {
        if (!isColumnExist($table, $key)) {
            unset($dataInsert[$key]);
        }
    }

    $pkValue = $data[$pkTable]; //set pk value
    $pkColumnName = $pkTable;  // set pk table name

    unset($dataInsert[$pkTable]); // auto increment, no need to update or insert

    if (!empty($exist)) {
        $id = $pkValue;
        $resCode = (db()->where($pkColumnName, $pkValue)->update($table, sanitizeInput($dataInsert))) ? 200 : 400;
        $message =  message($resCode, 'update');
    } else {
        $id = db()->insert($table, sanitizeInput($dataInsert));
        $resCode = ($id !== false) ? 200 : 400;
        $message =  message($resCode, 'create');
    }

    $result = array(
        "resCode" => $resCode,
        "message" => $message,
        "id" => $id,
        "data" => sanitizeInput($dataInsert)
    );

    return $result;
}

function rawQuery($query)
{
    return db()->rawQuery($query);
}

function countValue($table, $columnToCount, $dataToCount)
{
    $db = db(); // initiate database
    $db->where($columnToCount, $dataToCount);
    return $db->getValue($table, "count(*)");
}

function insertMulti($table, $data, $ids = NULL)
{
    if ($ids) {
        return (db()->insertMulti($table, $data)) ?? 400;
    } else {
        return (db()->insertMulti($table, $data)) ? 200 : 400;
    }
}

function table_list()
{
    $dbName = db_name();
    return db()->rawQuery("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$dbName'");
}

function primary_field($table, $debug = false)
{
    $dbName = db_name($debug);
    return db()->rawQuery("SELECT COLUMN_NAME, COLUMN_KEY, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table' AND COLUMN_KEY = 'PRI'");
}

function not_primary_field($table, $debug = false)
{
    $dbName = db_name($debug);
    $data = db()->rawQuery("SELECT COLUMN_NAME, COLUMN_KEY, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table' AND COLUMN_KEY <> 'PRI'");

    foreach ($data as $row) {
        $fields[] = array('column_name' => $row['COLUMN_NAME'], 'column_key' => $row['COLUMN_KEY'], 'data_type' => $row['DATA_TYPE']);
    }

    return $fields;
}

function all_field($table, $debug = false)
{
    $dbName = db_name($debug);
    $data = db()->rawQuery("SELECT COLUMN_NAME, COLUMN_KEY, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table'");

    foreach ($data as $row) {
        $fields[] = array('column_name' => $row['COLUMN_NAME'], 'column_key' => $row['COLUMN_KEY'], 'data_type' => $row['DATA_TYPE']);
    }

    return $fields;
}

function enum_field($table, $columnName)
{
    $dbName = db_name();
    $data = db()->rawQuery("SELECT
              TRIM(TRAILING ')' FROM TRIM(LEADING '(' FROM TRIM(LEADING 'enum' FROM column_type))) column_type
            FROM
              information_schema.columns
            WHERE
              TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table' AND column_name = '$columnName'");

    return explodeArr($data[0]['column_type']);
}

function sanitizeInput($dataArr)
{
    $sanitize = [];

    if (isAssociative($dataArr)) {
        foreach ($dataArr as $key => $value) {
            // check if empty field
            if ($value == '') {
                $sanitize[$key] = NULL;
            } else {
                $sanitize[$key] = (!isJson($value)) ? escape($value) : $value;
            }
        }
    } else {
        foreach ($dataArr[0] as $key => $value) {
            // check if empty field
            if ($value == '') {
                $sanitize[$key] = NULL;
            } else {
                $sanitize[$key] = (!isJson($value)) ? escape($value) : $value;
            }
        }
    }

    return $sanitize;
}

function isTableExist($table)
{
    $dbName = db_name();
    $result = db()->rawQuery("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table'");

    if (empty($result))
        return false;
    else
        return true;
}

function isColumnExist($table, $columnName)
{
    $dbName = db_name();

    // check table
    if (isTableExist($table)) {
        $result = db()->rawQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table' AND COLUMN_NAME='$columnName'");
    }

    if (empty($result))
        return false;
    else
        return true;
}
