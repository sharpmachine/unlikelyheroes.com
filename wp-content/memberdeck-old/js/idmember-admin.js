jQuery(document).ready(function() {
	jQuery("#level-delete").hide();
	jQuery("#credit-delete").hide();
	jQuery("#download-delete").hide();
	if (jQuery('#edit-level').val() == "Choose Level") {
		jQuery('.list-shortcode').hide();
	}
	var bodyClass = jQuery('body').attr('class');
	if (bodyClass.indexOf('memberdeck') !== -1) {
		jQuery('body').addClass('md-admin');
	}
	/*jQuery('input[name="export_customers"]').click(function(e) {
		e.preventDefault();
		export_customers();
	});*/
	function get_levels() {
		jQuery.ajax({
			url: md_ajaxurl,
			type: 'POST',
			data: {action: 'idmember_get_levels'},
			success: function(res) {
				//console.log(res);
				levels = JSON.parse(res);
				//console.log(levels);
				jQuery.each(levels, function() {
					// options for credit dropdown
					jQuery("#credit-assign").append(jQuery("<option/>", {
						value: this.id,
						text: this.level_name
					}));
					// options for level dropdown
					jQuery("#edit-level").append(jQuery("<option/>", {
						value: this.id,
						text: this.level_name
					}));
					// options for level dropdown
					jQuery("#occ_level").append(jQuery("<option/>", {
						value: this.id,
						text: this.level_name
					}));
					// checkboxes for dasbhoard settings
					jQuery("#assign-checkbox").append(jQuery("<input/>", {
						type: 'checkbox',
						name: 'lassign[]',
						class: 'lassign',
						id: 'assign-' + this.id,
						value: this.id
					}));
					jQuery("#assign-checkbox").append(jQuery("<label/>", {
						text: this.level_name,
						for: 'assign-' + this.id
					}));
					jQuery("#assign-checkbox").append("<br/>");
				});
				jQuery("#edit-level").change(function() {
					var leveledit = parseInt(jQuery("#edit-level").val());
					if (jQuery(this).val() == "Choose Level") {
						jQuery('.list-shortcode').hide();
					}
					else {
						jQuery('.list-shortcode').show();
					}
					if (leveledit) {
						//console.log(leveledit);
						jQuery("#level-name").val(levels[leveledit].level_name);
						jQuery("#level-price").val(levels[leveledit].level_price);
						jQuery("#credit-value").val(levels[leveledit].credit_value);
						jQuery("#txn-type").val(levels[leveledit].txn_type);
						jQuery("#level-type").val(levels[leveledit].level_type);
						jQuery("#recurring-type").val(levels[leveledit].recurring_type);
						jQuery("#plan").val(levels[leveledit].plan);
						jQuery('#license-count').val(levels[leveledit].license_count);
						jQuery("#level-submit").val('Update');
						jQuery("#level-delete").show();
						jQuery(".list-shortcode").text('Purchase form shortcode: [memberdeck_checkout product="' + leveledit + '"]');
					}
					else {
						jQuery("#level-name").val('');
						jQuery("#level-price").val('');
						jQuery("#credit-value").val(0);
						jQuery("#txn-type").val('capture');
						jQuery("#plan").val('');
						jQuery('#license-count').val('');
						jQuery("#level-submit").val('Create');
						jQuery("#level-delete").hide();
					}
					var type = jQuery("#level-type").val();
					if (type == 'recurring') {
						jQuery("#recurring-input").show();
					}
					else {
						jQuery("#recurring-input").hide();
					}	
				});
				var type = jQuery("#level-type").val();
				jQuery("#level-type").change(function() {
					type = jQuery("#level-type").val();
					if (type == 'recurring') {
						jQuery("#recurring-input").show();
					}
					else {
						jQuery("#recurring-input").hide();
					}
				});
			}
		});
	}
	function get_downloads() {
		jQuery.ajax({
			url: md_ajaxurl,
			type: 'POST',
			data: {action: 'idmember_get_downloads'},
			success: function(res) {
				//console.log(res);
				var downloads = JSON.parse(res);
				//console.log(downloads);
				jQuery.each(downloads, function() {
					// options for level dropdown
					jQuery("#edit-download").append(jQuery("<option/>", {
						value: this.id,
						text: this.download_name
					}));
				});
				var occEnabled = jQuery(this).attr('checked');
				jQuery('input[name="enable_occ"]').removeAttr('checked');
				mdid_project_list();
				jQuery("#edit-download").change(function() {
					var downloadedit = parseInt(jQuery("#edit-download").val());
					jQuery('.lassign').removeAttr('checked');
					if (downloadedit) {
						jQuery("#download-name").val(downloads[downloadedit].download_name);
						jQuery("#download-version").val(downloads[downloadedit].version);
						if (downloads[downloadedit].enable_occ == '1') {
							jQuery('input[name="enable_occ"]').attr('checked', 'checked');
						}
						else {
							jQuery('input[name="enable_occ"]').removeAttr('checked');
						}
						if (downloads[downloadedit].hidden == '1') {
							jQuery('input[name="hidden"]').attr('checked', 'checked');
						}
						else {
							jQuery('input[name="enable_s3"]').removeAttr('checked');
						}
						if (downloads[downloadedit].enable_s3 == '1') {
							jQuery('input[name="enable_s3"]').attr('checked', 'checked');
						}
						else {
							jQuery('input[name="enable_s3"]').removeAttr('checked');
						}
						jQuery('#occ_level').val(downloads[downloadedit].occ_level);
						jQuery('#id_project').val(downloads[downloadedit].id_project);
						jQuery("#dash-position").val(downloads[downloadedit].position);
						jQuery('#licensed').val(downloads[downloadedit].licensed);
						var levels = downloads[downloadedit].levels;
						jQuery.each(levels, function(k,v) {
							//console.log(v);
							jQuery('.lassign[value="' + v + '"]').attr('checked', 'checked');
						});
						jQuery("#download-link").val(downloads[downloadedit].download_link);
						jQuery("#info-link").val(downloads[downloadedit].info_link);
						jQuery("#doc-link").val(downloads[downloadedit].doc_link);
						jQuery("#image-link").val(downloads[downloadedit].image_link);
						jQuery("#button-text").val(downloads[downloadedit].button_text);
						jQuery("#download-submit").val('Update');
						jQuery("#download-delete").show();
					}
					else {
						jQuery("#download-name").val('');
						jQuery("#download-version").val('');
						jQuery('input[name="enable_s3"]').removeAttr('checked');
						jQuery('input[name="hidden"]').attr('checked', 'checked');
						jQuery('input[name="enable_occ"]').removeAttr('checked');
						jQuery('#occ_level').val(0);
						jQuery('#id_project').val(0);
						jQuery("#dash-position").val('a');
						jQuery('#licensed').val('0');
						//jQuery('.lassign').removeAttr('checked');
						jQuery("#download-link").val('');
						jQuery("#info-link").val('');
						jQuery("#doc-link").val('');
						jQuery("#image-link").val('');
						jQuery("#button-text").val('');
						jQuery("#download-submit").val('Create');
						jQuery("#download-delete").hide();
					}
					toggle_occ_dash();
				});
				jQuery('input[name="enable_occ"]').change(function() {
					toggle_occ_dash();
				});
			}
		});
	}
	function export_customers() {
		jQuery('input[name="export_customers"]').attr('disabled', 'disabled');
		jQuery.ajax({
			url: md_ajaxurl,
			type: 'POST',
			data: {action: 'md_export_customers'},
			success: function(res) {
				console.log(res);
				var url = res;
				if (url !== undefined) {
					//jQuery('input[name="export_customers"]').after('&nbsp;<a href="' + url + '"><button class="button">Download File</button></a>');
					jQuery('input[name="export_customers"]').removeAttr('disabled');
					//window.location.href = url;
				}
			}
		});
	}
	function toggle_occ_dash() {
		occEnabled = jQuery('input[name="enable_occ"]').attr('checked');
		//console.log(occEnabled);
		if (occEnabled == 'checked') {
			jQuery('#occ_level').parents('.form-input').show();
			if (jQuery('#id_project').length > 0) {
				jQuery('#id_project').parents('.form-input').show();
			}
			else {
				jQuery('#id_project').parents('.form-input').hide();
			}
		}
		else {
			jQuery('#occ_level').parents('.form-input').hide();
			jQuery('#id_project').parents('.form-input').hide();
		}
	}
	function mdid_project_list() {
		jQuery.ajax({
			url: md_ajaxurl,
			type: 'POST',
			data: {action: 'mdid_project_list'},
			success: function(res) {
				if (res) {
					json = JSON.parse(res);
					if (json) {
						//console.log(json);
						jQuery.each(json, function() {
							if (jQuery('#id_project option[value="' + this.id + '"]').length <= 0) {
								jQuery('#id_project').append(jQuery('<option/>', {
									value: this.id,
									text: this.product_name
								}));
							}
						});
						
					}
				}
			}
		});
	}
	get_levels();
	get_downloads();
	jQuery.ajax({
		url: md_ajaxurl,
		type: 'POST',
		data: {action: 'idmember_get_credits'},
		success: function(res) {
			//console.log(res);
			credits = JSON.parse(res);
			jQuery.each(credits, function() {
				jQuery("#edit-credit").append(jQuery("<option/>", {
					value: this.id,
					text: this.credit_name
				}));
			});
			jQuery("#edit-credit").change(function() {
				var creditedit = parseInt(jQuery("#edit-credit").val());
				jQuery("#credit-name").val(credits[creditedit].credit_name);
				jQuery("#credit-price").val(credits[creditedit].credit_price);
				jQuery("#credit-count").val(credits[creditedit].credit_count);
				jQuery("#credit-assign").val(credits[creditedit].credit_level);
				jQuery("#credit-submit").val('Update');
				jQuery("#credit-delete").show();
			});
		}
	});
	jQuery('#memberdeck-users td.name-title').click(function(e) {
		e.preventDefault();
		var parent = jQuery(this).parent('tr').attr('id');
		if (parent) {
			id = parent.replace('user-', '');
			jQuery("#user-list, .search-box").hide();
			jQuery("#user-profile").show();
			jQuery.ajax({
				async: false,
				url: md_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_get_profile', ID: id},
				success: function(res) {
					//console.log(res);
					json = JSON.parse(res);
					if (json.shipping_info) {
						jQuery.each(json.shipping_info, function(k, v) {
							//console.log('k: ' + k);
							//console.log('v: ' + v);
							jQuery('#user-profile .' + k).val(v);
						});
					}
					if (json.usermeta) {
						jQuery.each(json.usermeta, function(k, v) {
							//console.log('k: ' + k);
							//console.log('v: ' + v);
							jQuery('#user-profile .' + k).val(v);
						});
					}
					if (json.userdata.data) {
						jQuery.each(json.userdata.data, function(k, v) {
							//console.log('k: ' + k);
							//console.log('v: ' + v);
							jQuery('#user-profile .' + k).val(v);
						});
					}
					jQuery('#confirm-edit-profile').click(function(e) {
						e.preventDefault();
						var new_userdata = {};
						var inputs = jQuery('#user-profile input');
						var error = false;
						//console.log(inputs);
						jQuery.each(jQuery(inputs), function(k,v) {
							//console.log(jQuery(this).attr('name'));
							//console.log('k: ' + k + ' = v: ' + v);
							var inputName = jQuery(this).attr('name');
							if (inputName == 'display_name' || inputName == 'user_email') {
								if (jQuery(this).val().length <= 0) {
									error = true;
								}
							}
							new_userdata[inputName] = jQuery(this).val();
						});
						new_userdata['id'] = id;
						//console.log(new_userdata);
						if (error) {
							jQuery('p.error').remove();
							jQuery('#user-profile').prepend('<p class="error">Error, missing or empty fields.</p>');
						}
						else {
							jQuery.ajax({
								async: false,
								url: md_ajaxurl,
								type: 'POST',
								data: {action: 'idmember_edit_profile', Userdata: new_userdata},
								success: function(res) {	
									//console.log(res);
									location.reload();
								}
							});
						}
					});
				}
			});
		}
		jQuery("#cancel-edit-profile").click(function(e) {
			jQuery(".form-input").html('');
			jQuery("#user-list, .search-box").show();
			jQuery("#user-profile").hide();
			e.preventDefault();
		});
	});
	jQuery("#memberdeck-users td.current-levels").click(function(e) {
		e.preventDefault();
		var parent = jQuery(this).parent('tr').attr('id');
		if (parent) {
			id = parent.replace('user-', '');
			jQuery("#user-list, .search-box").hide();
			jQuery("#edit-user").show();

			jQuery.ajax({
				async: false,
				url: md_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_get_levels'},
				success: function(res) {
					//console.log(res);
					levels = JSON.parse(res);
					//console.log(levels);
					jQuery.each(levels, function() {
						jQuery(".form-input").append(jQuery("<tr class='level" + this.id + "'><th class='check-column'><input type='checkbox' value='" + this.id + "'/></th><td><label>" + this.level_name + "</label></td></tr>"));
					});
				}
			});
			jQuery.ajax({
				url: md_ajaxurl,
				type: 'POST',
				data: {action: 'idmember_edit_user', ID: id},
				success: function(res) {
					//console.log(res);
					var count = jQuery(".form-input input[type='checkbox']").size() - 1;
					json = JSON.parse(res);
					console.log(json);
					if (json.levels.length > 0) {
						for (i = 0; i <= count; i++) {
							jQuery("input[value='" + json.levels[i] + "']").attr('checked', 'checked');
							jQuery('.form-input tr').eq(i).append('<td/><td></td>');
						}
					}
					else {
						for (i = 0; i <= count; i++) {
							jQuery('.form-input tr').eq(i).append('<td/><td>none</td>');
						}
					}
					jQuery.each(json.levels, function(k, v) {
						var lid = v;
						var aid = k;
						if (json.lasts[k]) {
							var edate = json.lasts[k]['e_date'];
							var odate = json.lasts[k]['order_date'];
							var oid = json.lasts[k]['id'];

							if (!edate || edate == undefined || edate == '') {
								edate = 'lifetime';
							}
							else if (edate.indexOf('0000-00-00 00:00:00') !== -1) {
								edate = 'lifetime';
							}
							jQuery(".form-input tr.level" + v).children('td:last-child').prev().text(odate);
							jQuery(".form-input tr.level" + v).children('td:last-child').html('<a data-id="' + oid + '" class="edit-date" href="#">' + edate + '</a>');
						}
					});
					jQuery('.form-input').on('click', '.edit-date', function(e) {
						e.preventDefault();
						var clone = this;
						id = jQuery(this).data('id');
						jQuery(this).replaceWith('<span id="edit-fields"><input type="text" name="edit-date" value="yyyy-mm-dd"/><p class="edit-options"><span class="trash"><a href="#" class="edit-cancel delete">cancel</a></span>&nbsp;|&nbsp;<a href="#" class="lifetime">non-expiring</a></span></p>');
						jQuery('input[name="edit-date"]').datepicker({
							defaultDate: '0000-00-00',
							dateFormat : 'yy-mm-dd',
							onClose: function() {
								var newDate = jQuery(this).val();
								jQuery('span.trash').remove();
								if (newDate !== '' || newDate !== 'yyyy-mm-dd') {
									jQuery(this).replaceWith('<a data-id="' + id + '" class="edit-date date-edited" href="#">' + newDate + '</a>');
								}
								else {
									jQuery(this).replaceWith('<a data-id="' + id + '" class="edit-date date-edited" href="#">0000-00-00</a>');
								}
							}
						});
						jQuery(".edit-cancel").click(function(e) {
							e.preventDefault();
							jQuery('#edit-fields').replaceWith(clone);
						});
						jQuery(".lifetime").click(function(e) {
							e.preventDefault();
							jQuery('p.edit-options').remove();
							jQuery('input[name="edit-date"]').replaceWith('<a data-id="' + id + '" class="edit-date date-edited" href="#">0000-00-00</a>');
						});
					});
				}
			});

			jQuery("#confirm-edit").click(function(e) {
				e.preventDefault();
				var new_levels = [];
				jQuery.each(levels, function() {
					new_levels.push({'level': this.id, 'value': jQuery(".form-input input[value='" + this.id + "']").attr('checked')});
				});
				var new_dates = [];
				jQuery.each(jQuery('.date-edited'), function() {
					var id = jQuery(this).data('id');
					var date = jQuery(this).text();
					new_dates.push({'id': id, 'date': date});
				});
				//console.log(new_levels);
				jQuery.ajax({
					url: md_ajaxurl,
					type: 'POST',
					data: {action: 'idmember_save_user', ID: id, Levels: new_levels, Dates: new_dates},
					success: function(res) {
						console.log(res);
						if (!res) {
							jQuery(".form-input").html('');
							jQuery("#user-list, .search-box").show();
							jQuery("#edit-user").hide();
							window.location = "?page=memberdeck-users";
						}
					}
				});
			});
		}
		jQuery("#cancel-edit").click(function(e) {
			e.preventDefault();
			console.log('this');
			jQuery(".form-input").html('');
			jQuery("#user-list").show();
			jQuery("#edit-user").hide();
		});
	});
	jQuery('#assign-checkbox .select').click(function(e) {
		e.preventDefault();
		jQuery('.lassign').attr('checked', 'checked');
	});
	jQuery('#assign-checkbox .clear').click(function(e) {
		e.preventDefault();
		jQuery('.lassign').removeAttr('checked');
	});
	// Gateway js
	jQuery.getJSON(md_currencies, function(data) {
		jQuery.each(data.currency, function() {
			jQuery('#pp-currency').append('<option value="' + this.code + '" data-symbol="' + this.symbol + '">' + this.code + '</option>');
			var selCurrency = jQuery('#pp-currency').data('selected');
			jQuery('#pp-currency').val(selCurrency);
		});
		jQuery('#pp-currency').change(function() {
			var selSymbol = jQuery(this).find(':selected').data('symbol');
			jQuery('input[name="pp-symbol"]').val(selSymbol);
		});
	});

	/* This code ensures that only one credit card processing gateway can be active at once */

	jQuery('#es').change(function() {
		if (jQuery(this).attr('checked') == 'checked') {
			jQuery('#eb').removeAttr('checked').attr('disabled', 'disabled');
		}
		else {
			jQuery('#eb').removeAttr('disabled');
		}
	});

	jQuery('#eb').change(function() {
		if (jQuery(this).attr('checked') == 'checked') {
			jQuery('#es').removeAttr('checked').attr('disabled', 'disabled');
		}
		else {
			jQuery('#es').removeAttr('disabled');
		}
	});

	/* This code ensures that only one smtp client can be active at once */

	jQuery('#enable_sendgrid').change(function() {
		if (jQuery(this).attr('checked') == 'checked') {
			jQuery('#enable_mandrill').removeAttr('checked').attr('disabled', 'disabled');
		}
		else {
			jQuery('#enable_mandrill').removeAttr('disabled');
		}
	});

	jQuery('#enable_mandrill').change(function() {
		if (jQuery(this).attr('checked') == 'checked') {
			jQuery('#enable_sendgrid').removeAttr('checked').attr('disabled', 'disabled');
		}
		else {
			jQuery('#enable_sendgrid').removeAttr('disabled');
		}
	});

	jQuery.ajax({
		url: md_ajaxurl,
		type: 'POST',
		data: {action: 'md_get_levels'},
		success: function(res) {
			//console.log(res);
			if (res) {
				json = JSON.parse(res);
				jQuery.each(json, function(k,v) {
					jQuery('.md-settings-container #level-list').append('<option value="' + this.id + '">' + this.level_name + '</option>');
				});
			}
		}
	});
	jQuery('#btnProcessPreauth').click(function(e) {
		e.preventDefault();
		jQuery(this).attr('disabled', 'disabled');
		var level = jQuery('#level-list').val();
		jQuery.ajax({
			url: md_ajaxurl,
			type: 'POST',
			data: {action: 'md_process_preauth', Level: level},
			success: function(res) {
				console.log(res);
				json = JSON.parse(res);
				jQuery("#charge-confirm").html('<div id="charge-notice" class="updated fade below-h2" id="message"><p>' + json.counts.success + ' Successful Transactions Processed, ' + json.counts.failures + ' Failed Transactions.</p><a id="close-notice" href="#">Close</a></div>');
	    		jQuery("#close-notice").click(function(event) {
	    			if (jQuery("#charge-notice").is(":visible")) {
	    				jQuery("#charge-notice").hide();
	    			}
	    		});
				jQuery('#btnProcessPreauth').removeAttr('disabled');
			}
		});
	});
});