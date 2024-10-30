<?php
/*
 Plugin Name: Contact Page With Google Map
 Description: This plugin will let users quickly and easily create a contact page with the company hours, address, phone number, and an optional Google Map.
 Version:     1.6.1
 Author:      Corporate Zen
 Author URI:  http://www.corporatezen.com/
 License:     GPL3
 License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 Text Domain: zen-contact-page
 Domain Path: /languages
 
Contact Page With Google Map is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
Contact Page With Google Map is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with Contact Page With Google Map. If not, see https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Error: Direct access to this code is not allowed.' );

//require_once 'newsletter.php';
require_once 'shortcode.php';
require_once 'settings_page.php';
require_once 'dynamic_metadata.php';

// de-activate hook
function cpwgm_deactivate_plugin() {
	// clear the permalinks to remove our post type's rules
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'cpwgm_deactivate_plugin' );


// activation hook
function cpwgm_active_plugin() {
	// trigger our function that registers the custom post type
	cpwgm_setup_post_types();
	
	// clear the permalinks after the post type has been registered
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'cpwgm_active_plugin' );


function cpwgm_setup_post_types() {
	$labels = array(
			'name'                => 'Contact Page',
			'singular_name'       => 'Contact Page',
			'menu_name'           => 'Contact Pages',
			'all_items'           => 'All Contact Pages',
			'view_item'           => 'View Contact Page',
			'add_new_item'        => 'Add New Contact Page',
			'add_new'             => 'Add New',
			'edit_item'           => 'Edit Contact Page',
			'update_item'         => 'Update Contact Page',
			'search_items'        => 'Search Contact Pages',
			'not_found'           => 'Not Found',
			'not_found_in_trash'  => 'Not found in Trash'
	);
	
	$args = array(
			'labels'              => $labels,
			'menu_icon'           => 'dashicons-id-alt',
			'description'         => 'Simply enter some information such as address and hours of operation, and include any phone numbers or emails you want to display, and a shortcode is automatically created for you.',
			'public'              => true,
			'publicly_queryable'  => false,
	        'exclude_from_search' => true,
			'show_in_nav_menus'   => true,
			'capability_type'     => 'page',
			'map_meta_cap'        => true,
			'menu_position'       => 20,
			'hierarchical'        => false,
			'rewrite'             => false,
			'query_var'           => false,
			'delete_with_user'    => false,
			'supports'            => array( 'title', 'revisions' ),
			'show_in_rest'        => true,
			'rest_base'           => 'pages',
			'rest_controller_class' => 'WP_REST_Posts_Controller'
	);
	
	register_post_type( 'contact_page_cpt', $args );
}
add_action( 'init', 'cpwgm_setup_post_types' );

// register meta boxes
function cpwgm_add_contact_metaboxes() {
	add_meta_box('cpwgm_details', 'Business Name & Address', 'cpwgm_fill_contact_details_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('cpwgm_google_api_key', 'Google Maps', 'cpwgm_fill_map_box', 'contact_page_cpt', 'side', 'default');
	add_meta_box('cpwgm_phones', 'Phone Numbers', 'cpwgm_fill_phone_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('cpwgm_emails', 'Emails', 'cpwgm_fill_email_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('cpwgm_hours', 'Hours of Operation', 'cpwgm_fill_contact_hours_box', 'contact_page_cpt', 'normal', 'default');
	add_meta_box('cpwgm_social', 'Social Media', 'cpwgm_fill_social_box', 'contact_page_cpt' , 'normal', 'low');
	//add_meta_box('cpwgm_footer', 'Contact Page Footer', 'cpwgm_fill_footer_box', 'contact_page_cpt', 'normal', 'low');	
}
add_action( 'add_meta_boxes', 'cpwgm_add_contact_metaboxes' );

// fill map metabox
function cpwgm_fill_map_box() {
	global $post;
	$key = get_post_meta($post->ID, 'google_api_key', true);
	
	echo 'You can generate your own API key <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">here</a><br><br>';
	echo 'If you are unfamiliar with Google Maps or do not know what an API Key is, you can find out more information here: <a target="_blank" href="https://developers.google.com/maps/faq">FAQ</a><br><br>';
	echo 'Your Google Maps API Key: <input type="text" name="google_api_key" value="' . $key . '" class="widefat" />';
}

// fill contact details metabox
function cpwgm_fill_contact_details_box() {
	global $post;
	
	$biz_name = get_post_meta( $post->ID, 'contact_page_name', true );
	//$email    = get_post_meta( $post->ID, 'contact_page_email', true );
	//$phone    = get_post_meta( $post->ID, 'contact_page_phone', true );
	$address  = get_post_meta( $post->ID, 'contact_page_street', true );
	$city     = get_post_meta( $post->ID, 'contact_page_city', true );
	$state    = get_post_meta( $post->ID, 'contact_page_state', true );
	$zip      = get_post_meta( $post->ID, 'contact_page_zip', true );
	
	// Echo out the field
	echo 'Business Name (to display): <input type="text" name="contact_page_name" value="' . $biz_name. '" class="widefat" />';
	echo 'Street Address: <input type="text" name="contact_page_street" value="' . $address. '" class="widefat" />';
	echo 'City: <input type="text" name="contact_page_city" value="' . $city. '" class="widefat" />';
	echo 'State: <input type="text" name="contact_page_state" value="' . $state . '" class="widefat" />';
	echo 'Zip: <input type="text" pattern="[0-9]+" maxlength="10" name="contact_page_zip" value="' . $zip. '" class="widefat" />';
	//echo 'Phone (xxx-xxx-xxxx): <input type="text" pattern="^\d{3}-\d{3}-\d{4}$" name="contact_page_phone" value="' . $phone. '" class="widefat" />';
	//echo 'Email: <input type="email" name="contact_page_email" value="' . $email. '" class="widefat" />';
	
	$additionals = get_post_meta( $post->ID, 'cpwgm_additionals', true );
	$additional_item_id = 0;
	
	echo '<p><input type="button" class="button-primary button add_additional_item" value="Add New Item" /></p>';
	
	echo '<div class="cpwgm_additional_wrap">';
	
	if (!empty($additionals)) {
	    foreach ($additionals as $additional) {
	        echo '<p class="cpwgm_additional_item_wrap" id="item_' . $additional_item_id . '">';
    	        echo '<span class="item_label_span">Label:</span><input name="cpwgm_additionals[' . $additional_item_id . '][label]" type="" class="cpwgm_additional_input cpwgm_additional_input__label" value="' . esc_attr($additional["label"]) . '" />';
    	        echo '<span class="item_label_span">Entry:</span><input name="cpwgm_additionals[' . $additional_item_id . '][entry]" type="" class="cpwgm_additional_input cpwgm_additional_input__entry" value="' .  esc_attr($additional["entry"]) . '" />';
    	        echo '<input type="button" value="Remove Item" class="button remove_item" />';
	        echo '</p>';
	        
	        $additional_item_id = $additional_item_id + 1;
	    }
	}
	
	echo '</div><!-- .cpwgm_additional_wrap -->';
}

// fill hours metabox
function cpwgm_fill_contact_hours_box() {
	global $post;
	
	// is open
	$mday_is_open     = get_post_meta( $post->ID, 'mday_is_open', true );
	$tuesday_is_open  = get_post_meta( $post->ID, 'tuesday_is_open', true );
	$wday_is_open     = get_post_meta( $post->ID, 'wday_is_open', true );
	$thursday_is_open = get_post_meta( $post->ID, 'thursday_is_open', true );
	$fday_is_open     = get_post_meta( $post->ID, 'fday_is_open', true );
	$saturday_is_open = get_post_meta( $post->ID, 'saturday_is_open', true );
	$sunday_is_open   = get_post_meta( $post->ID, 'sunday_is_open', true );
	
	$mday_open_checked     = ( $mday_is_open == 1 ? 'checked' : '' );
	$tuesday_open_checked  = ( $tuesday_is_open == 1 ? 'checked' : '' );
	$wday_open_checked     = ( $wday_is_open == 1 ? 'checked' : '' );
	$thursday_open_checked = ( $thursday_is_open == 1 ? 'checked' : '' );
	$fday_open_checked     = ( $fday_is_open == 1 ? 'checked' : '' );
	$saturday_open_checked = ( $saturday_is_open == 1 ? 'checked' : '' );
	$sunday_open_checked   = ( $sunday_is_open == 1 ? 'checked' : '' );
	
	// all day
	$mday_allday     = get_post_meta( $post->ID, 'mday_allday', true );
	$tuesday_allday  = get_post_meta( $post->ID, 'tuesday_allday', true );
	$wday_allday     = get_post_meta( $post->ID, 'wday_allday', true );
	$thursday_allday = get_post_meta( $post->ID, 'thursday_allday', true );
	$fday_allday     = get_post_meta( $post->ID, 'fday_allday', true );
	$saturday_allday = get_post_meta( $post->ID, 'saturday_allday', true );
	$sunday_allday   = get_post_meta( $post->ID, 'sunday_allday', true );
	
	$mday_allday_checked     = ( $mday_allday == 1 ? 'checked' : '' );
	$tuesday_allday_checked  = ( $tuesday_allday == 1 ? 'checked' : '' );
	$wday_allday_checked     = ( $wday_allday == 1 ? 'checked' : '' );
	$thursday_allday_checked = ( $thursday_allday == 1 ? 'checked' : '' );
	$fday_allday_checked     = ( $fday_allday == 1 ? 'checked' : '' );
	$saturday_allday_checked = ( $saturday_allday == 1 ? 'checked' : '' );
	$sunday_allday_checked   = ( $sunday_allday == 1 ? 'checked' : '' );
	
	// hours
	$mday_start	    = get_post_meta( $post->ID, 'mday_start', true );
	$mday_end       = get_post_meta( $post->ID, 'mday_end', true );
	$tuesday_start	= get_post_meta( $post->ID, 'tuesday_start', true );
	$tuesday_end    = get_post_meta( $post->ID, 'tuesday_end', true );
	$wday_start	    = get_post_meta( $post->ID, 'wday_start', true );
	$wday_end       = get_post_meta( $post->ID, 'wday_end', true );
	$thursday_start	= get_post_meta( $post->ID, 'thursday_start', true );
	$thursday_end   = get_post_meta( $post->ID, 'thursday_end', true );
	$fday_start	    = get_post_meta( $post->ID, 'fday_start', true );
	$fday_end       = get_post_meta( $post->ID, 'fday_end', true );
	$saturday_start	= get_post_meta( $post->ID, 'saturday_start', true );
	$saturday_end   = get_post_meta( $post->ID, 'saturday_end', true );
	$sunday_start	= get_post_meta( $post->ID, 'sunday_start', true );
	$sunday_end     = get_post_meta( $post->ID, 'sunday_end', true );
	
	echo '<p>Check the days you are open. For those days, enter when you open and when you close. If you are open 24 hours, select the "All Day" option.';
	echo '<div class="day" id="monday"><div class="hours_div"><input class="is_open" name="mday_is_open" id="mday_is_open" type="checkbox" ' . $mday_open_checked . '/> Monday: </div>From <input value="' . $mday_start . '" maxlength="25" class="start" name="mday_start" id="mday_start" type="text"/> To <input value="' . $mday_end . '" maxlength="25" class="end" name="mday_end" id="mday_end" type="text" /> All Day: <input class="allday" name="mday_allday" id="mday_allday" type="checkbox" ' . $mday_allday_checked . ' /></div>';
	echo '<div class="day" id="tuesday"><div class="hours_div"><input class="is_open" name="tuesday_is_open" id="tuesday_is_open" type="checkbox" ' . $tuesday_open_checked. ' /> Tuesday: </div>From <input value="' . $tuesday_start . '" maxlength="25" class="start" name="tuesday_start" id="tuesday_start" type="text" /> To <input value="' . $tuesday_end . '" maxlength="25" class="end" name="tuesday_end" id="tuesday_end" type="text" /> All Day: <input class="allday" name="tuesday_allday" id="tuesday_allday" type="checkbox" ' . $tuesday_allday_checked . ' /></div>';
	echo '<div class="day" id="wednesday"><div class="hours_div"><input class="is_open" name="wday_is_open" id="wday_is_open" type="checkbox" ' . $wday_open_checked. ' /> Wednesday: </div>From <input value="' . $wday_start . '" maxlength="25" class="start" name="wday_start" id="wday_start" type="text" /> To <input value="' . $wday_end . '" maxlength="25" class="end" name="wday_end" id="wday_end" type="text" /> All Day: <input class="allday" name="wday_allday" id="wday_allday" type="checkbox" ' . $wday_allday_checked . ' /></div>';
	echo '<div class="day" id="thursday"><div class="hours_div"><input class="is_open" name="thursday_is_open" id="thursday_is_open" type="checkbox" ' . $thursday_open_checked. ' /> Thursday: </div>From <input value="' . $thursday_start . '" maxlength="25" class="start" name="thursday_start" id="thursday_start" type="text" /> To <input value="' . $thursday_end . '" maxlength="25" class="end" name="thursday_end" id="thursday_end" type="text" /> All Day: <input class="allday" name="thursday_allday" id="thursday_allday" type="checkbox" ' . $thursday_allday_checked . ' /></div>';
	echo '<div class="day" id="friday"><div class="hours_div"><input class="is_open" name="fday_is_open" id="fday_is_open" type="checkbox" ' . $fday_open_checked. ' /> Friday: </div>From <input value="' . $fday_start . '" maxlength="25" class="start" name="fday_start" id="fday_start" type="text" /> To <input value="' . $fday_end . '" maxlength="25" class="end" name="fday_end" id="fday_end" type="text" /> All Day: <input class="allday" name="fday_allday" id="fday_allday" type="checkbox" ' . $fday_allday_checked . ' /></div>';
	echo '<div class="day" id="saturday"><div class="hours_div"><input class="is_open" name="saturday_is_open" id="saturday_is_open" ' . $saturday_open_checked. ' type="checkbox" /> Saturday: </div>From <input value="' . $saturday_start . '" maxlength="25" class="start" name="saturday_start" id="saturday_start" type="text" /> To <input value="' . $saturday_end . '" maxlength="25" class="end" name="saturday_end" id="saturday_end" type="text" /> All Day: <input class="allday" name="saturday_allday" id="saturday_allday" type="checkbox" ' . $saturday_allday_checked . ' /></div>';
	echo '<div class="day" id="sunday"><div class="hours_div"><input class="is_open" name="sunday_is_open" id="sunday_is_open"  ' . $sunday_open_checked. ' type="checkbox" /> Sunday: </div>From <input value="' . $sunday_start . '" maxlength="25" class="start" name="sunday_start" id="sunday_start" type="text" /> To <input value="' . $sunday_end . '" maxlength="25" class="end" name="sunday_end" id="sunday_end" type="text" /> All Day: <input class="allday" name="sunday_allday" id="sunday_allday" type="checkbox" ' . $sunday_allday_checked . ' /></div>';
}

// fill footer metabox
/*
function cpwgm_fill_footer_box( $post ) {
    
    $settings = array(
        'textarea_name' => 'cpwgmfooter'
    );
    
    echo '<p>You can place any text or html in the footer. We recommend using a contact form plugin like Contact Form 7 or Gravity Forms and placing the shortcodes for your contact form(s) here.</p>';
    
    $foot = get_post_meta($post->ID, 'cpwgm_footer_data' , true );
    wp_editor( htmlspecialchars_decode( $foot ), 'cpwgmfooter', $settings);
}
*/

