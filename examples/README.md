# Simple DB Router Directory

A minimal WordPress plugin that routes contact directory data to a dedicated external database.

## Features

- Adds a new admin page for managing contact entries (name, phone, email, address, website).
- Stores data in a custom table located in a separate database.
- Secure database interaction with input sanitization and error handling.
- Basic CRUD functionality (Create, Read, Update, Delete).
- CSRF protection with WordPress nonces.

## Requirements

Before activating the plugin, you **must define the external database connection parameters** in your `wp-config.php` file.

Add the following lines to your `wp-config.php` for second database, do not replace with your actual database credentials!

```php
define('SDBD_DB_HOST', 'your-db-host');
define('SDBD_DB_NAME', 'your-db-name');
define('SDBD_DB_USER', 'your-db-user');
define('SDBD_DB_PASS', 'your-db-password');
````

> ⚠️ If these constants are not set, the plugin will fallback to the default WordPress database, which may not be desired.

## Installation

1. Copy the plugin folder into the `/wp-content/plugins/` directory.
2. Define the DB connection constants in your `wp-config.php` as described above.
3. Activate the plugin from the WordPress admin panel.
4. Go to `Directory` in the admin menu to start managing entries.

## Security

* All data is sanitized before being inserted or updated in the database.
* Nonce verification is used to prevent CSRF attacks.
* Database errors are logged or displayed in the admin panel where appropriate.

## Notes

* The plugin creates a table called `wp_directory_contacts` in the target database.
* Indexing is applied to the `name` field for faster queries.
* No front-end output is included in this plugin; it's designed for admin use only.

## License

Copyright by Volkan Kücükbudak- Privat-License! Only for showcase and education!
