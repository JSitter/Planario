<?php
    function planario_install(){
        global $wpdb;
        $table_name = $wpdb->prefix . "_planario_events";
        $charset_collate = $wpdb->get_charset_collate();
        
    }
