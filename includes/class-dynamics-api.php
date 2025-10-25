<?php
class DSL_Dynamics_API {
    private $settings, $oauth;
    public function __construct() {
        $this->settings = get_option('dsl_options');
        $this->oauth = new DSL_OAuth_Handler();
    }
    private function request($endpoint, $method='GET', $data=null) {
        $token = $this->oauth->get_valid_access_token();
        $url = rtrim($this->settings['dynamics_url'], '/') . '/api/data/v9.2/' . ltrim($endpoint, '/');
        $args = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'OData-MaxVersion' => '4.0',
                'OData-Version' => '4.0',
            ],
            'method' => $method,
        ];
        if ($data) $args['body'] = json_encode($data);
        $response = wp_remote_request($url, $args);
        return json_decode(wp_remote_retrieve_body($response), true);
    }
    public function get_contact_by_email($email) {
        $endpoint = "contacts?\$filter=emailaddress1 eq '{$email}'";
        $result = $this->request($endpoint);
        return isset($result['value'][0]) ? $result['value'][0] : false;
    }
    public function update_contact($id, $data) {
        $endpoint = "contacts($id)";
        return $this->request($endpoint, 'PATCH', $data);
    }
}
?>
