<?php

add_action( 'admin_menu', function () {
    $page_title = $menu_title = __( 'SendWP', 'sendwp' );
    $capability = 'manage_options';
    $menu_slug = 'sendwp';
    $function = function () {
        do_action('sendwp_heartbeat');
        $vars = [
            'enabled' => __('SendWP is currently enabled and sending email.', 'sendwp'),
            'disabled' => __('SendWP is currently disabled and NOT sending email.', 'sendwp'),
            'loading' => __('Loading', 'sendwp') . '...',
            'ajaxNonce' => wp_create_nonce( 'sendwp_settings_nonce' )
        ];
        include 'views/menu.html.php';
        wp_enqueue_script('sendwp-settings', plugins_url( 'assets/js/admin/settings.js', dirname(__DIR__)));
        wp_localize_script('sendwp-settings', 'sendwpAdmin', $vars);
        wp_enqueue_style('sendwp-settings', plugins_url( 'assets/css/admin/settings.css', dirname(__DIR__)));
    };
    $position = 100; // After bottom seperator.

    add_submenu_page( 'tools.php', $page_title, $menu_title, $capability, $menu_slug, $function, $position );
} );