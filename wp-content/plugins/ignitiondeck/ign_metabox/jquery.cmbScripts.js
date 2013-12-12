jQuery(document).ready(function($) {
	 
	// Datepicker
	$('.cmb_datepicker').each(function (){
		$('#' + jQuery(this).attr('id')).datepicker();
		// $('#' + jQuery(this).attr('id')).datepicker({ dateFormat: 'yy-mm-dd' });
		// For more options see http://jqueryui.com/demos/datepicker/#option-dateFormat
	});
	
	var pID = jQuery('#post_ID').val();
	
	// File and image upload handling 
	//-------------------------------------------------------------------------------------------//
	var formfield;
	var uploadStatus = true;
	
	$('.upload_button').click(function() {
		formfield = $(this).prev('input').attr('name');
		tb_show('', 'media-upload.php?post_id=' + pID + 'type=image&cbm_setting=cbm_value&TB_iframe=true');
		return false;
	});
	
	$('.remove_file_button').live('click', function() {
		formfield = $(this).attr('rel');
		$('input.' + formfield).val('');
		$(this).parent().remove();
		return false;
	});
	var type = jQuery('input[name*="ign_project_type"]:checked').val();
		if (type == 'pwyw') {
			jQuery(".new_level").hide();
			jQuery(".new_levels").hide();
		}
		if (type == 'level-based') {
			jQuery(".new_level").show();
			jQuery(".new_levels").show();
		}
	jQuery('input[name*="ign_project_type"]').click(function() {
		type = jQuery('input[name*="ign_project_type"]:checked').val();
		if (type == 'pwyw') {
			jQuery(".new_level").hide();
			jQuery(".new_levels").hide();
		}
		if (type == 'level-based') {
			jQuery(".new_level").show();
			jQuery(".new_levels").show();
		}
	});
	
	/*
	$( 'div#gallery-settings' ).hide();
	$( '.savesend input.button[value*="Insert into Post"], .media-item #go_button' ).attr( 'value', 'Use this File' );
	$( '.savesend a.wp-post-thumbnail' ).hide();
	$( '#media-items .align' ).hide();
	$( '#media-items .url' ).hide();
	*/
	
	window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html) {
		if (formfield) {
			
	        if ( $(html).html(html).find('img').length > 0 ) {
	        	itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
	        } else {
	        	// It's not an image. Get the URL to the file instead.
	        	var htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
	        	itemurl = htmlBits[1]; // Use the URL to the file.
	        	var itemtitle = htmlBits[2];
	        	itemtitle = itemtitle.replace( '>', '' );
	        	itemtitle = itemtitle.replace( '</a>', '' );
	        }
         
			var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
			var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt|psd|eps|ai*)/gi;
			var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
			var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
        
			if (itemurl.match(image)) {
			 	uploadStatus = '<div class="img_status"><img src="'+itemurl+'" alt="" /><a href="#" class="remove_file_button" rel="' + formfield + '">Remove Image</a></div>';
			} else {
			// No output preview if it's not an image
			// Standard generic output if it's not an image.
				html = '<a href="'+itemurl+'" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">'+html+'</span>&nbsp;&nbsp;&nbsp;<a href="#" class="remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			$('.' + formfield).val(itemurl);
			$('.' + formfield).siblings('.cmb_upload_status').slideDown().html(uploadStatus);
			tb_remove();
        
		} else {
			window.original_send_to_editor(html);
		}
		// Clear the formfield value so the other media library popups can work as they are meant to. - 2010-11-11.
		formfield = '';
	}
});