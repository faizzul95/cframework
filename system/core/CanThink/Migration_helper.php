<?php

function migration_db()
{
    $migrateDB = $_ENV['MIGRATION_TABLE'];

    $dbName = db_name();
    $checkTable = db()->rawQuery("SELECT COUNT(*) as total FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$migrateDB'");

    // if not exist create table
    if ($checkTable[0]['total'] == 0) {
        db()->rawQuery("CREATE TABLE $migrateDB (
                        migration_id bigint NOT NULL AUTO_INCREMENT,
                        migration_file VARCHAR (200) NULL,
                        batch int DEFAULT '1' NULL,
                        PRIMARY KEY (migration_id)
                        ) ENGINE=InnoDB");
    }
}

function migrate($table, $column = array(), $key = array())
{
    // migration_db(); // check db migrate exist
    $dbName = db_name();

    $checkTableExist = db()->rawQuery("SELECT COUNT(*) AS total FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='$dbName' AND TABLE_NAME='$table'");

    $defaultLength = array(
        'TEXT' => NULL,
        'BIGINT' => NULL,
        'TIME' => NULL,
        'DATE' => NULL,
        'TIMESTAMP' => NULL,
        'DATETIME' => NULL,
        'VARCHAR' => '255',
        'TINYINT' => '4',
        'CHAR' => '5',
        'DECIMAL' => '10,2',
    );

    // if table not exist create new
    if ($checkTableExist[0]['total'] == 0) {

        $query = "CREATE TABLE $table (";

        foreach ($column as $columnName => $set) {

            // $type = $length = $attributes = $autoIncrement = $nullable = "";
            $type = (isset($set['type'])) ? $set['type'] : 'INT'; // set default to int

            $length = NULL;
            if (isset($set['length'])) {
                $length =  '(' . $set['length'] . ')';
            } else {
                if ($type == 'VARCHAR' || $type == 'TINYINT' || $type == 'CHAR' || $type == 'DECIMAL') {
                    $length =  '(' . $defaultLength[$type] . ')';
                }
            }

            $unsigned = (isset($set['unsigned']) == TRUE) ? 'UNSIGNED' : NULL; // set false to empty
            $autoIncrement = (isset($set['auto_increment']) === TRUE) ? ' AUTO_INCREMENT' : NULL; // set false to empty

            $nullable = 'NOT NULL'; // set default
            $default = NULL;
            if (isset($set['null'])) {
                if ($set['null'] === TRUE) {
                    $nullable = 'NULL';
                }
            }

            if (isset($set['default'])) {
                $textDefault = $set['default'];
                $default = " DEFAULT '$textDefault'";
            } else {
                $default = " $nullable";
            }

            $comment = NULL;
            if (isset($set['comment'])) {
                $textComment = $set['comment'];
                $comment = " COMMENT '$textComment'";
            }

            if (!isset($set['drop'])) {
                $query .= $columnName . " $type $length $default $autoIncrement $comment,";
            }
        }

        $query .=  "created_at TIMESTAMP NULL DEFAULT NULL,";
        $query .=  "updated_at TIMESTAMP NULL DEFAULT NULL,";

        foreach ($key as $keyName => $attribute) {
            $query .= $keyName . " ($attribute)";
        }
        $query .= ") ENGINE=InnoDB";

        rawQuery($query);
    }

    // else check for alter table
    else {

        $query = "ALTER TABLE $table ";

        $columnArray = array();
        foreach ($column as $columnName => $set) {
            // $type = $length = $attributes = $autoIncrement = $nullable = "";
            $type = (isset($set['type'])) ? $set['type'] : 'INT'; // set default to int

            $length = NULL;
            if (isset($set['length'])) {
                $length =  '(' . $set['length'] . ')';
            } else {
                if ($type == 'VARCHAR' || $type == 'TINYINT' || $type == 'CHAR' || $type == 'DECIMAL') {
                    $length =  '(' . $defaultLength[$type] . ')';
                }
            }

            $unsigned = (isset($set['unsigned']) == TRUE) ? 'UNSIGNED' : NULL; // set false to empty
            $autoIncrement = (isset($set['auto_increment']) === TRUE) ? ' AUTO_INCREMENT' : NULL; // set false to empty

            $nullable = 'NOT NULL'; // set default
            $default = NULL;
            if (isset($set['null'])) {
                if ($set['null'] === TRUE) {
                    $nullable = 'NULL';
                }
            }

            if (isset($set['default'])) {
                $textDefault = $set['default'];
                $default = " DEFAULT '$textDefault'";
            } else {
                $default = " $nullable";
            }

            $comment = NULL;
            if (isset($set['comment'])) {
                $textComment = $set['comment'];
                $comment = " COMMENT '$textComment'";
            }

            if (isColumnExist($table, $columnName)) {
                // CHANGE
                $addAfter = NULL;
                if (isset($set['after'])) {
                    $addAfter = 'AFTER ' . $set['after'];
                }

                if (isset($set['drop']) === TRUE) {
                    array_push($columnArray, trim("DROP `$columnName`"));
                } else {
                    if (isset($set['name'])) {
                        $newName = $set['name'];
                        array_push($columnArray, trim("CHANGE `$columnName` `$newName` $type $length $default $autoIncrement $comment $addAfter"));
                    } else {
                        array_push($columnArray, trim("CHANGE `$columnName` `$columnName` $type $length $default $autoIncrement $comment $addAfter"));
                    }
                }
            } else {
                // ADD
                if (!isset($set['drop'])) {
                    $addAfter = NULL;
                    if (isset($set['after'])) {
                        $addAfter = 'AFTER ' . $set['after'];
                    }
                    array_push($columnArray, trim("ADD `$columnName` $type $length $default $autoIncrement $comment $addAfter"));
                }
            }
        }

        $query .= implode(",", $columnArray);
        $query .= ";";
        rawQuery($query);
    }
}

function dropTable($tableName)
{
    $dbName = db_name();
    $dropTable = db()->rawQuery("DROP TABLE `$dbName`.`$tableName`");
}
