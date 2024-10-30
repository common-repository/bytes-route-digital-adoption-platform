<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.bytesroute.com/
 * @since             1.0.0
 * @package           bytes-route
 *
 * @wordpress-plugin
 * Plugin Name:       Bytes Route – Digital Adoption Platform
 * Plugin URI:        https://www.bytesroute.com/
 * Description:       Bytes Route is a Digital Adoption Platform that assists businesses in increasing user engagement, encouraging product adoption, and lowering churn rates.
 * Version:           1.0.0
 * Author:            Bytes Route
 * Author URI:        https://www.bytesroute.com/contact.html
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bytes-route
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

# Allow HTML
$allowed_tags = wp_kses_allowed_html( 'post' );
$allowed_tags['input'] = array(
	'type'      => true,
	'id'        => true,
	'name'      => true,
	'class'      => true,
	'value'     => true,
	'class'     => true,
	'onclick'   => true,
);
$allowed_tags['a']['onclick'] = true;

// Add menu under Settings Menu
add_action( 'admin_menu', 'bytesroutedotcom_menu_entry', 100 );
function bytesroutedotcom_menu_entry() {
    add_menu_page(
        __( 'Bytes Route Settings' ),
        __( 'Bytes Route Settings' ),
        'manage_options',
        'bytes-route-settings',
        'generate_bytesroutedotcom_settings_page',
		'',
		80
    );
}

// Callback function to display settings page
function generate_bytesroutedotcom_settings_page() {
    
    // Get Submitted Form Values Frontend
	if ($_POST['bytes_route_submit'] == 1) {
	    $error = false;
	    $js_code = wp_kses_no_null($_POST['bytes_route_js_code']);
	    
	    // Checking if js code exists
	    if (empty($js_code)) {
			$error = false;
			$error_message = 'Please enter Frontend Bytes Route Code Snippet.';
		} else if(!strpos($js_code, 'https://app.bytesroute.com') !== false) {
			$error = true;
			$error_message = 'Invalid Frontend Bytes Route Code Snippet.';	    
		}
		
		// If no error, save option
		if (!$error) {
		    update_option('bytes_route_js_code', $js_code);
		    $success = true;
			$success_message = 'Frontend Bytes Route Code Snippet successfully saved.';	      
		}
	}

	    // Get Submitted Form Values Backend
	if ($_POST['bytes_route_submit_backend'] == 1) {
	    $error_backend = false;
	    $js_code_backend = wp_kses_no_null($_POST['bytes_route_js_code_backend']);
	    
	    // Checking if js code exists
	    if (empty($js_code_backend)) {
			$error_backend = false;
			$error_backend_message = 'Please enter Backend Bytes Route Code Snippet.';
		} else if(!strpos($js_code_backend, 'https://app.bytesroute.com') !== false) {
			$error_backend = true;
			$error_backend_message = 'Invalid Backend Bytes Route Code Snippet.';	    
		}
		
		// If no error_backend, save option
		if (!$error_backend) {
		    update_option('bytes_route_js_code_backend', $js_code_backend);
		    $success_backend = true;
			$success_backend_message = 'Backend Bytes Route Code Snippet successfully saved.';	    
		}
	}

	$js_code = stripslashes(get_option('bytes_route_js_code'));
	$js_code_backend = stripslashes(get_option('bytes_route_js_code_backend'));

	$page_content = '<div class="wrap">';
	$page_content.= "<h1>Bytes Route Settings</h1>";
	$page_content .= '<p class="note" style="color:blue">';
	$page_content.= esc_html('Bytes Route Code Snippet Example: <script id="brt-script" src="https://app.bytesroute.com/script.js?id=1111"></script>');
	$page_content .= '</p>';

	if ($error) {
        $page_content .= '<p class="error" style="color:red;">'.esc_html($error_message).'</p>';
	}
	if ($success) {
        $page_content .= '<p class="success" style="color:green;">'.esc_html($success_message).'</p>';
	}
	
	$page_content.= '<form method="post" action=""><input type="hidden" value="1" name="bytes_route_submit">';
	$page_content.= '<table class="form-table" role="presentation"><tbody><tr><th scope="row"><label for="blogname">Frontend Bytes Route Code Snippet</label></th><td><textarea name="bytes_route_js_code" rows="4" cols="60" id="moderation_keys" class="code">'.esc_textarea($js_code).'</textarea></td></tr></tbody></table>';

	$page_content.= '<p class="submit"><input type="submit" value="Save" name="submit" class="button button-primary" " ></p>';
	$page_content.= '</form>';


	if ($error_backend) {
        $page_content .= '<p class="error" style="color:red;">'.esc_html($error_backend_message).'</p>';
	}
	if ($success_backend) {
        $page_content .= '<p class="success" style="color:green;">'.esc_html($success_backend_message).'</p>';
	}
	
	$page_content.= '<form method="post" action="" ><input type="hidden" value="1" name="bytes_route_submit_backend">';
	$page_content.= '<table class="form-table" role="presentation"><tbody><tr><th scope="row"><label for="blogname">Backend Bytes Route Code Snippet</label></th><td><textarea name="bytes_route_js_code_backend" rows="4" cols="60" id="moderation_keys" class="code">'.esc_textarea($js_code_backend).'</textarea></td></tr></tbody></table>';
	$page_content.= '<p class="submit"><input type="submit" value="Save" name="submit" class="button button-primary"></p>';
	$page_content.= '</form>';

	$page_content.= '</div>';
	echo wp_kses_no_null($page_content);

}

// Display on frontend
if ( ! function_exists( 'bytesroutedotcom_js_code_display' ) ) {
	function bytesroutedotcom_js_code_display() {
		$js_code = stripslashes(get_option('bytes_route_js_code'));
		if ($js_code) {

		    echo wp_kses_no_null($js_code);


		}
	}
}
add_action( 'wp_head', 'bytesroutedotcom_js_code_display' );

// Display on backend
if ( ! function_exists( 'bytesroutedotcom_js_code_display_backend' ) ) {
	function bytesroutedotcom_js_code_display_backend() {

		$js_code_backend = stripslashes(get_option('bytes_route_js_code_backend'));
		if ($js_code_backend) {
		    echo  wp_kses_no_null($js_code_backend);

		}
	}
}
add_action( 'admin_head', 'bytesroutedotcom_js_code_display_backend' );