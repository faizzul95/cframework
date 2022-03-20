<?php

// Import all model want to use
// REMINDER : Make sure alias or 'as' are not same as any class name
use User_model as users;

class User extends Controller
{
    public function index()
    {
        error('404'); // redirect to page error 404
    }

    // open page list 
    public function list()
    {
        $data = [
            'title' => 'User List',
            'currentSidebar' => 'User',
            'currentSubSidebar' => 'List',
        ];

        render('user/list_user', $data);
    }

    // open page list 2
    public function list2()
    {
        $data = [
            'title' => 'User List',
            'currentSidebar' => 'User',
            'currentSubSidebar' => 'List',
        ];

        render('user/list_user2', $data);
    }

    // use in list_user for client side datatable (with csrf)
    public function getAll()
    {
        json(users::all());
    }

    // use in list_user2 for server side datatable
    public function getAllServerSide()
    {
        echo $this->users->getlist();
    }

    public function getUsersByID()
    {
        $id = escape($_POST['id']);
        $data = users::find($id); // call static function
        json($data);
    }

    public function getUsersByCode()
    {
        $code = escape($_POST['id']);
        $data = users::find($code, 'user_code'); // call static function
        json($data);
    }

    public function create()
    {
        $data = users::insert($_POST); // call static function
        json($data);
    }

    public function update()
    {
        $data = users::update($_POST); // call static function
        json($data);
    }

    public function save()
    {
        // $data = users::save($_POST);
        $data = users::updateOrInsert($_POST); // call static function
        json($data);
    }

    public function delete()
    {
        $data = users::delete($_POST['id']); // call static function
        json($data);
    }
}


 /* 

 Q&A SECTION

 Q : What is static function / method ?
 A : A static function is a member function of a class that can be called even when an object of the class is not initialized.

 Q : How to call or access static function @ method ?
 A : Just use '::' without ' to call static function. 
     Example : users::find($id). users is alias that has been declare on top of controller file using 'use'

 Q : How to call normal or non-static function @ method in model ?
 A : Just use '$this->' without ' to call a non-static function from model. 
     Example : $this->users->getUserByID($id). users is alias that has been declare on top of controller file using 'use'

SIMPLE DOCUMENTATION

STATIC METHOD / FUNCTION AVAILABLE (USE ONLY IN CONTROLLER)
    1) all()
    2) find($value, $columnName)
    3) findOrFail($value)
    4) where($columnName, $value)
    5) first()
    6) last()
    7) save($array)
    8) insert($array)
    9) updateOrInsert($array)
    10) update($array)
    11) delete($value, $columnName)

Notes : 
 - for more global function please go to folder 
     1) system/core/Model.php 
     2) system/CanThink - all files

 Reminder :
 - Please avoid using save name as static method@function in MODEL (folder app/models). Controller is fine to redeclare same function name.
       
*/