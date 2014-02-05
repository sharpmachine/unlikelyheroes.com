jQuery(document).ready(function() {
	var type = jQuery("#payment-form").data('type');
	var logged = jQuery("#payment-form #logged-input").hasClass('yes');
	var isFree = jQuery("#payment-form").data('free');
	var epp = memberdeck_epp;
	var es = memberdeck_es;
	if (es == '1') {
		var stripeSymbol = jQuery('#stripe-input').data('symbol');
	}
	var eb = memberdeck_eb;
	var idset = jQuery("#payment-form #stripe-input").data('idset');
	var curSymbol = jQuery(".currency-symbol").eq(0).text();
	var txnType = jQuery("#payment-form").data('txn-type');
	var scpk = jQuery("#payment-form").data('scpk');
	var claim_paypal = jQuery("#payment-form").data('claimedpp');
	if (type == 'recurring') {
		var recurring = jQuery("#payment-form").data('recurring');
		jQuery('#payment-form #pay-with-balanced').remove();
		no_methods();
	}
	if (isFree == 'free') {
		jQuery("#payment-form #id-main-submit").text("Continue");
	}
	else if (jQuery('#payment-form .pay_selector').length > 1) {
		jQuery("#payment-form #id-main-submit").text("Choose Payment Method");
		jQuery("#payment-form #id-main-submit").attr("disabled", "disabled");
	}
	else {
		jQuery('#payment-form .pay_selector').hide();
		jQuery("#id-main-submit").removeAttr("disabled");
		if (epp == 1) {
			jQuery("#payment-form #id-main-submit").text("Pay with Paypal");
			jQuery("#payment-form #id-main-submit").attr("name", "submitPaymentPaypal");
			if (type == 'recurring') {
				jQuery("#ppload").load(memberdeck_pluginsurl + '/templates/_ppSubForm.php');
			}
			else {
				jQuery("#ppload").load(memberdeck_pluginsurl + '/templates/_ppForm.php');
			}
			jQuery("#payment-form #finaldescPayPal").show();
		}
		else {
			jQuery("#payment-form #pay-with-paypal").remove();
			jQuery("#payment-form #id-main-submit").text("Complete Checkout");
			jQuery("#payment-form #finaldescStripe").show();
			jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
			if (idset !== '1') {
				jQuery("#payment-form #stripe-input").show();
				jQuery(".pw").parents('.form-row').show();
				jQuery(".cpw").parents('.form-row').show();
			}
			if (jQuery('#payment-form .pay_selector').attr('id') == 'pay-with-stripe') {
				jQuery("#payment-form #id-main-submit").attr("name", "submitPaymentStripe");
				jQuery('.currency-symbol').text(stripeSymbol);
			}
			else if (jQuery('#payment-form .pay_selector').attr('id') == 'pay-with-balanced') {
				jQuery("#payment-form #id-main-submit").attr("name", "submitPaymentBalanced");
			}
		}
		no_methods();
	}
	if (txnType == 'preauth') {
		jQuery("#id-main-submit").removeAttr("disabled");
		jQuery("#pay-with-paypal").remove();
		jQuery("#id-main-submit").text("Complete Checkout");
		if (es == 1) {
			jQuery("#id-main-submit").attr("name", "submitPaymentStripe");
		}
		else if (eb == 1) {
			jQuery("#id-main-submit").attr("name", "submitPaymentBalanced");
		}
		if (!idset) {
			jQuery("#stripe-input").show();
			jQuery(".pw").parents('.form-row').show();
			jQuery(".cpw").parents('.form-row').show();
		}
		jQuery("#finaldescStripe").show();
		jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
	}
	// When Stripe Button is Clicked
	jQuery("#payment-form #pay-with-stripe").click(function(e) {
		e.preventDefault();
		if (curSymbol !== stripeSymbol) {
			jQuery('.currency-symbol').text(stripeSymbol);
		}
		jQuery("#id-main-submit").removeAttr("disabled");
		if (type == 'recurring') {
			jQuery("#ppload").unload(memberdeck_pluginsurl + '/templates/_ppSubForm.php');
		}
		else {
			jQuery("#ppload").unload(memberdeck_pluginsurl + '/templates/_ppForm.php');
		}
		if (!idset) {
			jQuery("#stripe-input").show();
			jQuery(".pw").parents('.form-row').show();
			jQuery(".cpw").parents('.form-row').show();
			jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
		}
		jQuery("#id-main-submit").attr("name", "submitPaymentStripe");
		jQuery("#id-main-submit").text("Complete Checkout");
		jQuery(".pay_selector").removeClass('active');
		jQuery(this).addClass("active");
		jQuery("#finaldescStripe").show();
		jQuery("#finaldescPayPal").hide();
	});
	// When Balanced Button is Clicked
	jQuery("#payment-form #pay-with-balanced").click(function(e) {
		e.preventDefault();
		if (curSymbol !== '$') {
			jQuery('.currency-symbol').text('$');
		}
		jQuery("#id-main-submit").removeAttr("disabled");
		if (type == 'recurring') {
			jQuery("#ppload").unload(memberdeck_pluginsurl + '/templates/_ppSubForm.php');
		}
		else {
			jQuery("#ppload").unload(memberdeck_pluginsurl + '/templates/_ppForm.php');
		}
		if (!idset) {
			jQuery("#stripe-input").show();
			jQuery(".pw").parents('.form-row').show();
			jQuery(".cpw").parents('.form-row').show();
			jQuery(".card-number, .card-cvc, card-expiry-month, card-expiry-year").addClass("required");
		}
		jQuery("#id-main-submit").attr("name", "submitPaymentBalanced");
		jQuery("#id-main-submit").text("Complete Checkout");
		jQuery(".pay_selector").removeClass('active');
		jQuery(this).addClass("active");
		jQuery("#finaldescStripe").show();
		jQuery("#finaldescPayPal").hide();
	});
	// When Paypal Button is Clicked
	jQuery("#payment-form #pay-with-paypal").click(function(e) {
		e.preventDefault();
		if (jQuery('.currency-symbol').eq(0) !== curSymbol) {
			jQuery('.currency-symbol').text(curSymbol);
		}
		jQuery("#id-main-submit").text("Pay with Paypal");
		jQuery("#id-main-submit").attr("name", "submitPaymentPaypal");
		jQuery("#id-main-submit").removeAttr("disabled");
		if (type == 'recurring') {
			jQuery("#ppload").load(memberdeck_pluginsurl + '/templates/_ppSubForm.php');
		}
		else {
			jQuery("#ppload").load(memberdeck_pluginsurl + '/templates/_ppForm.php');
		}
		
		jQuery("#stripe-input").hide();
		jQuery(".pw").parents('.form-row').hide();
		jQuery(".cpw").parents('.form-row').hide();
		jQuery(".pay_selector").removeClass('active');
		jQuery(this).addClass("active");
		jQuery("#finaldescPayPal").show();
		jQuery("#finaldescStripe").hide();
        jQuery(".card-number, .card-cvc, .card-expiry-month, .card-expiry-year").removeClass("required");
	});

	function no_methods() {
		if (jQuery('#payment-form .pay_selector').length < 1) {
			if (isFree !== 'free') {
				jQuery('#payment-form #id-main-submit').text('No Payment Options Available').attr('disabled', 'disabled');
			}
		}
	}

	function check_email() {
		var email = jQuery("#payment-form .email").val();
		//console.log(email);
		jQuery.ajax({
			url: memberdeck_ajaxurl,
			type: 'POST',
			data: {action: 'idmember_check_email', Email: email},
			success: function(res) {
				console.log(res);
				var	json = JSON.parse(res);
				//console.log(json);
				var response = json.response;
				if (!logged && response == 'exists') {
					jQuery(".payment-errors").html("<span id=\"email-error\">Email already exists<br>Please <a href=\"" + document.URL + "?login_form=1\">Login</a></span>");
					jQuery("#id-main-submit").removeAttr("disabled");
				}
				else {
					jQuery(".payment-errors").html("");
					if (isFree !== 'free') {
						//console.log('not free');
						processPayment();
					}
					else {
						//console.log('free');
						processFree();
					}
				}
			}
		});
	}
	jQuery("#id-main-submit").click(function(e) {
		if (es == '1' && isFree !== 'free') {
			if (scpk.length > 1) {
				memberdeck_pk = scpk;
			}
			if (jQuery('.pay_selector').length > 1) {
				if (jQuery('#pay-with-stripe').hasClass('active')) {
					Stripe.setPublishableKey(memberdeck_pk);
				}
			}	
			else {
				Stripe.setPublishableKey(memberdeck_pk);
			}
		}
		e.preventDefault();
		jQuery("#id-main-submit").attr("disabled", "disabled");
		var fname = jQuery(".first-name").val();
		var lname = jQuery(".last-name").val();
		var email = jQuery("#payment-form .email").val();
		
		var pw = jQuery(".pw").val();
		var cpw = jQuery(".cpw").val();
		var pid = jQuery("#payment-form").data('product');
		if (!logged) {
			if (jQuery('.pw').is(':visible')) {
				if (pw !== cpw) {
					jQuery(".payment-errors").text("Passwords do not match");
					jQuery("#id-main-submit").removeAttr("disabled");
					jQuery("#id-main-submit").text("Continue");
					var error = true;
				}
				else if (fname.length < 1 || lname.length < 1 || pw.length < 5 || validateEmail(email) == false) {
					jQuery(".payment-errors").append("Please complete all fields and ensure password 5+ characters.");
					jQuery("#id-main-submit").removeAttr("disabled");
					jQuery("#id-main-submit").text("Continue");
					var error = true;
				}
			}
			else {
				if (fname.length < 1 || lname.length < 1 || validateEmail(email) == false) {
					jQuery(".payment-errors").append("Please complete all fields.");
					jQuery("#id-main-submit").removeAttr("disabled");
					jQuery("#id-main-submit").text("Continue");
					var error = true;
				}
			}
		}

		if (error) {
			return false;
		}
		else {
			check_email();
		}
	});
	function processFree() {
		var fname = jQuery(".first-name").val();
		var lname = jQuery(".last-name").val();
		var email = jQuery("#payment-form .email").val();
		var pw = jQuery(".pw").val();
		var cpw = jQuery(".cpw").val();
		var pid = jQuery("#payment-form").data('product');
		var customer = ({'product_id': pid,
					    	'first_name': fname,
							'last_name': lname,
							'email': email,
							'pw': pw});
		//console.log(customer);
        jQuery.ajax({
	    	url: memberdeck_ajaxurl,
	    	type: 'POST',
	    	data: {action: 'idmember_free_product', Customer: customer},
	    	success: function(res) {
	    		//console.log(res);
	    		json = JSON.parse(res);
	    		if (json.response == 'success') {
	    			var product = json.product;
	    			window.location = memberdeck_siteurl + "/dashboard/?product=" + product;
	    		}
	    	}
		});
	}
	function processPayment() {
		var extraFields = jQuery('#extra_fields input');
		var fields = {'posts': {}};
		jQuery.each(extraFields, function(x, y) {
			var name = jQuery(this).attr('name');
			value = jQuery(this).val();
			fields.posts[x] = {};
			fields.posts[x].name = name;
			fields.posts[x].value = value;
		});
		var queryString = '';
		jQuery.each(fields.posts, function() {
			queryString = queryString + '&' + this.name + '=' + this.value;
		});
		if (jQuery("#id-main-submit").attr("name") == "submitPaymentStripe") {
			jQuery(".payment-errors").text("");
			jQuery("#id-main-submit").text("Processing...");
			if (!idset) {
				try {
					Stripe.createToken({
			        number: jQuery(".card-number").val(),
			        cvc: jQuery(".card-cvc").val(),
			        exp_month: jQuery(".card-expiry-month").val(),
			        exp_year: jQuery(".card-expiry-year").val()
				    }, stripeResponseHandler);
				}
				catch(e) {
					jQuery('#id-main-submit').removeAttr('disabled');
					jQuery('#id-main-submit').text('Continue Checkout');
					jQuery(".payment-errors").text('There is a problem with your Stripe credentials');
				}
			}
			else {
				//jQuery("#id-main-submit").text("Processing...");
 				var pid = jQuery("#payment-form").data('product');
				var fname = jQuery(".first-name").val();
				var lname = jQuery(".last-name").val();
				var email = jQuery("#payment-form .email").val();
				var pw = jQuery(".pw").val();
				var customer = ({'product_id': pid,
							    	'first_name': fname,
									'last_name': lname,
									'email': email,
									'pw': pw});
				//console.log(customer);
		        jQuery.ajax({
			    	url: memberdeck_ajaxurl,
			    	type: 'POST',
			    	data: {action: 'idmember_create_customer', Source: 'stripe', Customer: customer, Token: 'customer', Fields: fields.posts, txnType: txnType},
			    	success: function(res) {
			    		console.log(res);
			    		json = JSON.parse(res);
			    		if (json.response == 'success') {
			    			var paykey = json.paykey;
			    			var product = json.product;
			    			var orderID = json.order_id;
			    			var userID = json.user_id;
			    			var type = json.type;
			    			var custID = json.customer_id;
			    			jQuery(document).trigger('stripeSuccess', [orderID, custID, userID, product, paykey, fields, type]);
			    			// Code for Custom Goal: Sale
						    //_vis_opt_goal_conversion(201);
						    //_vis_opt_goal_conversion(202);
			    			// set a timeout for 1 sec to allow trigger time to fire
			    			setTimeout(function() {
			    				window.location = memberdeck_siteurl + "/dashboard/?product=" + product + "&paykey=" + paykey + queryString;
			    			}, 1000);
			    		}
			    		else {
			    			jQuery('#id-main-submit').removeAttr('disabled').text('');
			    			var selectedItem = jQuery('.payment-type-selector .active').attr('id');
			    			if (selectedItem == 'pay-with-paypal') {
			    				jQuery('#id-main-submit').text('Pay with Paypal');
			    			}
			    			else {
			    				jQuery('#id-main-submit').text('Continue Checkout');
			    			}
			    			jQuery(".payment-errors").text(json.message);
			    		}
			    	}
				});
			}
		    return false;
		}
		else if (jQuery('#id-main-submit').attr('name') == 'submitPaymentBalanced') {
			// process balanced
			jQuery(".payment-errors").text("");
			jQuery("#id-main-submit").text("Processing...");
			var pid = jQuery("#payment-form").data('product');
			var fname = jQuery(".first-name").val();
			var lname = jQuery(".last-name").val();
			var email = jQuery("#payment-form .email").val();
			var pw = jQuery(".pw").val();
			var creditCardData = {
				card_number: jQuery(".card-number").val(),
				security_code: jQuery(".card-cvc").val(),
			 	expiration_month: jQuery(".card-expiry-month").val(),
				expiration_year: jQuery(".card-expiry-year").val()
			};
			if (!idset) {
				balanced.card.create(creditCardData, balancedCallBack);
			}
			else {
				var pid = jQuery("#payment-form").data('product');
				var fname = jQuery(".first-name").val();
				var lname = jQuery(".last-name").val();
				var email = jQuery("#payment-form .email").val();
				var pw = jQuery(".pw").val();
				var customer = ({'product_id': pid,
							    	'first_name': fname,
									'last_name': lname,
									'email': email,
									'pw': pw});
				jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_create_customer', Source: 'balanced', Customer: customer, Token: 'customer', Fields: fields.posts, txnType: txnType},
				success: function(res) {
					console.log(res);
					json = JSON.parse(res);
					if (json.response == 'success') {
						var paykey = json.paykey;
						var product = json.product;
						var orderID = json.order_id;
						var userID = json.user_id;
						var type = json.type;
						var custID = json.customer_id;
						jQuery(document).trigger('stripeSuccess', [orderID, custID, userID, product, paykey, fields, type]);
						// Code for Custom Goal: Sale
					    //_vis_opt_goal_conversion(201);
					    //_vis_opt_goal_conversion(202);
						// set a timeout for 1 sec to allow trigger time to fire
						setTimeout(function() {
							window.location = memberdeck_siteurl + "/dashboard/?product=" + product + "&paykey=" + paykey + queryString;
						}, 1000);
					}
					else {
						jQuery('#id-main-submit').removeAttr('disabled').text('');
						var selectedItem = jQuery('.payment-type-selector .active').attr('id');
						if (selectedItem == 'pay-with-paypal') {
							jQuery('#id-main-submit').text('Pay with Paypal');
						}
						else {
							jQuery('#id-main-submit').text('Continue Checkout');
						}
						jQuery(".payment-errors").text(json.message);
					}
				}
				});
			}
		}
		else {
			//console.log('paypal');
			jQuery("#id-main-submit").text("Processing...");
			var cCode = jQuery('#payment-form').data('currency-code');
			var fname = jQuery(".first-name").val();
			var lname = jQuery(".last-name").val();
			var email = jQuery("#payment-form .email").val();
			var pw = jQuery(".pw").val();
			var cpw = jQuery(".cpw").val();
			var pid = jQuery("#payment-form").data('product');
			jQuery.ajax({
		    	url: memberdeck_ajaxurl,
		    	type: 'POST',
		    	data: {action: 'idmember_get_level', Level: pid},
		    	success: function(res) {
		    		//console.log(res);
		    		json = JSON.parse(res);
		    		//console.log(json);
		    		//return false;
		    		if (json) {
		    			//console.log(json);
		    			if (claim_paypal !== null && claim_paypal.length > 1) {
	    					memberdeck_pp = claim_paypal;
	    				}
		    			if (type == 'recurring') {
		    				jQuery('#buyform').attr('action', memberdeck_paypal);
		    				jQuery('#buyform input#pp-price').val(json.level_price);
		    				jQuery('#buyform input[name="currency_code"]').val(cCode);
		    				jQuery('#buyform input#pp-times').val(1);
		    				jQuery('#buyform input#pp-recurring').val('M');
		    				jQuery('#buyform input[name="item_number"]').val(json.id);
		    				jQuery('#buyform input[name="item_name"]').val(json.level_name);
				    		jQuery('#buyform input[name="return"]').val(memberdeck_siteurl + '/?ppsuccess=1');
				    		jQuery('#buyform input[name="cancel_return"]').val(memberdeck_siteurl + '/?ppsuccess=0');
				    		jQuery('#buyform input[name="notify_url"]').val(memberdeck_siteurl + '/?memberdeck_notify=pp&email=' + email + queryString);
				    		jQuery('#buyform input[name="business"]').val(memberdeck_pp);
		    			}
		    			else {
		    				jQuery('#buyform').attr('action', memberdeck_paypal);
		    				jQuery('#buyform input#pp-price').val(json.level_price);
		    				jQuery('#buyform input[name="currency_code"]').val(cCode);
				    		jQuery('#buyform input[name="item_number"]').val(json.id);
		    				jQuery('#buyform input[name="item_name"]').val(json.level_name);
				    		jQuery('#buyform input[name="return"]').val(memberdeck_siteurl + '/?ppsuccess=1');
				    		jQuery('#buyform input[name="cancel_return"]').val(memberdeck_siteurl + '/?ppsuccess=0');
				    		jQuery('#buyform input[name="notify_url"]').val(memberdeck_siteurl + '/?memberdeck_notify=pp&email=' + email + queryString);
				    		jQuery('#buyform input[name="business"]').val(memberdeck_pp);
		    			}
		    			jQuery("#buyform").submit();
		    		}
		    	}
			});
		}
	}
	function stripeResponseHandler(status, response) {
		var extraFields = jQuery('#extra_fields input');
		var fields = {'posts': {}};
		jQuery.each(extraFields, function(x, y) {
			var name = jQuery(this).attr('name');
			value = jQuery(this).val();
			fields.posts[x] = {};
			fields.posts[x].name = name;
			fields.posts[x].value = value;
		});
		var queryString = '';
		jQuery.each(fields.posts, function() {
			queryString = queryString + '&' + this.name + '=' + this.value;
		});
	    if (response.error) {
	        jQuery(".payment-errors").text(response.error.message);
	        jQuery(".submit-button").removeAttr("disabled");
	        jQuery('#id-main-submit').text('Continue Checkout');
	    } else {
	    	jQuery("#id-main-submit").text("Processing...");
	        var formy = jQuery("#payment-form");
	        var token = response["id"];
	        //console.log(token);
	        formy.append('<input type="hidden" name="stripeToken" value="' + token + '"/>');
	        var pid = jQuery("#payment-form").data('product');
			var fname = jQuery(".first-name").val();
			var lname = jQuery(".last-name").val();
			var email = jQuery("#payment-form .email").val();
			var pw = jQuery(".pw").val();
			var customer = ({'product_id': pid,
						    	'first_name': fname,
								'last_name': lname,
								'email': email,
								'pw': pw});
			//console.log(customer);
	        jQuery.ajax({
		    	url: memberdeck_ajaxurl,
		    	type: 'POST',
		    	data: {action: 'idmember_create_customer', Source: 'stripe', Customer: customer, Token: token, Fields: fields.posts, txnType: txnType},
		    	success: function(res) {
		    		console.log(res);
		    		json = JSON.parse(res);
		    		if (json.response == 'success') {
		    			var paykey = json.paykey;
		    			var product = json.product;
		    			var orderID = json.order_id;
			    		var userID = json.user_id;
			    		var type = json.type;
			    		jQuery(document).trigger('stripeSuccess', [orderID, userID, product, paykey, fields, type]);
		    			// Code for Custom Goal: Sale
					    //_vis_opt_goal_conversion(201);
					    //_vis_opt_goal_conversion(202);
		    			// set a timeout for 1 sec to allow trigger time to fire
		    			setTimeout(function() {
		    				window.location = memberdeck_siteurl + "/dashboard/?product=" + product + "&paykey=" + paykey + queryString;
		    			}, 1000);
		    		}
		    		else {
		    			jQuery('#id-main-submit').removeAttr('disabled').text('');
		    			var selectedItem = jQuery('.payment-type-selector .active').attr('id');
		    			if (selectedItem == 'pay-with-paypal') {
		    				jQuery('#id-main-submit').text('Pay with Paypal');
		    			}
		    			else {
		    				jQuery('#id-main-submit').text('Continue Checkout');
		    			}
		    			jQuery(".payment-errors").text(json.message);
		    		}
		    	}
			});
	        //formy.get(0).submit();
	    }
	}
	function balancedCallBack(response) {
		var extraFields = jQuery('#extra_fields input');
		var fields = {'posts': {}};
		jQuery.each(extraFields, function(x, y) {
			var name = jQuery(this).attr('name');
			value = jQuery(this).val();
			fields.posts[x] = {};
			fields.posts[x].name = name;
			fields.posts[x].value = value;
		});
		var queryString = '';
		jQuery.each(fields.posts, function() {
			queryString = queryString + '&' + this.name + '=' + this.value;
		});
		switch (response.status) {
			case 201:
				// WOO HOO! MONEY!
				// response.data.uri == URI of the bank account resource you
				// can store this card URI in your database
				console.log(response.data);
				var form = jQuery("#payment-form");
				// the uri is an opaque token referencing the tokenized card
				var cardTokenURI = response.data['uri'];
				// append the token as a hidden field to submit to the server
				jQuery('<input>').attr({
				type: 'hidden',
				value: cardTokenURI,
				name: 'balancedCreditCardURI'
				}).appendTo(form);

				var pid = jQuery("#payment-form").data('product');
				var fname = jQuery(".first-name").val();
				var lname = jQuery(".last-name").val();
				var email = jQuery("#payment-form .email").val();
				var pw = jQuery(".pw").val();
				var customer = ({'product_id': pid,
							    	'first_name': fname,
									'last_name': lname,
									'email': email,
									'pw': pw});
				jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_create_customer', Source: 'balanced', Customer: customer, Token: response.data['id'], Fields: fields.posts, txnType: txnType},
				success: function(res) {
					console.log(res);
					json = JSON.parse(res);
					if (json.response == 'success') {
						var paykey = json.paykey;
						var product = json.product;
						var orderID = json.order_id;
						var userID = json.user_id;
						var type = json.type;
						var custID = json.customer_id;
						jQuery(document).trigger('stripeSuccess', [orderID, custID, userID, product, paykey, fields, type]);
						// Code for Custom Goal: Sale
					    //_vis_opt_goal_conversion(201);
					    //_vis_opt_goal_conversion(202);
						// set a timeout for 1 sec to allow trigger time to fire
						setTimeout(function() {
							window.location = memberdeck_siteurl + "/dashboard/?product=" + product + "&paykey=" + paykey + queryString;
						}, 1000);
					}
					else {
						jQuery('#id-main-submit').removeAttr('disabled').text('');
						var selectedItem = jQuery('.payment-type-selector .active').attr('id');
						if (selectedItem == 'pay-with-paypal') {
							jQuery('#id-main-submit').text('Pay with Paypal');
						}
						else {
							jQuery('#id-main-submit').text('Continue Checkout');
						}
						jQuery(".payment-errors").text(json.message);
					}
				}
				});
				break;
			case 400:
			 	// missing field - check response.error for details
			 	console.log(response.error);
			 	var message = '';
			 	jQuery.each(response.error, function(k,v) {
			 		message = message + ' ' + v;
			 	});
			 	jQuery(".payment-errors").text(message);
			 	jQuery('#id-main-submit').text('Continue Checkout');
			 	break;
			case 402:
			 	// we couldn't authorize the buyer's credit card
			 	// check response.error for details
			 	console.log(response.error);
			 	var message = '';
			 	jQuery.each(response.error, function(k,v) {
			 		message = message + ' ' + v;
			 	});
			 	jQuery(".payment-errors").text('Card Declined');
			 	jQuery('#id-main-submit').text('Continue Checkout');
			 	break;
			case 404:
				 // your marketplace URI is incorrect
			 	console.log(response.error);
			 	var message = '';
			 	jQuery.each(response.error, function(k,v) {
			 		message = message + ' ' + v;
			 	});
			 	jQuery(".payment-errors").text(message);
			 	jQuery('#id-main-submit').text('Continue Checkout');
			 	break;
			case 500:
			 	// Balanced did something bad, please retry the request
			 	break;
			}
	}
	jQuery("form[name='reg-form']").submit(function(e) {
		e.preventDefault();
		jQuery(".payment-errors").text("");
		jQuery("#id-reg-submit").attr("disabled", "disabled");
		var fname = jQuery(".first-name").val();
		var lname = jQuery(".last-name").val();
		var email = jQuery("#payment-form .email").val();
		var pw = jQuery(".pw").val();
		var cpw = jQuery(".cpw").val();
		var regkey = jQuery("form[name='reg-form']").data('regkey');
		console.log(regkey);
		var update = true;
		if (regkey == undefined || regkey == '') {
			//console.log(uid);
			//jQuery(".payment-errors").text("There was an error processing your registration. Please contact site administrator for assistance");
			update = false;
		}

		if (pw !== cpw) {
			jQuery(".payment-errors").text("Passwords do not match");
			jQuery("#id-reg-submit").removeAttr("disabled");
			var error = true;
		}
		
		if (fname.length < 1 || lname.length < 1 || validateEmail(email) == false || pw.length < 5) {
			jQuery(".payment-errors").append("Please complete all fields and ensure password 5+ characters.");
			jQuery("#id-reg-submit").removeAttr("disabled");
			var error = true;
		}
		console.log('update: ' + update);
		if (error == true) {
			//console.log('error');
			return false;
		}

		else if (update == true) {
			var user = ({'regkey': regkey,
				'first_name': fname,
				'last_name': lname,
				'email': email,
				'pw': pw});
			jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_update_user', User: user},
				success: function(res) {
					console.log(res);
					json = JSON.parse(res);
					if (json.response == 'success') {
						window.location = memberdeck_durl;
					}
					else {
						jQuery(".payment-errors").text("There was an error processing your registration. Please contact site administrator for assistance");
					}
				}
			});
		}
		else {
			var user = ({'first_name': fname,
				'last_name': lname,
				'email': email,
				'pw': pw});
			jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'memberdeck_insert_user', User: user},
				success: function(res) {
					//console.log(res);
					json = JSON.parse(res);
					if (json.response == 'success') {
						window.location = memberdeck_durl;
					}
					else {
						jQuery(".payment-errors").text("There was an error processing your registration. Please contact site administrator for assistance");
					}
				}
			})
		}
	});
	function validateEmail(email) { 
	    var validate = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return validate.test(email);
	}
	jQuery('.inactive').click(function(e) {
		e.preventDefault();
		resetTT();
		var levelID = jQuery(this).data('levelid');
		var pid = jQuery(this).data('pid');
		var infoLink = jQuery(this).parents('.inactive-item').attr('href');
		if (levelID > 0) {
			var offset = jQuery(this).offset();
			var top = offset.top;
			//console.log('top ' + top);
			var left = offset.left;
			var height = jQuery(this).height();
			//console.log('height: ' + height);
			var width = jQuery(this).width();
			var ttHeight = jQuery('.buy-tooltip').height();
			//console.log('ttheight: ' + ttHeight);
			//console.log(top + (height / 2) - (ttHeight));
			var ttWidth = jQuery('.buy-tooltip').width();
			var ttPaddingTop = jQuery('.buy-tooltip').css('padding-top').replace('px', '');
			var ttPaddingLeft = jQuery('.buy-tooltip').css('padding-left').replace('px', '');
			ttTotalTop = ttPaddingTop * 2;
			jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'md_level_data', level_id: levelID},
				success: function(res) {
					//console.log(res);
					json = JSON.parse(res);
					if (json) {
						//console.log(json);
						jQuery('.buy-tooltip').data('levelid', levelID);
						jQuery('.buy-tooltip').data('pid', pid);
						jQuery('.buy-tooltip').show().css('top', top + (height / 2) - ttHeight - ttTotalTop).css('left', left + (width / 2) - (ttWidth / 2) - ttPaddingLeft);
						var tt = jQuery('.buy-tooltip');
						jQuery(tt).find('.tt-product-name').text(json.level_name);
						jQuery(tt).find('.tt-price').text(json.level_price);
						jQuery(tt).find('.tt-credit-value').text(json.credit_value);
						if (json.credit_value > 1) {
							jQuery(tt).find('.credit-text').text('credits');
						}
						else {
							jQuery(tt).find('.credit-text').text('credit');
						}
						jQuery('.tt-more').attr('href', infoLink);
					}
				}
			});
		}
		else {
			window.location.href = infoLink;
		}
	});
	jQuery('.tt-close').click(function(e) {
		e.preventDefault();
		//if (!jQuery('.buy-tooltip').is(':hover') && jQuery('.buy-tooltip').is(':visible')) {
			//console.log('leave');
			resetTT();
		//}
	});
	function resetTT() {
		jQuery('.buy-tooltip').data('levelid', null);
		jQuery('.buy-tooltip').data('pid', null);
		var tt = jQuery('.buy-tooltip');
		jQuery('.buy-tooltip').hide();
		jQuery(tt).find('.tt-product-name').text('');
		jQuery(tt).find('.tt-price').text('');
		jQuery(tt).find('.tt-credit-value').text('');
		jQuery(tt).find('.tt-more').attr('href', '');
	}
	jQuery('.md_occ').click(function(e) {
		e.preventDefault();
		jQuery(this).attr('disabled', 'disabled');
		var payMethod = jQuery('select[name="occ_method"]').val();
		//console.log(payMethod);
		var levelid = jQuery('.buy-tooltip').data('levelid');
		var pid = jQuery('.buy-tooltip').data('pid');
		var fname = jQuery('.md-firstname').text();
		var lname = jQuery('.md-lastname').text();
		var customer = ({'product_id': levelid,
	    	'first_name': fname,
			'last_name': lname});
		var fields = [{'name': 'project_id', 'value': pid}, {'name': 'project_level', 'value': 0}];
		if (payMethod == 'cc') {
			jQuery.ajax({
		    	url: memberdeck_ajaxurl,
		    	type: 'POST',
		    	data: {action: 'idmember_create_customer', Source: null, Customer: customer, Token: 'customer', Fields: fields, txnType: null},
		    	success: function(res) {
		    		console.log(res);
		    		json = JSON.parse(res);
		    		if (json.response == 'success') {
		    			var paykey = json.paykey;
		    			var product = json.product;
		    			var orderID = json.order_id;
		    			var userID = json.user_id;
		    			var type = json.type;
		    			var custID = json.customer_id;
		    			jQuery(document).trigger('stripeSuccess', [orderID, custID, userID, product, paykey, null, type]);
		    			location.reload();	    			
		    		}
		    		else {
		    			jQuery('.md_occ').removeAttr('disabled');
		    		}
		    	}
			});
		}
		else if (payMethod == 'credit') {
			jQuery.ajax({
				url: memberdeck_ajaxurl,
				type: 'POST',
				data: {action: 'md_use_credit', Customer: customer, Token: 'customer'},
				success: function(res) {
					//console.log(res);
					json = JSON.parse(res);
					if (json) {
						//console.log(json);
						if (json.response == 'success') {
			    			var paykey = json.paykey;
			    			var product = json.product;
			    			var orderID = json.order_id;
			    			var userID = json.user_id;
			    			var type = json.type;
			    			var custID = null;
			    			jQuery(document).trigger('creditSuccess', [orderID, custID, userID, product, paykey, null, type]);
			    			location.reload();	    			
			    		}
			    		else {
			    			jQuery('.md_occ').removeAttr('disabled');
			    		}
					}
				}
			});
		}
		else {
			jQuery('.md_occ').removeAttr('disabled');
		}
	});

	/**
	* Bridge js
	*/

	// First, let's apply MemberDeck links to to standard IgnitionDeck widgets
	jQuery.ajax({
		url: memberdeck_ajaxurl,
		type: 'POST',
		data: {action: 'mdid_project_list'},
		success: function(res) {
			//console.log(res);
			json = JSON.parse(res);
			//console.log(json);
			jQuery.each(json, function(k, v) {
				//console.log('k: ' + k + ', v: ' + v);
				jQuery.each(jQuery('.id-widget, #ign-product-levels'), function() {
					var widget = jQuery(this);
					var projectID = jQuery(this).data('projectid');
					if (projectID == v.id) {
						// Let's transform the links
						var fhDecks = jQuery(this).find('.level-binding');
						jQuery.each(fhDecks, function(k, v) {
							var href = jQuery(this).attr('href');
							if (href && href.indexOf('mdid') == -1) {
								var withMD = href.replace('prodid', 'mdid_checkout');
								jQuery(this).attr('href', withMD);
							}
						});
						var deckSource = jQuery(this).attr('id');
						if (deckSource && deckSource.indexOf('ign-product-levels') !== -1) {
							// 500
							jQuery('.ign-supportnow a').click(function(e) {
								e.preventDefault();
								jQuery('html, body').animate({
									scrollTop: jQuery(widget).offset().top
								}, 1000, function() {
									
								});
								jQuery(window).bind('mousewheel', function() {
									jQuery('html, body').stop();
								});
							});
						}
						else {
							jQuery(this).find('.btn-container a').click(function(e) {
								e.preventDefault();
								jQuery('html, body').animate({
									scrollTop: jQuery(widget).offset().top
								}, 1000, function() {
									
								});
								jQuery(window).bind('mousewheel', function() {
									jQuery('html, body').stop();
								});
							});
						}
						/*if (jQuery(this).find('.level-binding').length == 0) {
							//console.log(jQuery(this).find('.level-binding').length);
							jQuery.each(jQuery(this).find('.level-group'), function(k) {
								//console.log(this);
								var level = k + 1;
								jQuery(this).wrap('<a class="level-binding" href="?mdid_checkout=' + v + '&level=' + level + '"/>');
							});
						}*/
					}
				});	
			});
		}
	});
});