// handles saving the data
function cpwgm_save_contact_page_cpt($post_id, $post) {
    
    if ($post->post_type != 'contact_page_cpt') {
        return $post->ID;
    }
	
	// Is the user allowed to edit the post or page?
    if ( !current_user_can( 'edit_post', $post->ID ) ) {
		return $post->ID;
    }
    
    $meta = array();
    
	// map api key
	$meta['google_api_key'] = ( isset($_POST['google_api_key']) ? $_POST['google_api_key'] : '');
	
	// title and contact form option
	$meta['contact_form_title'] = ( isset($_POST['contact_form_title']) ? $_POST['contact_form_title'] : '');
	$meta['display_form'] = ( isset($_POST['display_form']) ? $_POST['display_form'] : '');
		
	// details
	$meta['contact_page_name']   = ( isset($_POST['contact_page_name']) ? sanitize_text_field( $_POST['contact_page_name'] ) : '');
	//$meta['contact_page_email']  = ( isset($_POST['contact_page_email']) ? sanitize_email( $_POST['contact_page_email'] ) : '');
	//$meta['contact_page_phone']  = ( isset($_POST['contact_page_phone']) ? sanitize_text_field( $_POST['contact_page_phone'] ) : '');
	$meta['contact_page_street'] = ( isset($_POST['contact_page_street']) ? sanitize_text_field( $_POST['contact_page_street'] ) : '');
	$meta['contact_page_city']   = ( isset($_POST['contact_page_city']) ? sanitize_text_field( $_POST['contact_page_city'] ) : '');
	$meta['contact_page_state']  = ( isset($_POST['contact_page_state']) ? sanitize_text_field( $_POST['contact_page_state'] ) : '');
	$meta['contact_page_zip']    = ( isset($_POST['contact_page_zip']) ? sanitize_text_field( $_POST['contact_page_zip'] ) : '');
		
	// is open checkbox
	$meta['mday_is_open']     = ( isset($_POST['mday_is_open']) ? 1 : 0);
	$meta['tuesday_is_open']  = ( isset($_POST['tuesday_is_open']) ? 1 : 0);
	$meta['wday_is_open']     = ( isset($_POST['wday_is_open']) ? 1 : 0);
	$meta['thursday_is_open'] = ( isset($_POST['thursday_is_open']) ? 1 : 0);
	$meta['fday_is_open']     = ( isset($_POST['fday_is_open']) ? 1 : 0);
	$meta['saturday_is_open'] = ( isset($_POST['saturday_is_open']) ? 1 : 0);
	$meta['sunday_is_open']   = ( isset($_POST['sunday_is_open']) ? 1 : 0);
		
	// hours
	$meta['mday_start']	    = ( isset($_POST['mday_start']) ? sanitize_text_field( $_POST['mday_start'] ) : '');
	$meta['mday_end']       = ( isset($_POST['mday_end']) ? sanitize_text_field( $_POST['mday_end'] ) : '');
	$meta['tuesday_start']	= ( isset($_POST['tuesday_start']) ? sanitize_text_field( $_POST['tuesday_start'] ) : '');
	$meta['tuesday_end']    = ( isset($_POST['tuesday_end']) ? sanitize_text_field( $_POST['tuesday_end'] ) : '');
	$meta['wday_start']	    = ( isset($_POST['wday_start']) ? sanitize_text_field( $_POST['wday_start'] ) : '');
	$meta['wday_end']       = ( isset($_POST['wday_end']) ? sanitize_text_field( $_POST['wday_end'] ) : '');
	$meta['thursday_start']	= ( isset($_POST['thursday_start']) ? sanitize_text_field( $_POST['thursday_start'] ) : '');
	$meta['thursday_end']   = ( isset($_POST['thursday_end']) ? sanitize_text_field( $_POST['thursday_end'] ) : '');
	$meta['fday_start']	    = ( isset($_POST['fday_start']) ? sanitize_text_field( $_POST['fday_start'] ) : '');
	$meta['fday_end']       = ( isset($_POST['fday_end']) ? sanitize_text_field( $_POST['fday_end'] ) : '');
	$meta['saturday_start']	= ( isset($_POST['saturday_start']) ? sanitize_text_field( $_POST['saturday_start'] ) : '');
	$meta['saturday_end']   = ( isset($_POST['saturday_end']) ? sanitize_text_field( $_POST['saturday_end'] ) : '');
	$meta['sunday_start']	= ( isset($_POST['sunday_start']) ? sanitize_text_field( $_POST['sunday_start'] ) : '');
	$meta['sunday_end']     = ( isset($_POST['sunday_end']) ? sanitize_text_field( $_POST['sunday_end'] ) : '');
		
	// all day checkboxes
	$meta['mday_allday']     = ( isset($_POST['mday_allday']) ? 1 : 0 );
	$meta['tuesday_allday']  = ( isset($_POST['tuesday_allday']) ? 1 : 0);
	$meta['wday_allday']     = ( isset($_POST['wday_allday']) ? 1 : 0);
	$meta['thursday_allday'] = ( isset($_POST['thursday_allday']) ? 1 : 0);
	$meta['fday_allday']     = ( isset($_POST['fday_allday']) ? 1 : 0);
	$meta['saturday_allday'] = ( isset($_POST['saturday_allday']) ? 1 : 0);
	$meta['sunday_allday']   = ( isset($_POST['sunday_allday']) ? 1 : 0);
		
	foreach ($meta as $key => $value) {
		if ($post->post_type == 'revision')
			return;
				
		$value = implode(',', (array)$value);
				
		if (get_post_meta($post->ID, $key, FALSE)) {
			update_post_meta($post->ID, $key, $value);
		} else {
				add_post_meta($post->ID, $key, $value);
		}
				
		if (!$value)
			delete_post_meta($post->ID, $key);
	}
	
	/*
	if ( !empty($_POST['cpwgmfooter']) ) {
	    $footer_data = htmlspecialchars( $_POST['cpwgmfooter'] );
	    update_post_meta($post_id, 'cpwgm_footer_data', $footer_data );
	}
	*/
}
add_action('save_post', 'cpwgm_save_contact_page_cpt', 1, 2);

