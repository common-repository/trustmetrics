<?php
	if ( ! defined( 'ABSPATH' ) ) exit;
	$token = get_transient('trustmetrics_custom_auth_token');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php include_once('tm-header.php')?>
	<title>Trustmetrics Dashboard</title>
</head>
<body>
<?php
 // Check if the token is set and valid
 if (!empty($token)) {
	require_once('common.php');

	$search = "Email";
	if (isset($_GET['channel']) && !empty($_GET['channel'])) {
		$search = sanitize_text_field($_GET['channel']);
	}

	$url = TRUSTMETRICS_API_ENDPOINT . 'api/campaigns/sent';
	$token = get_transient('trustmetrics_custom_auth_token');

	$headers = array(
		'Accept'        => 'application/json',
		'Authorization' => 'Bearer ' . $token,
	);

	$response = wp_remote_post(
		$url,
		array(
			'headers'    => $headers,
			'body'       => array('channel' => $search),
		)
	);

	$response_data = json_decode(wp_remote_retrieve_body($response), true);

	$timeframe = "All Time";
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d");
	
	if (isset($_GET['timeframe']) && !empty($_GET['timeframe'])) {
		$allowed_timeframes = array("All Time", "Last Month", "Last Week", "Yesterday", "Custom"); // Adjust as needed
		$timeframe = in_array($_GET['timeframe'], $allowed_timeframes) ? sanitize_text_field($_GET['timeframe']) : "All Time";
	}

	if (isset($_GET['dates']) && !empty($_GET['dates'])) {
		$date2 = sanitize_text_field($_GET['dates']);
	}

	$dates = array($date1, $date2);
	$filters = array("source" => "", "timeframe" => $timeframe, "dates" => $dates);
	$val = array("params" => $filters);

	$url = TRUSTMETRICS_API_ENDPOINT . 'api/dashboard/summary';

	$response = wp_remote_post(
		$url,
		array(
			'headers' => $headers,
			'body'    => urldecode(http_build_query($val)),
		)
	);

	$review = json_decode(wp_remote_retrieve_body($response), true);
	$platforms = array_keys($review);

	foreach ($platforms as $platform) {
		if (isset($review[$platform]['ratings'])) {
			foreach ($review[$platform]['ratings'] as $rating) {
				// Your processing logic here
			}
		}
	}
  ?>
	
	<div class="tm-container" id="dashboard-content">
		<?php include_once('sticky-header.php') ?>
		<!-- dashboard content -->
		<div class="container-fluid">
			<div id="main">
				<div class="row row-offcanvas row-offcanvas-left">
					<div class="col-12">
						<div class="row bg-dash">
							<div class="col-md-4 col-lg-6">
								<p class="dashboard">Dashboard</p>
							</div>
							<div class="col-md-8 col-lg-6">
								<div class="row">
									<div class="col-4">
										<p class="dash-item">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#adaeaf" d="M12 8H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h3l5 4V4l-5 4m3 7.6L13 14H4v-4h9l2-1.6v7.2m6.5-3.6c0 1.71-.96 3.26-2.5 4V8c1.53.75 2.5 2.3 2.5 4Z"/></svg>
											<span><?php if(isset($review['campaigns'])) echo esc_html($review['campaigns']); ?></span>
											<a class="right" style="color:black; text-decoration:none" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>campaigns" target="_blank"><span class="item-title">Campaigns</span></a>
										</p>
									</div>
									<div class="col-4">
										<p class="dash-item">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#adaeaf" d="M9 22c-.6 0-1-.4-1-1v-3H4c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2h-6.1l-3.7 3.7c-.2.2-.4.3-.7.3H9m1-6v3.1l3.1-3.1H20V4H4v12h6m6.3-10l-1.4 3H17v4h-4V8.8L14.3 6h2m-6 0L8.9 9H11v4H7V8.8L8.3 6h2Z"/></svg>
											<span><?php if(isset($review['reviews']['total'])) echo esc_html($review['reviews']['total']) ?></span>
											<a class="right" style="color:black; text-decoration:none" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>reviews" target="_blank"><span class="item-title">Reviews</span></a>
										</p>
									</div>
									<div class="col-4">
										<p class="dash-item-last">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#adaeaf" d="M16.5 15.5c1.72 0 3.75.8 4 1.28v.72h-8v-.72c.25-.48 2.28-1.28 4-1.28m0-1.5c-1.83 0-5.5.92-5.5 2.75V19h11v-2.25c0-1.83-3.67-2.75-5.5-2.75M9 13c-2.33 0-7 1.17-7 3.5V19h7v-1.5H3.5v-1c0-.63 2.79-2.16 6.32-2a5.12 5.12 0 0 1 1.55-1.25A12.28 12.28 0 0 0 9 13m0-6.5A1.5 1.5 0 1 1 7.5 8A1.5 1.5 0 0 1 9 6.5M9 5a3 3 0 1 0 3 3a3 3 0 0 0-3-3m7.5 3.5a1 1 0 1 1-1 1a1 1 0 0 1 1-1m0-1.5A2.5 2.5 0 1 0 19 9.5A2.5 2.5 0 0 0 16.5 7Z"/></svg>
											<span><?php if(isset($review['contacts'])) echo esc_html($review['contacts']) ?></span>
											<a class="right" style="color:black; text-decoration:none" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>contacts" target="_blank"><span class="item-title">Contacts</span></a>
										</p>
									</div>
								</div>
							</div>
							
						</div>
						<div class="row px-3 pt-4">
							<div class="col-md-8 col-lg-9">
						    <p class="lead business"><?php if ((isset($dashdata['company']['name']))) echo esc_html($dashdata['company']['name']) ?></p>
								
							</div>
							<div class="col-md-4 col-lg-3"> 
								<form action="" method="get" id="filterTimeframe">
									<select name="timeframe" id="timeframe" class="form-control selectbox" style='position: relative' onchange="submitTimeframeForm()">
										<option value="" disabled>Time Frame</option>
										<option value="All Time" <?php if($timeframe == "All Time"){ echo esc_html('selected'); } ?>>All Time</option>
										<option value="Last Month" <?php if($timeframe == "Last Month"){ echo esc_html('selected'); } ?>>Last Month</option>
										<option value="Last Week" <?php if($timeframe == "Last Week"){ echo esc_html('selected');} ?>>Last Week</option>
										<option value="Yesterday" <?php if($timeframe == "Yesterday"){ echo esc_html('selected');} ?>>Yesterday</option>
										<!-- <option value="Custom" <?php if($timeframe == "Custom"){ echo esc_html('selected');} ?>>Custom</option> -->
									</select>
									<input type="hidden" name="page" value="Dashboard">
									<input type="hidden" name="action" value="timeframe">
								</form>
							</div>
						</div>
						
						<div class="row mb-3 px-3">
							<div class="col-xl-4 col-sm-6 pt-1">
								<div class="card bg-dash text-black elevation-4 h-10">
									<div class="card-body bg-dash">
										<h5 class="badge-row text-capitalize d-flex flex-nowrap dash-star">
										<svg style="margin-top: -5px;" xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"><path fill="#1a2551" d="m5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22Z"/></svg> <span>Overall Rating</span>
										</h5>
										<p class="rating d-flex flex-nowrap"><label for="rate"><?php if (isset($review['reviews'])) echo esc_html($review['reviews']['average']); ?>/5</label>
											<span class="star">
												<?php if(isset($review['reviews'])){
													$a = doubleval($review['reviews']['average']);
													for ($i = 1; $i <= 5; $i++) { ?>
														<?php if($a < ($i - 0.7)) { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="m8.85 17.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425l-.825 3.575ZM5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22ZM12 13.25Z"/></svg>
														<?php }else if($a < $i) { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="M12 8.125v7.8l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4ZM5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22Z"/></svg>
														<?php }else { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="m5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22Z"/></svg>
														<?php } ?>
													<?php }
												}?>
											</span>
										</p>
										<p class="d-flex justify-space-between flex-wrap mt-2"><span class="d-block left">Based on <?php if ((isset($review['reviews']['total']))) echo esc_html($review['reviews']['total']) ?>
												reviews</span><a class="right" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>reviews" target="_blank"><span class="d-block">View
													Reviews <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 11v2h12l-5.5 5.5l1.42 1.42L19.84 12l-7.92-7.92L10.5 5.5L16 11H4Z"/></svg></span></a>
										</p>
									</div>
								</div>
							</div>
							
							<?php if(isset($dashdata['platforms'])){ foreach($dashdata['platforms'] as $platforms) {  ?>
								<div class="col-xl-4 col-sm-6 pt-1">
									<div class="card bg-dash text-black elevation-4 h-10">
										<div class="card-body bg-dash">
											<h5 class="badge-row text-capitalize d-flex flex-nowrap">
												<div class="company-logo"
													style="background-image: url(<?php echo esc_html($platforms['logo']) ?>); background-repeat: no-repeat; width: 32px; ">
												</div> <span><?php echo esc_html($platforms['name']); ?></span>
											</h5>
											<p class="rating d-flex flex-nowrap"><label
													for="rate"><?php if (isset($review['reviews'])) echo esc_html($review[$platforms['slug']]['average']) ?>/5</label>
												<span class="star">
												<?php if(isset($review[$platforms['slug']]['average'])){
													$a = doubleval($review[$platforms['slug']]['average']);
													for ($i = 1; $i <= 5; $i++) { ?>
														<?php if($a < ($i - 0.7)) { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="m8.85 17.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425l-.825 3.575ZM5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22ZM12 13.25Z"/></svg>
														<?php }else if($a < $i) { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="M12 8.125v7.8l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4ZM5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22Z"/></svg>
														<?php }else { ?>
															<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24"><path fill="#f7c133" d="m5.825 22l1.625-7.025L2 10.25l7.2-.625L12 3l2.8 6.625l7.2.625l-5.45 4.725L18.175 22L12 18.275L5.825 22Z"/></svg>
														<?php } ?>
													<?php }
												}?>
												</span>
											</p>
											<p class="d-flex justify-space-between flex-wrap mt-2"><span class="d-block left">Based on
													<?php if ((isset($review[$platforms['slug']]['total']))) echo esc_html($review[$platforms['slug']]['total']) ?>

													reviews</span><a class="right" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>reviews" target="_blank"><span
														class="d-block">View
														Reviews <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 11v2h12l-5.5 5.5l1.42 1.42L19.84 12l-7.92-7.92L10.5 5.5L16 11H4Z"/></svg></span></a>
											</p>
										</div>
									</div>
								</div>
							<?php } }?>

							<div class="col-xl-4 col-sm-6 pt-1">
								<div class="card bg-dash text-black elevation-4 h-10">
									<div class="card-body bg-dash">
										<h5 class="badge-row text-capitalize d-flex flex-nowrap">
											<div class="company-logo"
												style="background-image: url(<?php echo esc_url(plugins_url('../assets/images/send.png', __FILE__ )) ?>); background-repeat: no-repeat; background-size: cover; background-position: center center; height: 30px; width: 30px; ">
											</div><span>Sent Invitations</span>
										</h5>
										<p class="rating d-flex flex-nowrap"><label for="rate"><?php if(isset($review['campaigns'])) echo esc_html($review['campaigns']) ?></label><span>
										<!--<i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
													class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i
													class="fa-regular fa-star"></i></span></p>
                                           -->
										<p class="d-flex justify-space-between flex-wrap mt-2"><span class="d-block left">
										In chosen time-period</span><a class="right" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>campaigns" target="_blank"><span class="d-block">View
													Invitations <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M4 11v2h12l-5.5 5.5l1.42 1.42L19.84 12l-7.92-7.92L10.5 5.5L16 11H4Z"/></svg></span></a>
										</p>
									</div>
								</div>
							</div>
						</div>
						<!--/row-->

						<div class="row mt-3 px-3">
							<div class="col-md-12 py-2">
								<div class="card bg-white text-black elevation-4 h-10  p-0 m-0" style="max-width:100% !important; padding-left: 0px !important; padding-right: 0px !important;">
									<div class="card-body bg-white px-0 pt-3 pb-0 m-0">

										<div class="row mb-0 px-2 pb-3 m-0" style="border-bottom: 1px solid #DEE2E6;">
											<div class="col-md-9 col-lg-10">
												<p class="lead business">Campaigns Sent(<span
														style="display:inline-block; font-size: 19px; font-weight: bold;">
														<?php if(isset($response_data) && !empty($response)) $count = absint(count($response_data));
    													echo esc_html($count); ?></span>)</p>
											</div>
											
										
											<div class="col-md-3 col-lg-2">
												<form action="" method="get" id="filterForm">
													<select name="channel" class="form-control selectbox"
														style='position: relative' onchange="change()">
														<!--<option value="" name="channel">Select Channel</option> -->
														<option <?php if($search == "Email"){ echo esc_html("selected"); } ?> value="Email" name="channel">Email</option>
														<option <?php if($search == "API"){ echo esc_html("selected"); } ?> value="API" name="channel">API</option>
													</select>
													<input type="hidden" name="page" value="Dashboard">
													<input type="hidden" name="action" value="compaign">
												</form>
									
											</div>
										</div>
										
										<div class="row p-0 m-0">
											<div class="col-lg-12  p-0 m-0 ctable">
												<table class="table compaign-table mb-0">
													<thead class="theader" style="border-bottom: 1px solid #DEE2E6 !important;">
														<tr class="camp-theader">
															<th style="white-space: nowrap;" scope="col">Campaign Name</th>
															<th style="white-space: nowrap;" scope="col">Created On</th>
															<th style="white-space: nowrap;" scope="col">Sent to</th>
															<th colspan="5" scope="col">Outcomes</th>
															<th style="white-space: nowrap;" scope="col">Updated On</th>
														</tr>
														<tr class="camp-tsubheader">
															<?php if(isset($response_data) && !empty($response_data)) { ?>
																<th colspan="3"></th>
																<th class="status-sent">Sent</th>
																<th class="status-open">Open</th>
																<th class="status-pending">Pending</th>
																<th class="status-failed">Failed</th>
																<th class="status-unsubscribed">Unsubscribed</th>
																<th></th>
															<?php } ?>
														</tr>
														<tr>
															<td style="padding: 0px; margin:0px;" colspan="9">
																<hr class="divider"/>
															</td>
														</tr>
													</thead>
													<tbody>
														<?php 
															foreach ($response_data as $campaign) {
															$originalDate = (isset($campaign['created_at'])) ? $campaign['created_at'] : "";
															$updatedDate = (isset($campaign['updated_at'])) ? $campaign['updated_at'] : "";
															$date = date("d M y", strtotime($originalDate));
															$udate = date("d M y", strtotime($updatedDate));
				
														?>
															<tr>
																<td scope="col"> <strong> <?php if(isset($campaign['name'])) echo esc_html($campaign['name']); ?> </strong> </td>
																<td scope="col"><?php echo esc_html($date); ?></td>
																<td scope="col"><?php if(isset($campaign['contacts_count'])) echo esc_html($campaign['contacts_count']); ?>
																</td>
																<td class="status-sent" scope="col">
																<span><?php if(isset($campaign['contacts_count'])) echo esc_html($campaign['contacts_count']); ?></span>
																</td>
																<td class="status-open" scope="col">
																<span><?php if(isset($campaign['open_contacts_count'])) echo esc_html($campaign['pending_contacts_count']); ?></span>
																</td>
																<td class="status-pending" scope="col">
																<span><?php if(isset($campaign['pending_contacts_count'])) echo esc_html($campaign['open_contacts_count']); ?></span>
																</td>
																<td class="status-failed" scope="col">
																<span><?php if(isset($campaign['failed_contacts_count'])) echo esc_html($campaign['failed_contacts_count']); ?></span>
																</td>
																<td class="status-unsubscribed" scope="col">
																<span><?php if(isset($campaign['unsubscribed_contacts_count'])) echo esc_html($campaign['unsubscribed_contacts_count']); ?></span>
																</td>
																<td scope="col"><?php echo esc_html($udate); ?></td>
															</tr>
														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>


									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/main col-->
				</div>
			</div>
			<!--/.container-->

		</div>
	</div>
<?php
}else { ?>
	<div id="login-content">
		<div class="row">
			<div class="container">
				<br /><br /><br /><br />
				<p style="text-align:center;">
					<img src="<?php echo esc_url(plugins_url('../assets/images/logo.png', __FILE__ ));?>" alt="trustmetrics">
				</p><br />
				<h3 style="text-align:center"><b>Welcome to the Trustmetrics Setup wizard!</b></h3>
				<div class="text-center">
					<p style="text-align: center;font-size:16px">You're just minutes away from receiving more reviews for your business.</p>
					<p style="text-align: center;font-size:16px; margin-top:-12px">Experience the full power of Trustmetrics today!</p>
						<button type="button" class="btn btn-lg px-10 px-sm-5 btn-green" id="signup" onclick="showSignUp()">New? Claim your Free Account</button>

				</div>
				<div class="text-center">
					<button type="button" class="btn btn-lg px-10 px-sm-5 btn-blue" onclick="showLogin()">Connect your Existing Account</button>
				</div><br />
			</div>
		</div>
	</div>
<?php } ?>
<?php trustmetrics_enqueue_footer_scripts(); ?>

<?php
	trustmetrics_enqueue_dashboard_script();
?>
</body>
</html>