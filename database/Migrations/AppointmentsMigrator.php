<?php

namespace FluentPlugin\Database\Migrations;

class AppointmentsMigrator
{
    static $tableName = 'appointment';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . static::$tableName;

        $sql = "CREATE TABLE $table (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            patient_id INT UNSIGNED NOT NULL,
            doctor_id INT UNSIGNED NOT NULL,
            date DATE NOT NULL,
            start_time TIME NOT NULL,
            end_time TIME NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (patient_id) REFERENCES {$wpdb->prefix}patients(id) ON DELETE CASCADE,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->prefix}doctors(id) ON DELETE CASCADE
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
