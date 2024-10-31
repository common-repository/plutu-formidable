/**
 * Initialization
 */
jQuery(document).ready(function () {

    var payment = plutu_fields.selected_payment;

    if(payment == 'plutu-sadad'){
        jQuery("#plutu-sadad .frm_toggle_container").show();
        jQuery("#plutu-edfali .frm_toggle_container").hide();
    } else if (payment == 'plutu-edfali') {
        jQuery("#plutu-edfali .frm_toggle_container").show();
        jQuery("#plutu-sadad .frm_toggle_container").hide();
    }else{
        jQuery("#plutu-sadad .frm_toggle_container").show();
        jQuery("#plutu-edfali .frm_toggle_container").hide();
    }

    jQuery("#selected_payment").val(plutu_fields.first_payment_method);
    jQuery( "#plutu-sadad .frm_trigger" ).click(function() {
        jQuery("#selected_payment").val('plutu-sadad');
    });
    jQuery( "#plutu-edfali .frm_trigger" ).click(function() {
        jQuery("#selected_payment").val('plutu-edfali');
    });
    jQuery( "#plutu-sadad .frm_trigger" ).click(function() {
        if(!jQuery('#plutu-edfali .frm_toggle_container').is(':hidden')){
            jQuery('#plutu-edfali .frm_toggle_container').slideUp( "slow" );
        }
        jQuery('#plutu-sadad .frm_toggle_container').slideDown( "slow" );
    });
    jQuery( "#plutu-edfali .frm_trigger" ).click(function() {
        if(!jQuery('#plutu-sadad .frm_toggle_container').is(':hidden')){
            jQuery('#plutu-sadad .frm_toggle_container').slideUp( "slow" );
        }
        jQuery('#plutu-edfali .frm_toggle_container').slideDown( "slow" );
    });
    jQuery("body").on('change', 'input[name="item_meta['+plutu_fields.total_field+']"]', function(){
        if(jQuery(this).val() > 0){
            jQuery(plutu_fields.field_container).fadeIn('slow');
        }else{
            if( plutu_fields.is_hidden_section ) {
                jQuery(plutu_fields.field_container).fadeOut('fast');
            }
        }
    });

});