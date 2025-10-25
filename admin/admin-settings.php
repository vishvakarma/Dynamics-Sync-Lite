<?php
// Add settings page to WP admin
add_action('admin_menu', function(){
    add_options_page('Dynamics Sync Settings', 'Dynamics 365', 'manage_options', 'dynamics-sync-lite', function(){
        ?>
        <div class="wrap"><h1>Dynamics Sync Lite Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('dsl_settings_group');
                      do_settings_sections('dynamics-sync-lite');
                      submit_button(); ?>
            </form>
        </div>
        <?php
    });
});
// Register settings
add_action('admin_init', function(){
    register_setting('dsl_settings_group', 'dsl_options');
    add_settings_section('dsl_main_section', 'OAuth Settings', null, 'dynamics-sync-lite');
    // Fields for Client ID, Secret, Tenant ID, URL
    add_settings_field('dsl_client_id', 'Client ID', function(){
        $opts = get_option('dsl_options');
        echo '<input type="text" name="dsl_options[client_id]" value="'.esc_attr($opts['client_id'] ?? '').'">';
    }, 'dynamics-sync-lite', 'dsl_main_section');
    add_settings_field('dsl_client_secret', 'Client Secret', function(){
        $opts = get_option('dsl_options');
        echo '<input type="password" name="dsl_options[client_secret]" value="'.esc_attr($opts['client_secret'] ?? '').'">';
    }, 'dynamics-sync-lite', 'dsl_main_section');
    add_settings_field('dsl_tenant_id', 'Tenant ID', function(){
        $opts = get_option('dsl_options');
        echo '<input type="text" name="dsl_options[tenant_id]" value="'.esc_attr($opts['tenant_id'] ?? '').'">';
    }, 'dynamics-sync-lite', 'dsl_main_section');
    add_settings_field('dsl_dynamics_url', 'Dynamics 365 URL', function(){
        $opts = get_option('dsl_options');
        echo '<input type="text" name="dsl_options[dynamics_url]" value="'.esc_attr($opts['dynamics_url'] ?? '').'">';
    }, 'dynamics-sync-lite', 'dsl_main_section');
});
?>
