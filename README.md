# 🧪 External DB Dummy Plugin for WordPress

This plugin demonstrates how to connect a WordPress plugin to one or more **external databases**, using basic hardcoded connection pooling logic (no GUI, no config screen).

## 🧠 Idea

- WordPress core continues to use its **main database (DB1)**
- This plugin connects to an **external database (DB2)** with custom logic
- Heavy plugin data (e.g. products, prices, logs, etc.) can be stored outside of the main WP DB
- Helps reduce **bloat and performance load** on the main WordPress database

## 💡 Example Use Case

Imagine you're building a big plugin like a shop, a CRM, or a directory.  
Instead of dumping thousands of rows into `wp_postmeta`, your plugin uses a separate database.

This project shows **how to do that with a minimal setup**.

## 📦 Structure

```

wp-external-db-dummy-plugin/
├── wp-external-db-dummy-plugin.php   ← Main plugin file
├── db-config.php                     ← Hardcoded DB hosts + weights
├── external-db.php                   ← Connection pooling logic
└── README.md                         ← This file

````

---

## 🔧 `db-config.php`

```php
<?php
// Hardcoded database pool (can be extended later)
return [
    'mydb1' => ['host' => '127.0.0.1', 'user' => 'user1', 'pass' => 'pw1', 'db' => 'plugin_db_1', 'weight' => 2],
    'mydb2' => ['host' => '127.0.0.2', 'user' => 'user2', 'pass' => 'pw2', 'db' => 'plugin_db_2', 'weight' => 1],
];
````

---

## 🔌 `external-db.php`

```php
<?php

function get_external_db_connection() {
    $configs = require __DIR__ . '/db-config.php';

    // Basic weight-based connection selection (manual pool)
    $pool = [];
    foreach ($configs as $key => $cfg) {
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
```

---

## 🔌 `wp-external-db-dummy-plugin.php`

```php
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
```

---

## 📌 Notes

* This plugin does not use `$wpdb`
* No options or settings in WordPress
* Pure PHP + mysqli
* Connection pooling is simulated with a weighted array

---

## 🔐 Requirements

* PHP 7.4+
* `mysqli` enabled
* WordPress 5.0+
* External DBs accessible from the WP host

---

## 🤖 Author

Made by **VolkanSah \:D** – Giving WordPress plugins their own brains.

Feel free to fork, improve, or turn it into a real boilerplate for plugins that want to **scale outside the core**.

```

