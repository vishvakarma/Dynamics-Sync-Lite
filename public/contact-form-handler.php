<?php
// Shortcode to show contact form
function dsl_contact_form_shortcode() {
//     if (!is_user_logged_in()) return '<p><a href="https://login.microsoftonline.com/common/oauth2/v2.0/authorize?client_id=YOUR_CLIENT_ID&response_type=code&redirect_uri=http%3A%2F%2Flocalhost&response_mode=query&scope=User.Read
// ">Log in</a> to view/edit contact info.</p>';

    // In your shortcode (or admin settings page)
    if (!is_user_logged_in()) {
        return '<p>Please log in before connecting to Dynamics 365.</p>';
    }
    $oauth = new DSL_OAuth_Handler();
    $auth_url = $oauth->get_authorization_url();
    echo '<a href="' . esc_url($auth_url) . '" class="button">Login with Dynamics 365</a>';
    
    /*$api = new DSL_Dynamics_API();
    $contact = $api->get_contact_by_email($user->user_email);
    echo "<pre>";print_r($contact);echo "</pre>";
    ob_start(); ?>
    <form id="dsl-contact-form" method="post">
        <?php wp_nonce_field('dsl_update_contact', 'dsl_nonce'); ?>
        <input type="hidden" name="contact_id" value="<?php echo esc_attr($contact['contactid'] ?? ''); ?>" />
        <label>First Name: <input type="text" name="firstname" value="<?php echo esc_attr($contact['firstname'] ?? ''); ?>" /></label>
        <label>Last Name: <input type="text" name="lastname" value="<?php echo esc_attr($contact['lastname'] ?? ''); ?>" /></label>
        <label>Email: <input type="email" name="emailaddress1" value="<?php echo esc_attr($contact['emailaddress1'] ?? ''); ?>" /></label>
        <button type="submit">Update</button>
        <div id="dsl-message"></div>
    </form>
    <?php
    return ob_get_clean();*/
}
add_shortcode('dynamics_contact_form', 'dsl_contact_form_shortcode');

// Enqueue JS frontend
add_action('wp_enqueue_scripts', function(){
    wp_enqueue_script('dsl-contact-js', DSL_PLUGIN_URL.'public/public-scripts.js', ['jquery']);
    wp_localize_script('dsl-contact-js', 'dslAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('dsl_ajax_nonce')
    ]);
});

// AJAX handling (PHP)
add_action('wp_ajax_dsl_update_contact', function(){
    check_ajax_referer('dsl_update_contact', 'dsl_nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message' => 'Login required']);
    $id = sanitize_text_field($_POST['contact_id']);
    $data = [
        'firstname' => sanitize_text_field($_POST['firstname']),
        'lastname'  => sanitize_text_field($_POST['lastname']),
        'emailaddress1' => sanitize_email($_POST['emailaddress1']),
    ];
    $api = new DSL_Dynamics_API();
    $result = $api->update_contact($id, $data);
    if ($result) wp_send_json_success(['message' => 'Contact updated!']);
    else wp_send_json_error(['message' => 'Update failed.']);
});
?>
