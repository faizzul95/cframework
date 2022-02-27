<?php

function groupArray($arr, $colNames)
{
    $groupStr = '$groupedArr';
    $groupedArr = array();

    foreach ($colNames as $groupCol) {
        $groupStr .= '[$value[\'' . $groupCol . '\']]';
    }
    foreach ($arr as $key => $value) {
        $grpStr = $groupStr . '[] = $value;';
        eval($grpStr);
    }

    return $groupedArr;
}

function fillUndefinedIndex($arr, $colNames, $emptyIndexStr = 'undefined')
{
    foreach ($colNames as $colName) {
        foreach ($arr as $key => $value) {
            if (!isset($value[$colName])) {
                $arr[$key][$colName] = $emptyIndexStr;
            }
        }
    }
    return $arr;
}

function searcharrayExist($valueSearch, $array)
{
    foreach ($array as $keyData => $data) {
        if ($keyData == $valueSearch) {
            return true;
        }
    }
    return false;
}

// use only for RBAC
function searcharrayvaluesExist($valueSearch, $array, $colNames)
{
    foreach ($array as $data) {
        if ($data[$colNames] == $valueSearch) {
            return true;
        }
    }
    return false;
}

function explodeArr($array, $type = ',')
{
    $str = explode($type, $array);
    return $str;
}

function merge($array, $array2)
{
    $str = array_merge($array, $array2);
    return $str;
}

function isArray($data)
{
    return (is_array($data)) ? true : false;
}

function isAssociative($arr)
{
    foreach (array_keys($arr) as $key)
        if (!is_int($key)) return TRUE;
    return FALSE;
}

function object_to_array($data)
{
    if (is_array($data) || is_object($data)) {
        $result = [];
        foreach ($data as $key => $value) {
            $result[$key] = (is_array($data) || is_object($data)) ? object_to_array($value) : $value;
        }
        return $result;
    }

    return $data;
}

function convertObjectToArray($data)
{
    if (is_object($data)) {
        return (array) $data;
        exit();
    }

    return $data;
}
