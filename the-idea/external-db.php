<?php
function get_external_db_connection() {
    $configs = require __DIR__ . '/db-config.php';

    // Simple weighted connection pool
    $pool = [];
    foreach ($configs as $cfg) {
        for ($i = 0; $i < $cfg['weight']; $i++) {
            $pool[] = $cfg;
        }
    }

    $selected = $pool[array_rand($pool)];

    $mysqli = new mysqli($selected['host'], $selected['user'], $selected['pass'], $selected['db']);

    if ($mysqli->connect_error) {
        error_log("External DB connection failed: " . $mysqli->connect_error);
        return null;
    }

    return $mysqli;
}
