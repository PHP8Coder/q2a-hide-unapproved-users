<?php
/*
    Plugin Name: Hide Unapproved Users
    Plugin URI: https://phpx.dev/
    Plugin Description: Hides unapproved users from the public user list
    Plugin Version: 1.1
    Plugin Date: 2025-04-19
    Plugin Author: Torsten Wenzel
    Plugin Author URI: https://phpx.dev/
    Plugin License: GPLv2
    Plugin Minimum Question2Answer Version: 1.8
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

// Register layer that filters user list
qa_register_plugin_layer('hide-unapproved-users-layer.php', 'Hide Unapproved Users Layer');
qa_register_plugin_module('page', 'hide-unapproved-users-block-profile.php', 'hide_unapproved_users_block_profile', 'Block Unapproved Profiles');
