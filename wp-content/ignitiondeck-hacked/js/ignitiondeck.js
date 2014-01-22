jQuery(document).ready(function() {
	jQuery('#share-embed').click(function() {
		jQuery('.social-button').toggle();
		jQuery('.embed-box').toggle();
	});
	// FAQ/UPDATES
	jQuery('.product-dashed-heading').click(function () {
        //console.log('clicked');
        jQuery('#prodfaq').slideToggle('fast', function () {
            if (jQuery('.product-dashed-heading').children('.sign').html() == "+")
                jQuery('.product-dashed-heading').children('.sign').html('-');
            else
                jQuery('.product-dashed-heading').children('.sign').html('+');
        });     
    });
    
    jQuery('.product-dashed-heading1').click(function () {      
        jQuery('#produpdates').slideToggle('fast', function () {
            if (jQuery('.product-dashed-heading1').children('.sign').html() == "+")
                jQuery('.product-dashed-heading1').children('.sign').html('-');
            else
                jQuery('.product-dashed-heading1').children('.sign').html('+');
        }); 
    });
    
    jQuery('.product-dashed-heading2').click(function () {
        jQuery('#prodbuyers').slideToggle('fast', function () {
            if (jQuery('.product-dashed-heading2').children('.sign').html() == "+")
                jQuery('.product-dashed-heading2').children('.sign').html('-');
            else
                jQuery('.product-dashed-heading2').children('.sign').html('+');
        });
    });
	// Purchase form scripts
    var btnName = jQuery("#button_pay_purchase").attr('name');
    var payChoices = jQuery("#payment-choices a").size();
    var level = jQuery('#form_pay').data('level');

    if (payChoices > 1) {
    	jQuery("#button_pay_purchase").attr('disabled', 'disabled');
        jQuery('.pay-choice').click(function(e) {
            e.preventDefault();
            if (jQuery(this).attr('id').indexOf('pay-with-paypal') !== -1) {
                jQuery('#pay-with-paypal').addClass("active");
                jQuery("input.main-btn").attr("name", btnName).val('Pay with Paypal');
                jQuery("#button_pay_purchase").removeAttr('disabled');
            }
            else {
                jQuery('#pay-with-paypal').removeClass("active");
            }
        });
    }
    else {
        jQuery('#form_pay #pay-with-paypal').addClass("active");
        //jQuery("input.main-btn").attr("name", btnName).val('Pay with Paypal');
        jQuery("#form_pay #payment-choices").hide();
    }
    var proj_type = jQuery("#project_type").val();
    if (proj_type) {
	    if (proj_type == 'pwyw') {
	        var price_entry = jQuery("#price_entry").val();
	        jQuery('.preorder-form-product-price').html(price_entry);
	        jQuery('input[name="price"]').val(price_entry);
	        jQuery("#price_entry").change(function() {
	            price_entry = jQuery("#price_entry").val();
	             price_entry = price_entry.replace(/\D/g,'');
	            jQuery('input[name="price"]').val(price_entry);
	            jQuery('.preorder-form-product-price').html(price_entry);
	        });
	        jQuery(".main-btn").click(function(e) {
	            if (price_entry < 0.99) {
	                e.preventDefault();
	                jQuery("#price_entry").addClass("red-border");
	                alert('Please enter a contribution value greater than 0.99.');
	            }
	            else {
	                jQuery("#price_entry").removeClass("red-border");
	            }
	        });
	    }
	    else {
	        jQuery('#level_select').ddslick({
	            selectText: "Choose your level of support",
	            onSelected: function(selectedData){
	                //callback function: do something with selectedData;
	                //console.log(selectedData);
	                price = selectedData['selectedData']['price'];
	                desc = selectedData['selectedData']['description'];
	                selLevel = selectedData['selectedData']['value'];
	                jQuery(document).trigger('levelChange', price);
	                jQuery('.preorder-form-product-price').text(price);
	                jQuery('.id-checkout-level-desc').html(desc);
	                jQuery('input[name="price"]').val(price);
	                jQuery('input[name="desc"]').val(desc);
	                jQuery('input[name="level"]').val(selLevel);
	            }   
	        });
	        if (level && level > 0) {
	        	jQuery('#level_select').ddslick('select', {index: level - 1});
	        }
	        jQuery(".noclick").click(function(e) {
	            e.preventDefault();
	            jQuery("a.dd-option:first-child").select();
	        });
	    
	        /*jQuery('#level_select').change(function () {
	            var price = jQuery(this).find(':selected').attr('price');
	            //alert(price);
	            jQuery('input[name="price"]').val(price);
	            jQuery('.preorder-form-product-price').html(price);
	        });

	        jQuery('#level_select').change(function() {
	            var desc = jQuery(this).find(':selected').attr('desc');
	            //alert(desc);
	            jQuery('input[name="desc"]').val(desc);
	            jQuery('.id-checkout-level-desc').html(desc);
	        });*/
	    }
	}
    jQuery('#message-container .error .close-notification').click(function(e) {
        e.preventDefault();
        jQuery('#message-container').hide();
    });
    jQuery("#button_pay_purchase").click(function(e) {
    	if (btnName.indexOf('Paypal') == -1 && btnName.indexOf('Preapproval') == -1 && btnName.indexOf('Adaptive') == -1 && btnName.indexOf('Popup') == -1) {
        	e.preventDefault();
        }
        var price = parseInt(jQuery('input[name="price"]').val());
        if (price > 0) {
	        var type = jQuery('#form_pay').data('projectType');
	        var level = jQuery('input[name="level"]').val();
	        var post_id = jQuery('#form_pay').data('postid');
	        var project = jQuery('#form_pay').data('projectid');
	        var url = id_siteurl;
	        jQuery(".ign-checkout-button .main-btn").val('Processing...');
	        var widgetClass = jQuery('.id-purchase-form div:first-child').attr('id');
	        var validate = checkIgnitionDeckForm(widgetClass, proj_type, level, post_id, project, url);
	        if (validate == true) {
	            jQuery(document).trigger('validate', true);
	            if (jQuery("#button_pay_purchase").attr('name') == btnName) {
	                return true;
	            }
	        }
	        else {
	            jQuery(".ign-checkout-button .main-btn").val('Continue Checkout');
	            jQuery(document).trigger('validate', false);
	        }
	    }
        return false;
    });
	/* Shortcode Grid Magic */

	if (jQuery('.grid_wrap').length > 0) {
		var wide = parseInt(jQuery('.grid_wrap').data('wide'));
		jQuery('.grid_item:nth-child(' + wide + 'n + ' + wide + ')').css('margin-right', 0);
		jQuery('.grid_item:nth-child(' + wide + 'n + ' + (wide + 1) + ')').css('clear', 'both');
	}
    /*jQuery(document).bind('validate', function(e, validated) {
        if (jQuery("#button_pay_purchase").attr('name') == btnName) {
            console.log('in here');
            jQuery("#form_pay").submit();
            return false;
        }
    });*/

	// IDE //

	jQuery('input[name="project_fesubmit"]').submit(function(e) {
		//e.preventDefault();
		error = false;
		var fes = jQuery('.form-row .required');
		jQuery.each(fes, function() {
			if (jQuery(this).val() == '') {
				error = true;
			}
			else {
				console.log(jQuery(this).val());
			}
		});
		console.log(error);
		if (error == true) {
			return false;
		}
	});
	if (jQuery('.id-fes-form-wrapper').length > 0) {
		jQuery('.id-fes-form-wrapper .date').datepicker({});
	}
	jQuery('#fes input[name="project_levels"]').change(function() {
		var fesLevels = countLevels();
		newLevels = jQuery(this).val();
		levelChange = newLevels - fesLevels;
		formLevel(fesLevels, levelChange);
	});
	var thumbs = jQuery('#fes input[type="file"]');
	jQuery.each(jQuery(thumbs), function(k,v) {
		var url = jQuery(this).data('url');
		if (url && url.length > 0) {
			var name = jQuery(this).attr('name');
			jQuery(this).replaceWith('<span class="image_swap"><img class="project_image" src="' + url + '"/><br/><a name="' + name + '" href="#" class="remove_image">Remove</a></span>');
			jQuery('#fes .remove_image').click(function(e) {
				e.preventDefault();
				var name = jQuery(this).attr('name');
				jQuery(this).parent('.image_swap').replaceWith('<input type="file" name="' + name + '" class="' + name + '"/>');
			});
		}
	});
});
function countLevels() {
	var fesLevels = jQuery('#fes .form-level:visible').length;
	return fesLevels;
}
function formLevel(fesLevels, levelChange) {
	console.log(levelChange);
	if (levelChange < 0) {
		levelChange = Math.abs(levelChange);
		for (i = 1; i <= levelChange; i++) {
			jQuery('#fes .form-level:visible').last().toggle();
		}
	}
	else {
		for (i = 1; i <= levelChange; i++) {
			var clone = jQuery('#fes .form-level').eq(0).clone();
			console.log(jQuery('#fes .form-level:hidden'));
			if (jQuery('#fes .form-level:hidden').length > 0) {
				jQuery('#fes .form-level:hidden').first().toggle();
			}
			else {
				jQuery('#fes .form-level').last().after(clone);
			}
		}
	}
}
function checkIgnitionDeckForm(formId, type, level, post_id, project, url){
	//first lets set price
	var keys = [{
		'level': level,
		'post_id': post_id,
		'project': project}];
	if (type !== 'pwyw') {
		jQuery.ajax({
			url: url + 'wp-admin/admin-ajax.php',
			type: 'POST',
			data: {action: 'id_validate_price', Keys: keys},
			success: function(res) {
				//console.log(res);
				jQuery('input[name="price"]').val(res);
			}
		});
	}
    
    //clear previous results
    jQuery('#'+ formId +' .required').removeClass('red-border');
    jQuery('#'+ formId +' .form-item-error-msg').remove();

    
    var result = true;
    jQuery('#'+ formId +' .required').each(function(){
        if(isEmpty(this)){
        	//console.log(this);
            jQuery(this).addClass('red-border');
            jQuery(this).after('<span class="form-item-error-msg"> required </span>');
            
            if(result){
               result= !result;
            }
        }
    });

    if(!isEmail(jQuery('#'+ formId +' .email').val())){
        jQuery('#'+ formId +' .email').addClass('red-border');
        jQuery('#'+ formId +' .email').after('<span class="form-item-error-msg"> invalid </span>');
        
        if(result){
           result= !result;
        }
    }
    //console.log(result);
    return result;
}

