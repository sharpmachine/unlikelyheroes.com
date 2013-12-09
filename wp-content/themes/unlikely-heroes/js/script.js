/* Author: 

*/

// Allows you to use the $ shortcut.  Put all your code  inside this wrapper
jQuery(document).ready(function($) {
	
	// Forces WordPress to place nice with dropdowns
	$("li.dropdown > a").addClass('dropdown-toggle');
	$("li.dropdown > a").attr('data-toggle','dropdown');
	// $("a.dropdown-toggle").append('<i class="fa fa-angle-down"></i>');

	// Add bootstrap pagination class to WordPress pagination
	$("ul.page-numbers").addClass('pagination');

	// HTML Placeholder for IE
	$('[placeholder]').focus(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
			input.val('');
			input.removeClass('placeholder');
		}
	}).blur(function() {
		var input = $(this);
		if (input.val() == '' || input.val() == input.attr('placeholder')) {
			input.addClass('placeholder');
			input.val(input.attr('placeholder'));
		}
	}).blur().parents('form').submit(function() {
		$(this).find('[placeholder]').each(function() {
			var input = $(this);
			if (input.val() == input.attr('placeholder')) {
				input.val('');
			}
		})
	});

	var header = $(".navbar-collapse");
	$(window).scroll(function() {    
		var scroll = $(window).scrollTop();

		if (scroll >= 162) {
			header.removeClass(".navbar-collapse").addClass("navbar-color");
		} else {
			header.removeClass("navbar-color").addClass(".navbar-collapse");
		}
	});
});























