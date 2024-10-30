// get a unique ID for the item
function getUniqueID(the_object) {
	var the_ids = [];
    	
    jQuery(the_object).each(function() {
       	id_attr = jQuery(this).attr('id');
       	curr_id = id_attr.substr(id_attr.length - 1);
    	the_ids.push(parseInt(curr_id));
    });
    	
    var i = 1;
    	
    jQuery.each(the_ids, function(index, value) {
    	if (jQuery.inArray(i, the_ids) === -1) {
    		// not found, do nothing
    	} else {
    		// found
    		i = i + 1;
    	}
    });
    	
    return i;
}

jQuery(document).ready(function() {
	
	// add to the main form, otherwise social media icon upload doesn't work
	jQuery('#post').attr('enctype','multipart/form-data');
	
	/*
	 * hours of operation js handlers
	 */
	jQuery(".day").each(function() {
		if (jQuery(this).find(".is_open").is(":checked") != true) {
	        jQuery(this).find(".start").val("Closed").prop("disabled", true);
			jQuery(this).find(".end").val("Closed").prop("disabled", true);
			jQuery(this).find(".allday").prop("disabled", true);
		}
			
		if (jQuery(this).find(".allday").is(":checked") == true) {
			jQuery(this).find(".start").val("All Day").prop("disabled", true);
			jQuery(this).find(".end").val("All Day").prop("disabled", true);
		}
	});
			
	jQuery(".is_open").change(function(){
		var thisdiv = jQuery(this).closest(".day");
		if (jQuery(this).is(":checked") == true){
	        thisdiv.find(".start").val("").prop("disabled", false);
			thisdiv.find(".end").val("").prop("disabled", false);
			thisdiv.find(".allday").prop("disabled", false);
	    } else {
	        thisdiv.find(".start").val("Closed").prop("disabled", true);
			thisdiv.find(".end").val("Closed").prop("disabled", true);
			thisdiv.find(".allday").prop("disabled", true);
			thisdiv.find(".allday").attr("checked", false);
	    }
	});
			
	jQuery(".allday").change(function(){
		var thisdiv = jQuery(this).closest(".day");
		if (jQuery(this).is(":checked") == false){
	        thisdiv.find(".start").val("").prop("disabled", false);
			thisdiv.find(".end").val("").prop("disabled", false);
	    } else {
	        thisdiv.find(".start").val("All Day").prop("disabled", true);
			thisdiv.find(".end").val("All Day").prop("disabled", true);
	    }
	});
	
	// phones
	jQuery(".add_phone_item").live('click', function() {
		var numOfItems = jQuery('.cpwgm_item_wrap').length;
		numOfItems = numOfItems + 1;
		new_itemID = getUniqueID('.cpwgm_item_wrap');
                                    
		jQuery(this).parents('.inside').find('.cpwgm_phone_wrap').append('<p class="cpwgm_item_wrap" id="item_' + new_itemID + '"><span class="item_label_span">Number Title:</span><input name="cpwgm_phones[' + new_itemID + '][label]" type="" class="cpwgm_phone_input cpwgm_phone_input__label" value="" /><span class="item_label_span">Number:</span><input name="cpwgm_phones[' + new_itemID + '][entry]" type="" class="cpwgm_phone_input cpwgm_phone_input__entry" value="" /><input type="button" value="Remove Number" class="button remove_item" /></p>');
	});
	
	// emails
	jQuery(".add_email_item").live('click', function() {
		var numOfItems = jQuery('.cpwgm_email_item_wrap').length;
		numOfItems = numOfItems + 1;
		new_itemID = getUniqueID('.cpwgm_email_item_wrap');
		
		jQuery(this).parents('.inside').find('.cpwgm_email_wrap').append('<p class="cpwgm_email_item_wrap" id="item_' + new_itemID + '"><span class="item_label_span">Email Title:</span><input name="cpwgm_emails[' + new_itemID + '][label]" type="" class="cpwgm_phone_input cpwgm_phone_input__label" value="" /><span class="item_label_span">Email:</span><input name="cpwgm_emails[' + new_itemID + '][entry]" type="email" class="cpwgm_phone_input cpwgm_phone_input__entry" value="" /><input type="button" value="Remove Email" class="button remove_item" /></p>');
	});
	
	// additionals
	jQuery(".add_additional_item").live('click', function() {
		var numOfItems = jQuery('.cpwgm_additional_item_wrap').length;
		numOfItems = numOfItems + 1;
		new_itemID = getUniqueID('.cpwgm_additional_item_wrap');
		
		jQuery(this).parents('.inside').find('.cpwgm_additional_wrap').append('<p class="cpwgm_additional_item_wrap" id="item_' + new_itemID + '"><span class="item_label_span">Label:</span><input name="cpwgm_additionals[' + new_itemID + '][label]" type="" class="cpwgm_additional_input cpwgm_additional_input__label" value="" /><span class="item_label_span">Entry:</span><input name="cpwgm_additionals[' + new_itemID + '][entry]" type="" class="cpwgm_additional_input cpwgm_additional_input__entry" value="" /><input type="button" value="Remove Item" class="button remove_item" /></p>');
	});
	
	// social media icons
	jQuery(".add_social_item").live('click', function() {
		var numOfItems = jQuery('.cpwgm_social_item_wrap').length;
		numOfItems = numOfItems + 1;
		new_itemID = getUniqueID('.cpwgm_social_item_wrap');
		
		jQuery(this).parents('.inside').find('.cpwgm_social_wrap').append('<p class="cpwgm_social_item_wrap" id="item_' + new_itemID + '"><span class="item_label_span">URL: </span><input type="url" class="cpwgm_additional_input cpwgm_additional_input__label" name="cpwgm_socials[' + new_itemID + '][entry_url]" value=""/><span class="item_label_span">Icon: </span><input type="file" accept="image/*" class="cpwgm_additional_input cpwgm_additional_input__entry" name="cpwgm_social_icons[' + new_itemID + ']" /><input type="hidden" id="cpwgm_hidden_url" name="cpwgm_socials[' + new_itemID + '][file_url]" value="" /><input type="hidden" id="cpwgm_hidden_file_path" name="cpwgm_socials[' + new_itemID + '][file_path]" value="" /><input type="button" value="Remove Icon" class="button remove_item remove_social_icon" /><span class="current_icon_url"><strong>Current Icon: </strong>none</span></p>');
	
	});	
	
	// remove single item
    jQuery(".remove_item").live('click', function() {
        jQuery(this).parents('p').remove();
    });
    
    // use ajax to call php to delete the file
    jQuery(".remove_social_icon").live('click', function() {
    	
    	var filepath = jQuery(this).parents('.cpwgm_social_item_wrap').find("#cpwgm_hidden_file_path").val(); // get file path to pass to script    	
    	
    	jQuery.ajax({
    		type: "POST",
    		url: pluginDir + "/contact-page-with-google-map/remove_social_icon.php",
    		data: { file_path: filepath }
    	}).done(function( msg ) {
    		//alert( msg );
    	});    

    });
});