function isEmpty(element){
    if(element.value == ''){
        return true;
    }
    return false;
}

function isEmail(email){
    var emailfilter=/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i;
    return emailfilter.test(email);
}

var app = {
    log: function(mixed){
//        console.log(mixed);
    }
}

app.popupwindow = function(url, title, w, h){
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
}

//ONLOAD function to call after the window has been loaded
window.onload = function () {
	// FOR getting the project number, is called for the ignitiondeck-functions.php when Product
	// is changed in Add Post/Add Page page
	jQuery('#project_id_shortcodes').change(function () {
		var prod_num = jQuery(this).val();
		jQuery.ajax({
			type: "POST",
			url: 'admin-ajax.php',
			data: {action: 'get_product_number', product_id: prod_num},
			success: function(html) {						
				//alert(jQuery.trim(html));
				jQuery('.id-metabox-short-codes .shortcode-content span').html(jQuery.trim(html));
			}
		});
	});
	
	/*jQuery('.insert-project-link').fancybox({
		'width'				: '300px',
		'autoScale'			: false,
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	});*/
	
	//================================================================================================
	// Add New Project page popup for inserting a Project URL
	//================================================================================================
	// For Add New Project page to fill the text box with a link of the post, page or ID project page
	
	jQuery('.post-link-filler').click(function () {
		var page_link = jQuery(this).attr('href');
		//alert("page_link: " + page_link);
		jQuery('[name=link-insert-box]').val(page_link);
		return false;
	});
	
	jQuery('#insert-project-link #search_page').keypress(function () {
		var ajax_url = jQuery('#insert-project-link span[url]').attr('url');
		jQuery.ajax({
			type: "POST",
			url: ajax_url,
			data: "action=" + 'get_pages_links'
			+ "&page_title=" + jQuery(this).val()
			,
			success: function(html) {						
				//alert(jQuery.trim(html));
				jQuery('#insert-project-link .pages-links').html(jQuery.trim(html));
				jQuery('.post-link-filler').click(function () {
					var page_link = jQuery(this).attr('href');
					jQuery('[name=link-insert-box]').val(page_link);
					return false;
				});
			}
		});
	});
	
	jQuery('#insert-project-link #btnTransferLink').click(function () {
		jQuery('.product-url-container').val(jQuery('#link-insert-box').val());
		jQuery.fancybox.close();
	});
	//================================================================================================
};
function storeurladdress() {
   	if(document.getElementById('select_pageurls').value == 'external_url')
   	{
	   	document.getElementById('proj_url_cont').style.display = 'block';
	   	document.getElementById('proj_posts').style.display = 'none';
   	}
   	else if(document.getElementById('select_pageurls').value == 'page_or_post')
   	{
	   	document.getElementById('proj_posts').style.display = 'block';
	   	document.getElementById('proj_url_cont').style.display = 'none';
   	}
   	else if(document.getElementById('select_pageurls').value == 'current_page')
	{
		document.getElementById('proj_posts').style.display = 'none';
	   	document.getElementById('proj_url_cont').style.display = 'none';
	}
}

