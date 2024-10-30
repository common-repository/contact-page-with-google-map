<?php
/*
 * This file will handle the dynamic metadata, such as phone numbers, emails, social media, ect
 */

// fill phone metabox
function cpwgm_fill_phone_box() {
    global $post;
    
    $phones = get_post_meta( $post->ID, 'cpwgm_phones', true );
    $phone_item_id = 0;
    
    echo '<p><input type="button" class="button-primary button add_phone_item" value="Add New Number" /></p>';
    
    echo '<div class="cpwgm_phone_wrap">';
    
    if (!empty($phones)) {
        foreach ($phones as $phone) {
            echo '<p class="cpwgm_item_wrap" id="item_' . $phone_item_id . '">';
            echo '<span class="item_label_span">Number Title:</span><input name="cpwgm_phones[' . $phone_item_id . '][label]" type="" class="cpwgm_phone_input cpwgm_phone_input__label" value="' . esc_attr($phone["label"]) . '" />';
            echo '<span class="item_label_span">Number:</span><input name="cpwgm_phones[' . $phone_item_id . '][entry]" type="" class="cpwgm_phone_input cpwgm_phone_input__entry" value="' . esc_attr($phone["entry"]) . '" />';
                echo '<input type="button" value="Remove Number" class="button remove_item" />';
            echo '</p>';
            
            $phone_item_id = $phone_item_id + 1;
        }
    }
        
    echo '</div><!-- .cpwgm_phone_wrap -->';
}

// fill email metabox
function cpwgm_fill_email_box() {
    global $post;
    
    $emails = get_post_meta( $post->ID, 'cpwgm_emails', true );
    $email_item_id = 0;
    
    echo '<p><input type="button" class="button-primary button add_email_item" value="Add New Email" /></p>';
    
    echo '<div class="cpwgm_email_wrap">';
    
    if (!empty($emails)) {
        foreach ($emails as $email) {
            echo '<p class="cpwgm_email_item_wrap" id="item_' . $email_item_id . '">';
                echo '<span class="item_label_span">Email Title:</span><input name="cpwgm_emails[' . $email_item_id . '][label]" type="" class="cpwgm_phone_input cpwgm_phone_input__label" value="' . esc_attr($email["label"]) . '" />';
                echo '<span class="item_label_span">Email:</span><input name="cpwgm_emails[' . $email_item_id . '][entry]" type="email" class="cpwgm_phone_input cpwgm_phone_input__entry" value="' .  esc_attr($email["entry"]) . '" />';
                echo '<input type="button" value="Remove Email" class="button remove_item" />';
            echo '</p>';
            
            $email_item_id = $email_item_id + 1;
        }
    }
    
    echo '</div><!-- .cpwgm_email_wrap -->';
}

// fill social metabox
function cpwgm_fill_social_box() { ?>
    <script>
    var pluginDir = "<?php echo plugins_url() ?>";
    </script><?php
    
    global $post;
    
    // use a nonce to prevent save post (with empty $_POST) on move to trash
    echo '<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="' . wp_create_nonce(plugin_basename(__FILE__) . $post->ID ) . '" />';
    
    $socials = get_post_meta( $post->ID, 'cpwgm_socials', true );
    $social_item_id = 0;  
    
    /*
    echo '<pre>';
    print_r( $socials );
    echo '<hr><hr><hr>';
    print_r( get_post_meta( $post->ID, 'cpwgm_social_icons', true ) );
    echo '</pre>';
    */
    
    echo '<p><input type="button" class="button-primary button add_social_item" value="Add New Social Icon" /></p>';
    
    echo '<div class="cpwgm_social_wrap">';
    
    if (!empty($socials)) {
        foreach ($socials as $social) {
            $social_item_id = $social_item_id + 1;
            
            $social_icon_html = 'none';
            if (!empty($social['file_url'])) {
                $social_icon_html = '<img class="social_icon_preview" height="30" width="30" src="' .  $social['file_url'] . '" />';
            }
                        
            echo '<p class="cpwgm_social_item_wrap" id="item_' . $social_item_id . '">
              <span class="item_label_span">URL: </span><input type="url" class="cpwgm_additional_input cpwgm_additional_input__label" name="cpwgm_socials[' . $social_item_id . '][entry_url]" value="' . $social['entry_url'] . '"/>
              <span class="item_label_span">Icon: </span><input type="file" accept="image/*" class="cpwgm_additional_input cpwgm_additional_input__entry" name="cpwgm_social_icons[' . $social_item_id . ']" />
              <input type="hidden" id="cpwgm_hidden_url" name="cpwgm_socials[' . $social_item_id . '][file_url]" value="' . $social['file_url'] . '" />
              <input type="hidden" id="cpwgm_hidden_file_path" name="cpwgm_socials[' . $social_item_id . '][file_path]" value="' . $social['file_path'] . '" />
              <input type="button" value="Remove Icon" class="button remove_item remove_social_icon" />
              <span class="current_icon_url"><strong>Current Icon: </strong>' . $social_icon_html . '</span>
         </p>';  
            
           // $social_item_id = $social_item_id + 1;
        }
    }
    
    echo '</div><!-- .cpwgm_social_wrap -->';
}

