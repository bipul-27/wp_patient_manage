<?php

namespace FluentPlugin\Database\Migrations;

class PrescriptionTestsMigrator
{
    static $tableName = 'prescription_tests';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . static::$tableName;

        $sql = "CREATE TABLE $table (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
             doctor_id INT UNSIGNED NOT NULL,
            patient_id INT UNSIGNED NOT NULL,
            prescription_id INT UNSIGNED NOT NULL,
            test_name VARCHAR(255) NOT NULL,
            extra_info TEXT,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->prefix}doctors(id) ON DELETE CASCADE,
            FOREIGN KEY (patient_id) REFERENCES {$wpdb->prefix}patients(id) ON DELETE CASCADE,
            FOREIGN KEY (prescription_id) REFERENCES {$wpdb->prefix}prescription(id) ON DELETE CASCADE
        ) $charsetCollate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public static function drop()
    {
        global $wpdb;
        $table = $wpdb->prefix . static::$tableName;
        $wpdb->query("DROP TABLE IF EXISTS $table");
    }
}
