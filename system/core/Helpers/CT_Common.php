<?php

function camelcase($str)
{
    $str = ucwords(strtolower($str));
    return $str;
}

function currency_format($amount)
{
    return number_format((float)$amount, 2, '.', ',');
}

function addcomma($array)
{
    $comma_separated = implode(',', array_map(function ($i) {
        return $i;
    }, $array));
    return $comma_separated;
}

function update_running_no($run_id, $table = "master_running_no")
{
    $rowRef =  db()->where("run_id", $run_id)->fetchRow($table);

    $data = [
        'run_current_no' => $rowRef['run_current_no'] + 1,
        'updated_at' => timestamp(),
    ];

    db()->where('run_id', $run_id)->update($table, $data);
}

function encode_base64($sData)
{
    $sBase64 = base64_encode($sData);
    return strtr($sBase64, '+/', '-_');
}

function decode_base64($sData)
{
    $sBase64 = strtr($sData, '-_', '+/');
    return base64_decode($sBase64);
}

function encodeID($id)
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $uniqueURL = substr(str_shuffle($permitted_chars), 0, 15) . '' . $id . '' . substr(str_shuffle($permitted_chars), 0, 15);
    return encode_base64($uniqueURL);
}

function decodeID($id)
{
    $id = decode_base64($id);
    return substr($id, 15, -15);
}

function genCode($str, $table, $column, $codeLength = 4)
{
    $strArr = explode(' ', $str);
    $i = 1;
    $idx = 0;
    $code = '';

    while ($i < $codeLength) {
        if (isset($strArr[$idx][0])) {
            $code .= $strArr[$idx][0];
            $strArr[$idx] = substr($strArr[$idx], 1);
        } else {
            $code .= '0';
        }

        $i++;
        $idx++;
        if ($idx >= sizeof($strArr)) {
            $idx = 0;
        }
    }

    $codeCnt = rawQuery("select count(*)+1 as cnt from {$table} where {$column} like '{$code}_%'");
    return $code . $codeCnt['cnt'];
}

function formatIC($icno, $type = 1)
{

    if ($type == 1) {
        // count length. nric should be 12.
        if (strlen($icno) == 12) {
            // add - to ic number
            $first = substr($icno, 0, 6);
            $second = substr($icno, 6, 2);
            $third = substr($icno, 8, 4);

            $icno = $first . '-' . $second . '-' . $third;
        }
    } else {
        // remove - to ic number
        $icno = str_replace("-", "", $icno);
    }

    return $icno;
}