// handles saving the data
function cpwgm_save_contact_page_cpt_dynamic($post_id, $post) {
    
    global $pagenow;
    
    if ($post->post_type != 'contact_page_cpt') {
        return $post_id;
    }
    
    if ($pagenow->action != 'add') {
        if (!wp_verify_nonce($_POST['prevent_delete_meta_movetotrash'], plugin_basename(__FILE__) . $post->ID) ) {
            return $post_id;
        }
    }
    
    if (!current_user_can( 'edit_post', $post->ID)) {
        return $post_id;
    }
    
    // phones
    $phones = ( isset( $_POST['cpwgm_phones'] ) ? $_POST['cpwgm_phones'] : array() );
    array_walk ( $phones, function ( &$value, &$key ) {
        $value['label'] = sanitize_text_field ( $value['label'] );
        $value['entry'] = sanitize_text_field ( $value['entry'] );
    });
    update_post_meta ( $post_id, 'cpwgm_phones', $phones);
    
    // emails
    $emails = ( isset( $_POST['cpwgm_emails'] ) ? $_POST['cpwgm_emails'] : array() );
    array_walk ( $emails, function ( &$value, &$key ) {
        $value['label'] = sanitize_text_field ( $value['label'] );
        $value['entry'] = sanitize_text_field ( $value['entry'] );
    });
    update_post_meta ( $post_id, 'cpwgm_emails', $emails);
    
    // emails
    $adds = ( isset( $_POST['cpwgm_additionals'] ) ? $_POST['cpwgm_additionals'] : array() );
    array_walk ( $adds, function ( &$value, &$key ) {
        $value['label'] = sanitize_text_field ( $value['label'] );
        $value['entry'] = sanitize_text_field ( $value['entry'] );
    });
    update_post_meta ( $post_id, 'cpwgm_additionals', $adds);
    

    ///////////////////////////////// icons here ///////////////////////////////////// 
    
    // Setup the array of supported file types. Accept common image file types, along with icons and bitmaps
    $supported_types = array(
        'image/gif',
        'image/x-icon',
        'image/jpeg',
        'image/pjpeg',
        'image/png'
    );
    
    $old_socials = get_post_meta($post_id, 'cpwgm_socials', true);
    $old_icons = get_post_meta($post_id, 'cpwgm_social_icons', true);
    
    $socials = ( isset( $_POST['cpwgm_socials'] ) ? $_POST['cpwgm_socials'] : array() );
    
    $icon_array = array();
    $socials_array = array();    
    
    if ( empty($socials) && empty($_FILES['cpwgm_social_icons']['name']) ) { // if all empty, delete all
        
        // do nothing, empty array will be saved at the end
        
    } else if (!empty($_FILES['cpwgm_social_icons']['name']) ) { // file inputs exist, save entry urls and check for empty files
        
        $j = 0;
        foreach ($socials as $social) {
            $j++;
            $socials_array[$j]['file_url']  = esc_url_raw( $social['file_url'] );
            $socials_array[$j]['entry_url'] = esc_url_raw( $social['entry_url'] );   
            $socials_array[$j]['file_path'] = sanitize_file_name( $social['file_path'] );
            
            if ( !empty($socials[$j]['file_url']) ) {
                $icon_array[$j] = $old_icons[$j];
            } else {
                $icon_array[$j] = array();
            }                        
        }
        
        $i = 0;
        foreach ($_FILES['cpwgm_social_icons']['name'] as $file) {
            $i++;
            
            if ( empty($_FILES['cpwgm_social_icons']['name'][$i]) ) { // empty file input
                
                if (!empty($socials_array[$i]['file_url'])) { //  empty file input, but data in $_POST
                    
                    // do nothing, array is already set
                    
                } else { // empty file input, and nothing in $_POST
                    $socials_array[$i]['file_url'] = '';
                    $socials_array[$i]['file_path'] = '';
                }                                   
                    
            } else if ( !empty($_FILES['cpwgm_social_icons']['name'][$i]) ) { // there was a file uploaded to the input
                
                $arr_file_type = wp_check_filetype(basename($_FILES['cpwgm_social_icons']['name'][$i]));
                $uploaded_type = $arr_file_type['type'];
                
                if (in_array($uploaded_type, $supported_types)) {
                    
                    if ($_FILES['cpwgm_social_icons']['size'][$i] >= 1000000) {
                        wp_die('The image "' . $_FILES['cpwgm_social_icons']['name'][$i] . '" is over 1MB in size. These images need to be small, as they are only icons, and large images will slow down your website tremendously.');
                    }
                    
                    $upload = wp_upload_bits($_FILES['cpwgm_social_icons']['name'][$i], null, file_get_contents($_FILES['cpwgm_social_icons']['tmp_name'][$i]));
                    
                    if (isset($upload['error']) && $upload['error'] != 0) {
                        wp_die('There was an error uploading the file "' . $_FILES['cpwgm_social_icons']['name'][$i] . '". The error is: ' . $upload['error']);
                    } else {
                        $socials_array[$i]['file_url'] = $upload['url'];
                        $socials_array[$i]['file_path'] = $upload['file'];
                        $icon_array[$i] = $upload;
                    }
                } else {
                    wp_die('One of the files you uploaded is not an image or icon. Please try again using only images or icons.');    
                } // end if file type is supported
            } // end if there was a new file uploaded  
        } // end of foreach item
    }
    
    update_post_meta($post_id, 'cpwgm_social_icons', $icon_array);
    update_post_meta($post_id, 'cpwgm_socials', $socials_array);       
}
add_action('save_post', 'cpwgm_save_contact_page_cpt_dynamic', 2, 2);

?>