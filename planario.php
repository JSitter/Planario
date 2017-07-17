<?php
/*
Plugin Name: Planario
Description: Store dates and times
Author: Justin Sitter
Author URI: http://jaytria.com
Version: 0.1
*/

// ────────────────────────────────────────────────────────────────────────────────
//  Add Table to database on plugin install
// ────────────────────────────────────────────────────────────────────────────────

function planario_install(){
    global $wpdb;
    $table_name = $wpdb->prefix . "_planario_events"; //Piece together plugin table name
    $charset_collate = $wpdb->get_charset_collate();  


    /* ────────────────────────────────────────────────────────────────────────────────
        event_id : primary key
        user_id : wordpress user_id
        event : 
        start_time:
        end_time
     */// ────────────────────────────────────────────────────────────────────────────────

    $sql_query = "CREATE TABLE " . $table_name . " (
        event_id mediumint(9) NOT NULL AUTO_INCREMENT, 
        user_id int(5) NOT NULL,
        title varchar(256) DEFAULT '' NOT NULL,
        start_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        end_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        location varchar(256) DEFAULT '' NOT NULL,
        notes longtext DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    )   $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php'); //call dbDelta function from upgrade.php to update db
    dbDelta( $sql );


}

// ────────────────────────────────────────────────────────────────────────────────
/*  Insert Record into Database

    $record = array(
        'event'     =>  $event, 
        'user_id'   =>  $user_id, 
        'start_time'=>  $start_time,
        'end_time'  =>  $end_time,   
)

*/// ────────────────────────────────────────────────────────────────────────────────
function planario_db_insert( $record ){
    $user_id = $record['event'];
    $start_time = $record['user_id'];
    $end_time = $record['start_time'];
    $location = $record['end_time'];
    
    global $wpdb;
    $table_name = $wpdb->prefix . '_planario_events';

    $wpdb->insert(
        $table_name,
        array(
            'event' => $event,
            'user_id' => $user_id,
            'start_time' => $start_time,
            'end_time' => $end_time,
        )
    );

}

function planario_get_event_all( $user_id ){

}

function planario_get_event_range( $user_id ){

}

function planario_remove_event( $event_id ){

}

function db_test_insert(){
    $record = array(
        'event' => 'Meet me in Seattle @ Noon',
        'user_id' => '23',
        'start_time' => "0000-00-00 00:00:00",
        'end_time' => "0000-00-00 00:00:00",
    );
}

// ────────────────────────────────────────────────────────────────────────────────
//  Activate Install functions on plugin initilization
// ────────────────────────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'planario_install');
register_activation_hook( __FILE__, 'db_test_insert' );