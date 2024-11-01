<?php
	if ( ! defined( 'ABSPATH' ) ) exit;	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once('tm-header.php'); ?>
	<title>Trustmetrics Dashboard</title>
</head>
<body>
	<div id="login-content">
		<div class="row">
			<div class="container">
				<br /><br /><br /><br />
				<p style="text-align:center;">
					<img src="<?php echo esc_url(plugins_url('../assets/images/logo.png', __FILE__)); ?>" alt="trustmetrics">
				</p><br />
				<h3 style="text-align:center"><b>Welcome to the Trustmetrics Setup wizard!</b></h3>
				<div class="text-center">
					<p style="text-align: center; font-size:16px">You're just minutes away from receiving more reviews for your business.</p>
					<p style="text-align: center; font-size:16px; margin-top:-12px">Experience the full power of Trustmetrics today!</p>
					<button type="button" class="btn btn-lg px-10 px-sm-5 btn-green" id="signup" onclick="showSignUp()">New? Claim your Free Account</button>
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-lg px-10 px-sm-5 btn-blue" onclick="showLogin()">Connect your Existing Account</button>
				</div><br />
			</div>
		</div>
	</div>
	<?php trustmetrics_enqueue_footer_scripts(); ?>
	
	
	<?php 
		trustmetrics_enqueue_dashboard_script();
	?>
</body>
</html>
