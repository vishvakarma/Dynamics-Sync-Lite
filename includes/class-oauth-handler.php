<?php
class DSL_OAuth_Handler {
    private $settings;
    public function __construct() {
        $this->settings = get_option('dsl_options');
    }
    public function get_authorization_url() {
        $params = [
            'client_id' => $this->settings['client_id'],
            'response_type' => 'code',
            'redirect_uri' => admin_url('admin-ajax.php?action=dsl_oauth_callback'),
            'response_mode' => 'query',
            'scope' => 'https://contactpoint360.com/test',
            'state' => wp_create_nonce('dsl_oauth_state'),
        ];
        return "https://login.microsoftonline.com/{$this->settings['tenant_id']}/oauth2/v2.0/authorize?" . http_build_query($params);
    }
    public function exchange_code_for_token($code) {
        $token_url = "https://login.microsoftonline.com/{$this->settings['tenant_id']}/oauth2/v2.0/token";
        $body = [
            'client_id' => $this->settings['client_id'],
            'client_secret' => $this->settings['client_secret'],
            'code' => $code,
            'redirect_uri' => admin_url('admin-ajax.php?action=dsl_oauth_callback'),
            'grant_type' => 'authorization_code',
            'scope' => 'https://crm.dynamics.com/data',
        ];
        $response = wp_remote_post($token_url, [ 'body' => $body ]);
        $result = json_decode(wp_remote_retrieve_body($response), true);
        if (!empty($result['access_token'])) {
            update_option('dsl_access_token', $result['access_token']);
            update_option('dsl_refresh_token', $result['refresh_token']);
            update_option('dsl_token_expires', time() + $result['expires_in']);
            return $result['access_token'];
        }
        return false;
    }
    public function get_valid_access_token() {
        $token = get_option('dsl_access_token');
        $expires = get_option('dsl_token_expires', 0);
        if (empty($token) || time() >= $expires) {
            return false; // Prompt for re-authentication if expired/not present
        }
        return $token;
    }
}
?>