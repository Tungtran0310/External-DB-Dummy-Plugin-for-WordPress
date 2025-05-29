<?php
/*
Plugin Name: Simple DB Router Directory
Description: Minimal plugin to route directory data to a dedicated database.
Version: 0.2
Author: Volkan Kücükbudak
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// DB-Konfiguration über wp-config.php
// Füge diese Zeilen in deine wp-config.php ein:
// define('SDBD_DB_HOST', 'localhost');
// define('SDBD_DB_NAME', 'directory_db');
// define('SDBD_DB_USER', 'db_user');
// define('SDBD_DB_PASS', 'db_pass');

class SimpleDBDirectory {
    private static $instance = null;
    private $db = null;
    private $table_name = '';
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        register_activation_hook(__FILE__, [$this, 'create_table']);
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_init', [$this, 'handle_actions']);
    }
    
    // Sichere DB-Verbindung mit Fehlerbehandlung
    private function get_db() {
        if ($this->db === null) {
            // Fallback auf Standard-DB falls Konstanten nicht gesetzt
            $host = defined('SDBD_DB_HOST') ? SDBD_DB_HOST : DB_HOST;
            $name = defined('SDBD_DB_NAME') ? SDBD_DB_NAME : DB_NAME;
            $user = defined('SDBD_DB_USER') ? SDBD_DB_USER : DB_USER;
            $pass = defined('SDBD_DB_PASS') ? SDBD_DB_PASS : DB_PASSWORD;
            
            $this->db = new wpdb($user, $pass, $name, $host);
            $this->table_name = $this->db->prefix . 'directory_contacts';
            
            // DB-Verbindung testen
            if (!empty($this->db->error)) {
                wp_die('Database connection failed: ' . $this->db->error);
            }
        }
        return $this->db;
    }
    
    // Tabelle erstellen mit besserer Fehlerbehandlung
    public function create_table() {
        $db = $this->get_db();
        $charset_collate = $db->get_charset_collate();
        
        $sql = "CREATE TABLE {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            phone varchar(20),
            email varchar(100),
            address text,
            website varchar(200),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX idx_name (name)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $result = dbDelta($sql);
        
        if (!empty($db->last_error)) {
            error_log('SDBD Table creation error: ' . $db->last_error);
        }
    }
    
    public function admin_menu() {
        add_menu_page(
            'Directory', 
            'Directory', 
            'manage_options', 
            'sdbd-directory', 
            [$this, 'admin_page'],
            'dashicons-groups'
        );
    }
    
    // CSRF-sichere Action-Behandlung
    public function handle_actions() {
        if (!isset($_POST['sdbd_action']) || !current_user_can('manage_options')) {
            return;
        }
        
        // Nonce prüfen
        if (!wp_verify_nonce($_POST['sdbd_nonce'], 'sdbd_action')) {
            wp_die('Security check failed');
        }
        
        $db = $this->get_db();
        
        switch ($_POST['sdbd_action']) {
            case 'add':
                $this->add_contact($db);
                break;
            case 'delete':
                $this->delete_contact($db);
                break;
            case 'edit':
                $this->edit_contact($db);
                break;
        }
    }
    
    private function add_contact($db) {
        $result = $db->insert($this->table_name, [
            'name' => sanitize_text_field($_POST['sdbd_name']),
            'phone' => sanitize_text_field($_POST['sdbd_phone']),
            'email' => sanitize_email($_POST['sdbd_email']),
            'address' => sanitize_textarea_field($_POST['sdbd_address']),
            'website' => esc_url_raw($_POST['sdbd_website']),
        ]);
        
        if ($result === false) {
            add_action('admin_notices', function() use ($db) {
                echo '<div class="error"><p>Error: ' . $db->last_error . '</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="updated"><p>Contact added successfully.</p></div>';
            });
        }
    }
    
    private function delete_contact($db) {
        $id = intval($_POST['contact_id']);
        $result = $db->delete($this->table_name, ['id' => $id], ['%d']);
        
        if ($result === false) {
            add_action('admin_notices', function() use ($db) {
                echo '<div class="error"><p>Delete failed: ' . $db->last_error . '</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="updated"><p>Contact deleted.</p></div>';
            });
        }
    }
    
    private function edit_contact($db) {
        $id = intval($_POST['contact_id']);
        $result = $db->update(
            $this->table_name,
            [
                'name' => sanitize_text_field($_POST['sdbd_name']),
                'phone' => sanitize_text_field($_POST['sdbd_phone']),
                'email' => sanitize_email($_POST['sdbd_email']),
                'address' => sanitize_textarea_field($_POST['sdbd_address']),
                'website' => esc_url_raw($_POST['sdbd_website']),
            ],
            ['id' => $id],
            ['%s', '%s', '%s', '%s', '%s'],
            ['%d']
        );
        
        if ($result === false) {
            add_action('admin_notices', function() use ($db) {
                echo '<div class="error"><p>Update failed: ' . $db->last_error . '</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="updated"><p>Contact updated.</p></div>';
            });
        }
    }
    
    public function admin_page() {
        $db = $this->get_db();
        $edit_contact = null;
        
        // Edit-Modus prüfen
        if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
            $edit_contact = $db->get_row($db->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $_GET['edit']));
        }
        
        // Kontakte laden
        $results = $db->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
        
        if ($db->last_error) {
            echo '<div class="error"><p>Database Error: ' . $db->last_error . '</p></div>';
        }
        ?>
        
        <div class="wrap">
            <h1>Directory Management</h1>
            
            <!-- Add/Edit Form -->
            <div class="card" style="max-width: 600px;">
                <h2><?php echo $edit_contact ? 'Edit Contact' : 'Add New Contact'; ?></h2>
                <form method="post" style="padding: 20px;">
                    <?php wp_nonce_field('sdbd_action', 'sdbd_nonce'); ?>
                    <input type="hidden" name="sdbd_action" value="<?php echo $edit_contact ? 'edit' : 'add'; ?>">
                    <?php if ($edit_contact): ?>
                        <input type="hidden" name="contact_id" value="<?php echo $edit_contact->id; ?>">
                    <?php endif; ?>
                    
                    <table class="form-table">
                        <tr>
                            <th><label for="sdbd_name">Name *</label></th>
                            <td><input type="text" id="sdbd_name" name="sdbd_name" value="<?php echo $edit_contact ? esc_attr($edit_contact->name) : ''; ?>" class="regular-text" required></td>
                        </tr>
                        <tr>
                            <th><label for="sdbd_phone">Phone</label></th>
                            <td><input type="text" id="sdbd_phone" name="sdbd_phone" value="<?php echo $edit_contact ? esc_attr($edit_contact->phone) : ''; ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="sdbd_email">Email</label></th>
                            <td><input type="email" id="sdbd_email" name="sdbd_email" value="<?php echo $edit_contact ? esc_attr($edit_contact->email) : ''; ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th><label for="sdbd_address">Address</label></th>
                            <td><textarea id="sdbd_address" name="sdbd_address" rows="3" class="large-text"><?php echo $edit_contact ? esc_textarea($edit_contact->address) : ''; ?></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="sdbd_website">Website</label></th>
                            <td><input type="url" id="sdbd_website" name="sdbd_website" value="<?php echo $edit_contact ? esc_attr($edit_contact->website) : ''; ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" class="button button-primary" value="<?php echo $edit_contact ? 'Update Contact' : 'Add Contact'; ?>">
                        <?php if ($edit_contact): ?>
                            <a href="<?php echo admin_url('admin.php?page=sdbd-directory'); ?>" class="button">Cancel</a>
                        <?php endif; ?>
                    </p>
                </form>
            </div>
            
            <!-- Contacts List -->
            <h2>All Contacts (<?php echo count($results); ?>)</h2>
            <?php if (empty($results)): ?>
                <p>No contacts found.</p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Website</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($results as $contact): ?>
                        <tr>
                            <td><strong><?php echo esc_html($contact->name); ?></strong></td>
                            <td><?php echo esc_html($contact->phone); ?></td>
                            <td><?php echo esc_html($contact->email); ?></td>
                            <td><?php echo $contact->website ? '<a href="' . esc_url($contact->website) . '" target="_blank">Visit</a>' : '-'; ?></td>
                            <td><?php echo esc_html($contact->created_at); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=sdbd-directory&edit=' . $contact->id); ?>" class="button button-small">Edit</a>
                                <form method="post" style="display: inline;" onsubmit="return confirm('Really delete this contact?');">
                                    <?php wp_nonce_field('sdbd_action', 'sdbd_nonce'); ?>
                                    <input type="hidden" name="sdbd_action" value="delete">
                                    <input type="hidden" name="contact_id" value="<?php echo $contact->id; ?>">
                                    <input type="submit" class="button button-small button-link-delete" value="Delete">
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <?php
    }
}

// Plugin initialisieren
SimpleDBDirectory::getInstance();
