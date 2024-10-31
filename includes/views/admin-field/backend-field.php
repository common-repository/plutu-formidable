<?php 
/**
 * Admin fields
 * 
 * @version 1.0.0
 * @package PlutuFormidable\Views
 */

defined( 'ABSPATH' )or exit; 

?>
<h3>
    <?php esc_html_e( 'Payment Methods', 'plutu-formidable' ); ?>
</h3>

<div class="frm_grid_container">

    <p class="frm12 frm_form_field frm_first frm_sep_val_checkbox">

    <label for="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][sadad]">
        <input <?php checked(in_array('sadad', $field['field_options']['payment_methods']), 1); ?> type="checkbox" name="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][]" id="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][sadad]" value="sadad" <?php echo !in_array('sadad', $payment_methods)? 'disabled="disabled"' : ''; ?>>
        <?php esc_html_e( 'Sadad Payment Method', 'plutu-formidable' ); ?> 
        <?php 
        if( !in_array('sadad', $payment_methods) ) {
            esc_html_e('(Disabled)', 'plutu-formidable' );
        }
        ?>
        
    </label>

    <label for="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][edfali]">

        <input <?php checked(in_array('edfali', $field['field_options']['payment_methods']), 1); ?>  type="checkbox" name="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][]" id="field_options[payment_methods_<?php echo absint( $field['id'] ); ?>][edfali]" value="edfali" <?php echo !in_array('edfali', $payment_methods)? 'disabled="disabled"' : ''; ?>>
        <?php esc_html_e( 'Adfali Payment Method', 'plutu-formidable' ); ?> 
        <?php 
        if( !in_array('edfali', $payment_methods) ) {
            esc_html_e('(Disabled)', 'plutu-formidable' );
        }
        ?>

    </label>

</div>

<h3>
    <?php esc_html_e( 'Configure payment amounts', 'plutu-formidable' ); ?>
</h3>

<div class="frm_grid_container">

    <label><?php esc_html_e( 'Sadad', 'plutu-formidable' ); ?></label>

    <p class="frm6">

        <label for="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][min]">
            <input type="number" name="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][min]" id="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][min]" value="<?php echo absint( $field['field_options']['amounts']['sadad']['min'] ); ?>">
            <?php esc_html_e( 'Minimum value', 'plutu-formidable' ); ?>
        </label>

    </p>

    <p class="frm6">

        <label for="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][max]">
            <input type="number" name="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][max]" id="field_options[amounts_<?php echo absint( $field['id'] ); ?>][sadad][max]" value="<?php echo absint( $field['field_options']['amounts']['sadad']['max'] ); ?>">
            <?php esc_html_e( 'Maximum value', 'plutu-formidable' ); ?>
        </label>

    </p>

    <label>
        <?php esc_html_e( 'Adfali', 'plutu-formidable' ); ?>
    </label>

    <p class="frm6">

        <label for="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][min]">
            <input type="number" name="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][min]" id="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][min]" value="<?php echo absint( $field['field_options']['amounts']['edfali']['min'] ); ?>">
            <?php esc_html_e( 'Minimum value', 'plutu-formidable' ); ?>
        </label>

    </p>

    <p class="frm6">

        <label for="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][max]">
            <input type="number" name="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][max]" id="field_options[amounts_<?php echo absint( $field['id'] ); ?>][edfali][max]" value="<?php echo absint( $field['field_options']['amounts']['edfali']['max'] ); ?>">
            <?php esc_html_e( 'Maximum value', 'plutu-formidable' ); ?>
        </label>

    </p>

    <p>

        <label for="field_options[hide_icons_<?php echo absint( $field['id'] ); ?>]">

            <input <?php checked($field['field_options']['hide_icons'], 1); ?> type="checkbox" name="field_options[hide_icons_<?php echo esc_attr( $field['id'] ); ?>]" id="field_options[hide_icons_<?php echo esc_attr( $field['id'] ); ?>]" value="1">
            <?php esc_html_e( 'Hide payment methods icons', 'plutu-formidable' ); ?>

        </label>

    </p>
    
</div>
<br /><hr style="width: 100%;"><br />
<div class="frm_grid_container">

<?php if( function_exists('load_formidable_pro') ) { ?>

<label for="field_options[use_custom_value_<?php echo absint( $field['id'] ); ?>]">

    <input <?php checked($field['field_options']['use_custom_value'], 1); ?>  type="checkbox" name="field_options[use_custom_value_<?php echo absint( $field['id'] ); ?>]" id="field_options[use_custom_value_<?php echo absint( $field['id'] ); ?>][use_custom_value]" value="1">
    <?php esc_html_e( 'Use custom amount', 'plutu-formidable' ); ?>

    <?php if( function_exists('load_formidable_pro') ) { ?>
        <?php esc_html_e( '(If it is enabled the total field will be ignored)', 'plutu-formidable' ); ?> 
    <?php } ?>

</label>

<?php } ?>

<p class="frm12">

    <label for="field_options[value_<?php echo absint( $field['id'] ); ?>]">
    <?php esc_html_e( 'Amount', 'plutu-formidable' ); ?>

    <span class="frm_help frm_icon_font frm_tooltip_icon frm_tooltip_expand" data-placement="right" title="" data-original-title="
        <?php esc_html_e( 'You can specify a single value, Also you can assign multiple values and separate them with a comma or leave blank to allow the user to enter the value.', 'plutu-formidable' ); ?>
        ">
    </span>

    </label>

    <input type="text" name="field_options[value_<?php echo absint( $field['id'] ); ?>]" id="field_options[value_<?php echo absint( $field['id'] ); ?>]" value="<?php echo esc_attr( $field['field_options']['value'] ); ?>">

</p>



<?php if( function_exists('load_formidable_pro') ) { ?>
<br /><hr style="width: 100%;"><br />

<div class="frm-inline-message">
<?php esc_html_e( 'To use the Total field, you must uncheck (Use custom amount) option', 'plutu-formidable' ); ?>

</div>
<p>

    <label for="total_field_<?php echo absint( $field['id'] ); ?>" class="frm_help" title="" data-original-title="<?php esc_html_e( 'The total field associated with the payment method', 'plutu-formidable' ); ?>">
        <?php esc_html_e( 'Total field', 'plutu-formidable' ); ?>
    </label>

</p>


<p>

    <label for="field_options[hidden_field_<?php echo absint( $field['id'] ); ?>]">

        <input <?php checked($field['field_options']['hidden_field'], 1); ?> type="checkbox" name="field_options[hidden_field_<?php echo esc_attr( $field['id'] ); ?>]" id="field_options[hidden_field_<?php echo esc_attr( $field['id'] ); ?>]" value="1">
        <?php esc_html_e( 'Set the Plutu payment section to be hidden if the total is zero.', 'plutu-formidable' ); ?>

    </label>

</p>

<select id="total_field_<?php echo absint( $field['id'] ); ?>" name="field_options[total_field_<?php echo absint( $field['id'] ) ?>]">

    <option value=""><?php esc_html_e( '&mdash; Select &mdash;' ,'plutu-formidable' ); ?></option>

    <?php
    $sel = false;
    $form_fields = FrmField::get_all_for_form( $args['field']['form_id'] );
    foreach ( $form_fields as $ff ) {
        if($ff->type != 'total'){
            continue;
        }
    ?>
    <option value="<?php echo absint( $ff->id ) ?>" <?php selected( $field['field_options']['total_field'] == $ff->id, 1 ) ?>>
        <?php echo esc_html( $ff->name ); ?>
    </option>
    <?php } ?>

</select>

<?php } ?>
<div>
<br />
<hr style="width: 100%;">