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
        $users = [
            1 => ['name' => 'CanThink Administrator', 'nickname' => 'Administrator', 'email' => 'admin@developer.com', 'role' => 0],
            2 => ['name' => 'Mohd Fahmy Izwan', 'nickname' => 'Fahmy', 'email' => 'fahmy@developer.com', 'role' => 1],
        ];

        foreach ($users as $id => $user) {
            Users::save([
                'user_id' => $id,
                'user_full_name' => $user['name'],
                'user_preferred_name' => $user['nickname'],
                'user_email' => $user['email'],
                'user_avatar' => 'default/user.png',
                'role_id' => $user['role'],
                'user_status' => '1',
                'user_password' => password_hash('1234qwer', PASSWORD_DEFAULT)
            ]);
        }

        $class = get_class($this);

        echo "$class running succesfully <br>";
    }
}
