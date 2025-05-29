<?php
/**
 * Plugin Name: External DB Dummy Plugin
 * Description: A simple plugin that connects to an external DB with manual connection pooling (no GUI).
 * Version: 0.1
 * Author: Als Batman :D
 */

require_once __DIR__ . '/external-db.php';

add_action('admin_notices', function () {
    $conn = get_external_db_connection();
    if (!$conn) {
        echo "<div class='notice notice-error'><p>External DB connection failed.</p></div>";
        return;
    }

    $result = $conn->query("SELECT * FROM products LIMIT 5");
    echo "<div class='notice notice-success'><p>External DB connected. Found " . $result->num_rows . " products.</p></div>";
});
