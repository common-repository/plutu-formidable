/**
 * Initialization
 */
jQuery(document).ready(function(){

    jQuery("#send-edfali-verification-code").click(function(e){

        e.preventDefault();
        send_plutu_edfali_otp_request();
        return false;

    });

    jQuery('#edfali-value').keypress(function(e){
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13){

            e.preventDefault();
            send_plutu_edfali_otp_request();
            return false;

        }
    });

    jQuery('#edfali-mobile-number').keypress(function(e){
        var keyCode = e.keyCode || e.which;

        if (keyCode == 13){

            e.preventDefault();
            send_plutu_edfali_otp_request();
            return false;

        }
    });
    jQuery("#resend-edfali-otp").click(function(e){

        e.preventDefault();

        jQuery('input#edfali-mobile-number').val('');
        jQuery('#edfali_verified').val('');
        jQuery('#edfali_process_id').val('');
        jQuery('#edfali-message').hide();
        jQuery('#edfali-confirm').hide();
        jQuery('#edfali-verify').fadeIn();
        jQuery(plutu_fields.payment_method_error).fadeOut();

        return false

    });

    function send_plutu_edfali_otp_request() {

        if(jQuery('#edfali_verified').val() != ''){
            return false;
        }

        jQuery('.verify-edfali-icon').html('<i class="plutu-loader"></i>');
        jQuery('.verify-edfali-text').html(plutu_fields.sending);
        jQuery('input#edfali-mobile-number').prop("disabled", true);
        jQuery('input#edfali-value').prop("disabled", true);
        jQuery('#send-edfali-verification-code').prop("disabled", true);
        jQuery('button[type="submit"]').prop("disabled", true);
        jQuery('#edfali-message').hide();
        jQuery('#edfali-message').removeClass('frm_error_style').removeClass('frm_message');
        jQuery(plutu_fields.payment_method_error).fadeOut();

        if( plutu_fields.use_custom_value ) {
            var amount = jQuery('#edfali-value').val();
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
                'mobile_number':jQuery('#edfali-mobile-number').val(),
                'nonce': plutu_fields.edfali_nonce,
                'action': 'send_edfali_otp_request' 
            }, success: function ( result ) {
                jQuery('.verify-edfali-icon').html('<i class="frm_icon_font frm_email_icon"></i>');
                jQuery('.verify-edfali-text').html(plutu_fields.send);

                jQuery('#edfali-mobile-number').prop("disabled", false);
                jQuery('#edfali-value').prop("disabled", false);
                jQuery('#send-edfali-verification-code').prop("disabled", false);
                jQuery('button[type="submit"]').prop("disabled", false);

                if ( result.success ) {
                    jQuery('#edfali-message').html(result.data.message);
                    jQuery('#edfali_process_id').val(result.data.process_id);
                    jQuery('#edfali_verified').val('1');
                    jQuery('#edfali-message').addClass('frm_message');
                    jQuery('#edfali-verify').hide();
                    jQuery('#edfali-confirm').show();
                    jQuery('#edfali_otp').focus();

                } else {
                    jQuery('#edfali-message').html(result.data.error.message);
                    jQuery('#edfali-message').addClass('frm_error_style');
                }
                jQuery('#edfali-message').show();

            },
            error: function () {
                jQuery('.verify-edfali-icon').html('<i class="frm_icon_font frm_email_icon"></i>');
                jQuery('.verify-edfali-text').html(plutu_fields.send);
                jQuery('button[type="submit"]').prop("disabled", false);
                jQuery('#edfali-mobile-number').prop("disabled", false);
                jQuery('#edfali-value').prop("disabled", false);
                jQuery('#send-edfali-verification-code').prop("disabled", false);
            }
        });

        return false;
    }
    
});