<?php 
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die;
}

// get each file uploaded as a social media icon for the custom post type and delete it
$ids = array();
$args = array( 'post_type' => 'contact_page_cpt');

$loop = new WP_Query( $args );
while ( $loop->have_posts() ) : $loop->the_post();
    $ids[] = get_the_ID();
endwhile;

if (!empty($ids)) {
    foreach ($ids as $id) {
        $socials = get_post_meta( $post->ID, 'cpwgm_socials', true );
        if (!empty($socials)) {
            foreach ($socials as $social) {
                if (!empty($social['file_path'])) {
                    $was_deleted = unlink($social['file_path']);
                }
            }
        }
    }
}

// delete options the plugin set
/*
delete_option('cpwgm_settings');
delete_option('cpwgm__settings');
delete_option('cpwgm__select_field_0');
delete_option('cpwgm__select_field_1');
delete_option('cpwgm__select_field_2');
delete_option('cpwgm__select_field_3');
delete_option('cpwgm__select_field_4');
delete_option('cpwgm__select_field_5');
delete_option('cpwgm__select_field_6');
delete_option('cpwgm__select_field_7');
delete_option('cpwgm__select_field_8');
*/
?>