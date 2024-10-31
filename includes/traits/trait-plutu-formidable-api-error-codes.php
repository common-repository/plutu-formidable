<?php
/**
 * API Error codes 
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Traits
 */

defined( 'ABSPATH' )or exit;

trait Plutu_Formidable_Api_Error_Codes {

    /**
     * Get error code
     * 
     * @access public
     * @param  string $code
     * @return string
     */
    public function get_api_error_code( $code ) {

        $errors = [

            'UNAUTHORIZED' => __( 'Something went wrong', 'plutu-formidable' ),
            'INVALID_GATEWAY' => __( 'Something went wrong', 'plutu-formidable' ),
            'INVALID_INPUTS' => __( 'Please make sure all payment fields are filled out correctly', 'plutu-formidable' ),
            'DENIED_ACCESS_GATEWAY' => __( 'Something went wrong', 'plutu-formidable' ),
            'FORBIDDEN_IP_ADDRESS' => __( 'Oops! IP address restrictions, Please contact the site administrator', 'plutu-formidable' ),
            'TOO_MAY_REQUESTS' => __( 'Too many requests received', 'plutu-formidable' ),
            'TEST_MODE_NOT_SUPPORTED' => __( 'Test mode is not supported for payment gateway', 'plutu-formidable' ),
            'MISSING_PARAMETER' => __( 'Oops! configuration missing, Please contact the site administrator', 'plutu-formidable' ),
            'NOT_SUBSCRIBED' => __( 'The mobile number is not subscribed to Adfali service', 'plutu-formidable' ),
            'INVALID_AMOUNT' => __( 'Invalid amount', 'plutu-formidable' ),
            'CONFIRMATION_ERROR' => __( 'Something went wrong, please check the OTP code', 'plutu-formidable' ),
            'INVALID_MOBILE_NUMBER' => __( 'Incorrect mobile number', 'plutu-formidable' ),
            'INVALID_MOBILE_NUMBER_OR_BIRTH_YEAR' => __( 'Incorrect mobile number/year of birth', 'plutu-formidable' ),
            'CHECK_BANK_ACCOUNT' => __( 'There is a problem with your account, please check with your bank', 'plutu-formidable' ),
            'ADD_PAYMENT_ERROR' => __( 'Something went wrong', 'plutu-formidable' ),
            'BACKEND_SERVER_ERROR' => __( 'There is a problem connecting to the bank server, please try again later', 'plutu-formidable' ),
            'AUTH_ERROR' => __( 'Something went wrong', 'plutu-formidable' ),
            'BACKEND_ERROR' => __( 'Backend error, Please contact the site administrator', 'plutu-formidable' ),
            'INVLIAD_OTP' => __( 'Invalid OTP. please check your code and try again', 'plutu-formidable' ),
            'INVALID_INVOICE_AMOUNT_OR_NUMBER' => __( 'Incorrect invoice ID or amount', 'plutu-formidable' ),
            'DUPLICATED_INVOICE_NUMBER' => __( 'Invoice number already exists', 'plutu-formidable' ),
            'EMPTY_MOBILE_NUMBER' => __( 'The mobile number is empty', 'plutu-formidable' ),
            'EMPTY_BIRTH_YEAR' => __( 'Birth year is empty', 'plutu-formidable' ),
            'OTP_EXPIRED' => __( 'The OTP has exceeded the time allowed for its use', 'plutu-formidable' ),
            'OTP_WAIT_BEFORE_RESNED' => __( 'Please wait a while before requesting an OTP resend again', 'plutu-formidable' ),
            'INVALID_MERCHANT_CATEGORY' => __( 'Oops! configuration missing, Please contact the site administrator', 'plutu-formidable' ),
            'UNAUTHORIZED_MERCHANT_ACCOUNT' => __( 'Oops! configuration missing, Please contact the site administrator', 'plutu-formidable' ),
            'NOT_ALLOWED_AMOUNT' => __( 'Transaction amount is not allowed', 'plutu-formidable' ),
            'INSUFFICIENT_BALANCE' => __( 'Insufficient balance for the transaction', 'plutu-formidable' ),
            'PHONE_NUMBER_IS_LOCKED' => __( 'Phone number is locked', 'plutu-formidable' ),
            
        ];

        if( array_key_exists( $code, $errors ) ) {
            return $errors[$code];
        }
        return __( 'Something went wrong', 'plutu-formidable' );
        
    }

}