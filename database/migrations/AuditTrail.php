<?php

class AuditTrail
{
    protected $table = 'pmo_audit_trails';

    public function up()
    {
        $column =  [
            'id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'user_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'role_id' => array(
                'type' => 'BIGINT',
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'user_fname' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'event' => array(
                'type' => 'ENUM',
                'length' => "'insert','update','delete'",
                'null' => TRUE
            ),
            'table_name' => array(
                'type' => 'VARCHAR',
                'length' => 128,
                'null' => TRUE
            ),
            'old_values' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE
            ),
            'new_values' => array(
                'type' => 'LONGTEXT',
                'null' => TRUE
            ),
            'url' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'length' => 50,
                'null' => TRUE
            ),
            'user_agent' => array(
                'type' => 'VARCHAR',
                'length' => 255,
                'null' => TRUE
            )
        ];

        $key = [
            1 => ['type' => 'PRIMARY KEY', 'reference' => 'id'],
            2 => ['type' => 'INDEX', 'reference' => 'user_id'],
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