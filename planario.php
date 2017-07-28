<?php
/*
Plugin Name: Planario
Description: Simple event planner created for employment consideration at Yardi.
Author: Justin Sitter
Author URI: http://jaytria.com
Version: 0.1
*/

// ────────────────────────────────────────────────────────────────────────────────
//  Planario Version 0.1
// ────────────────────────────────────────────────────────────────────────────────
//
//  Simple event planner Wordpress plugin 
//  for employment consideration at Yardi
// ────────────────────────────────────────────────────────────────────────────────

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
    //call dbDelta function from upgrade.php to update db
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php'); 
    dbDelta( $sql_query );

}

// ────────────────────────────────────────────────────────────────────────────────
/*  
    DB Insert, Update, and Delete functions
    Insert Record into Database

    $record = array(
        'event'     =>  $event, 
        'user_id'   =>  $user_id, 
         'start_time'=>  $start_time,
        'end_time'  =>  $end_time,   
)

*/// ────────────────────────────────────────────────────────────────────────────────
function planario_db_insert( $record ){
    $event = sanitize_text_field($record['event']);
    $user_id = sanitize_text_field($record['user_id']);
    $start_time = sanitize_text_field($record['start_time']);
    $end_time = sanitize_text_field($record['end_time']);
    
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

function planario_db_update($record){
    global $wpdb;
    $id = stripslashes_deep(sanitize_text_field($record['id']));
    $field = sanitize_text_field($record['field']);
    $data = sanitize_text_field($record['data']);

    if($field == 'event_title'){
        $field = 'event';
    } 

    $table_name = $wpdb->prefix . 'planario_events';
    
    return $wpdb->update($table_name, array($field=>$data), array( 'id'=>$id) );

       
}

// ────────────────────────────────────────────────────────────────────────────────
//  Get all events belonging to logged-in user
// ────────────────────────────────────────────────────────────────────────────────
function planario_get_event_all( $user_id ){
    global $wpdb;
    $table_name = DB_NAME . ".".$wpdb->prefix . 'planario_events';

    return $wpdb->get_results("SELECT id, event, start_time, end_time FROM $table_name WHERE user_id=$user_id");
}

// ────────────────────────────────────────────────────────────────────────────────
//  Delete Event by id
// ────────────────────────────────────────────────────────────────────────────────
function planario_remove_event( $event_id ){
    global $wpdb;
    $table_name = $wpdb->prefix . 'planario_events';

    return $wpdb->delete($table_name, array( 'ID' => $event_id));
}

// ────────────────────────────────────────────────────────────────────────────────
//  Activate db table install functions on plugin initilization
// ────────────────────────────────────────────────────────────────────────────────
register_activation_hook( __FILE__, 'planario_install');

// ────────────────────────────────────────────────────────────────────────────────
//  Create Menu Item
//      function handles creating menu and directing traffic to plugin page
// ────────────────────────────────────────────────────────────────────────────────
function planario_menu_item(){
    add_menu_page('Planario Event Manager', 'Planario Events', 'manage_options', plugin_dir_path( __FILE__ ) . 'planario_page.php');
}


// ────────────────────────────────────────────────────────────────────────────────
//  Build HTML Table
// ────────────────────────────────────────────────────────────────────────────────
function planario_event_table(){
    $results = planario_get_event_all(get_current_user_id());

    return planario_build_html_table($results);
};

function planario_build_html_table_row( $values ){

    $html_row = "<td class='planario_data_item' data-id='$values->id' data-column='event_title'>$values->event</td><td class='planario_data_item' data-id='$values->id' data-column='start_time'>$values->start_time</td><td class='planario_data_item' data-id='$values->id' data-column='end_time'>$values->end_time</td>";
    
    //Add Delete Button
    $html_row .= "<td class='planario-ctl-column'><a onclick='delete_entry($values->id)' class='planario-delete-btn'>Delete</a></td>";

    return $html_row;
}

function planario_build_html_table( $db_return ){
    $html_table = "<table id='planario_table' class='planario'>";
    foreach($db_return as $record){
       $row = planario_build_html_table_row($record);
        $html_table .= "<tr>".$row. "</tr>";
    }
    $html_table .= "</table>";
    return $html_table;
}

// ────────────────────────────────────────────────────────────────────────────────
//  Load stylesheet on admin page
// ────────────────────────────────────────────────────────────────────────────────
function planario_load_plugin_page_items( $page ){

    //Only load files on plugin page
    if($page != "planario/planario_page.php"){
       
       return;
    }

    wp_enqueue_style( 'custom_admin_style', plugins_url('styles.css', __FILE__ ));

    //Add Javascript to page
    wp_register_script('planario-page-js', plugin_dir_url(__FILE__) . 'planario_script.js');
    wp_enqueue_script('planario-page-js', null, false);
    wp_enqueue_script('jquery');
    
    //Add Ajax to page
    wp_localize_script( 'planario-page-js', 'ajax_object', array('request' => admin_url('admin-ajax.php')));

}


// ────────────────────────────────────────────────────────────────────────────────
//  Action Items
// ────────────────────────────────────────────────────────────────────────────────
//add menu item
add_action('admin_menu', 'planario_menu_item');
//Enqueue css on admin page
add_action( 'admin_enqueue_scripts', 'planario_load_plugin_page_items');

//Ajax Requests
//delete_event action
add_action('wp_ajax_delete_event', 'planario_action_delete_event');
//add_event action
add_action('wp_ajax_add_event', 'planario_action_add_event');
//edit_event action
add_action('wp_ajax_edit_event', 'planario_action_edit_event');


// ────────────────────────────────────────────────────────────────────────────────
//  AJAX Requests
//      adding AJAX functions after wp_ajax per wordpress codex
// ────────────────────────────────────────────────────────────────────────────────
function planario_action_delete_event(){
    planario_remove_event($_POST['event_id']);
   
    wp_send_json_success(planario_event_table());
    wp_die();

}

function planario_action_add_event(){

    $record = array(
        'event'     =>  $_POST['event_name'],
        'user_id'   =>  get_current_user_id(), 
        'start_time'=>  $_POST['start_time'],
        'end_time'  =>  $_POST['end_time'],   
    );
    planario_db_insert($record);
    wp_send_json_success(planario_event_table());
    wp_die;
}

function planario_action_edit_event(){
    $cell = array(
    'id' => $_POST['id'],
    'field'=> $_POST['field'],
    'data' => $_POST['data']
    );
     
    planario_db_update($cell);
    wp_send_json_success(planario_event_table());
    wp_die;
}