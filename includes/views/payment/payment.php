<?php 
/**
 * Payment container
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Views
 */

defined( 'ABSPATH' )or exit; 

wp_enqueue_script( 'plutu-formidable-payment-scripts' );

?>
<div id="plutu-container">

    <?php if ( !empty( $error ) ) { ?>
    <!-- Payment error -->
    <div class="frm_error_style" id="frm_error_field_<?php echo esc_html($field_id); ?>-payment_method">
        <?php echo esc_html($error); ?>
    </div>
    <!-- Payment error -->
    <?php } ?>

    <!-- Payment content -->

    <?php if ( in_array( 'sadad', $payment_methods ) ) { ?>
    <!-- Sadad -->
    <div id="plutu-sadad">
        <?php 
            $this->load_template('payment/sadad_form.php', [
                'form_id' => $form_id, 
                'field_id' => $field_id, 
                'field_name' => $field_name,
                'payment_methods' => $payment_methods,
                'selected_payment' => $selected_payment,
                'error_field' => $error_field,
                'error' => $error,
                'errors' => $errors,
                'total_field_id' => $total_field_id,
                'sadad_verified' => $sadad_verified,
                'sadad_mobile_number' => $sadad_mobile_number,
                'sadad_birth_year' => $sadad_birth_year,
                'sadad_process_id' => $sadad_process_id,
                'sadad_otp' => $sadad_otp,
                'use_custom_value' => $use_custom_value,
                'hide_icons' => $hide_icons,
                'value_field' => $value_field,
            ]);
        ?>
    </div>
    <!-- Sadad -->
    <?php } ?>

    <?php if ( in_array( 'edfali', $payment_methods ) ) { ?>
    <!-- Adfali -->
    <div id="plutu-edfali">
        <?php 

            $this->load_template('payment/adfali_form.php', [
                'form_id' => $form_id, 
                'field_id' => $field_id, 
                'field_name' => $field_name,
                'payment_methods' => $payment_methods,
                'selected_payment' => $selected_payment,
                'error_field' => $error_field,
                'error' => $error,
                'errors' => $errors,
                'total_field_id' => $total_field_id,
                'edfali_verified' => $edfali_verified,
                'edfali_mobile_number' => $edfali_mobile_number,
                'edfali_process_id' => $edfali_process_id,
                'edfali_otp' => $edfali_otp,
                'use_custom_value' => $use_custom_value,
                'hide_icons' => $hide_icons,
                'value_field' => $value_field,
            ]);

        ?>
    </div>
    <!-- Adfali -->
    <?php } ?>

    <!-- Payment content -->
    <input type="hidden" name="<?php echo esc_html($field_name); ?>[payment_method]" id="selected_payment" value="">
</div>

<script>
var plutu_fields = {
    first_payment_method: <?php echo json_encode( esc_html( $first_payment_method ) ); ?>,
    selected_payment: <?php echo json_encode( esc_html( $selected_payment ) ); ?>,
    payment_method_error: "#frm_error_field_<?php echo esc_html( $field_id ); ?>-payment_method",
    field_container: "#frm_field_<?php echo esc_html($field_id); ?>_container",
    sending: "<?php esc_html_e('Sending...', 'plutu-formidable'); ?>",
    send: "<?php esc_html_e('Send OTP', 'plutu-formidable'); ?>",
    total_field: <?php echo json_encode( esc_html($total_field_id) ); ?>,
    edfali_nonce: <?php echo json_encode( wp_create_nonce( 'edfali_nonce' ) ); ?>,
    sadad_nonce: <?php echo json_encode( wp_create_nonce( 'sadadapi_nonce' ) ); ?>,
    use_custom_value: <?php echo json_encode( esc_html( $use_custom_value ) ); ?>,
    is_hidden_section: <?php echo json_encode( esc_html( $is_hidden_section ) ); ?>,
    form_id: <?php echo json_encode( esc_html( $form_id) ); ?>,
    url: <?php echo json_encode( admin_url('admin-ajax.php') ); ?>
}
</script>

<style>
    <?php if( $is_hidden_section ) { ?>
    #frm_field_<?php echo esc_html($field_id); ?>_container{
        display: none;
    }
    <?php } ?>
    #frm_field_<?php echo esc_html($field_id); ?>_container .frm_primary_label{
        display: block;
    }
    #frm_field_<?php echo esc_html($field_id); ?>_container .plutu-loader {
        font-size: 10px;
        text-indent: -9999em;
        width: 1em;
        display: inline-block;
        height: 1em;
        border-radius: 50%;
        background: #ffffff;
        background: -moz-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -webkit-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -o-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: -ms-linear-gradient(left, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        background: linear-gradient(to right, #ffffff 10%, rgba(255, 255, 255, 0) 42%);
        position: relative;
        -webkit-animation: load3 1.4s infinite linear;
        animation: load3 1.4s infinite linear;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
    }
    #frm_field_<?php echo esc_html($field_id); ?>_container .frm_icon_font.frm_email_icon{
        color: #fff;
    }
    #frm_field_<?php echo esc_html($field_id); ?>_container .plutu-loader:before {
        width: 0%;
        height: 0%;
        background: #ffffff;
        border-radius: 100% 0 0 0;
        position: absolute;
        top: 0;
        left: 0;
        content: '';
    }
    #frm_field_<?php echo esc_html($field_id); ?>_container .plutu-loader:after {
        background: transparent;
        width: 75%;
        height: 75%;
        border-radius: 50%;
        content: '';
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
    #plutu-container .frm_button{
        cursor: pointer;
    }
    #plutu-container .frm_toggle_container{
        padding: 10px 0;
    }
    @-webkit-keyframes load3 {
      0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @keyframes load3 {
      0% {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    #plutu-container .frm_section_heading{
        border: 1px solid #ccc;
        padding: 10px;
        margin-bottom: 10px;
        background: #fff;
        border-radius: 5px;
    }
    #plutu-container .frm_description{
        display: inline-block;
    }
    #plutu-container .frm_pos_top{
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0 0 0;
    }
    #resend-edfali-otp, #resend-sadad-otp{
        cursor: pointer;
    }
    #plutu-container .frm_payment_plutu_logo{
        display: inline;
        width: 50px;
        margin-top: 10px;
        padding: 5px;
    }
    #plutu-container .frm_payment_img{
        float: left;
        width: 120px;
        border: 1px dotted #c9c8c8;
        border-radius: 5px;
        margin: 0px 10px;
        margin-left: 0;
    }
    .rtl #plutu-container .frm_payment_img{
        float: right !important;
        margin-left: unset !important;
        margin-right: 0px !important;
    }
    #plutu-container .frm_payment_head{
        display: inline-block;
    }
</style>