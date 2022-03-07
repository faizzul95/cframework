<?php

use User_model as Users;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->_createDefaultUser();
    }

    protected function _createDefaultUser()
    {
        $users = $this->_dataSeed();

        foreach ($users as $id => $user) {
            Users::save([
                'user_id' => $id,
                'user_full_name' => $user['name'],
                'user_preferred_name' => $user['nickname'],
                'user_email' => $user['email'],
                'user_avatar' => 'default/user.png',
                'user_status' => '1',
                'user_password' => password_hash('password', PASSWORD_DEFAULT)
            ]);
        }

        $class = get_class($this);

        echo "<b style='color:red'><i>{$class}</i></b> running succesfully <br>";
    }

    public function _dataSeed()
    {
        return [
            1 => ['name' => 'CanThink Administrator', 'nickname' => 'Administrator', 'email' => 'admin@developer.com'],
            2 => ['name' => 'Mohd Fahmy Izwan', 'nickname' => 'Fahmy', 'email' => 'fahmy@developer.com'],
        ];
    }
}