function storepurchaseurladdress() {
   	if(document.getElementById('select_purchase_pageurls').value == 'external_url')
   	{
	   	document.getElementById('purchase_url_cont').style.display = 'block';
	   	document.getElementById('purchase_posts').style.display = 'none';
   	}
   	else if(document.getElementById('select_purchase_pageurls').value == 'page_or_post')
   	{
	   	document.getElementById('purchase_posts').style.display = 'block';
	   	document.getElementById('purchase_url_cont').style.display = 'none';
   	}
   	else if(document.getElementById('select_purchase_pageurls').value == 'current_page')
	{
		document.getElementById('purchase_posts').style.display = 'none';
	   	document.getElementById('purchase_url_cont').style.display = 'none';
	}
}

function storetyurladdress()
{
   	if(document.getElementById('select_ty_pageurls').value == 'external_url')
   	{
	   	document.getElementById('ty_url_cont').style.display = 'block';
	   	document.getElementById('ty_posts').style.display = 'none';
   	}
   	else if(document.getElementById('select_ty_pageurls').value == 'page_or_post')
   	{
	   	document.getElementById('ty_posts').style.display = 'block';
	   	document.getElementById('ty_url_cont').style.display = 'none';
   	}
   	else if(document.getElementById('select_ty_pageurls').value == 'current_page')
	{
		document.getElementById('ty_posts').style.display = 'none';
	   	document.getElementById('ty_url_cont').style.display = 'none';
	}
}

