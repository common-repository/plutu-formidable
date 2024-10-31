<?php
/**
 * Payment formidable field
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Models
 */

defined( 'ABSPATH' )or exit;

class Plutu_Formidable_Field extends FrmFieldType {

    use Plutu_Formidable_Helper;

    /**
     * Field name
     * 
     * @var string
     */
    protected $type = 'plutu';

    /**
     * Field settings
     * 
     * @access protected
     * @return array
     */
    protected function field_settings_for_type() {
        $settings = array(
            'clear_on_focus' => false,
            'description' => false,
            'visibility' => true,
        );
        return $settings;
    }

    /**
     * Extra fields
     * 
     * @access public
     * @return array
     */
    protected function extra_field_opts() {
        return array(
            'payment_methods' => array(), 
            'total_field' => '', 
            'hidden_field' => '',
            'hide_icons' => '',
            'use_custom_value' => '',
            'value' => '',
            'amounts' => array(
                'sadad' => array(
                    'max' => 1000,
                    'min' => 1,
                ),
                'edfali' => array(
                    'max' => 1000,
                    'min' => 1,
                ),
            ),
        );
    }

    /**
     * Show options
     * 
     * @access public
     * @param array $args - Includes 'field', 'display', and 'values'.
     * @return void
     */
    public function show_primary_options( $args ) {

        $field = $args['field'];
        $options = get_option( 'plutu_formidable_options', [] );
        $payment_methods = [];
        if( isset( $options['payment_methods'] ) && !empty( $options['payment_methods'] ) ) {
            $payment_methods = $options['payment_methods'];
        }
        // Load template
        $this->load_template( 'admin-field/backend-field.php', [ 
            'field' => $field, 
            'options' => $options, 
            'payment_methods' => $payment_methods
        ] );

        parent::show_primary_options( $args );

    }

    /**
     * Prepare the value in the table of entries
     * 
     * @access public
     * @param  array $value
     * @param  array $atts
     * @return string
     */
    protected function prepare_display_value( $value, $atts ) {

        if ( ! is_array( $value ) ) {
            return $value;
        }

        $new_value = '';
        if ( isset( $value['payment_method'] ) ) {
            switch ( $value['payment_method'] ) {
                case 'plutu-sadad':
                    $new_value = __( 'Sadad Payment', 'plutu-formidable' );
                    break;
                case 'plutu-edfali':
                    $new_value = __( 'Adfali Payment', 'plutu-formidable' );
                    break;
            }
        }

        if ( isset( $value['transaction_id'] ) ) {
            $new_value .= ' | ' . sprintf( 
                __( 'Transaction ID: %s', 'plutu-formidable' ), 
                $this->sanitize_text_field_or_null( 'transaction_id', $value ) 
            );

        }

        if ( isset( $value['amount'] ) ) {
            $new_value .= ' | ' . sprintf( 
                __( 'Amount: %s LYD', 'plutu-formidable' ), 
                $this->sanitize_text_field_or_null( 'amount', $value ) 
            );
        }

        return $new_value;

    }

