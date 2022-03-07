<?php

class Users
{
    protected $table = 'pmo_user';

    public function up()
    {
        $column =  [
            'user_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'user_code' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'comment' => 'Auto Generate, Use for system reference',
                'null' => TRUE
            ),
            'user_salutation' => array(
                'type' => 'VARCHAR',
                'length' => 10,
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
            'user_gender' => array(
                'type' => 'VARCHAR',
                'length' => 15,
                'null' => TRUE,
            ),
            'user_email' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'user_username' => array(
                'type' => 'VARCHAR',
                'length' => 20,
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
                'null' => TRUE
            ),
            'user_status' => array(
                'type' => 'TINYINT',
                'null' => TRUE,
                'comment' => '0 - Inactive, 1 - Active',
                'default' => '1',
            )
        ];

        $key = [
            1 => ['type' => 'PRIMARY KEY', 'reference' => 'user_id'],
            2 => ['type' => 'INDEX', 'reference' => 'user_code'],
            3 => ['type' => 'INDEX', 'reference' => 'cpy_code'],
        ];

        migrate($this->table, $column, $key);
        echo "Table <b style='color:red'><i>{$this->table}</i></b> migrate running succesfully <br>";
    }

    public function down()
    {
        dropTable($this->table);
        echo "Table <b style='color:red'><i>{$this->table}</i></b> drop succesfully <br>";
    }

    public function relation()
    {
        // empty
    }
}

// type => INT, TINYINT, BIGINT, CHAR, VARCHAR, TEXT, DATE, YEAR, TIMESTAMP, DATE, TIME, DATETIME, DECIMAL, FLOAT, BOOLEAN, ENUM
// unsigned => TRUE / FALSE
// auto_increment => TRUE / FALSE
// null => TRUE / FALSE
// length
// comment
// default
// rename => (only use to change column name)
// after => (add column after tablename)
// drop => TRUE (remove if dont want to drop)