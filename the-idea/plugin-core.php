<?php
/*
Plugin Name: Simple DB Router
Description: Routes specific plugin queries to a dedicated secondary database for testing purposes.
Version: 0.1
Author: Your Name
*/

global $secondary_db;

// Define secondary DB connection (hartcodiert)
$secondary_db = new wpdb('db_user2', 'db_pass2', 'db_name2', 'db_host2');

// Funktion, um Query-Routing zu entscheiden
function sdb_route_query($query, $wpdb) {
    global $secondary_db;

    //  Leite alle Queries für die Tabelle 'plugin_special_table' um
    if (strpos($query, 'plugin_special_table') !== false) {
        // Query an secondary DB senden und Ergebnis zurückgeben
        return $secondary_db->query($query);
    }

    // Sonst normale Verarbeitung (original $wpdb)
    return false; // false signalisiert WP, normal weiterzumachen
}

// Hook in die query-Execution
add_filter('query', 'sdb_route_query', 10, 2);
