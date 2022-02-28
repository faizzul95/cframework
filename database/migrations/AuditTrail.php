<?php

class AuditTrail
{
    protected $table = 'audit_trails';

    public function up()
    {
        $column =  [
            'id' => array(
                'type' => 'INT',
                'length' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'user_id' => array(
                'type' => 'INT',
                'length' => 11,
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
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'new_values' => array(
                'type' => 'TEXT',
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
            2 => ['type' => 'INDEX', 'reference' => 'usr_id'],
        ];

        migrate($this->table, $column, $key);
        echo "Table <b style='color:red'><i>{$this->table}</i></b> migrate running succesfully <br>";
    }

    public function down()
    {
        // empty
    }

    public function relation()
    {
        // empty
    }
}
