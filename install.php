<?php
    function planario_install(){
        global $wpdb;
        $table_name = $wpdb->prefix . "_planario_events";
        $charset_collate = $wpdb->get_charset_collate();

        $sql_query = "CREATE TABLE " . $table_name . " (
            event_id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id int(5) NOT NULL,
            title varchar(256) DEFAULT '' NOT NULL,
            start_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            end_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            location varchar(256) DEFAULT '' NOT NULL,
            notes longtext DEFAULT, '' NOT NULL,
            PRIMARY KEY  (id)
        )   $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta( $sql );


    }

    function planario_insert($title, $user_id, $start_time, $end_time, $location, $notes){
        global $wpdb;
        $table_name = $wpdb->prefix . '_planario_events';

        $wpdb->insert(
            $table_name,
            array(
                'title' => $title,
                'user_id' => $user_id,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'location' => $location,
                'notes' => $notes,
            )
        );
    }
