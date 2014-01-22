jQuery(document).ready(function() {
	/*var theURL = jQuery("#the-url").text();
	jQuery("#edit-level").change(function() {
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
	});*/
	jQuery("#button-style").change(function() {
		var button = jQuery("#button-style").val();
		jQuery("#button-display a").removeClass();
		jQuery("#button-display a").addClass(button);
		jQuery("#button-code").val('<a class="sc-button" href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=' + md_sc_clientid + '" class="' + button + '"><span>Connect with Stripe</span></a>');
	});
});