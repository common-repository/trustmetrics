<?php
	/** 
	* Plugin Name: Trustmetrics
	* Plugin URI:  https://www.trustmetrics.io/
	* Description: Trustmetrics helps you build social proof, differentiate from the competition, attract more visitors and get more clients
	* Version: 1.0
	* Author: Trustmetrics Team
	* Author URI: https://www.trustmetrics.io/
	* License: GPL v2 or later
	* License URI: https://www.gnu.org/licenses/gpl-2.0.html
	* Plugin Icon: assets/images/trustmetrics-logo.svg
	* Text Domain: trustmetrics
	*
	* @package Trustmetrics
	*/
 

// Don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	ob_start();
	 //define('TRUSTMETRICS_API_ENDPOINT', 'http://127.0.0.1:8000/');
	// define('TRUSTMETRICS_API_ENDPOINT','https://staging-api.trustmetrics.me/');
	define('TRUSTMETRICS_API_ENDPOINT','https://api.trustmetrics.me/');

	// define('TRUSTMETRICS_API_ENDPOINT_FRONTEND','http://localhost:8080/');
	// define('TRUSTMETRICS_API_ENDPOINT_FRONTEND','https://staging-app.trustmetrics.me/');
	define('TRUSTMETRICS_API_ENDPOINT_FRONTEND','https://app.trustmetrics.me/');

	// Check if user is logged-in or logged out
	if (!function_exists('trustmetrics_remove_cookie')) { 

		function trustmetrics_remove_cookie()
		{
			delete_transient('trustmetrics_custom_auth_token');
		}
	}
	register_deactivation_hook( __FILE__, 'trustmetrics_remove_cookie' );


	include('inc/function.php'); 

	add_action('admin_menu', 'trustmetrics_sub_menu');
	if (!function_exists('trustmetrics_sub_menu')) { 
		function trustmetrics_sub_menu(){
			add_menu_page('Trustmetrics','Trustmetrics','','trustmetrics','','data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBzdGFuZGFsb25lPSJubyI/Pgo8IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDIwMDEwOTA0Ly9FTiIKICJodHRwOi8vd3d3LnczLm9yZy9UUi8yMDAxL1JFQy1TVkctMjAwMTA5MDQvRFREL3N2ZzEwLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4wIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciCiB3aWR0aD0iNTYuMDAwMDAwcHQiIGhlaWdodD0iNzQuMDAwMDAwcHQiIHZpZXdCb3g9IjAgMCA1Ni4wMDAwMDAgNzQuMDAwMDAwIgogcHJlc2VydmVBc3BlY3RSYXRpbz0ieE1pZFlNaWQgbWVldCI+CjxtZXRhZGF0YT4KQ3JlYXRlZCBieSBwb3RyYWNlIDEuMTYsIHdyaXR0ZW4gYnkgUGV0ZXIgU2VsaW5nZXIgMjAwMS0yMDE5CjwvbWV0YWRhdGE+CjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAuMDAwMDAwLDc0LjAwMDAwMCkgc2NhbGUoMC4xMDAwMDAsLTAuMTAwMDAwKSIKZmlsbD0iIzAwMDAwMCIgc3Ryb2tlPSJub25lIj4KPHBhdGggZD0iTTEyNiA3MDggYy0xMyAtMTggLTE2IC01NSAtMTYgLTE5NSAwIC0yMTIgMSAtMjEzIDEwOSAtMjEzIDU5IDAgNzIKLTMgODQgLTIxIDEzIC0xOCAxMyAtMjggLTUgLTg3IC0zNyAtMTI0IC0yMyAtMTY2IDUyIC0xNjAgMzMgMyAzOSA5IDExMiAxMjgKbDc3IDEyNSAwIDIwNiBjMSAxODcgLTEgMjA3IC0xNyAyMjIgLTE2IDE1IC00NSAxNyAtMjAwIDE3IC0xNzYgMCAtMTgxIC0xCi0xOTYgLTIyeiBtMjI2IC0xMTUgYzEwIC0yMSAyMiAtMjkgNDYgLTMxIDM4IC00IDQwIC0xMSA4IC00MSAtMjAgLTE5IC0yMwotMjggLTE2IC01MiA5IC0zMiA0IC0zNSAtMzUgLTE3IC0yMSA5IC0zMSA5IC00NiAwIC0zMiAtMjAgLTQwIC0xNSAtMzMgMjIgNQoyOCAyIDM3IC0xNSA0OCAtMzEgMTkgLTI2IDM4IDEwIDM4IDI0IDAgMzIgNiA0MCAzMCAxMyAzNyAyNCAzOCA0MSAzeiIvPgo8L2c+Cjwvc3ZnPgo=');
			
			add_submenu_page('trustmetrics','Dashboard','Dashboard','manage_options','Dashboard','trustmetrics_dashboard');

			add_submenu_page('trustmetrics','Badges','Badges','manage_options','Badges','trustmetrics_badges');

			add_submenu_page('trustmetrics','Widgets','Widgets','manage_options','Widgets','trustmetrics_Widgets');

			add_submenu_page('trustmetrics','Settings','Settings','manage_options','Settings','trustmetrics_setting');
			//add_submenu_page('trustmetrics','Connect','Connect','manage_options','Connect','trustmetrics_connect');
			
		}
	}
	if (!function_exists('trustmetrics_menu')) { 

		function trustmetrics_menu(){
			echo esc_html__("Welcome to Trustmetrics",'trustmetrics');
		}
	}
	if (!function_exists('trustmetrics_dashboard')) { 

		function trustmetrics_dashboard(){
			$token = get_transient('trustmetrics_custom_auth_token');
			if(isset($token)){
				include('pages/tm-dashboard.php');  
			}else{
				include('pages/tm-login.php');
			}
		}
	}
	

	if (!function_exists('trustmetrics_badges')) { 

		function trustmetrics_badges(){
			$token = get_transient('trustmetrics_custom_auth_token');
			if (isset($token)) {
				include('pages/tm-badges.php');
			} else {
				include('pages/tm-login.php');
			}
		}
	}
	if (!function_exists('trustmetrics_Widgets')) { 

		function trustmetrics_Widgets(){
			$token = get_transient('trustmetrics_custom_auth_token');
			if (isset($token)) {
				$url = TRUSTMETRICS_API_ENDPOINT . "api/user";
				
				$headers = array(
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $token,
				);
			
				$response = wp_remote_get(
					$url,
					array(
						'headers' => $headers,
					)
				);
			
				$httpStatusCode = wp_remote_retrieve_response_code($response);
			
				if ($httpStatusCode == 401) {
					delete_transient('trustmetrics_custom_auth_token');
					wp_redirect(esc_url(site_url('/wp-admin/admin.php?page=Dashboard')));
				} else {
					$body = wp_remote_retrieve_body($response);
					$admin_detail = json_decode($body);
					include('pages/tm-widgets.php');
				}
			} else {
				include('pages/tm-login.php');
			}
		}
	}
	if (!function_exists('trustmetrics_setting')) { 

		function trustmetrics_setting(){
			$token = get_transient('trustmetrics_custom_auth_token');
			if (isset($token)) {
				$url = TRUSTMETRICS_API_ENDPOINT . "api/user";
				
				$headers = array(
					'Accept'        => 'application/json',
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $token,
				);
			
				$response = wp_remote_get(
					$url,
					array(
						'headers' => $headers,
					)
				);
			
				$httpStatusCode = wp_remote_retrieve_response_code($response);
			
				if ($httpStatusCode == 401) {
					delete_transient('trustmetrics_custom_auth_token');
					wp_redirect(esc_url(site_url('/wp-admin/admin.php?page=Dashboard')));
				} else {
					$body = wp_remote_retrieve_body($response);
					$admin_detail = json_decode($body);
					include('pages/tm-setting.php');
				}
			} else {
				include('pages/tm-login.php');
			}	
		}
	}

	//--------------------Widget Shortcode---------------------------
	if (!function_exists('trustmetrics_widget_shortcode')) { 
		function trustmetrics_widget_shortcode($atts) {
			$default = array(
				'token' => 'xyz',
				'type' => 'widget',
			);
			$result = shortcode_atts($default, $atts);
			$endpoint = TRUSTMETRICS_API_ENDPOINT;
			
			// Register and enqueue the external script
			wp_register_script('trustmetrics_widget_script', $endpoint . 'embed/trustmetrics.js', array(), null, true);
			wp_enqueue_script('trustmetrics_widget_script');
	
			// Create a unique handle for the inline script
			$unique_handle = 'trustmetrics_inline_script_' . $result['token'];
	
			// Add inline script
			$inline_script = "(function(d, s, id){ 
				var js; 
				if (d.getElementById(id)) {return;} 
				js = d.createElement(s); 
				js.id = id; 
				js.src = '{$endpoint}embed/trustmetrics.js'; 
				d.getElementsByTagName('head')[0].appendChild(js); 
			}(document, 'script', 'TrustmetricsWidgetScript'));";
			wp_add_inline_script('trustmetrics_widget_script', $inline_script);
	
			// Filter to add custom attributes if needed
			add_filter('script_loader_tag', function($tag, $handle) use ($unique_handle) {
				if ($unique_handle !== $handle) {
					return $tag;
				}
				$id = 'trustmetrics-script-' . esc_attr($result['token']);
				$class = 'tmscript';
				$token = esc_attr($result['token']);
				return str_replace('<script ', '<script id="' . $id . '" class="' . $class . '" token="' . $token . '" ', $tag);
			}, 10, 2);
	
			// Build the output string
			if($result['type'] == "popup"){
				$output = '<div class="trustmetrics-widget popup" id="trustmetrics-' . esc_attr($result['token']) . '" token="' . esc_attr($result['token']) . '"></div>';
			} else {
				$output = '<div class="trustmetrics-widget" id="trustmetrics-' . esc_attr($result['token']) . '" token="' . esc_attr($result['token']) . '"></div>';
			}
	
			return $output;
			
		}
	}
	add_shortcode('trustmetrics_widget', 'trustmetrics_widget_shortcode');

	//--------------------Badget Shortcode---------------------------

	if (!function_exists('trustmetrics_badge_shortcode')) { 
		function trustmetrics_badge_shortcode($atts) {
			$default = array(
				'token' => 'xyz',
				'type' => 'Badge Widget',
			);
			$result = shortcode_atts($default, $atts);
			$endpoint = TRUSTMETRICS_API_ENDPOINT;
	
			// // Register and enqueue the external script
			 wp_register_script('trustmetrics_widget_script', $endpoint . 'widget/script.js', array(), null, true);
			 $unique_handle = 'trustmetrics_widget_script_' . $result['token'];
			// wp_enqueue_script('trustmetrics_widget_script');
			 wp_enqueue_script($unique_handle, $endpoint . 'widget/script.js', array(), null, true);
	
			
			// Filter to add custom attributes
			add_filter('script_loader_tag', function($tag, $handle) use ($result,$unique_handle) {
				if ($unique_handle !== $handle) {
					return $tag;
				}
				$class = 'tmscript';
				$token = esc_attr($result['token']);
				$widget = esc_attr($result['type']);
				return str_replace(' src', " widget=\"$widget\" class=\"$class\" token=\"$token\" src", $tag);
			}, 10, 2);
	
			// Build the output string
			$output = '<div id="trustmetrics-' . esc_attr($result['token']) . '"></div>';
	
			return $output;
		}
	}
	add_shortcode('trustmetrics_badge', 'trustmetrics_badge_shortcode');
		// Create a custom admin page without adding it to the admin menu
		function trustmetrics_add_hidden_admin_page() {
			add_submenu_page(
				'Trustmetrics', // This makes the page hidden from the admin menu
				'Connect Trustmetrics',           // Page title
				'Connect Trustmetrics',                 // Menu title (not used since it's hidden)
				'manage_options',               // Capability
				'connect-trustmetrics',           // Menu slug
				'trustmetrics_display_page'     // Function to display page content
			);
		}
		add_action('admin_menu', 'trustmetrics_add_hidden_admin_page');
		
		// 45600/a 
		// Function to display the content of the custom admin page
		function trustmetrics_display_page() {
			include('pages/tm-connect.php');  
			
		}
		function trustmetrics_enqueue_token_script() {
			if (isset($_GET['page']) && $_GET['page'] == 'connect-trustmetrics') {
				wp_register_script('trustmetrics_placeholder_script', '', array(), null, true);
		
				// Enqueue the placeholder script
				wp_enqueue_script('trustmetrics_placeholder_script');
		
		
				// Inline script content
				$inline_script = "window.close();";
		
				// Add the inline script to the placeholder script
				wp_add_inline_script('trustmetrics_placeholder_script', $inline_script);
			}
		  }
	  add_action('wp_enqueue_scripts', 'trustmetrics_enqueue_token_script');
	
?>