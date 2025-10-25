<?php

// Creating a nonce for form submission
function dsl_display_contact_form() {
    wp_nonce_field('dsl_update_contact_action', 'dsl_contact_nonce');
    // Form HTML here
}

// Verifying nonce on form submission
function dsl_handle_contact_update() {
    if (!isset($_POST['dsl_contact_nonce']) || 
        !wp_verify_nonce($_POST['dsl_contact_nonce'], 'dsl_update_contact_action')) {
        wp_die('Security check failed');
    }
    
    // Process the form
}

function dsl_ajax_update_contact() {
    check_ajax_referer('dsl_ajax_nonce', 'security');
    
    // Process AJAX request
}
add_action('wp_ajax_update_contact', 'dsl_ajax_update_contact');

function dsl_sanitize_contact_data($data) {
    return [
        'firstname' => sanitize_text_field($data['firstname'] ?? ''),
        'lastname' => sanitize_text_field($data['lastname'] ?? ''),
        'email' => sanitize_email($data['email'] ?? ''),
        'phone' => sanitize_text_field($data['phone'] ?? ''),
        'address' => sanitize_textarea_field($data['address'] ?? '')
    ];
}

function dsl_check_https() {
    if (!is_ssl() && !is_admin()) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p>Dynamics Sync Lite requires HTTPS for secure communication.</p></div>';
        });
    }
}
add_action('admin_init', 'dsl_check_https');
