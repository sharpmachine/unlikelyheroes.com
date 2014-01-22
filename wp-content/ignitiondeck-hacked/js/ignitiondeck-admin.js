jQuery(document).ready(function() {
	// edit project page
	var short_desc = 'ign_project_description';
	var long_desc = 'ign_project_long_description';
	var updates = 'ign_updates';
	var disclaimer = 'ign_disclaimer';
	var faq = 'ign_faqs';

	if (jQuery("#product_meta").length > 0) {
		if (jQuery("#wp-content-wrap").hasClass('tmce-active')) {
			tinyMCE.execCommand('mceAddControl', false, short_desc);
			tinyMCE.execCommand('mceAddControl', false, long_desc);
			tinyMCE.execCommand('mceAddControl', false, updates);
			tinyMCE.execCommand('mceAddControl', false, disclaimer);
			tinyMCE.execCommand('mceAddControl', false, faq);
		}
		else {
			tinyMCE.execCommand('mceRemoveControl', false, short_desc);
			tinyMCE.execCommand('mceRemoveControl', false, long_desc);
			tinyMCE.execCommand('mceRemoveControl', false, updates);
			tinyMCE.execCommand('mceRemoveControl', false, disclaimer);
			tinyMCE.execCommand('mceRemoveControl', false, faq);
		}
		//adding a function to enable/disable tiny mce in postmeta
		jQuery('#content-html').click(function() {
			//console.log('html');
			tinyMCE.execCommand('mceRemoveControl', false, short_desc);
			tinyMCE.execCommand('mceRemoveControl', false, long_desc);
			tinyMCE.execCommand('mceRemoveControl', false, updates);
			tinyMCE.execCommand('mceRemoveControl', false, disclaimer);
			tinyMCE.execCommand('mceRemoveControl', false, faq);
		});
		jQuery('#content-tmce').click(function() {
			//console.log('mce');
			tinyMCE.execCommand('mceAddControl', false, short_desc);
			tinyMCE.execCommand('mceAddControl', false, long_desc);
			tinyMCE.execCommand('mceAddControl', false, updates);
			tinyMCE.execCommand('mceAddControl', false, disclaimer);
			tinyMCE.execCommand('mceAddControl', false, faq);
		});
	}
	jQuery('#payment_gateway').change(function() {
		var option2 = jQuery('#payment_gateway').val();

		if (option2 == 'standard_paypal') {
			jQuery('#standard-settings-container').slideDown('fast');
			jQuery('#adaptive-settings-container').slideUp('fast');
			jQuery("#charge-screen").hide();
		}

		else if (option2 == 'adaptive_paypal') {
			jQuery('#standard-settings-container').slideUp('fast');
			jQuery('#adaptive-settings-container').slideDown('fast');
			jQuery("#charge-screen").show();
		}

		else {
			jQuery('#standard-settings-container').slideUp('fast');
			jQuery('#adaptive-settings-container').slideUp('fast');
		}
	});
	var option1 = jQuery('#payment_gateway').val();
	var url = id_ajaxurl;
	if (jQuery('#project-list').length > 0) {
		jQuery.ajax( {
			async: false,
			url: url,
			type: 'POST',
			data: {action: 'idpp_products_handler'},
			success: function(response) {
				//console.log(response);
				json = JSON.parse(response);
				jQuery.each(json, function() {
					jQuery("#project-list").append("<option value=\"" + this.id + "\" data-projid=\"" + this.id + "\">" + this.product_name.replace(/\\/g, '') + "</option>");
				});
			}
		});
	}
	var project = jQuery("#project-list").find(':selected').attr('data-projid');
	jQuery("#project-list").change(function() {
		project = jQuery("#project-list").find(':selected').attr('data-projid');
		//console.log(project);
	});
	//console.log(project);
	jQuery("#btnProcessPP").click(function(e) {
		e.preventDefault();
		jQuery("#btnProcessPP").attr("disabled", "disabled");
		jQuery.ajax( {
	    	url: url,
	    	type: 'POST',
	    	data: {action: 'idpp_process_handler', Project: project},
	    	success: function(response) {
	    		//console.log(response);
	    		json = JSON.parse(response);
	    		//console.log(json);
	    		jQuery("#charge-confirm").html('<div id="charge-notice" class="updated fade below-h2" id="message"><p>' + json.counts.success + ' Successful Transactions Processed, ' + json.counts.failures + ' Failed Transactions.</p><a id="close-notice" href="#">Close</a></div>');
	    		jQuery("#close-notice").click(function(event) {
	    			if (jQuery("#charge-notice").is(":visible")) {
	    				jQuery("#charge-notice").hide();
	    			}
	    		});
	    		jQuery("#btnProcessPP").removeAttr("disabled");
	    	}
	    });
	    return false;
	});

	if (option1 == 'standard_paypal') {
		jQuery('#standard-settings-container').slideDown('fast');
		jQuery('#adaptive-settings-container').slideUp('fast');
	}

	else if (option1 == 'adaptive_paypal') {
		jQuery('#standard-settings-container').slideUp('fast');
		jQuery('#adaptive-settings-container').slideDown('fast');
		jQuery("#charge-screen").show();
	}

	else {
		jQuery('#standard-settings-container').slideUp('fast');
		jQuery('#adaptive-settings-container').slideUp('fast');
	}
	/* Deck Builder */
	if (jQuery('select[name="deck_select"]').length > 0) {
		jQuery.ajax({
			url: url,
			type: 'POST',
			data: {action: 'get_deck_list'},
			success: function(res) {
				//console.log(res);
				json = JSON.parse(res);
				if (json) {
					//console.log(json);
					jQuery.each(json, function(k,v) {
						jQuery('select[name="deck_select"]').append('<option value="' + this.id + '">' + this.id + ': ' + this.attrs.deck_title + '</option>');
					});
					jQuery('select[name="deck_select"]').change(function() {
						var selected = jQuery(this).val();
						if (selected > 0) {
							jQuery.ajax({
								url: url,
								type: 'POST',
								data: {action: 'get_deck_attrs', Deck: selected},
								success: function(res) {
									//console.log(res);
									if (res.length > 0) {
										json = JSON.parse(res);
										if (json) {
											jQuery.each(json, function(k,v) {
												if (v == 1) {
													jQuery('input[name="' + k + '"]').attr('checked', 'checked');
												}
												else {
													jQuery('input[name="' + k + '"]').val(v);
												}
											});
										}
									}
								}
							});
						}
						else {
							jQuery('.deck-attr-text').val('');
							jQuery('.deck-attr').removeAttr('checked');
						}
					});
				}
			}
		});
	}
	jQuery("#manual-switch").click(function(e) {
		e.preventDefault();
		jQuery("#level-select").toggle();
		jQuery("#manual-select").toggle();
	});
	if (jQuery('select[name="product_id"]').length > 0) {
		jQuery('select[name="product_id"]').change(function() {
			get_levels();
		});
		function get_levels() {
			var pid = jQuery('#product_id').val();
			jQuery.ajax({
				type: "POST",
				async: false,
				url: id_ajaxurl,
				data: {action: 'get_product_levels', Project: pid},
				success: function(res) {		
					//console.log(res);				
					jQuery('#product_level').html(jQuery.trim(res));
					var lvlPrice = jQuery('#product_level option:selected').data('price');
					if (lvlPrice == '' || lvlPrice == undefined) {
						lvlPrice = 0;
					}
					jQuery('input[name="prod_price"]').val(lvlPrice)
				}
			});
		};
		jQuery('#product_level').change(function() {
			var lvlPrice = jQuery('#product_level option:selected').data('price');
			if (lvlPrice == '' || lvlPrice == undefined) {
				lvlPrice = 0;
			}
			jQuery('input[name="prod_price"]').val(lvlPrice)
		});
		get_levels();
	}
	jQuery('input[name="btn_generate_code"]').click(function(e) {
		product_number = document.getElementById('product_number').value;
		document.getElementById('embed_code').innerHTML = '<iframe frameBorder="0" scrolling="no" src="'+id_homeurl+'/?ig_embed_widget=1&product_no='+product_number+'" width="214" height="366"></iframe>';
	});
	// Check/Uncheck
	jQuery("#check-all-settings").click(function(e) {
		e.preventDefault();
		jQuery(".main-setting").attr('checked', 'checked');
	});
	jQuery("#clear-all-settings").click(function(e) {
		e.preventDefault();
		jQuery(".main-setting").removeAttr('checked');
	});
	jQuery(".hide-notice").click(function(e) {
		e.preventDefault();
		var notice = jQuery(this).attr('id');
		//console.log(notice);
		jQuery(this).parents('.updated').hide();
		jQuery.ajax({
			url: id_ajaxurl,
			type: 'POST',
			data: {action: 'id_hide_notice', Notice: notice},
			success: function(res) {
				//console.log(res);
			}
		});
	});
	jQuery('#emailSettingsForm input[type="radio"]').click(function() {
		jQuery('#emailSettingsForm input[type="radio"]').removeAttr('checked');
		jQuery(this).attr('checked', 'checked');
	});
});