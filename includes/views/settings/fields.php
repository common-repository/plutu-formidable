<?php 
/**
 * Sadad form
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Views\Settings
 */

defined( 'ABSPATH' )or exit; 

?>
<p class="howto">
    <?php esc_html_e('Plutu is a unified Payment gateway to manage all Libyan payment gateway providers in one place while providing the ability to integrate these providers with your online Marketplace in one process.', 'plutu-formidable'); ?>
    <br />
    <a target="_blank" href="https://plutu.ly"><?php esc_html_e('Plutu', 'plutu-formidable'); ?></a> | <a target="_blank" href="https://docs.plutu.ly"><?php esc_html_e('Documentations', 'plutu-formidable'); ?></a> | <a target="_blank" href="https://help.plutu.ly"><?php esc_html_e('Help', 'plutu-formidable'); ?></a>
</p>

<form action="options.php" method="POST">

    <h3>
        <?php esc_html_e('Plutu Payment Methods', 'plutu-formidable'); ?>
    </h3>

    <p>
        <label>
            <span class="frm_toggle">
            <input type="checkbox" name="options[payment_methods][]" id="sadad" value="sadad" 
            <?php if( in_array('sadad', $payment_methods) )  echo esc_html('checked' ); ?>> 
                <?php esc_html_e('Sadad', 'plutu-formidable'); ?>
                <span class="frm_toggle_slider"></span>
            </span>
            <?php esc_html_e('Sadad payment method', 'plutu-formidable'); ?>
        </label>
    </p>

    <p>
        <label>
            <span class="frm_toggle">
            <input type="checkbox" name="options[payment_methods][]" id="edfali" value="edfali" 
            <?php if( in_array('edfali', $payment_methods) ) echo esc_html('checked' ); ?>> 
                <?php esc_html_e('Adfali', 'plutu-formidable'); ?>
                <span class="frm_toggle_slider"></span>
            </span>
            <?php esc_html_e('Adfali payment method', 'plutu-formidable'); ?>
        </label>
    </p>

    <h3>
        <?php esc_html_e('API Configurations', 'plutu-formidable'); ?>
    </h3>
    <p class="howto">
        <?php esc_html_e('To use the Sandbox, you must use the test mode access token.', 'plutu-formidable'); ?>
    </p>
    <p>
        <label class="frm_left_label">
            <?php esc_html_e('API Key', 'plutu-formidable'); ?>
            <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="Plutu API key"></span>
        </label>
        <input name='options[api_key]' type='text' value="<?php echo esc_html($options['api_key']); ?>" />
    </p>

    <p>
        <label class="frm_left_label">
            <?php esc_html_e('Access Token', 'plutu-formidable'); ?>
            <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="Plutu access token"></span>
        </label>
        <textarea rows="6" name='options[access_token]' type='text'><?php echo esc_html($options['access_token']); ?></textarea>
    </p>

    <p>
        <input name="submit" type="submit" value="<?php esc_html_e('Save', 'plutu-formidable');?>" class="button-primary frm-button-primary">
    </p>
    
</form>