jQuery(document).ready(function() {
	var url = id_ajaxurl;
	jQuery.ajax({
		url: url,
		data: {action: 'idstripe_fund_type'},
		success: function(res) {
			jQuery("#fund-type").val(res);
		}
	});
	jQuery.ajax({
		url: url,
		data: {action: 'get_idstripe_currency'},
		success: function(res) {
			//console.log(res);
			jQuery("select[name='currency']").val(res);
		}
	});
	/*jQuery.ajax({
		url: url,
		type: 'POST',
		data: {action: 'idstripe_products_handler'},
		success: function(response) {
			json = JSON.parse(response);
			jQuery.each(json, function() {
				jQuery("#project-list").append("<option projid=\"" + this.id + "\">" + this.product_name + "</option>");
			});
		}
	});*/
	jQuery("#btnProcessStripe").click(function(e) {
		e.preventDefault();
		var project = jQuery("#project-list").find(':selected').data('projid');
		jQuery("#project-list").change(function() {
			project = jQuery("#project-list").find(':selected').data('projid');
		});
		jQuery("#btnProcessStripe").attr("disabled", "disabled");
		jQuery.ajax( {
	    	url: url,
	    	type: 'POST',
	    	data: {action: 'idstripe_process_handler', Project: project},
	    	success: function(response) {
	    		console.log(response);
	    		json = JSON.parse(response);
	    		jQuery("#charge-confirm").html('<div id="charge-notice" class="updated fade below-h2" id="message"><p>' + json.counts.success + ' Successful Transactions Processed, ' + json.counts.failures + ' Failed Transactions.</p><a id="close-notice" href="#">Close</a></div>');
	    		jQuery("#close-notice").click(function(event) {
	    			if (jQuery("#charge-notice").is(":visible")) {
	    				jQuery("#charge-notice").hide();
	    			}
	    		});
	    		jQuery("#btnProcessStripe").removeAttr("disabled");
	    	}
	    });
	    return false;
	});
});