/**
 * Initialization
 */
jQuery(document).ready(function(){

    jQuery("#send-sadad-verification-code").click(function(e){

        e.preventDefault();
        send_plutu_sadad_otp_request();
        return false;

    });

    jQuery('#sadad-value').keypress(function(e){
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13 ){

            e.preventDefault();
            send_plutu_sadad_otp_request();
            return false;

        }
    });

    jQuery('#sadad-mobile-number').keypress(function(e){
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13 ){

            e.preventDefault();
            send_plutu_sadad_otp_request();
            return false;

        }
    });

    jQuery('#sadad-birth-year').keypress(function(e){
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13){

            e.preventDefault();
            send_plutu_sadad_otp_request();
            return false;

        }
    });

    jQuery("#resend-sadad-otp").click(function(e){

        e.preventDefault();

        jQuery('input#sadad-mobile-number').val('');
        jQuery('input#sadad-birth-year').val('');
        jQuery('#sadad_verified').val('');
        jQuery('#sadad_process_id').val('');
        jQuery('#sadad-message').hide();
        jQuery('#sadad-confirm').hide();
        jQuery('#sadad-verify').fadeIn();
        jQuery(plutu_fields.payment_method_error).fadeOut();

        return false;

    });

    function send_plutu_sadad_otp_request() {

        if( jQuery('#sadad_verified').val() != '' ){
            return false;
        }

        jQuery('.verify-sadad-icon').html('<i class="plutu-loader"></i>');
        jQuery('.verify-sadad-text').html(plutu_fields.sending);
        jQuery('input#sadad-mobile-number').prop("disabled", true);
        jQuery('input#sadad-birth-year').prop("disabled", true);
        jQuery('input#sadad-value').prop("disabled", true);
        jQuery('button#send-sadad-verification-code').prop("disabled", true);
        jQuery('button[type="submit"]').prop("disabled", true);
        jQuery('#sadad-message').hide();
        jQuery('#sadad-message').removeClass('frm_error_style').removeClass('frm_message');
        jQuery(plutu_fields.payment_method_error).fadeOut();


        if( plutu_fields.use_custom_value ) {
            var amount = jQuery('#sadad-value').val();
        }else{
            var amount = jQuery('input[name="item_meta['+plutu_fields.total_field+']"]').val();
        }

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: plutu_fields.url,
            data: {
                'form_id':plutu_fields.form_id,
                'amount':amount,
                'mobile_number':jQuery('#sadad-mobile-number').val(),
                'birth_year':jQuery('#sadad-birth-year').val(),
                'nonce': plutu_fields.sadad_nonce,
                'action': 'send_sadad_otp_request' 
            }, success: function ( result ) {
                jQuery('.verify-sadad-icon').html('<i class="frm_icon_font frm_email_icon"></i>');
                jQuery('.verify-sadad-text').html(plutu_fields.send);

                jQuery('#sadad-mobile-number').prop("disabled", false);
                jQuery('#sadad-birth-year').prop("disabled", false);
                jQuery('#sadad-value').prop("disabled", false);
                jQuery('#send-sadad-verification-code').prop("disabled", false);
                jQuery('button[type="submit"]').prop("disabled", false);

                if ( result.success ) {
                    jQuery('#sadad-message').html(result.data.message);
                    jQuery('#sadad_process_id').val(result.data.process_id);
                    jQuery('#sadad_verified').val('1');
                    jQuery('#sadad-message').addClass('frm_message');
                    jQuery('#sadad-verify').hide();
                    jQuery('#sadad-confirm').show();
                    jQuery('#sadad_otp').focus();
                } else {
                    jQuery('#sadad-message').html(result.data.error.message);
                    jQuery('#sadad-message').addClass('frm_error_style');
                }
                jQuery('#sadad-message').show();

            },
            error: function () {
                jQuery('.verify-sadad-icon').html('<i class="frm_icon_font frm_email_icon"></i>');
                jQuery('.verify-sadad-text').html(plutu_fields.send);
                jQuery('button[type="submit"]').prop("disabled", false);
                jQuery('#sadad-mobile-number').prop("disabled", false);
                jQuery('#sadad-birth-year').prop("disabled", false);
                jQuery('#sadad-value').prop("disabled", false);
                jQuery('#send-sadad-verification-code').prop("disabled", false);
            }
        });

        return false;
    }
    
});