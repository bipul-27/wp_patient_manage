<?php

namespace FluentPlugin\Database\Migrations;

class MedicineMigrator
{
    static $tableName = 'medicine';

    public static function migrate()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();
        $table = $wpdb->prefix . static::$tableName;

        $sql = "CREATE TABLE $table(
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            doctor_id INT UNSIGNED NOT NULL,
            name VARCHAR(255) NOT NULL UNIQUE,
            generic_name VARCHAR(255) NOT NULL,
            brand_name VARCHAR(255) NULL,
            dosage_form VARCHAR(255) NOT NULL,
            strength VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NULL,
            updated_at TIMESTAMP NULL,
            FOREIGN KEY (doctor_id) REFERENCES {$wpdb->prefix}doctors(id) ON DELETE CASCADE
        )$charsetCollate;";

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