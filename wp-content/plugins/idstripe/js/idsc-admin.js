jQuery(document).ready(function() {
	jQuery.ajax({
		url: id_ajaxurl,
		type: 'POST',
		data: {action: 'idstripe_products_handler'},
		success: function(response) {
			json = JSON.parse(response);
			jQuery.each(json, function() {
				jQuery("#project-list").append("<option value=\"" + this.id + "\">" + this.product_name + "</option>");
			});
		}
	});
	var theURL = jQuery("#the-url").text();
	jQuery("#project-list").change(function() {
		var projectid = jQuery(this).val();
		if (projectid >= 1) {
			var projectName = jQuery('#project-list option[value="' + projectid + '"]').text();
			jQuery("#your-url").text(projectName + ' URL');
			jQuery("#the-url").text(theURL + '&state=' + projectid);
		}
		else {
			jQuery("#your-url").text('Your URL');
			jQuery("#the-url").text(theURL);
		}
	});
	jQuery("#button-style").change(function() {
		var button = jQuery("#button-style").val();
		jQuery("#button-display a").removeClass();
		jQuery("#button-display a").addClass(button);
		jQuery("#button-code").val('<a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=' + idsc_clientid + '" class="' + button + '"><span>Connect with Stripe</span></a>');
	});
});