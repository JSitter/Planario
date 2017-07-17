<?php
/*
Plugin Name: Planario
Description: Store events and times.
Author: Justin Sitter
Author URI: http://jaytria.com
Version: 0.1
*/

// ────────────────────────────────────────────────────────────────────────────────
//  Add Table to wordpress database on plugin install
// ────────────────────────────────────────────────────────────────────────────────

function planario_install(){
    global $wpdb;
    $table_name = $wpdb->prefix . "planario_events"; //Piece together plugin table name
    $charset_collate = $wpdb->get_charset_collate();  


    /* ────────────────────────────────────────────────────────────────────────────────
        id : primary key
        user_id : wordpress user_id
        event : Name of event
        start_time: date object
        end_time : date object
    */// ────────────────────────────────────────────────────────────────────────────────

    $sql_query = "CREATE TABLE " . $table_name . " (
      id int(10) NOT NULL AUTO_INCREMENT,
      user_id int(10) NOT NULL,
      event tinytext NOT NULL,
      start_time VARCHAR(100) NULL,
      end_time VARCHAR(100) NULL,
      PRIMARY KEY  (id),
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php'); //call dbDelta function from upgrade.php to update db
    dbDelta( $sql_query );


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
    $event = $record['event'];
    $user_id = $record['user_id'];
    $start_time = $record['start_time'];
    $end_time = $record['end_time'];
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'planario_events';

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
    global $wpdb;
    $table_name = $wpdb->prefix . 'planario_events';

    $returned_array = $wpdb->get_results( "SELECT * FROM ".$table_name." WHERE user_id = ".$user_id, ARRAY_A );

}

function planario_remove_event( $event_id ){
    global $wpdb;
    $table_name = $wpdb->prefix . 'planario_events';
    $return = $wpdb->delete($table_name, array( 'ID' => $event_id));

}

function db_test_insert(){
    
    $record = array(
        'event' => 'Meet me in Seattle @ Noon',
        'user_id' => get_current_user_id(),
        'start_time' => "0000-00-00 00:00:00",
        'end_time' => "0000-00-00 00:00:00",
    );

    planario_db_insert($record);
}

function db_test_list_events(){
    planario_get_event_all('23');
}

function db_test_delete(){
    planario_remove_event('10');
}

// ────────────────────────────────────────────────────────────────────────────────
//  Activate db table install functions on plugin initilization
// ────────────────────────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'planario_install');
register_activation_hook( __FILE__, 'db_test_insert' );
register_activation_hook( __FILE__, 'db_test_list_events');

// ────────────────────────────────────────────────────────────────────────────────
//  Create Menu Item
//      function handles creating menu and directing traffic to plugin page
// ────────────────────────────────────────────────────────────────────────────────
function planario_menu_item(){
    add_menu_page('Planario Event Manager', 'Planario Events', 'manage_options', plugin_dir_path( __FILE__ ) . 'planario_page.php');
}
add_action('admin_menu', 'planario_menu_item');