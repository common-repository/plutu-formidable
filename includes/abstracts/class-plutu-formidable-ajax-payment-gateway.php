<?php
/**
 * AJAX payment abstract gateway
 * Handles Ajax requests for payment gateway functionality
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Abstracts
 */

defined( 'ABSPATH' )or exit;

class Plutu_Formidable_Ajax_Payment_Gateway{

    use Plutu_Formidable_Api_Error_Codes, Plutu_Formidable_Helper;

    /**
     * Payment method key
     * 
     * @var string
     */
    protected $key;

    /**
     * Payment method name
     * 
     * @var string
     */
    protected $payment;

    /**
     * Payment method nonce
     * 
     * @var string
     */
    protected $nonce;

    /**
     * Create new instance
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        if( !empty( $this->key ) ) {
            add_action( 'wp_ajax_send_' . $this->key . '_otp_request', [$this, 'send_otp_request'] );
            add_action( 'wp_ajax_nopriv_send_' . $this->key . '_otp_request', [$this, 'send_otp_request'] );
        }
    }

    /**
     * Send otp request
     * 
     * @access public
     * @return void print json
     */
    protected function verify_processing( $parameters ) {
        
        // Verifies that a correct security nonce was used
        if ( !wp_verify_nonce( $parameters['nonce'], $this->nonce ) ) {
            wp_send_json_error( ['error' => ['message' => __( 'Token mismatch, please refresh the page', 'plutu-formidable' )]] );
        }

        // If it is an empty amount
        if( $parameters['amount'] === '' ){
            wp_send_json_error( ['error' => ['message' => __( 'The amount required', 'plutu-formidable' )]] );
        }

        // Check if the amount is a number without any characters
        if( !is_numeric( $parameters['amount'] ) ){
            wp_send_json_error( ['error' => ['message' => __( 'Amount must be integer', 'plutu-formidable' )]] );
        }

        // Check if the amount is valid
        try {
            $amount_passed = $this->valid_value( $parameters['form_id'], $parameters['amount'] );
            if( !$amount_passed ) {
                wp_send_json_error( ['error' => ['message' => __('Please check amount value', 'plutu-formidable') ]] );
            }
        } catch ( Exception $e ) {
            wp_send_json_error( ['error' => ['message' => $e->getMessage()]] );

        }

        // Plutu field
        $field = $this->get_plutu_field( $parameters['form_id'] );
        // Amount per transaction
        $max_amount = $this->sanitize_text_field_or_null( 'max', $field->field_options['amounts'][$this->key] );
        $min_amount = $this->sanitize_text_field_or_null( 'min', $field->field_options['amounts'][$this->key] );

        try {
            // Check the cart total allowed for the payment method amount
            $this->is_valid_amount( $parameters['amount'], $max_amount, $min_amount );
        } catch ( Exception $e ) {
            $error_message = apply_filters( 'plutu_formidable_error_alert_message', $e->getMessage() );
            wp_send_json_error( ['error' => ['message' => $error_message]] );

        }

        // Set amount in sessions
        $this->set_session_value( 'plutu_formidable_amount', $parameters['amount'] );
        // Remove nonce
        $this->remove_field_data_key( 'nonce', $parameters );

        // Send API request
        $api = new Plutu_Formidable_Api_Request;
        $api->set_payment_method( $this->payment );
        $api_response = $api->verify( $parameters );
        // Successful request
        if( $api->get_status_code() == 200 ) {
            
            if( isset( $api_response->result->process_id ) ) {
                $process_id = sanitize_text_field( $api_response->result->process_id );
                // successful response
                wp_send_json_success( ['message' => __( 'An OTP has been sent to your mobile number', 'plutu-formidable' ), 'process_id' => $process_id] );

            }else{
                $error_message = __( 'Process ID is not included, please try again, contact us if the problem occurs again.', 'plutu-formidable' );
                wp_send_json_error( ['error' => ['message' => $error_message]] );
            }

        }
        // Handle errors
        $error_code = isset($api_response->error->code)? $api_response->error->code : '';
        $error_message = $this->get_api_error_code( $error_code );
        wp_send_json_error( ['error' => ['message' => $error_message]] );

    }

}