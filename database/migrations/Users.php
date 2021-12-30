<?php

class User_migration
{
    public function up()
    {
        $table = 'users';

        $column =  [
            'user_id' => array(
                'type' => 'INT',
                'length' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'user_code' => array(
                'type' => 'VARCHAR',
                'length' => 15,
                'null' => TRUE
            ),
            'user_full_name' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'user_preferred_name' => array(
                'type' => 'VARCHAR',
                'length' => 30,
                'null' => TRUE
            ),
            'user_preferred_name' => array(
                'type' => 'VARCHAR',
                'length' => 30,
                'null' => TRUE
            ),
            'user_gender' => array(
                'type' => 'TINYINT',
                'length' => '1',
                'comment' => '1 - Male, 2 - Female',
                'default' => '1'
            ),
            'user_email' => array(
                'type' => 'VARCHAR',
                'length' => 100,
                'null' => TRUE
            ),
            'user_username' => array(
                'type' => 'VARCHAR',
                'length' => 100,
                'null' => TRUE
            ),
            'user_password' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'user_avatar' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'default' => 'default/user.png',
                // 'null' => FALSE, <-- remove or commment of set default
            ),
            'role_id' => array(
                'type' => 'INT',
                'length' => 11,
                'default' => '1',
            ),
            'user_status' => array(
                'type' => 'INT',
                'length' => 11,
                'default' => '1',
                'comment' => '0 - Inactive, 1 - Active',
            ),
            'test_column_for drop' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'drop' => TRUE, // example for drop column, if set drop to true it will never will add / will be remove if exist
            )
        ];

        $key = array('PRIMARY KEY' => 'user_id');

        $result = migrate($table, $column, $key);
        echo "Table '$table' migrate running succesfully <br>";
    }

    public function down()
    {
        //     // dropTable('users');
        //     // dropColumn('users'. 'user_email');
    }
}


  // $relation = [
        //     'USER_COMPANY' => array(
        //         'FOREIGN KEY' => 'company_id',
        //         'REFERENCES_TABLE' => 'company',
        //         'REFERENCES_KEY' => 'company_id',
        //         'ON DELETE' => 'CASCADE',
        //         'ON UPDATE' => 'NO ACTION',
        //         'null' => FALSE,
        //     ),
        // ];




// type => INT, TINYINT, BIGINT, CHAR, VARCHAR, TEXT, DATE, YEAR, TIMESTAMP, DATE, TIME, DATETIME, DECIMAL, FLOAT, BOOLEAN
// unsigned => TRUE / FALSE
// auto_increment => TRUE / FALSE
// null => TRUE / FALSE
// length
// comment
// default
// name => (only use to change column name)
// after => (add column after tablename)
// drop => TRUE (remove if dont want to drop)