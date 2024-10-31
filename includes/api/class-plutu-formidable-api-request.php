<?php
/**
 * Api request 
 * A layer to send a request to an external API and return response data
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Api
 */

defined( 'ABSPATH' )or exit;

class Plutu_Formidable_Api_Request{

    /**
     * Http status code
     * 
     * @var int
     */
    protected $http_status_code;

    /**
     * Gateway name
     * 
     * @var string
     */
    protected $gateway;

    /**
     * Set payment method
     * 
     * @access public
     * @param string $gateway
     * @param string $payment
     * @return void
     */
    public function set_payment_method( $gateway ) {
        $this->gateway = $gateway;
    }

    /**
     * Send (verify) api request
     * 
     * @access public
     * @param  array $parameters
     * @return;
     */
    public function verify( $parameters ) {
        $parameters['endpoint'] = 'transaction/' . $this->gateway . '/verify';
        return $this->execute( $parameters );
    }

    /**
     * Send (confirm) api request
     * 
     * @access public
     * @param  array $parameters
     * @return;
     */
    public function process( $parameters ) {
        $parameters['endpoint'] = 'transaction/' . $this->gateway . '/confirm';
        return $this->execute( $parameters );
    }

    /**
     * Get status code
     * 
     * @access public
     * @return int
     */
    public function get_status_code() {
        return $this->http_status_code;
    }
    
    /**
     * Execute request
     * 
     * @access protected
     * @param  array $data ['endpoint' => '', 'param1' => '', ...]
     * @return Json
     */
    protected function execute( $data ) {
        
        // Declare parameters
        $settings = get_option( 'plutu_formidable_options' );
        $api_key = $settings['api_key'];
        $access_token = $settings['access_token'];

        // API base URL
        $url = 'https://api.plutus.ly/api/v1/';
        $url = apply_filters( 'plutu_formidable_api_base_url', $url, $this->gateway );
        $url = defined( 'PLUTU_FORMIDABLE_API_BASE_URL' )? PLUTU_FORMIDABLE_API_BASE_URL : $url;

        $endpoint = '';
        if( isset( $data['endpoint'] ) ) {
            $endpoint = $data['endpoint'];
            unset( $data['endpoint'] );
        }
        $url .= $endpoint;

        $payload = [
            'method' => 'POST',
            'timeout' => 600,
            'headers' => [
                'Accept' => 'application/json',
                'X-API-KEY' => $api_key,
                'Authorization' => 'Bearer ' . $access_token
            ],
            'body' => $data,
        ];
        // Retrieve the response from the HTTP request using the POST method.
        $response = wp_remote_post( $url, $payload );
        $this->http_status_code = wp_remote_retrieve_response_code( $response );
        return json_decode( wp_remote_retrieve_body( $response ) );

    }

}