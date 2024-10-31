<?php
/**
 * Payment gateway helper
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Traits
 */

defined( 'ABSPATH' )or exit;

trait Plutu_Formidable_Helper {

    /**
     * Set session value
     * 
     * @access public
     * @param string $key
     * @param string $value
     * @return void
     */
    public function set_session_value( $key, $value = '' ) {
        $_SESSION[$key] = sanitize_text_field( $value );
    }

    /**
     * Remove session value
     * 
     * @access public
     * @param string $key
     * @return void
     */
    public function remove_session_key( $key ) {
        if( isset( $_SESSION[$key] ) ) {
            unset( $_SESSION[$key] );
        }
    }

    /**
     * Get field option as boolean
     * 
     * @access public
     * @param  object $field
     * @param  string $key
     * @return bool
     */
    public function get_field_option_bool( $field, $key ) {
        return isset( $field->field_options[$key] )? (bool) $field->field_options[$key] : false;
    }

    /**
     * Get Plutu field based on form id
     * 
     * @access public
     * @param  int $form_id
     * @return object
     */
    public function get_plutu_field( $form_id ){
        $field = FrmField::get_all_types_in_form( $form_id, 'plutu' );
        // Check the total field associated with the Plutu field
        if ( empty( $field ) ) {
            return null;
        }
        return current( $field );
    }

    /**
     * Get totals field based on Plutu field in same form
     * 
     * @access public
     * @param  int $form_id
     * @return object
     */
    public function get_totals_field( $form_id ){
        $field = $this->get_plutu_field( $form_id );
        if( !is_null( $field ) ) {
            $total_field = FrmField::getOne( $field->field_options['total_field'] );
            if( !empty( $total_field ) ) {
                return $total_field;
            }
        }
        return null;
    }

    /**
     * Get value field based on Plutu field in same form
     * 
     * @access public
     * @param  int $form_id
     * @return object
     */
    public function get_value_field( $form_id ){
        $field = $this->get_plutu_field( $form_id );
        if( !is_null( $field ) ) {
            $value = $field->field_options['value'];
            if( !empty( $value ) ) {
                $values = array_filter( explode( ',', $value ) );
                $values = $this->setValuesArray( $values );
                $values = array_filter( $values, 'is_numeric' );
                $values = array_filter($values, function( $number ) { 
                    return $number > 0;
                });
                if( !empty( $values ) && count( $values ) > 0 ){
                    return $values;
                }
            }else{
                return '';
            }
        }
        return null;
    }

    /**
     * Set values and labels
     * 
     * @access private
     * @param array $values
     * @return array
     */
    private function setValuesArray($values){
        $result = [];
        foreach($values as $value){
            if(  strpos($value, ':') !== false ){
                $value = explode( ':', $value, 2);
                $result[ $value[0] ] = $value[1];
            } else { 
                $result[] = $value;
            }
        }
        return $result;
    }

    /**
     * Get value field based on Plutu field in same form
     * 
     * @access public
     * @param  int $form_id
     * @return object
     */
    public function get_use_custom_value_field( $form_id ){
        $field = $this->get_plutu_field( $form_id );
        if( !is_null( $field ) ) {
            if( !function_exists('load_formidable_pro') ) {
                return 1;
            }else{
                return $field->field_options['use_custom_value'];
            }
        }
        return null;
    }

    /**
     * Sanitizes a string from user input or from the database
     * 
     * @access public
     * @param  string $field
     * @param  array  $data
     * @return string|null
     */
    public function sanitize_text_field_or_null( $field, $data = []) {
        return isset( $data[ $field ] )? trim( sanitize_text_field( $data[ $field ] ) ) : null;
    }

    /**
     * Sanitizes a string from user _GET input
     * 
     * @access public
     * @param  string $field
     * @return string|null
     */
    public function sanitize_text_get_field_or_null( $field ) {
        return isset( $_GET[ $field ] )? trim( sanitize_text_field( $_GET[ $field ] ) ) : null;
    }

    /**
     * Sanitizes a string from user _POST input
     * 
     * @access public
     * @param  string $field
     * @return string|null
     */
    public function sanitize_text_post_field_or_null( $field ) {
        return isset( $_POST[ $field ] )? trim( sanitize_text_field( $_POST[ $field ] ) ) : null;
    }

    /**
     * Validate the input exist in _POST
     * 
     * @access public
     * @param  string $field
     * @return bool
     */
    public function is_text_post_field_exists( $field ) {
        $exists = false;
        if( isset( $_POST[ $field ] ) ) {
            $value = trim( sanitize_text_field( $_POST[ $field ] ) );
            if( !empty( $value ) ) {
                $exists = true;
            }

        }
        return $exists;
    }

