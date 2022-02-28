<?php

// set configuration audit trails
$audit = [

    /*
    |--------------------------------------------------------------------------
    | Audit Trail Enable 
    |--------------------------------------------------------------------------
    |
    | The default settings is set to 'FALSE'. change to 'TRUE' if this function needed
    |
    */

    'audit_enable' => TRUE,

    /*
    |--------------------------------------------------------------------------
    | Audit Trail Table Name 
    |--------------------------------------------------------------------------
    |
    | Change the audit table name according to your preference
    |
    */

    'audit_table' => 'audit_trails',

    /*
    |--------------------------------------------------------------------------
    | Insert event track
    |--------------------------------------------------------------------------
    |
    | Set [TRUE/FALSE] to track insert event.
    |
    */

    'track_insert' => TRUE,

    /*
    |--------------------------------------------------------------------------
    | Update event track
    |--------------------------------------------------------------------------
    |
    | Set [TRUE/FALSE] to track update event.
    |
    */

    'track_update' => TRUE,


    /*
    |--------------------------------------------------------------------------
    | Delete event track
    |--------------------------------------------------------------------------
    |
    | Set [TRUE/FALSE] to track delete event.
    |
    */

    'track_delete' => TRUE,

    /*
    |--------------------------------------------------------------------------
    | Audit Trail Column Name
    |--------------------------------------------------------------------------
    */

    'column' => [
        "id",
        "user_id",
        "user_fname",
        "event",
        "table_name",
        "old_values",
        "old_values",
        "new_values",
        "url",
        "ip_address",
        "user_agent",
    ],


];
