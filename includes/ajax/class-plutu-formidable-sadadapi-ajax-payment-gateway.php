<?php
/**
 * Sadad API ajax
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Ajax
 */

defined( 'ABSPATH' )or exit;

class Plutu_Formidable_Sadadapi_Ajax_Payment_Gateway extends Plutu_Formidable_Ajax_Payment_Gateway {

    /**
     * Payment method key
     * 
     * @var string
     */
    protected $key = 'sadad';

    /**
     * Payment method name
     * 
     * @var string
     */
    protected $payment = 'sadadapi';

    /**
     * Payment method nonce
     * 
     * @var string
     */
    protected $nonce = 'sadadapi_nonce';

    /**
     * Send otp request
     * 
     * @access public
     * @return void print json
     */
    public function send_otp_request() {

        $form_id = $this->sanitize_text_post_field_or_null( 'form_id' );
        $amount = $this->sanitize_text_post_field_or_null( 'amount' );
        $mobile_number = $this->sanitize_text_post_field_or_null( 'mobile_number' );
        $birth_year = $this->sanitize_text_post_field_or_null( 'birth_year' );
        $nonce = $this->sanitize_text_post_field_or_null( 'nonce' );

        // Verify that all fields are present
        if( empty( $mobile_number ) || empty( $birth_year ) || empty( $amount ) ){
            wp_send_json_error( ['error' => ['message' => __( 'All fields are required', 'plutu-formidable' )]] );
        }
        // Check valid mobile number format
        $mobile_number = $this->is_valid_mobile_number( $mobile_number, false );
        if( is_null($mobile_number) ) {
            wp_send_json_error( ['error' => ['message' => __( 'Invalid phone number format, must start with 091 or 91', 'plutu-formidable' )]] );
        }
        // Check if the year of birth is a number and four digits
        if( empty( $birth_year ) || !is_numeric( $birth_year ) || strlen( $birth_year ) != 4 || $birth_year > date('Y') ) {
            wp_send_json_error( ['error' => ['message' => __( 'Please enter a valid birth year', 'plutu-formidable' )]] );
        }

        return $this->verify_processing( [
            'form_id' => $form_id, 
            'amount' => $amount, 
            'mobile_number' => $mobile_number, 
            'birth_year' => $birth_year, 
            'nonce' => $nonce
        ] );
    }

}