    /**
     * Field html
     * 
     * @access public
     * @param  array $args
     * @param  array $shortcode_atts
     * @return string
     */
    public function front_field_input( $args, $shortcode_atts ) {

        $errors = $args['errors'];
        $field_id = intval( $args['field_id'] );
        $field_name = $args['field_name'];
        $form_id = intval( $args['form']->id );
        $total_field_id = 0;

        // @ Ceate new entry
        if( $args['form_action'] == 'create' ) {

            $display = false;
            // Is the Plutu field in the form?
            $field = $this->get_plutu_field( $form_id );
            $use_custom_value = (bool) $this->get_use_custom_value_field( $form_id );
            if( $use_custom_value ){
               $display = true; 
            }else{
                // The form must contain at least one product field
                $total_field = $this->get_totals_field( $form_id );
                if ( !is_null( $total_field ) ) {
                    $total_field_id = $total_field->id;
                    $display = true; 
                }else{
                    return $this->load_error_message( 
                        __( 'The Total field associated with the Plutu field is missing', 'plutu-formidable'), false
                    );
                }
            }

            if ( $display ) {

                $options = get_option('plutu_formidable_options', []);
                $payment_methods = [];
                $first_payment_method = '';
                $selected_payment = '';

                // Get the payment method based on the form settings and global settings
                if( isset( $options['payment_methods'] ) && !empty( $options['payment_methods'] ) ) {
                    $form_payments = $field->field_options['payment_methods'];
                    $payment_methods = $options['payment_methods'];
                    $payment_methods = array_intersect( $form_payments, $payment_methods );
                    $first_payment_method = current( $payment_methods );
                    // Set first payment method
                    if( !empty( $first_payment_method ) ) {
                        $first_payment_method = 'plutu-' . $first_payment_method;
                        $selected_payment = $first_payment_method;
                    }
                }

                // There are payment methods?
                if( !empty( $payment_methods ) ) {

                    // Selected payment
                    if( isset( $_POST['item_meta'][$field_id]['payment_method'] ) ){
                        $selected_payment = $this->sanitize_text_field_or_null( 'payment_method', $_POST['item_meta'][$field_id] );
                        if( !empty($selected_payment) ){
                            $first_payment_method = $selected_payment;
                        }
                    }

                    // Declare parameters
                    $value_field = $this->get_value_field( $form_id );
                    $error_field = 'field' . $field_id . '-payment_method';
                    $error = isset( $errors[$error_field] )? $errors[$error_field] : '';
                    $edfali_verified = $this->sanitize_text_post_field_or_null( 'edfali_verified' ) == 1? 1 : '';
                    $edfali_mobile_number = $this->sanitize_text_post_field_or_null( 'edfali_mobile_number' );
                    $edfali_process_id = $this->sanitize_text_post_field_or_null( 'edfali_process_id' );
                    $edfali_otp = '';
                    $sadad_verified = $this->sanitize_text_post_field_or_null( 'sadad_verified' ) == 1? 1 : '';
                    $sadad_mobile_number = $this->sanitize_text_post_field_or_null( 'sadad_mobile_number' );
                    $sadad_birth_year = $this->sanitize_text_post_field_or_null( 'sadad_birth_year' );
                    $sadad_process_id = $this->sanitize_text_post_field_or_null( 'sadad_process_id' );
                    $hide_icons = $this->get_field_option_bool( $field, 'hide_icons' );
                    $sadad_otp = '';
                    $is_hidden_section = $this->get_field_option_bool( $field, 'hidden_field' );
                    if($total_field_id == 0){
                        $is_hidden_section = false;
                    }

                    if( isset( $_POST['item_meta'][$field_id] ) ) {
                        $edfali_otp = $this->sanitize_text_field_or_null( 'edfali_otp', $_POST['item_meta'][$field_id] );
                        $sadad_otp = $this->sanitize_text_field_or_null( 'sadad_otp', $_POST['item_meta'][$field_id] );
                    }

                    // Load template
                    return $this->load_template( 'payment/payment.php', [ 
                        'form_id' => $form_id, 
                        'field_id' => $field_id, 
                        'field_name' => $field_name,
                        'payment_methods' => $payment_methods,
                        'first_payment_method' => $first_payment_method,
                        'selected_payment' => $selected_payment,
                        'error_field' => $error_field,
                        'error' => $error,
                        'errors' => $errors,
                        'total_field_id' => isset($total_field_id)? $total_field_id : 0,
                        'edfali_verified' => $edfali_verified,
                        'edfali_mobile_number' => $edfali_mobile_number,
                        'edfali_process_id' => $edfali_process_id,
                        'edfali_otp' => $edfali_otp,
                        'sadad_verified' => $sadad_verified,
                        'sadad_mobile_number' => $sadad_mobile_number,
                        'sadad_birth_year' => $sadad_birth_year,
                        'sadad_process_id' => $sadad_process_id,
                        'sadad_otp' => $sadad_otp,
                        'is_hidden_section' => $is_hidden_section,
                        'hide_icons' => $hide_icons,
                        'use_custom_value' => $use_custom_value,
                        'value_field' => $value_field,
                    ], false );

                }else{

                    return $this->load_error_message( 
                        __( 'There are no available payment methods associated with the form', 'plutu-formidable'), false
                    );

                }
            }
        }

        // @ View entry
        if( $args['form_action'] == 'update' && is_admin() ) {
            $item_id = $this->sanitize_text_get_field_or_null( 'id' );
            $value = $this->get_value_from_item_by_field_id( $item_id, $field_id );
            return $this->show_on_entry_edit( $field_id, $value );
        }

        return '';
    }

