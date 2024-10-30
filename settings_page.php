<?php
/*
 * This file defines the settings and setting-related functions for the settings page
 */

add_action( 'admin_menu', 'cpwgm__add_admin_menu' );
add_action( 'admin_init', 'cpwgm__settings_init' );

function cpwgm__add_admin_menu( ) {
    add_options_page( 'Contact Page With Google Map', 'CPWGM Display Options', 'manage_options', 'cpwgm_settings_page', 'cpwgm__options_page' );
}

function cpwgm__settings_init( ) {
    register_setting( 'cpwgm_settings', 'cpwgm__settings' );
    
    add_settings_section(
        'cpwgm__cpwgm_settings_section',
        __( '', 'cpwgm' ),
        'cpwgm__settings_section_callback',
        'cpwgm_settings'
        );
    
    // define each setting
    add_settings_field(
        'cpwgm__select_field_0',
        __( 'Enable Google Map?', 'cpwgm' ),
        'cpwgm__select_field_0_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    add_settings_field(
        'cpwgm__select_field_1',
        __( 'Google Map Height (in pixels)', 'cpwgm' ),
        'cpwgm__select_field_1_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    add_settings_field(
        'cpwgm__select_field_2',
        __( 'Enable Hours of Operation?', 'cpwgm' ),
        'cpwgm__select_field_2_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    add_settings_field(
        'cpwgm__select_field_7',
        __( 'Enable Social Media Icons?', 'cpwgm' ),
        'cpwgm__select_field_7_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    /*
    add_settings_field(
        'cpwgm__select_field_3',
        __( 'Display labels?', 'cpwgm' ),
        'cpwgm__select_field_3_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    */
    add_settings_field(
        'cpwgm__select_field_4',
        __( 'Layout', 'cpwgm' ),
        'cpwgm__select_field_4_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    add_settings_field(
        'cpwgm__select_field_5',
        __( 'Label style', 'cpwgm' ),
        'cpwgm__select_field_5_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    add_settings_field(
        'cpwgm__select_field_6',
        __( 'Entry style', 'cpwgm' ),
        'cpwgm__select_field_6_render',
        'cpwgm_settings',
        'cpwgm__cpwgm_settings_section'
        );
    
    // set defaults
    $defaults = array(
        'cpwgm__select_field_0' => '1',
        'cpwgm__select_field_1' => '300',
        'cpwgm__select_field_2' => '1',
        //'cpwgm__select_field_3' => '1',
        'cpwgm__select_field_4' => '0', // 0 = stacked, 1 = half/half
        'cpwgm__select_field_5' => 'h1',
        'cpwgm__select_field_6' => 'div',
        'cpwgm__select_field_7' => '1'
    );
    
    if(!get_option('cpwgm__settings')) {
        // option not found, so use defaults
        add_option('cpwgm__settings', $defaults);
    } else {
        // option already in the database, so we get the stored value and merge it with default
        $old = get_option('cpwgm__settings');
        $new = wp_parse_args($old, $defaults);
        
        update_option('cpwgm__settings', $new);
    }
}

function cpwgm__settings_section_callback( ) {
    //echo __( '<strong>Warning</strong>: It is not recommended to change these unless you are farmilar with HTML and your theme\'s CSS rules.', 'cpwgm' );
}

function cpwgm__options_page( ) { ?>
	<form action='options.php' method='post'>

		<h2>Contact Page With Google Map - Display Settings</h2>

		<?php settings_fields( 'cpwgm_settings' );
		do_settings_sections( 'cpwgm_settings' );
		submit_button(); ?>

	</form>
	<?php
}

function cpwgm__select_field_0_render( ) {    
    $options = get_option( 'cpwgm__settings' ); ?>
    <input type="radio" name='cpwgm__settings[cpwgm__select_field_0]' <?php if ($options['cpwgm__select_field_0'] == '0') echo 'checked'; ?> value='0'>Off
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_0]' <?php if ($options['cpwgm__select_field_0'] == '1') echo 'checked'; ?> value='1'>On
<?php
}


function cpwgm__select_field_1_render( ) { 
	$options = get_option( 'cpwgm__settings' ); ?>
	<input type="number" name="cpwgm__settings[cpwgm__select_field_1]" value="<?php echo $options['cpwgm__select_field_1']; ?>" /> px
<?php
}


function cpwgm__select_field_2_render( ) { 
	$options = get_option( 'cpwgm__settings' ); ?>
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_2]' <?php if ($options['cpwgm__select_field_2'] == '0') echo 'checked'; ?> value='0'>Off
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_2]' <?php if ($options['cpwgm__select_field_2'] == '1') echo 'checked'; ?> value='1'>On
<?php
}

