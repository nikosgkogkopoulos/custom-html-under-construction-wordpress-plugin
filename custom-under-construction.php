<?php
/*
Plugin Name: Custom Under Construction
Description: Displays a custom "Under Construction" page for non-logged-in users.
Version: 1.0
Author: Nikos Gkogkopoulos
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Add settings page to the admin menu
function cuc_add_admin_menu() {
    add_options_page('Custom Under Construction Settings', 'Custom Under Construction', 'manage_options', 'custom-under-construction', 'cuc_options_page');
}
add_action('admin_menu', 'cuc_add_admin_menu');

// Register settings
function cuc_settings_init() {
    register_setting('cuc_options_group', 'cuc_custom_html');
    register_setting('cuc_options_group', 'cuc_enabled');
    add_settings_section('cuc_settings_section', '', null, 'custom-under-construction');
    add_settings_field('cuc_custom_html_field', 'Custom HTML', 'cuc_custom_html_render', 'custom-under-construction', 'cuc_settings_section');
    add_settings_field('cuc_enabled_field', 'Enable Under Construction Page', 'cuc_enabled_render', 'custom-under-construction', 'cuc_settings_section');
}
add_action('admin_init', 'cuc_settings_init');

function cuc_custom_html_render() {
    $value = get_option('cuc_custom_html', '');
    echo '<textarea style="width: 100%; height: 400px;" id="cuc_custom_html" name="cuc_custom_html">' . esc_textarea($value) . '</textarea>';
}

function cuc_enabled_render() {
    $enabled = get_option('cuc_enabled', '0');
    echo '<input type="checkbox" id="cuc_enabled" name="cuc_enabled" value="1"' . checked(1, $enabled, false) . '/>';
}

function cuc_options_page() {
    ?>
    <div class="wrap">
        <h1>Custom Under Construction Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('cuc_options_group');
            do_settings_sections('custom-under-construction');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Display custom HTML for non-logged-in users if enabled
function cuc_display_custom_html() {
    if (!is_user_logged_in() && get_option('cuc_enabled', '0') == '1') {
        $custom_html = get_option('cuc_custom_html', '');
        if (!empty($custom_html)) {
            echo $custom_html;
            exit;
        }
    }
}
add_action('template_redirect', 'cuc_display_custom_html');
?>