    /**
     * Validate the input exist in _GET
     * 
     * @access public
     * @param  string $field
     * @return bool
     */
    public function is_text_get_field_exists( $field ) {
        $exists = false;
        if( isset( $_GET[ $field ] ) ) {
            $value = trim( sanitize_text_field( $_GET[ $field ] ) );
            if( !empty( $value ) ) {
                $exists = true;
            }

        }
        return $exists;
    }

    /**
     * Validate the input is in the data
     * 
     * @access public
     * @param  string $field
     * @param  array  $data
     * @return bool
     */
    public function is_text_field_exists( $field, $data ) {
        $exists = false;
        if( isset( $data[ $field ] ) ) {
            $value = trim( sanitize_text_field( $data[ $field ] ) );
            if( !empty( $value ) ) {
                $exists = true;
            }

        }
        return $exists;
    }

    /**
     * Check that the field exists and is equal to the other value
     * 
     * @access public
     * @param  string $field
     * @param  string $field_value
     * @return bool
     */
    public function is_text_get_field_exists_and_equlas( $field, $field_value='') {
        $equal = false;
        if( isset( $_GET[ $field ] ) ) {
            $value = trim( sanitize_text_field( $_GET[ $field ] ) );
            if( $value == $field_value ) {
                $equal = true;
            }
        }
        return $equal;
    }

    /**
     * Remove session value
     * 
     * @access public
     * @param string $key
     * @param array $data
     * @return void
     */
    public function remove_field_data_key( $key, $data ) {
        if( isset( $data[$key] ) ) {
            unset( $data[$key] );
        }
    }

    /**
     * Check that the total is allowed for the form amount
     * 
     * @access public
     * @param  string $amount
     * @param  string $maximum_amount
     * @param  string $minimum_amount
     * @return boolean
     * @throws Exception
     */
    public function is_valid_amount( $amount, $maximum_amount, $minimum_amount ) {

        // Allowed amounts for the payment method
        $maximum_amount = apply_filters( 'plutu_formidable_maximum_amount', $maximum_amount, $this->key );
        $minimum_amount = apply_filters( 'plutu_formidable_minimum_amount', $minimum_amount, $this->key );
        if( $amount > $maximum_amount ) {
            throw new Exception( sprintf( __( 'Sorry! Total is %s LYD higher than maximum amount %s LYD per transaction' , 'plutu-formidable'), $amount, $maximum_amount ) );
        } elseif ( $amount < $minimum_amount ) {
            throw new Exception( sprintf( __('Sorry! Total is %s LYD less than minimum amount %s LYD per transaction' , 'plutu-formidable'), $amount, $minimum_amount) );
        }

        return true;
    }

    /**
     * Valid value amount
     * 
     * @access public
     * @param  int $form_id
     * @param  flaot $amount
     * @return bool
     * @throws Exception
     */
    public function valid_value( $form_id, $amount ) {

        if( $amount <= 0 ){
            throw new Exception( __( 'The amount is invalid, it must be greater than zero', 'plutu-formidable' ) );
        }

        $amount_passed = false;
        // Is the Plutu field in the form?
        $field = $this->get_plutu_field( $form_id );
        $use_custom_value = (bool) $this->get_use_custom_value_field( $form_id );
        if( $use_custom_value ){
            $value_field = $this->get_value_field( $form_id );
            if( !empty( $value_field ) ){
                if( in_array($amount, $value_field) ){
                    $amount_passed = true;
                }else{
                    throw new Exception( __( 'The amount is not in the selected options' , 'plutu-formidable') );
                }
            }elseif($value_field === ''){
                $amount_passed = true;
            }
        } elseif( function_exists('load_formidable_pro') ) {
            // The form must contain at least one product field
            $total_field = $this->get_totals_field( $form_id );
            if( is_null( $total_field ) ) {
                throw new Exception( __('The Total field associated with the Plutu field is missing', 'plutu-formidable') );
            }else{
                $amount_passed = true;
            }
        } else {
            throw new Exception( __('The Total field associated with the Plutu field is missing', 'plutu-formidable') );
        }

        return $amount_passed;
    }

    /**
     * Validate mobile number format for Libyan number format and the prefix must be 09x
     * Return the formatted mobile number if successful, otherwise return null
     * 
     * @access public
     * @param  int  $mobile
     * @param  boolean  $all
     * @return string|null
     */
    public function is_valid_mobile_number( $mobile, $all = true ) {
        $pattern = $all? '((\+|00)?218|0?)?(9[0-9]{8})' : '((\+|00)?218|0?)?(91[0-9]{7})';
        if( preg_match( '/^' . $pattern . '$/', $mobile, $match ) ) {
            return $match[sizeof($match)-1];
        }
        return null;
    }


