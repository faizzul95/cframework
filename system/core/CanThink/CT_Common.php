<?php

function getMenu()
{
    // $roleID = session()->get('roleID');
    // $menuData = model('Menu_permission_model')->getMenuByRoleID($roleID, 1);

    $arrayMenu = array();

    // if ($menuData) {
    //     $dataGrouping = groupArray($menuData, ['is_main_menu']);

    //     foreach ($dataGrouping[0] as $main) {
    //         if ($main['menu_location'] == 1) {
    //             $arrayMenu[] = [
    //                 'menu_id' => $main['menu_id'],
    //                 'menu_title' => $main['menu_title'],
    //                 'menu_url' => $main['menu_url'],
    //                 'menu_order' => $main['menu_order'],
    //                 'menu_icon' => $main['menu_icon'],
    //                 'submenu' => model('Menu_permission_model')->getSubMenuByMenuID($roleID, $main['menu_id'])
    //             ];
    //         }
    //     }
    // }

    return $arrayMenu;
}

function getAsideMenu()
{
    // $roleID = session()->get('roleID');
    // $menuData = model('Menu_permission_model')->getMenuByRoleID($roleID, 0);
    $arrayMenu = array();

    // if ($menuData) {
    //     $dataGrouping = groupArray($menuData, ['is_main_menu']);
    //     foreach ($dataGrouping[0] as $main) {
    //         if ($main['menu_location'] == 0) {
    //             $arrayMenu[] = [
    //                 'menu_id' => $main['menu_id'],
    //                 'menu_title' => $main['menu_title'],
    //                 'menu_url' => $main['menu_url'],
    //                 'menu_order' => $main['menu_order'],
    //                 'menu_icon' => $main['menu_icon'],
    //                 'submenu' => model('Menu_permission_model')->getSubMenuByMenuID($roleID, $main['menu_id'])
    //             ];
    //         }
    //     }
    // }

    return $arrayMenu;
}

function uppercase($str)
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

function getAbilities($controllerName = NULL, $methodName = NULL)
{
    if (empty($controllerName) and empty($methodName)) {
        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $url = explode('/', $url);
        $url = ($url[1] != '') ? strtolower($url[0]) . "/" . $url[1] : strtolower($url[0]);
    } else {
        $url = (!empty($methodName)) ? strtolower($controllerName) . "/$methodName" : strtolower($controllerName);
    }

    $roleID = session()->get('roleID');

    $listAbility = array();

    $dataMenu = model('Menu_model')->getMenuByURL($url);

    if (!empty($dataMenu)) {
        $dataAbility = model('Menu_abilities_model')->getAbilityByMenuID($dataMenu['menu_id']);

        foreach ($dataAbility as $ability) {

            $abilities_name = $ability['abilities_name'];
            $ownedBy = $ability['only_owned'];
            $arrayRole = explodeArr($ownedBy);

            if (in_array($roleID, $arrayRole)) {
                array_push($listAbility, $abilities_name);
            }
        }
    }

    return $listAbility;
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

    $codeCnt = db()->rawQueryOne("select count(*)+1 as cnt from {$table} where {$column} like '{$code}_%'");
    return $code . $codeCnt['cnt'];
}
