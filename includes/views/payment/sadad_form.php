<?php 
/**
 * Sadad form
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Views\Payment
 */

defined( 'ABSPATH' )or exit; 

wp_enqueue_script( 'plutu-formidable-sadad-scripts' );

?>
<div class=" frm_section_heading">

    <div class="frm_trigger">
        
        <?php if( !$hide_icons ) { ?>
        <img class="frm_payment_img" src="<?php echo esc_html( $this->get_image_url('sadad.png') ); ?>" alt="" />
        <?php } ?>
        
        <div class="frm_payment_head">

            <h3 class="frm_pos_top active">

                <?php esc_html_e('Sadad', 'plutu-formidable'); ?>

            </h3>

            <div class="frm_description">

                <?php esc_html_e('Sadad mobile payment', 'plutu-formidable'); ?>
                    
            </div>

        </div>

    </div>

    <div class="frm_toggle_container frm_grid_container" style="">

        <div id="sadad-message" style="display: none;"></div>
        
        <!-- Sadad Verify -->
        <div id="sadad-verify">

        <?php if( $use_custom_value ) { ?>

            <div class="frm_form_field form-field">

                <label for="sadad-value" class="frm_primary_label">
                    <?php esc_html_e('Amount', 'plutu-formidable'); ?>
                    <span class="frm_required"></span>
                </label>

                <?php if( empty($value_field) ) { ?>
                
                    <input type="number" id="sadad-value" name="sadad_value" placeholder="<?php esc_html_e('Amount', 'plutu-formidable'); ?> <?php esc_html_e('LYD', 'plutu-formidable'); ?>">

                <?php } elseif( count($value_field) == 1 ) { ?>

                    <?php if( is_int( key($value_field) ) ) { ?>

                        <strong><?php echo esc_html( $value_field[ key($value_field) ] ); ?> <?php esc_html_e('LYD', 'plutu-formidable'); ?></strong>

                    <?php } else { ?>

                        <strong>
                            <?php echo esc_html( key($value_field) ); ?> <?php echo esc_html( $value_field[key($value_field)] ); ?> <?php esc_html_e('LYD', 'plutu-formidable'); ?>
                        </strong>

                    <?php } ?>

                    <input type="hidden" id="sadad-value" name="sadad_value" value="<?php echo esc_html( $value_field[key($value_field)] ); ?>">

                <?php } else { ?>

                    <select id="sadad-value" name="sadad_value">
                        <?php foreach ($value_field as $label => $value) { ?>
                            <option value="<?php echo esc_html( $value ); ?>">
                                <?php if( is_int( $label ) ) { ?>
                                    <?php echo esc_html( $value ); ?> <?php esc_html_e('LYD', 'plutu-formidable'); ?>
                                <?php }else{ ?>
                                    <?php echo esc_html( $label ); ?> - <?php echo esc_html( $value ); ?> <?php esc_html_e('LYD', 'plutu-formidable'); ?>
                                <?php } ?>
                            </option>
                        <?php } ?>
                    </select>

                <?php } ?>

                <span id="frm_field_sadad_value_container"></span>

            </div>

        <?php } ?>

            <div class="frm_form_field form-field">

                <label for="sadad-mobile-number" class="frm_primary_label">
                    <?php esc_html_e('Mobile number', 'plutu-formidable'); ?>
                    <span class="frm_required"></span>
                </label>

                <input type="text" id="sadad-mobile-number" name="sadad_mobile_number" placeholder="<?php esc_html_e('091xxxxxxx', 'plutu-formidable'); ?>" value="<?php echo esc_html($sadad_mobile_number); ?>">

                <span id="frm_field_sadad_mobile_number_container"></span>

            </div>

            <div class="frm_form_field form-field">

                <label for="sadad-birth-year" class="frm_primary_label">
                    <?php esc_html_e('Birth year', 'plutu-formidable'); ?>
                    <span class="frm_required"></span>
                </label>

                <input type="number" id="sadad-birth-year" name="sadad_birth_year" placeholder="<?php esc_html_e('Birth year xxxx', 'plutu-formidable'); ?>" value="<?php echo esc_html($sadad_birth_year); ?>">

                <span id="frm_field_sadad_birth_year_container"></span>

            </div>

            <button id="send-sadad-verification-code" class="frm_button">

                <span class="verify-sadad-icon">
                    <i class="frm_icon_font frm_email_icon"></i>
                </span>

                    <span class="verify-sadad-text"><?php esc_html_e('Send OTP', 'plutu-formidable'); ?></span>

            </button>
        </div>
        <!-- Sadad Verify -->

        <!-- Sadad Confirm -->
        <div id="sadad-confirm" style="display: none;">
            <?php if (isset($errors['field' . $field_id . '-sadad_otp']) && !empty($errors['field' . $field_id . '-sadad_otp'])){ ?>
                <div class="frm_error_style" id="frm_error_field_<?php echo esc_html($field_id); ?>-sadad_otp">
                    <?php echo esc_html($errors['field' . $field_id . '-sadad_otp']); ?>
                </div>
            <?php } ?>

            <div class="frm_form_field form-field" id="frm_field_<?php echo esc_html($field_id); ?>-sadad_otp_container" style="margin-bottom:0">

                <label for="sadad_otp" class="frm_primary_label">
                    <?php esc_html_e('OTP', 'plutu-formidable'); ?>
                    <span class="frm_required"></span>
                </label>

                <input type="text" id="sadad_otp" name="<?php echo esc_html($field_name); ?>[sadad_otp]"  value="<?php echo esc_html($sadad_otp); ?>">

                <div class="frm_description">
                    <?php esc_html_e('Enter the OTP code received from the Sadad service and submit the form', 'plutu-formidable'); ?>
                </div>
            </div>
            <div class="frm_description">

                <?php esc_html_e('Did not receive an OTP? Please wait for a few minutes and ', 'plutu-formidable'); ?>

                <a type="button" id="resend-sadad-otp">
                    <?php esc_html_e('try again', 'plutu-formidable'); ?>
                </a>

            </div>
        </div>

        <div>
            <a href="https://plutu.ly" target="_blank">
                <img class="frm_payment_plutu_logo" src="<?php echo esc_html( $this->get_image_url('plutu-logo.svg') ); ?>" alt="" />
            </a>
        </div>

        <!-- Sadad Confirm -->

        <input type="hidden" id="sadad_verified" name="sadad_verified" value="<?php echo esc_html($sadad_verified); ?>">
        <input type="hidden" id="sadad_process_id" name="sadad_process_id" value="<?php echo esc_html($sadad_process_id); ?>">
    </div>
    
</div>
<?php if( !empty( $sadad_verified ) ) { ?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('#sadad-verify').hide();
    jQuery('#sadad-message').hide();
    jQuery('#sadad-confirm').show();
});
</script>
<?php } ?>