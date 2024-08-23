<?php

namespace FluentPlugin\Database\Migrations;

class PrescriptionDiagnosesMigrator
{
    static $tableName = 'prescription_diagnoses';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . static::$tableName;

        $sql = "CREATE TABLE $table (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            prescription_id INT UNSIGNED NOT NULL,
            diagnosis TEXT NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
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