function cpwgm__select_field_7_render( ) {
	$options = get_option( 'cpwgm__settings' ); ?>
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_7]' <?php if ($options['cpwgm__select_field_7'] == '0') echo 'checked'; ?> value='0'>Off
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_7]' <?php if ($options['cpwgm__select_field_7'] == '1') echo 'checked'; ?> value='1'>On
<?php
}


function cpwgm__select_field_3_render( ) { 
	$options = get_option( 'cpwgm__settings' ); ?>
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_3]' <?php if ($options['cpwgm__select_field_3'] == '0') echo 'checked'; ?> value='0'>No
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_3]' <?php if ($options['cpwgm__select_field_3'] == '1') echo 'checked'; ?> value='1'>Yes
<?php
}


function cpwgm__select_field_4_render( ) { 
	$options = get_option( 'cpwgm__settings' ); ?>
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_4]' <?php if ($options['cpwgm__select_field_4'] == '0') echo 'checked'; ?> value='0'>Normal
	<input type="radio" name='cpwgm__settings[cpwgm__select_field_4]' <?php if ($options['cpwgm__select_field_4'] == '1') echo 'checked'; ?> value='1'>Two Column
<?php
}

function cpwgm__select_field_5_render( ) {    
    $options = get_option( 'cpwgm__settings' ); ?>
	<select name='cpwgm__settings[cpwgm__select_field_5]'>
		<option value='h1' <?php selected( $options['cpwgm__select_field_5'], 'h1' ); ?>>Heading 1 (h1)</option>
		<option value='h2' <?php selected( $options['cpwgm__select_field_5'], 'h2'); ?>>Heading 2 (h2)</option>
		<option value='h3' <?php selected( $options['cpwgm__select_field_5'], 'h3'); ?>>Heading 3 (h3)</option>
		<option value='h4' <?php selected( $options['cpwgm__select_field_5'], 'h4'); ?>>Heading 4 (h4)</option>
		<option value='h5' <?php selected( $options['cpwgm__select_field_5'], 'h5'); ?>>Heading 5 (h5)</option>
		<option value='h6' <?php selected( $options['cpwgm__select_field_5'], 'h6'); ?>>Heading 6 (h6)</option>
		<option value='p' <?php selected( $options['cpwgm__select_field_5'], 'p'); ?>>Paragraph (p)</option>
	</select>
<?php
}

function cpwgm__select_field_6_render( ) {
	$options = get_option( 'cpwgm__settings' ); ?>
	<select name='cpwgm__settings[cpwgm__select_field_6]'>
		<option value='h1' <?php selected( $options['cpwgm__select_field_6'], 'h1' ); ?>>Heading 1 (h1)</option>
		<option value='h2' <?php selected( $options['cpwgm__select_field_6'], 'h2'); ?>>Heading 2 (h2)</option>
		<option value='h3' <?php selected( $options['cpwgm__select_field_6'], 'h3'); ?>>Heading 3 (h3)</option>
		<option value='h4' <?php selected( $options['cpwgm__select_field_6'], 'h4'); ?>>Heading 4 (h4)</option>
		<option value='h5' <?php selected( $options['cpwgm__select_field_6'], 'h5'); ?>>Heading 5 (h5)</option>
		<option value='h6' <?php selected( $options['cpwgm__select_field_6'], 'h6'); ?>>Heading 6 (h6)</option>
		<option value='p' <?php selected( $options['cpwgm__select_field_6'], 'p'); ?>>Paragraph (p)</option>
		<option value='div' <?php selected( $options['cpwgm__select_field_6'], 'div'); ?>>Block (div)</option>
		<option value='span' <?php selected( $options['cpwgm__select_field_6'], 'span'); ?>>Inline-Block (span)</option>
	</select>
<?php
}

?>