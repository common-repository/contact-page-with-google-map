<?php 
function cpwgm_shortcode_handler($atts, $content) {
    global $wp_query, $post;
    
    $args = array(
        'posts_per_page' => '1',
        'post_type'      => 'contact_page_cpt',
        'post_status'    => 'publish',
        'p'              => $atts['p'],
    );
    
    $the_query= new WP_Query($args);
    
    $output = '<div class="clear"></div><div class="cpwgm">';
    
    // begin main loop
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
             
            $options     = get_option('cpwgm__settings' );
            $show_map    = $options['cpwgm__select_field_0'];
            $map_h       = $options['cpwgm__select_field_1'];
            $show_hours  = $options['cpwgm__select_field_2'];
            //$show_labels = $options['cpwgm__select_field_3'];
            $layout      = $options['cpwgm__select_field_4'];
            $label_style = $options['cpwgm__select_field_5'];
            $entry_style = $options['cpwgm__select_field_6'];
            $show_social = $options['cpwgm__select_field_7'];
            
            $api_key = get_post_meta($post->ID, 'google_api_key', true);
            $title   = get_post_meta($post->ID, 'contact_form_title', true);
            
            //$email    = get_post_meta($post->ID, 'contact_page_email', true);
            //$phone    = get_post_meta($post->ID, 'contact_page_phone', true);
            
            $biz_name = get_post_meta($post->ID, 'contact_page_name', true);
            $street   = get_post_meta($post->ID, 'contact_page_street', true);
            $city     = get_post_meta($post->ID, 'contact_page_city', true);
            $state    = get_post_meta($post->ID, 'contact_page_state', true);
            $zip      = get_post_meta($post->ID, 'contact_page_zip', true);
            $address  = $street . ' ' . $city . ' ' . $state . ' ' . $zip;
            
            if ($show_hours == '1') {
                // is open
                $mday_is_open     = get_post_meta($post->ID, 'mday_is_open', true);
                $tuesday_is_open  = get_post_meta($post->ID, 'tuesday_is_open', true);
                $wday_is_open     = get_post_meta($post->ID, 'wday_is_open', true);
                $thursday_is_open = get_post_meta($post->ID, 'thursday_is_open', true);
                $fday_is_open     = get_post_meta($post->ID, 'fday_is_open', true);
                $saturday_is_open = get_post_meta($post->ID, 'saturday_is_open', true);
                $sunday_is_open   = get_post_meta($post->ID, 'sunday_is_open', true);
                
                // all day
                $mday_allday     = get_post_meta($post->ID, 'mday_allday', true);
                $tuesday_allday  = get_post_meta($post->ID, 'tuesday_allday', true);
                $wday_allday     = get_post_meta($post->ID, 'wday_allday', true);
                $thursday_allday = get_post_meta($post->ID, 'thursday_allday', true);
                $fday_allday     = get_post_meta($post->ID, 'fday_allday', true);
                $saturday_allday = get_post_meta($post->ID, 'saturday_allday', true);
                $sunday_allday   = get_post_meta($post->ID, 'sunday_allday', true);
                
                // hours
                $mday_start	    = get_post_meta($post->ID, 'mday_start', true);
                $mday_end       = get_post_meta($post->ID, 'mday_end', true);
                $tuesday_start	= get_post_meta($post->ID, 'tuesday_start', true);
                $tuesday_end    = get_post_meta($post->ID, 'tuesday_end', true);
                $wday_start	    = get_post_meta($post->ID, 'wday_start', true);
                $wday_end       = get_post_meta($post->ID, 'wday_end', true);
                $thursday_start	= get_post_meta($post->ID, 'thursday_start', true);
                $thursday_end   = get_post_meta($post->ID, 'thursday_end', true);
                $fday_start	    = get_post_meta($post->ID, 'fday_start', true);
                $fday_end       = get_post_meta($post->ID, 'fday_end', true);
                $saturday_start	= get_post_meta($post->ID, 'saturday_start', true);
                $saturday_end   = get_post_meta($post->ID, 'saturday_end', true);
                $sunday_start	= get_post_meta($post->ID, 'sunday_start', true);
                $sunday_end     = get_post_meta($post->ID, 'sunday_end', true);
                
                if ($mday_is_open != 1) {
                    $monday = 'Closed';
                } elseif ($mday_allday == 1) {
                    $monday = 'All Day';
                } else {
                    $monday = $mday_start . ' to ' . $mday_end;
                }
                
                if ($tuesday_is_open != 1) {
                    $tuesday = 'Closed';
                } elseif ($tuesday_allday == 1) {
                    $tuesday = 'All Day';
                } else {
                    $tuesday = $tuesday_start . ' to ' . $tuesday_end;
                }
                
                if ($wday_is_open != 1) {
                    $wednesday = 'Closed';
                } elseif ($wday_allday == 1) {
                    $wednesday = 'All Day';
                } else {
                    $wednesday = $wday_start . ' to ' . $wday_end;
                }
                
                if ($thursday_is_open != 1) {
                    $thursday = 'Closed';
                } elseif ($thursday_allday == 1) {
                    $thursday = 'All Day';
                } else {
                    $thursday = $thursday_start . ' to ' . $thursday_end;
                }
                
                if ($fday_is_open != 1) {
                    $friday = 'Closed';
                } elseif ($fday_allday == 1) {
                    $friday = 'All Day';
                } else {
                    $friday = $fday_start . ' to ' . $fday_end;
                }
                
                if ($saturday_is_open != 1) {
                    $saturday = 'Closed';
                } elseif ($saturday_allday == 1) {
                    $saturday = 'All Day';
                } else {
                    $saturday = $saturday_start . ' to ' . $saturday_end;
                }
                
                if ($sunday_is_open != 1) {
                    $sunday = 'Closed';
                } elseif ($sunday_allday == 1) {
                    $sunday = 'All Day';
                } else {
                    $sunday = $sunday_start . ' to ' . $sunday_end;
                }
            }           
            
            // metadata
            $phones      = get_post_meta( $post->ID, 'cpwgm_phones', true );
            $emails      = get_post_meta( $post->ID, 'cpwgm_emails', true );
            $additionals = get_post_meta( $post->ID, 'cpwgm_additionals', true );
            $socials     = get_post_meta( $post->ID, 'cpwgm_socials', true);
            $icons       = get_post_meta( $post->ID, 'cpwgm_social_icons', true);
            
            // footer
            //$foot = ( get_post_meta($post->ID, 'cpwgm_footer_data' , true ) );
            //$foot = htmlspecialchars_decode( $foot );
            //$foot = do_shortcode($foot, true);
            
            // main content
            $content_post = get_post($post->ID);
            $post_content = $content_post->post_content;
            $post_content = apply_filters('the_content', $post_content);
            $post_content = str_replace(']]>', ']]&gt;', $post_content);
            
            $output .= $post_content;
            
            // which layout are we using
            if ($layout == '0' || ( $show_hours == '0' && $show_social == '0') ) {
                $class = 'col-lg-12 col-md-12 col-sm-12';
            } else {                                    
                $class = 'col-lg-6 col-md-6 col-sm-6';
            }
            
            // google map
			if (!empty($api_key) && $show_map == '1') { 
			    
			    if (is_numeric($map_h) && !empty($map_h)) {
			        $h = $map_h . 'px';
			    } else {
			        $h = '300px';
			    } 
			    
				$output .= '<div id="map" class="zen-contact_page_map">
					<iframe
						width="100%"
						height="' . $h . '"
						frameborder="0" 
						style="border:0; margin-bottom: 1.5rem;"
						src="https://www.google.com/maps/embed/v1/place?key=' . esc_html($api_key) . '&q=' . str_replace(' ', '+', esc_html($street) ) . ',' . str_replace(' ', '+', esc_html($city) ). '+' . esc_html($state) . '" allowfullscreen>
					</iframe>
				</div>';
			} 
			
			$address_label = '<' . $label_style . ' class="dynamic_heading_1">ADDRESS</' . $label_style . '>';
			$phone_label   = '<' . $label_style . ' class="dynamic_heading_1">PHONE</' . $label_style . '>';
			$email_label   = '<' . $label_style . ' class="dynamic_heading_1">EMAIL</' . $label_style . '>';
			$hour_label    = '<' . $label_style . ' class="dynamic_heading_1">HOURS</' . $label_style . '>';
			$social_label  = '<' . $label_style . ' class="dynamic_heading_1">SOCIAL</' . $label_style . '>';
			
			$output .= '<div class="contact-page-details">
					<div class="' . $class . '">
						<div class="sec">
							' . $address_label . '
							<' . $entry_style . '>' . esc_html($biz_name) . '</' . $entry_style . '>
                            <' . $entry_style . '>' . esc_html($street) . '</' . $entry_style . '>
                            <' . $entry_style . '>' . esc_html($city) . ', ' . esc_html($state) . '</' . $entry_style . '>
							<' . $entry_style . '>' . esc_html($zip) . '</' . $entry_style . '>';
			
			if (isset($additionals) && !empty($additionals)) {
				foreach ($additionals as $additional) {
					$output .= '<' . $entry_style . '>' . (!empty($additional['label']) ? esc_html($additional['label']) . ': ' : '' ) . esc_html($additional['entry']) . '</' . $entry_style . '>';
				}
			}
			$output .= '</div>';
			
			// phone numbers
			if (!empty($phones)) {
    			$output .= '<div class="sec">' . $phone_label . '';
    			foreach ($phones as $phone) {
    			    $output .= '<' . $entry_style . '>
                                    ' . (!empty($phone['label']) ? esc_html($phone['label']) . ': ' : '') . '<a href="tel:' . esc_html($phone['entry']) . '">' . esc_html($phone['entry']) . '</a>
                                </' . $entry_style . '>';
    			}			
    			$output.=   '</div>';
			}
			
			// emails
			if (!empty($emails)) {
    			$output .=	'<div class="sec">' . $email_label . '';
    			foreach ($emails as $email) {
    			    $output .= '<' . $entry_style . '>
                                    ' . (!empty($email['label']) ? esc_html($email['label']) . ': ' : '') . '<a href="mailto:' . esc_html($email['entry']) . '">' . esc_html($email['entry']) . '</a>
                                </' . $entry_style . '>';
    			}
    			$output .= '</div>';
			}
					
		    $output .= '</div><!-- .col-6 OR .col-12 -->';
			
		    if ($show_social == 1 || $show_hours == 1) {		        		    
    		    $output .= '<div class="' . $class . '">'; // starts column 2, hours and social media
    		    // hours
    			if ($show_hours == '1') {
    			    $output .= '<div class="sec">
            						' . $hour_label . '
            						<div class="day"><' . $entry_style . '><span class="dayname">Monday: </span>' . esc_html($monday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Tuesday: </span>' . esc_html($tuesday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Wednesday: </span>' . esc_html($wednesday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Thursday: </span>' . esc_html($thursday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Friday: </span>' . esc_html($friday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Saturday: </span>' . esc_html($saturday) . '</' . $entry_style . '></div>
            						<div class="day"><' . $entry_style . '><span class="dayname">Sunday: </span>' . esc_html($sunday) . '</' . $entry_style . '></div>
                                </div>';
    			}
    			
    			// social media
    			if (!empty($socials) && $show_social == 1) {
    			    $output .=	'<div class="sec">' . $social_label . '';
    			    foreach ($socials as $social) {
    			        if (!empty($social['entry_url']) && !empty($social['file_url'])) {
    			            $output .= '<a class="cpwgm_social_link" target="_blank" href="' . esc_url($social['entry_url']) . '">
                                            <img class="cpwgm_social_icon" src="' . esc_url($social['file_url']) . '" />
                                        </a>';
    			        }			        
    			    }
    			    $output .= '</div>';
    			}
    			
    			$output .= '</div><!-- .col-6 OR .col-12 -->';
		    }
						
			$output .= '<div class="clear"></div>
				</div>';
        }
    } else {
        // no posts found
    }
    
    $output .= '<div class="clear"></div>';
    
    wp_reset_query();
    wp_reset_postdata();
    
    return $output . '</div><!-- .cpwgm -->';
}

add_shortcode( 'display_cpwgm', 'cpwgm_shortcode_handler');

?>