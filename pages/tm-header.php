<?php
	if ( ! defined( 'ABSPATH' ) ) exit;	
?>
<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php 
		trustmetrics_enqueue_custom_styles();
		trustmetrics_enqueue_dashboard_styles();
		$token = get_transient('trustmetrics_custom_auth_token');
		if(isset($token) && !empty($token)):
	?>
	<?php endif; ?>