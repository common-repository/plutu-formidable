<?php
/**
 * Settings Controller
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Controllers
 */

defined( 'ABSPATH' )or exit;

class Plutu_Formidable_Settings_Controller{

    use Plutu_Formidable_Api_Error_Codes, Plutu_Formidable_Helper;

    /**
     * Create new instance
     * 
     * @access public
     * @return void
     */
    public function __construct() {

        add_action( 'init', [$this, 'start_sessions'] );
        add_action( 'frm_add_settings_section', [$this, 'settings'], 20 );
        add_filter( 'frm_get_field_type_class', [$this, 'include_plutu_field_class'], 10, 2 );
        add_filter( 'frm_available_fields', [$this, 'include_plutu_field'], 100 );
        add_filter( 'frm_entries_before_create', [$this, 'confirm_payment'], 100, 2 );
        add_action( 'admin_init', [$this, 'redirect_transaction_to_entry'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
        add_action( 'admin_footer', [$this, 'enqueue_icon'] );

    }

    /**
     * Start sessions
     * 
     * @access public
     * @return void
     */
    public function start_sessions() {
        if ( ! session_id() ) {
            session_start();
        }
    }

    /**
     * Enqueue icon
     * 
     * @access public
     * @return void
     */
    public function enqueue_icon() {
        if( $this->is_text_get_field_exists_and_equlas( 'page', 'formidable-settings' ) || 
            $this->is_text_get_field_exists_and_equlas( 'page', 'formidable' ) ) {
            echo esc_html( $this->load_image_content('icon.svg') );
        }
    }

    /**
     * Enqueue scripts
     * 
     * @access public
     * @return void
     */
    public function enqueue_scripts() {
        $payment = apply_filters( 'plutu_formidable_payment_js_url', $this->get_js_file_url('payment.js') );
        $sadad_js_url = apply_filters( 'plutu_formidable_sadad_js_url', $this->get_js_file_url('sadad.payment.js') );
        $edfali_js_url = apply_filters( 'plutu_formidable_edfali_js_url', $this->get_js_file_url('edfali.payment.js') );
        wp_register_script( 'plutu-formidable-payment-scripts', $payment, ['jquery'] );
        wp_register_script( 'plutu-formidable-sadad-scripts', $sadad_js_url, ['jquery'] );
        wp_register_script( 'plutu-formidable-edfali-scripts', $edfali_js_url, ['jquery'] );
    }

    /**
     * Settings
     * 
     * @access public
     * @param  array $sections
     * @return array
     */
    public function settings( $sections ) {
        return array_merge( $sections, [
            'plutu_formidable' => [
                'class' => __CLASS__,
                'function' => 'configurations',
                'icon' => 'frm_icon_font frm_plutu_icon',
                'name' => __( 'Plutu', 'plutu-formidable' ),
            ]
        ] );
    }

    /**
     * Configurations
     * 
     * @access public
     * @return void print
     */
    public static function configurations() {

        $action = isset( $_REQUEST['frm_action'] ) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param( $action );
        $frm_settings = FrmAppHelper::get_settings();

        if ($action == 'process-form') {

            $api_key = isset( $_POST['options']['api_key'] )? sanitize_text_field( $_POST['options']['api_key'] ) : '';
            $access_token = isset( $_POST['options']['access_token'] )? sanitize_text_field( $_POST['options']['access_token'] ) : '';
            $payment_methods = isset( $_POST['options']['payment_methods'] )? array_map( 'sanitize_text_field', $_POST['options']['payment_methods'] ) : [];

            // Update options
            update_option( 'plutu_formidable_options', [
                'api_key' => (string) $api_key,
                'access_token' => (string) $access_token,
                'payment_methods' => (array) $payment_methods,
            ] );

        }

        // Get options
        $options = get_option( 'plutu_formidable_options', ['api_key' => '', 'access_token' => '', 'payment_methods' => []] );
        $payment_methods = isset( $options['payment_methods'] )? $options['payment_methods'] : [];

        include PLUTU_FORMIDABLE_PLUGIN_RESOURCES . '/settings/fields.php';

    }

    /**
     * Include Plutu field class
     * 
     * @access public
     * @param  string $class
     * @param  string $field_type
     * @return string
     */
    public function include_plutu_field_class( $class, $field_type ) {
        if( $field_type == 'plutu' ) {
            return 'Plutu_Formidable_Field';
        }
        return $class;
    }

    /**
     * Include Plutu field to available fields
     * 
     * @access public
     * @param  array $fields
     * @return array
     */
    public function include_plutu_field($fields){
        $fields['plutu'] = array(
            'name' => __( 'Plutu', 'plutu-formidable' ),
            'icon' => 'frm_icon_font frm_plutu_icon',
        );  
        return $fields;
    }

    /**
     * Confirm payment transaction
     * 
     * @access public
     * @param  array $errors
     * @param  stdClass $form
     * @return array
     */
    public function confirm_payment( $errors, $form ) {

        if ( !empty( $errors ) ) {
            return $errors;
        }

        // Is the Plutu field in the form?
        $field = $this->get_plutu_field( $form->id );
        if ( empty( $field ) ) {
            return $errors;
        }

        // Declare parameters
        $otp = '';
        $process_id = '';
        $transaction_id = '';
        $payment = '';
        $field_id = intval( $field->id );
        $amount = $this->sanitize_text_field_or_null( 'plutu_formidable_amount', $_SESSION );

        try {
            $amount_passed = $this->valid_value( $form->id, $amount );
            if( !$amount_passed ) {
                $errors['plutu-total'] = __('Please check amount value', 'plutu-formidable');
            }
        } catch ( Exception $e ) {
            $errors['plutu-total'] = $e->getMessage();
        }

        // Payment inputs
        $values = isset( $_POST['item_meta'][$field_id] )? (array) $_POST['item_meta'][$field_id] : [];

        if( !empty( $values ) ) {

            $payment_method = $this->sanitize_text_field_or_null( 'payment_method', $values );

            switch ($payment_method) {

                case 'plutu-sadad':
                    $payment = 'sadadapi';
                    $otp = $this->sanitize_text_field_or_null( 'sadad_otp', $values );
                    $process_id = $this->sanitize_text_post_field_or_null( 'sadad_process_id' );
                break;

                case 'plutu-edfali':
                    $payment = 'edfali';
                    $otp = $this->sanitize_text_field_or_null( 'edfali_otp', $values );
                    $process_id = $this->sanitize_text_post_field_or_null( 'edfali_process_id' );
                break;
                
                default:
                    $errors['plutu-payment_method'] = __( 'Payment method is invalid', 'plutu-formidable' );
                break;
            }

        }else{
            $errors['plutu-total'] = __( 'Invalid total payment', 'plutu-formidable' );
        }

        if ( empty($errors) ) {

            if( !empty( $otp ) && !empty( $process_id ) && !empty( $amount ) ) {

                // Send API request
                $parameters = [];
                $parameters['amount'] = $amount;
                $parameters['invoice_no'] = $process_id;
                $parameters['customer_ip'] = $this->get_ip_address();
                $parameters['process_id'] = $process_id;
                $parameters['code'] = $otp;

                $api = new Plutu_Formidable_Api_Request;
                $api->set_payment_method( $payment );
                $api_response = $api->process( $parameters );

                // Successful request
                if( $api->get_status_code() == 200 ) {

                    // Trnascation ID
                    $transaction_id = isset($api_response->result->transaction_id)? sanitize_text_field( $api_response->result->transaction_id ) : 0;
                    // Trnascation amount
                    $transaction_amount = isset($api_response->result->amount)? sanitize_text_field( $api_response->result->amount ) : 0;

                    if( $transaction_amount != $amount ) {
                        $errors['field' . $field_id . '-payment_method'] = __('The transaction has been completed but the transaction amount is not equal to the total, please report us', 'plutu-formidable');
                    }

                }else{

                    $error_code = isset( $api_response->error->code )? $api_response->error->code : '';
                    $error_message = sanitize_text_field( $this->get_api_error_code( $error_code ) );
                    $errors['field' . $field_id . '-payment_method'] = $error_message;

                }
            }
        }

        // If there are no transaction errors, then inject the transaction ID into the values
        if( empty( $errors ) ) {
            $_POST['item_meta'][$field_id]['transaction_id'] = $transaction_id;
            $_POST['item_meta'][$field_id]['amount'] = $amount;
            
            // After the payment is completed, remove the amount from the sessions
            $this->remove_session_key( 'plutu_formidable_amount' );

        }

        return $errors;
    }

    /**
     * Redirection to the entry record by transaction payment ID
     * 
     * @access public
     * @return void
     */
    public function redirect_transaction_to_entry() {
        global $pagenow;
        if( $pagenow == 'admin.php' ) {
            if( $this->is_text_get_field_exists_and_equlas( 'page', 'formidable-entries' ) && 
                $this->is_text_get_field_exists_and_equlas( 'frm_action', 'show' ) ) {
                if( $this->is_text_get_field_exists( 'payment_id' ) ){
                    $transaction_id = $this->sanitize_text_get_field_or_null( 'payment_id' );
                    $url = $this->get_entry_url_by_transaction_id( $transaction_id );
                    // Redirect to entry URL
                    if ( wp_redirect( $url ) ) {
                        exit;
                    }
                }
            }
        }
    }

}