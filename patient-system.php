<?php defined('ABSPATH') or die;
/*
Plugin Name: Patient management
Description: A plugin that integrates Laravel for managing a patient management system.
Version: 1.0
Author: Bipul Karmokar34
*/

require __DIR__ . '/vendor/autoload.php';

call_user_func(function ($bootstrap) {
    $bootstrap(__FILE__);
}, require(__DIR__ . '/boot/app.php'));