    /**
     * Get entry url by transaction id
     * 
     * @access public
     * @param  int $transaction_id
     * @return string
     */
    public function get_entry_url_by_transaction_id( $transaction_id ) {
        global $wpdb;

        $transaction_id = absint( $transaction_id );

        $url = 'admin.php?page=formidable-entries';
        if( is_numeric( $transaction_id ) ) {
            $value = '%' . $wpdb->esc_like( $transaction_id ) . '%';
            // Fetching entry id based on transaction id
            $entry_id = $wpdb->get_var( 
                $wpdb->prepare( "SELECT `item_id` FROM {$wpdb->prefix}frm_item_metas WHERE `meta_value` LIKE %s", $value ) 
            );
            if( $entry_id && !empty( $entry_id ) ) {
                $url .= "&frm_action=show&id=" . intval( $entry_id );
            }
        }
        return esc_url( admin_url( $url ) );
    }

    /**
     * Get value from item by field id
     * 
     * @access public
     * @param  int $item_id
     * @param  int $field_id
     * @return string
     */
    public function get_value_from_item_by_field_id( $item_id, $field_id ) {
        global $wpdb;
        return $wpdb->get_var(
            $wpdb->prepare(
                "
                    SELECT `meta_value` 
                    FROM {$wpdb->prefix}frm_item_metas 
                    WHERE `item_id` = %d 
                    AND `field_id` = %d
                ", 
                intval( $item_id ), intval( $field_id )
            )
        );
    }


    /**
     * Get default payment method
     * 
     * @access public
     * @return string
     */
    public function get_default_payment_method() {
        $options = get_option( 'plutu_formidable_options', [] );
        if( isset( $options['payment_methods'] ) && !empty( $options['payment_methods'] ) ) {
            return 'plutu-' . current( $options['payment_methods'] );
        }
        return '';
    }

    /**
     * Get IP address
     * 
     * @access public
     * @return string
     */
    public function get_ip_address() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            $ip = rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) );
        } else {
            $ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
        }
        return (string) $ip;
    }

    /**
     * Get js url
     * 
     * @access public
     * @param  string $file
     * @return string
     */
    public function get_js_file_url( $file ) {
        $file = trim( $file );
        if( !empty( $file ) ) {
            return PLUTU_FROMIDABLE_PLUGIN_ASSETS_JS_URL . '/' . $file;
        }
        return '';
    }

    /**
     * Get image url
     * 
     * @access public
     * @param  string $image
     * @return string
     */
    public function get_image_url( $image ) {
        $image = trim( $image );
        if( !empty( $image ) ) {
            return esc_url( PLUTU_FROMIDABLE_PLUGIN_ASSETS_IMG_URL . '/' . $image );
        }
        return '';
    }

    /**
     * Load image content
     * 
     * @access public
     * @param  string $image
     * @return string
     */
    public function load_image_content( $image ) {
        $file = realpath( PLUTU_FROMIDABLE_PLUGIN_ASSETS_IMG_FILE . '/' . basename( $image ) );
        if( file_exists( $file ) ) {
            return include realpath( $file );
        }
        return '';
    }

    /**
     * Load template
     * 
     * @param  string $path
     * @param  array  $args
     * @param  bool $load
     * @return void print HTML
     */
    public function load_template( $path, $args = [], $load = true) {
        if( !empty( $args ) ) {
            extract( $args );
        }

        /**
         * Set or override plugin resource files
         * to overrride from child-theme it must be set in this path plutu-formidable/
         * 
         * @var string
         */
        $paths = apply_filters( 'plutu_formidable_resources_path', [
            get_stylesheet_directory() . '/plutu-formidable/' . $path,
            PLUTU_FORMIDABLE_PLUGIN_RESOURCES . '/' . $path,
        ], $path );

        // Load template
        foreach( $paths as $file ) {
            $file = realpath( $file );
            if( file_exists( $file ) ) {
                if( !$load ) ob_start();
                include_once realpath( $file );
                if( !$load ) return ob_get_clean();
                break;
            }
        }

    }

    /**
     * Load error message block
     * 
     * @access public
     * @param  string $message
     * @param  bool $load
     * @return void print HTML|string
     */
    public function load_error_message($message, $load = true){

        if( !$load ) ob_start();

        $style = apply_filters( 'plutu_formidable_error_message_css_class', 'frm_error_style' );
        ?>
            <div class="<?php echo esc_attr($style); ?>">
                <?php echo esc_html($message); ?>
            </div>
        <?php

        if( !$load ) return ob_get_clean();
    }

}