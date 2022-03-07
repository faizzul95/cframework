<?php

class Users
{
    protected $table = 'user';

    public function up()
    {
        $column =  [
            'usr_id' => array(
                'type' => 'INT',
                'length' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'usr_number' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'usr_kod_id' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'null' => TRUE
            ),
            'started_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'ended_date' => array(
                'type' => 'DATE',
                'null' => TRUE
            ),
            'usr_fname' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'usr_gender' => array(
                'type' => 'VARCHAR',
                'length' => 10,
                'null' => TRUE
            ),
            'usr_hp' => array(
                'type' => 'VARCHAR',
                'length' => 15,
                'null' => TRUE
            ),
            'usr_race' => array(
                'type' => 'VARCHAR',
                'length' => 100,
                'null' => TRUE
            ),
            'usr_religion' => array(
                'type' => 'VARCHAR',
                'length' => 100,
                'null' => TRUE
            ),
            'usr_ic' => array(
                'type' => 'VARCHAR',
                'length' => 20,
                'null' => TRUE
            ),
            'usr_add' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'null' => TRUE
            ),
            'usr_add2' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'null' => TRUE
            ),
            'usr_add3' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'null' => TRUE
            ),
            'usr_job_no' => array(
                'type' => 'VARCHAR',
                'length' => 15,
                'null' => TRUE
            ),
            'dept_id' => array(
                'type' => 'INT',
                'length' => '11',
                'comment' => 'Refer table master_department',
                'null' => TRUE
            ),
            'position_id' => array(
                'type' => 'INT',
                'length' => '11',
                'comment' => 'Refer table master_position',
                'null' => TRUE
            ),
            'bank_id' => array(
                'type' => 'INT',
                'length' => '11',
                'comment' => 'Refer table master_bank',
                'null' => TRUE
            ),
            'br_id' => array(
                'type' => 'INT',
                'length' => '11',
                'comment' => 'Refer table master_branch',
                'null' => TRUE
            ),
            'role_id' => array(
                'type' => 'INT',
                'length' => '11',
                'comment' => 'Refer table master_role',
                'null' => TRUE
            ),
            'acc_no' => array(
                'type' => 'VARCHAR',
                'length' => '50',
                'null' => TRUE
            ),
            'usr_status' => array(
                'type' => 'VARCHAR',
                'length' => '50',
                'null' => TRUE
            ),
            'usr_reason' => array(
                'type' => 'VARCHAR',
                'length' => '50',
                'null' => TRUE
            ),
            'avatar' => array(
                'type' => 'VARCHAR',
                'length' => '255',
                'default' => 'default/user.png',
                // 'null' => FALSE, <-- remove / comment if already set default
            ),
            'usr_password' => array(
                'type' => 'VARCHAR',
                'length' => '255',
                'null' => TRUE
            ),
            'test_column_for_drop' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'drop' => TRUE, // example for drop column, if set drop to true it will never will add / will be remove if exist
            ),
            'usr_username' => array(
                'after' => 'avatar', // will add after column avater (only work when update - need to run 2x if first create)
                'type' => 'VARCHAR',
                'length' => '30',
                'null' => TRUE
            ),
        ];

        $key = [
            1 => ['type' => 'PRIMARY KEY', 'reference' => 'usr_id'],
            2 => ['type' => 'INDEX', 'reference' => 'bank_id'],
            3 => ['type' => 'INDEX', 'reference' => 'position_id'],
            4 => ['type' => 'INDEX', 'reference' => 'dept_id'],
            5 => ['type' => 'INDEX', 'reference' => 'role_id'],
            6 => ['type' => 'INDEX', 'reference' => 'br_id'],
            7 => ['type' => 'INDEX', 'reference' => 'hahaha_kelakar'], // <--- if ada nak set key tapi column tu xwujud dia akan remove nanti dlm helper
        ];

        migrate($this->table, $column, $key);
        echo "Table <b style='color:red'><i>{$this->table}</i></b> migrate running succesfully <br>";
    }

    public function down()
    {
        // dropTable($this->table);
        // dropColumn($this->table. 'user_email');
    }

    public function relation()
    {
        $relation = [
            'USER_BRANCH' => array(
                'FOREIGN_KEY' => 'br_id',
                'REFERENCES_TABLE' => 'master_branch',
                'REFERENCES_KEY' => 'br_id',
                'ON_DELETE' => 'CASCADE',
                'ON_UPDATE' => 'NO ACTION',
            ),
            'USER_DEPARTMENT' => array(
                'FOREIGN_KEY' => 'dept_id',
                'REFERENCES_TABLE' => 'master_department',
                'REFERENCES_KEY' => 'dept_id',
                'ON_DELETE' => 'CASCADE',
                'ON_UPDATE' => 'NO ACTION',
            ),
        ];

        addRelation($this->table, $relation);
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