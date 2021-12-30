<?php

class User
{
    public function up()
    {
        $table = 'user';

        $column =  [
            'test_id' => array(
                'type' => 'INT',
                'length' => 5,
                'unsigned' => TRUE,
                'auto_increment' => TRUE,
                'null' => FALSE,
            ),
            'test_title' => array(
                'type' => 'VARCHAR',
                'length' => '100',
                'comment' => '1 - Male, 2 - Female',
                'default' => '1',
            ),
            'test_description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
            'test_description2' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'drop' => TRUE, // example for drop column 
            ),
            'test_description' => array(
                'type' => 'TEXT',
                'null' => TRUE,
            ),
        ];

        $key = array('PRIMARY KEY' => 'test_id');
        migrate($table, $column, $key);
        echo "Table '$table' migrate running succesfully <br>";
    }

    public function down()
    {
        // dropTable('user');
    }
}

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