    /**
     * Validate
     * 
     * @access public
     * @param  array $args
     * @return array
     */
    public function validate( $args ) {

        $id = $args['id'];
        $this->field->temp_id = $id;
        $values = $args['value'];
        $field_key = 'field' . $id . '-payment_method';

        // Payment method exists?
        $error = '';
        $errors = [];

        if( !$this->is_text_field_exists('payment_method', $values) ){
            $values['payment_method'] = $this->get_default_payment_method();
        }

        switch ( $values['payment_method'] ) {

            case 'plutu-sadad':

                $sadad_fields = true;

                // Amount
                $form_id = $this->sanitize_text_post_field_or_null( 'form_id' );
                if( !is_null( $form_id ) ) {
                    $use_custom_value = (bool) $this->get_use_custom_value_field( $form_id );
                    if( $use_custom_value ) {
                        if( !$this->is_text_post_field_exists( 'sadad_value' ) ) {
                            $errors['sadad_value'] = __( 'The amount required', 'plutu-formidable' );
                            $sadad_fields = false;
                        }
                    }
                }

                // Mobile number
                if( !$this->is_text_post_field_exists( 'sadad_mobile_number' ) ) {
                    $errors['sadad_mobile_number'] = __('The mobile phone number subscribed to the Sadad service is required', 'plutu-formidable');
                    $sadad_fields = false;
                }

                // Birth year
                if( !$this->is_text_post_field_exists( 'sadad_birth_year' ) ) {
                    $errors['sadad_birth_year'] = __('Birth year field is required', 'plutu-formidable');
                    $sadad_fields = false;
                }

                // All fields have been verified, check if the payment verification step has been completed
                if( $sadad_fields ) {

                    if( !$this->is_text_post_field_exists( 'sadad_verified' ) ) {
                        $errors['sadad_verified'] = __('Payment must be made via Sadad before proceeding', 'plutu-formidable');
                    } else {
                        if( !$this->is_text_post_field_exists( 'sadad_process_id' ) ) {
                            $errors['sadad_process_id'] = __('Payment must be made via Sadad before proceeding', 'plutu-formidable');
                        }
                    }
                }
                // OTP
                if( !$this->is_text_field_exists( 'sadad_otp', $values ) ) {
                    $error = __('OTP code received from Sadad is required', 'plutu-formidable');
                }else{
                    if ( !is_numeric( $this->sanitize_text_field_or_null( 'sadad_otp', $values ) ) ) {
                        $error = __('OTP must be numeric', 'plutu-formidable');
                    }
                }

                break;
            
            case 'plutu-edfali':

                $edfali_fields = true;

                // Amount
                $form_id = $this->sanitize_text_post_field_or_null( 'form_id' );
                if( !is_null( $form_id ) ) {
                    $use_custom_value = (bool) $this->get_use_custom_value_field( $form_id );
                    if( $use_custom_value ) {
                        if( !$this->is_text_post_field_exists( 'edfali_value' ) ) {
                            $errors['edfali_value'] = __( 'The amount required', 'plutu-formidable' );
                            $sadad_fields = false;
                        }
                    }
                }

                // Mobile number
                if( !$this->is_text_post_field_exists( 'edfali_mobile_number' ) ) {
                    $errors['edfali_mobile_number'] = __('The mobile phone number subscribed to the Adfali service is required', 'plutu-formidable');
                    $edfali_fields = false;
                }

                // All fields have been verified, check if the payment verification step has been completed
                if( $edfali_fields ) {

                    if( !$this->is_text_post_field_exists( 'edfali_verified' ) ) {
                        $errors['edfali_verified'] = __('Payment must be made via Adfali before proceeding', 'plutu-formidable');
                    } else {
                        if( !$this->is_text_post_field_exists( 'edfali_process_id' ) ) {
                            $errors['edfali_process_id'] = __('Payment must be made via Adfali before proceeding', 'plutu-formidable');
                        }
                    }
                }
                // OTP
                if( !$this->is_text_field_exists( 'edfali_otp', $values ) ) {
                    $error = __( 'OTP field is required', 'plutu-formidable' );
                }else{
                    if ( !is_numeric( $this->sanitize_text_field_or_null( 'edfali_otp', $values ) ) ) {
                        $error = __('OTP must be numeric', 'plutu-formidable');
                    }
                }

                break;

            default:

                    $error = __('No payment method selected. Please select one', 'plutu-formidable');

                break;

        }

        // Error exists?
        if( empty( $errors ) ) {
            if( !empty( $error ) ) {
                $errors[$field_key] = $error;
            }
        }
        return $errors;
    }

    /**
     * Show field in builder
     * 
     * @access public
     * @param  string $name
     * @return void
     */
    public function show_on_form_builder( $name = '' ) {
        ?>
            <div class="frm_html_field_placeholder">
                <?php esc_html_e('There is no preview of the Plutu field, please enable Payment Methods from Global Settings > Plutu. Then enable them in the settings of this field', 'plutu-formidable'); ?>
            </div>
        <?php
    }

    /**
     * Show field in entry edit
     * 
     * @access public
     * @param  string $field_id
     * @param  string $value
     * @return string
     */
    public function show_on_entry_edit( $field_id, $value = '' ) {
        ob_start();
        ?>
            <div id="frm_field_<?php echo esc_attr( $field_id ); ?>_container" class="frm_form_field form-field frm_top_container">
                <?php if( $value ) { 
                    $value = maybe_unserialize( $value );
                    echo esc_html( $this->prepare_display_value( $value, [] ) );
                } else {
                    echo esc_html_e( 'There are no payment details for this entry', 'plutu-formidable' );
                }
                ?>
            </div>
        <?php
        return ob_get_clean();
    }

}