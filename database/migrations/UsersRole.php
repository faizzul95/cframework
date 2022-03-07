<?php

class UsersRole
{
    protected $table = 'pmo_user_role';

    public function up()
    {
        $column =  [
            'user_role_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'user_code' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'comment' => 'Refer table pmo_user',
                'null' => TRUE
            ),
            'role_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'comment' => 'Refer table pmo_master_roles',
                'null' => TRUE,
            ),
            'role_status' => array(
                'type' => 'TINYINT',
                'null' => TRUE,
                'comment' => '0 - Inactive, 1 - Active',
                'default' => '1',
            )
        ];

        $key = [
            1 => ['type' => 'PRIMARY KEY', 'reference' => 'user_role_id'],
            2 => ['type' => 'INDEX', 'reference' => 'user_code'],
            3 => ['type' => 'INDEX', 'reference' => 'role_id'],
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