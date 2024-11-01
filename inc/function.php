<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	
  function trustmetrics_enqueue_custom_styles() {
    // Enqueue Roboto font
    wp_enqueue_style('roboto-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap');

    // Enqueue Material Icons font (preloaded)
    wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/css?family=Material+Icons', array(), null, 'all');
    
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', plugins_url('../assets/bootstrap/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('font-awesome', plugins_url('../assets/fontawesome/css/all.min.css', __FILE__));


    // Enqueue DataTables CSS
    wp_enqueue_style('datatables-css', plugins_url('../assets/datatables/css/dataTables.bootstrap5.min.css', __FILE__));

    // Enqueue Style CSS
    wp_enqueue_style('custom-style', plugins_url('../assets/css/style.css', __FILE__),'1.1', time());
  }

  add_action('wp_enqueue_styles', 'trustmetrics_enqueue_custom_styles');


  //------------------ dashboard CSS --------------------------
  function trustmetrics_enqueue_dashboard_styles() {
    wp_enqueue_style('dashboard-style', plugins_url('../assets/css/dashboard.css', __FILE__));
  }

  add_action('wp_enqueue_styles', 'trustmetrics_enqueue_dashboard_styles');

  //------------------ Dashboard JS --------------------------
  function trustmetrics_enqueue_dashboard_script() {
    wp_register_script('trustmetrics_placeholder_script', '', array(), null, true);

    // Enqueue the placeholder script
    wp_enqueue_script('trustmetrics_placeholder_script');

    // Generate the URLs
    $signup_url = esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND . 'signup?redirecturl=' . get_admin_url() . 'admin.php?page=connect-trustmetrics');
    $login_url = esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND . 'login?redirecturl=' . get_admin_url() . 'admin.php?page=connect-trustmetrics');

    // Localize script to pass PHP variables to JavaScript
    wp_localize_script('trustmetrics_placeholder_script', 'trustmetrics_globals', array(
        'apiEndpointFrontend' => TRUSTMETRICS_API_ENDPOINT_FRONTEND,
        'signupUrl' => $signup_url,
        'loginUrl' => $login_url,
    ));

    wp_enqueue_script('custom-dashboard-script', plugins_url('../assets/js/dashboard.js?v=1.1', __FILE__), array('jquery'), null, true);

  }

  add_action('wp_enqueue_scripts', 'trustmetrics_enqueue_dashboard_script');

  //------------------ widget CSS --------------------------
  function trustmetrics_enqueue_widget_styles() {
    wp_enqueue_style('widget-style', plugins_url('../assets/css/widget.css', __FILE__));
  }

  add_action('wp_enqueue_styles', 'enqueue_widget_styles');

  //------------------ Widget JS --------------------------
  function trustmetrics_enqueue_widget_script() {
    wp_enqueue_script('custom-widget-script', plugins_url('../assets/js/widget.js?v=52', __FILE__), array('jquery'), null, true);
  }

  add_action('wp_enqueue_scripts', 'trustmetrics_enqueue_widget_script');

  //------------------ Badge CSS --------------------------
  function trustmetrics_enqueue_badge_styles() {
    wp_enqueue_style('badge-style', plugins_url('../assets/css/badge.css', __FILE__));
  }

  add_action('wp_enqueue_styles', 'trustmetrics_enqueue_badge_styles');

  //------------------ Badge JS --------------------------
  function trustmetrics_enqueue_badge_script() {
    wp_enqueue_script('custom-badge-script', plugins_url('../assets/js/badge.js?v=55', __FILE__), array('jquery'), null, true);
  }

  add_action('wp_enqueue_scripts', 'trustmetrics_enqueue_badge_script');

  //------------------ Setting CSS --------------------------
  function trustmetrics_enqueue_setting_styles() {
    wp_enqueue_style('setting-style', plugins_url('../assets/css/switch.css', __FILE__));
  }

  add_action('wp_enqueue_styles', 'trustmetrics_enqueue_setting_styles');

  //------------------ Reload Page --------------------------
  if (!function_exists('trustmetrics_reload_page')) { 
    function trustmetrics_reload_page() {
      header("Location: " . esc_url(sanitize_text_field($_SERVER['REQUEST_URI'])));
      exit();
    }
  }



  //------------------ Footer JS  --------------------------
  function trustmetrics_enqueue_footer_scripts() {
    // wp_enqueue_script('jquery', plugins_url('../assets/js/jquery.min.js', __FILE__), array(), null, true);

    wp_enqueue_script('popper', plugins_url('../assets/js/popper.min.js', __FILE__), array('jquery'), null, true);

    wp_enqueue_script('bootstrap', plugins_url('../assets/bootstrap/js/bootstrap.bundle.min.js', __FILE__), array('jquery', 'popper'), null, true);
    
    wp_enqueue_script('dataTables', plugins_url('../assets/datatables/js/dataTables.min.js', __FILE__), array('jquery', 'popper'), null, true);
    wp_enqueue_script('dataTables-bootstrap', plugins_url('../assets/datatables/js/dataTables.bootstrap5.min.js', __FILE__), array('jquery', 'popper'), null, true);

    wp_enqueue_script('custom-script', plugins_url('../assets/js/custom.js', __FILE__), array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'trustmetrics_enqueue_footer_scripts');

  //------------------Logout--------------------------

  add_action('admin_post_logout', 'trustmetrics_logout');
  
  function trustmetrics_logout(){
    $url = esc_url(TRUSTMETRICS_API_ENDPOINT . 'api/logout');

    $response = wp_remote_post($url, array(
        'body' => array(),
    ));

    if (is_wp_error($response)) {
        echo esc_html($response->get_error_message());

    } else {
        // remove the token from the client
        delete_transient('trustmetrics_custom_auth_token');
        wp_redirect(esc_url(site_url('/wp-admin/admin.php?page=Dashboard')));
    }
  }
?>