// Submit form function for Purchasing product
function submitPurchaseForm(ajax_url) {
	//var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
	//jQuery('#submitBtn').trigger('click');
	//return false;
	//jQuery('#btnPayKey').attr('style','background: url("../images/loading.gif") no-repeat scroll top right transparent;');
	jQuery('#btnPayKey').val('Processing Payment...');
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: "action=" + 'get_paypal_paykey'
		+ "&" + jQuery('#form_pay').serialize()
		,
		success: function(html) {						
			//alert(jQuery.trim(html));
			//console.log(jQuery.trim(html));
			reply = jQuery.trim(html).split("|");
			if (reply[0] == "success") {
				//console.log(reply[1]);
				//jQuery('#pay_form_embedded').attr("action",reply[2]);
				jQuery('#paykey').val(reply[1]);
				var dgFlow = new PAYPAL.apps.DGFlow({ trigger: 'submitBtn' });
				jQuery('#submitBtn').trigger('click');
			} else {
				//console.log("Error: "+reply[1]);
				jQuery('#btnPayKey').val('Make Payment');
				jQuery('#message-container').html(	'<div class="notification error">' +
									'<a href="#" class="close-notification" title="Hide Notification" rel="tooltip">x</a>' +
									'<p><strong>Payment Error</strong> '+ reply[1] +'</p>' +
								'</div>');
				jQuery('#message-container').show();
			}
		}
	});
	
	return false;
}