<?php

function getCurrentUserID()
{
    return session()->get('userID');
}

function getCurrentUserRole()
{
    return session()->get('roleID');
}

function getCurrentRoleName()
{
    return session()->get('roleName');
}

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

function getPermission($controllerName = NULL, $methodName = NULL)
{
    // if (empty($controllerName) and empty($methodName)) {
    //     $url = rtrim($_GET['url'], '/');
    //     $url = filter_var($url, FILTER_SANITIZE_URL);
    //     $url = explode('/', $url);
    //     $url = ($url[1] != '') ? strtolower($url[0]) . "/" . $url[1] : strtolower($url[0]);
    // } else {
    //     $url = (!empty($methodName)) ? strtolower($controllerName) . "/$methodName" : strtolower($controllerName);
    // }

    // $roleID = session()->get('roleID');

    // $listAbility = array();

    // $dataMenu = model('Menu_model')->getMenuByURL($url);

    // if (!empty($dataMenu)) {
    //     $dataAbility = model('Menu_abilities_model')->getAbilityByMenuID($dataMenu['menu_id']);

    //     foreach ($dataAbility as $ability) {

    //         $abilities_name = $ability['abilities_name'];
    //         $ownedBy = $ability['only_owned'];
    //         $arrayRole = explodeArr($ownedBy);

    //         if (in_array($roleID, $arrayRole)) {
    //             array_push($listAbility, $abilities_name);
    //         }
    //     }
    // }

    // return $listAbility;

    return ['can-edit', 'can-delete'];
}

function checkPageAccess($roleAccess = array(), $exceptID = array())
{
    $userID = getCurrentUserID();
    $roleID = getCurrentUserRole();

    if (!in_array($userID, $exceptID)) {
        if (in_array($roleID, $roleAccess)) {
            return false;
            exit;
        } else {
            error('403');
            exit;
        }
    }
}
