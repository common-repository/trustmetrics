<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$dashdata = null;

$token = get_transient('trustmetrics_custom_auth_token');

$url = TRUSTMETRICS_API_ENDPOINT . 'api/user';

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

if ( is_wp_error( $response ) ) {
    $error_message = $response->get_error_message();
    echo esc_html($error_message);
} else {
    $response_code = wp_remote_retrieve_response_code( $response );
    $response_message = wp_remote_retrieve_response_message( $response );
    $headers = wp_remote_retrieve_headers( $response );

    if ($response_code == 200) {
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);
        $dashdata = $result;
    } elseif ($response_code == 401) {
       wp_redirect( admin_url( 'admin.php?page=Dashboard' ) );

        delete_transient('trustmetrics_custom_auth_token');
        exit;
    } else {
        // Handle other errors
        $error_message = wp_remote_retrieve_response_message($response);
        echo esc_html($error_message);
    }
   
}
// Check if company_id is set in the POST data and not empty
if (isset($_POST['company_id']) && !empty($_POST['company_id']) && isset($_REQUEST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])))) {
    
    $company_id = sanitize_text_field($_POST['company_id']);

    // Change the user's current company using API
    $url = TRUSTMETRICS_API_ENDPOINT . "api/company/change";
    $token = get_transient('trustmetrics_custom_auth_token');

    $headers = array(
        'Accept'        => 'application/json',
        'Authorization' => 'Bearer ' . $token,
        'Content-Type'  => 'application/json',
    );

    $params = array(
        'company' => $company_id,
    );

    // Make the HTTP POST request
    $response = wp_remote_post(
        $url,
        array(
            'headers'   => $headers,
            'body'      => wp_json_encode($params),
            'method'    => 'POST',
        )
    );

    // Check if the request was successful
    if (is_array($response) && !is_wp_error($response)) {
        // Get the response body
        $body = wp_remote_retrieve_body($response);

        // Decode the JSON result and update $dashdata
        $dashdata = json_decode($body, true);
    } else {
        // Handle the error
        $error_message = is_wp_error($response) ? $response->get_error_message() : 'Unknown error';
        echo esc_html($error_message);
    }
}

?>