add_action('wp_enqueue_scripts', 'cpwgm_enqueue_styles' );
function cpwgm_enqueue_styles() {
	wp_enqueue_style( 'cpwgm_style', plugins_url( '/css/cpwgm_style.css', __FILE__ ));
}

function cpwgm_admin_enqueue($hook) {
    
    global $post;
    
    if ($post->post_type != 'contact_page_cpt') {
        return;
    }
    
    wp_enqueue_style( 'cpwgm_admin_style', plugins_url( '/css/cpwgm_admin.css', __FILE__ ) );
    wp_enqueue_script( 'cpwgm_admin_js', plugins_url( '/js/cpwgm_admin.js', __FILE__ ) );
}
add_action( 'admin_enqueue_scripts', 'cpwgm_admin_enqueue' );

// Add the custom columns to the view all list on the admin side
add_filter( 'manage_contact_page_cpt_posts_columns', 'cpwgm_set_custom_edit_contact_page_cpt_columns' );
function cpwgm_set_custom_edit_contact_page_cpt_columns($columns) {
    $columns['address'] = 'Address';
    $columns['shortcode'] = 'Shortcode <br><i>(use this to display your info anywhere on your site)</i>';
    
    return $columns;
}


// Add the data to the custom columns on the view all list on the admin side
add_action( 'manage_contact_page_cpt_posts_custom_column' , 'cpwgm_custom_contact_page_cpt_column', 10, 2 );
function cpwgm_custom_contact_page_cpt_column( $column, $post_id ) {
    switch ( $column ) {
        
        case 'address' :
            $street   = get_post_meta($post_id, 'contact_page_street', true);
            $city     = get_post_meta($post_id, 'contact_page_city', true);
            $state    = get_post_meta($post_id, 'contact_page_state', true);
            $zip      = get_post_meta($post_id, 'contact_page_zip', true);
            $address  = $street . ' ' . $city . ' ' . $state . ' ' . $zip;
            echo $address;
            break;
            
        case 'shortcode' :
            echo '[display_cpwgm p=' . $post_id . ']';
            break;
    }
}

// delete social media icon files on post delete
add_action( 'before_delete_post', 'cpwgm_delete_files_on_post_delete' );
function cpwgm_delete_files_on_post_delete( $postid ){
           
    // We check if the global post type isn't ours and just return
    global $post_type;
    if ( $post_type != 'contact_page_cpt' ) return;
    
    $socials = get_post_meta( $postid, 'cpwgm_socials', true );
    
    if (!empty($socials)) {
        foreach ($socials as $social) {
            if (!empty($social['file_path'])) {
                $was_deleted = unlink($social['file_path']);
            }
        }
    }
}

?>