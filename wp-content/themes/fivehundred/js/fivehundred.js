jQuery(document).ready(function() {
	jQuery('#menu-header ul li').hover(function() {
		var hovered = jQuery(this).index();
		previous = hovered - 1;
		if (previous >= 0) {
			var sibling = jQuery('#menu-header li:first-child').prev().eq(previous);
			sibling.addClass('border-gone');
		}
	});
	jQuery('#menu-header ul li').mouseleave(function() {
		var hovered = jQuery(this).index();
		previous = hovered - 1;
		if (previous >= 0) {
			sibling = jQuery('#menu-header li').prev().eq(previous);
			sibling.removeClass('border-gone');
		}
	});
	if (jQuery('.filter_choice a').hasClass('active')) {
		if (jQuery('.filter-menu').data('order') == 'DESC') { 
			var direction = 'down';
		}
		else {
			var direction = 'up';
		}
		jQuery('.filter_choice a.active').append('&nbsp;<i class="fa fa-sort-' + direction + '"></i>');
	}
	
	jQuery("#menu-header ul li:has(ul)").addClass("noBottom");
   	jQuery("#menu-header div.menu ul").addClass("defaultMenu");
   	jQuery("#menu-header div.menu ul ul").removeClass("defaultMenu");
   	jQuery("#menu-header ul li:has(ul)").addClass("defaultMenu");

   	jQuery("#menu-header ul.defaultMenu ul.children").css({ display: 'none' });
	jQuery("#menu-header ul.defaultMenu li").hover(function() {
		jQuery(this).find('ul.children')
			.stop(true, true).delay(40).animate({ "height": "show" }, 300 );
			
	}, function(){
		jQuery(this).find('ul.children')
			.stop(true, true).delay(40).animate({ "height": "hide" }, 300 );
	});
	jQuery("#menu-header ul.menu ul.sub-menu").css({ display: 'none' });
	jQuery("#menu-header ul.menu li").hover(function() {
		jQuery(this).find('ul.sub-menu')
			.stop(true, true).delay(40).animate({ "height": "show" }, 300 );
			
	}, function(){
		jQuery(this).find('ul.sub-menu')
			.stop(true, true).delay(40).animate({ "height": "hide" }, 300 );
	});
});