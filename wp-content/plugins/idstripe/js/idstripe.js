jQuery(document).ready(function() {
	// First we need to make available the pay with stripe option, storing the paypal method just in case
	var btn = jQuery("input.main-btn").attr("name");
	var btntext = jQuery("input.main-btn").val();
    var pp_off = jQuery('#stripe-input').data('ppoff');
    var ccode = jQuery('#stripe-input').data('ccode');
    //console.log(ccode);
    var ccodeDefault = jQuery('.id-buy-form-currency').text();
    jQuery("input.main-btn").val("Choose Payment Type");
    if (pp_off) {
        jQuery("#pay-with-paypal").remove();
        if (jQuery(".pay-choice").size() <= 1) {
            jQuery("#stripe-input").show();
            jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
            jQuery("input.main-btn").attr("name", "submitPaymentStripe");
            jQuery("input.main-btn").val("Submit Payment");
            jQuery('.id-buy-form-currency').text(ccode);
        }
    }
    jQuery(".pay-choice").click(function(e) {
        e.preventDefault();
        if (jQuery(this).attr('id').indexOf('pay-with-stripe') !== -1) {
    		jQuery("#stripe-input").show();
    		jQuery("input.main-btn").attr("name", "submitPaymentStripe");
    		jQuery("input.main-btn").val("Submit Payment");
    		jQuery(this).addClass("active");
            jQuery("#button_pay_purchase").removeAttr('disabled');
            jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
            jQuery('.id-buy-form-currency').text(ccode);
        }
        else {
            jQuery("#stripe-input").hide();
            jQuery("#pay-with-stripe").removeClass("active");
            jQuery(".card-number, .card-cvc, .card-expiry-month, .card-expiry-year").removeClass("required");
            jQuery('.id-buy-form-currency').text(ccodeDefault);
        }
    });
	// Now we need to handle the submission of credit card details
    jQuery(document).bind('validate', function(e, validated) {
        //e.preventDefault();
        Stripe.setPublishableKey(jQuery("#stripe-input").data('pubkey'));
        if (jQuery("input.main-btn").attr("name") == "submitPaymentStripe" && validated == true) {
            jQuery("input[name='submitPaymentStripe']").attr("disabled", "disabled");
            jQuery("input[name='submitPaymentStripe']").val("Processing...");
            jQuery("#message-container").hide();
            Stripe.createToken({
                number: jQuery(".card-number").val(),
                cvc: jQuery(".card-cvc").val(),
                exp_month: jQuery(".card-expiry-month").val(),
                exp_year: jQuery(".card-expiry-year").val()
            }, stripeResponseHandler);
        }
    });	
});
function stripeResponseHandler(status, response) {
    if (response.error) {
        jQuery("#message-container").show();
        jQuery("span#paypal-error-message").text(response.error.message);
        jQuery(".main-btn").removeAttr("disabled");
        jQuery("input[name='submitPaymentStripe']").val("Complete Checkout");
        return false;
    } else {
        var formy = jQuery("#form_pay");
        var token = response["id"];
        formy.append('<input type="hidden" name="stripeToken" value="' + token + '"/>');
        //console.log(token);
        var fname = jQuery("#first_name").val();;
        if (!fname) {
        	fname = '';
        }	        
        var lname = jQuery("#last_name").val();
        if (!lname) {
        	lname = '';
        }
        var email = jQuery("#email").val();
        if (!email) {
        	email = '';
        }
        var address = jQuery("#address").val();
        if (!address) {
        	address = '';
        }
        var city = jQuery("#city").val();
        if (!city) {
        	city = '';
        }
        var state = jQuery("#state").val();
        if (!state) {
        	state = '';
        }
        var zip = jQuery("#zip").val();
        if (!zip) {
        	zip = '';
        }
        var country = jQuery("#country").val();
        if (!country) {
        	country = '';
        }
        var product = jQuery("#stripe-input").data('productid');

        var level = jQuery("input[name='level']").val()
        if (!level) {
        	level = 1;
        }

        var price = jQuery('input[name="price"]').val();
        amount = price.replace(',', '');
        var keys = [{
        	'fname': fname,
	        'lname': lname,
	        'email': email,
	        'address': address,
	        'city': city,
	        'state': state,
	        'zip': zip,
	        'country': country,
	        'product': product,
	        'level': level,
	        'token': token,
	        'amount': amount}];
        var url = idstripe_ajax_url;
        //formy.get(0).submit();
        jQuery.ajax( {
        	url: url,
        	type: 'POST',
        	data: {action: 'idstripe_submit_handler', Keys: keys},
        	success: function(response) {
                //console.log(response);
        		var json = JSON.parse(response);
                //console.log(json);
        		if (json.code == 'success') {
        			var ty_url = jQuery("#stripe-input").data('ty-url');
					window.location = ty_url;
        		}
        		else {
                    jQuery("input[name='submitPaymentStripe']").val("Complete Checkout");
                    jQuery("#message-container").show();
        			jQuery("span#paypal-error-message").text(json.code);
        		}
        	},
            error: function(xhr, options, exc) {
                console.log(exc);
            }
        });
    }
}
/*
    The easiest way to indicate that the form requires JavaScript is to show
    the form with JavaScript (otherwise it will not render). You can add a
    helpful message in a noscript to indicate that users should enable JS.
*/
if (window.Stripe) {
	jQuery("#stripe-payment-form").show()
}