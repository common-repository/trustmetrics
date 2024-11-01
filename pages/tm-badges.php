<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once('common.php');

	if (isset($token)) {
		$cookieValue = sanitize_text_field($token);
		$badges = [];
		
		$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
		$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
		$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';

		$filter = array(
			"search" => $search,
			"status" => $status,
			"type"   => $type,
		);
	
		$url = TRUSTMETRICS_API_ENDPOINT . 'api/badges?' . http_build_query($filter);
	
		$headers = array(
			'Authorization' => 'Bearer ' . $cookieValue,
			'Content-Type'  => 'application/json',
		);
	
		// Make the HTTP GET request
		$response = wp_remote_get(
			$url,
			array(
				'headers' => $headers,
			)
		);
	
		// Check if the request was successful
		if (is_array($response) && !is_wp_error($response)) {
			// Get the response body
			$body = wp_remote_retrieve_body($response);
			$badgePage1 = json_decode($body, true);
			$page = ceil($badgePage1['total'] / 20);
	
			if ($page > 1) {
				for ($i = 1; $i <= $page; $i++) {
					$url = TRUSTMETRICS_API_ENDPOINT . 'api/badges?page=' . $i;
	
					// Make the HTTP GET request for each page
					$response = wp_remote_get(
						$url,
						array(
							'headers' => $headers,
						)
					);
	
					// Get the response body for each page
					$badges[$i] = json_decode(wp_remote_retrieve_body($response), true);
				}
			} else {
				$badges[1] = $badgePage1;
			}
		} else {
			// Handle the error
			$error_message = is_wp_error($response) ? $response->get_error_message() : 'Unknown error';
			echo esc_html($error_message);
		}
	}	

?>


<!DOCTYPE html>
<html lang="en">

<head>
	<?php 
		include_once('tm-header.php');
		trustmetrics_enqueue_badge_styles();
	?>
	<title>Badges</title>
</head>

<body>

	<div class="tm-container">
		<?php include_once('sticky-header.php')?>
		<?php $i = 1;?>
		<div class="badge-page">
			<div class="container-fluid">
				<div class="row bg-dash">
					<div class="col-sm-2">
						<h1 class="dashboard float-left">Badges</h1>
						
					</div>
					<div class="col-sm-6"></div>
					<div class="col-sm-4">
						<div class="alert alert-success" role="alert" style="display: none;">
							<strong>Success</strong> <br>Badge Embed Script Copied to Clipboard.
						</div>
					</div>
				</div>

				<section>
					<form action="" method="GET" name="filterForm" id="filterForm">
					
					<?php wp_nonce_field(); ?>	
					<div class="row filters">
							<div class="col">
								<div class="row">
									<div class="col-md-10">
										<input type="search" id="search" name="search" placeholder="&#128269; Search"
											class="filter-content"
											style="border: none;padding-top: 2px;font-weight: bolder;font-size: 14px;"
											value="<?php if($filter){ echo esc_attr($filter['search']); } ?>"
											onkeyup="filter()">
									</div>
									<div class="col-md-2"> <a type="button" id="clear-search"
											style="<?php if ($filter['search'] != '') { ?> display:block <?php } else { ?> display:none <?php } ?>"
											onclick="clear_search()">&times;</a> </div>
								</div>
							</div>
							<div class="col ">
								<div class="row">
									<div class="col-md-10">
										<select name="status" id="status" class="select" placeholder="Status"
											onchange="filter()">
											<option value="" class="form-control" disabled selected>Status</option>
											<option value="active" class="form-control"
												<?php if ($filter['status'] == 'active') { echo esc_html('selected'); }?>>Active
											</option>
											<option value="inactive" class="form-control"
												<?php if ($filter['status'] == 'inactive') { echo esc_html('selected'); }?>>
												Inactive</option>
										</select>
									</div>
									<div class="col-md-2"> <a type="button" id="clear-status"
											style="<?php if ($filter['status'] != '') { ?> display:block <?php } else { ?> display:none <?php }?>"
											onclick="clear_status()">&times;
										</a> </div>
								</div>
							</div>
							<div class="col">
								<div class="row">
									<div class="col-md-10">
										<select name="type" id="type" class="select" onchange="filter()">
											<option value="" class="form-control" disabled selected>Layout</option>
											<option value="badge" class="form-control"
												<?php if ($filter['type'] == 'badge') { echo esc_html('selected'); }?>>Badge
											</option>
											<option value="ribbon" class="form-control"
												<?php if ($filter['type'] == 'ribbon') { echo esc_html('selected'); }?>>Ribbon
											</option>
											<option value="floating-badge" class="form-control"
												<?php if ($filter['type'] == 'floating-badge') { echo esc_html('selected'); }?>>Floating Badge
											</option>
										</select>
									</div>
									<div class="col-md-2"> <a id="clear-type" type="button"
											style="<?php if ($filter['type'] != '') { ?> display:block <?php } else { ?> display:none <?php }?>"
											onclick="clear_type()">&times;</a> </div>
								</div>
							</div>
							<div class="col">
								<a href="<?php echo esc_url(site_url('/wp-admin/admin.php?page=Badges')); ?>" class="clear"
									id="clear"
									style="<?php if ($filter['search'] != "" || $filter['status'] != "" || $filter['type'] != "") { ?> display:block <?php }else{ ?> display:none <?php } ?>"
									onclick="resetForm()">&times; Clear</a>
							</div>

						</div>
						<input type="hidden" name="page" value="Badges">
					</form>
				</section>

				<div class="card elevation-1 px-0 pt-3 mt-4 mx-1">
					<div class="card-header">
						<div class="row">
							<div class="col-md-6">
								<p class="lead business">Total Badges (<span><?php if(!empty($cookieValue) && isset($badgePage1)){
										echo esc_html($badgePage1['total']);  } ?></span>)
								</p>
							</div>
							<div class="col-md-6">
								<div class="col dropdown list_view tm-showhide">
									<button type="button" class="btn dropdown-toggle show_hide"
										data-bs-toggle="dropdown" style="border: 1px !important">
										Show/Hide Columns
									</button>
									<ul class="dropdown-menu">
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" value="show_name"
													id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="show_name">Name</label>
											</div>
										</li>
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" data-target="1"
													value="show_date" id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="show_date">Date</label>
											</div>
										</li>
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" data-target="2"
													value="show_status" id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="show_status">Status</label>
											</div>
										</li>
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" data-target="3"
													value="show_views" id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="show_views">Views</label>
											</div>
										</li>
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" data-target="4"
													value="show_layout" id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="show_layout">Layout</label>
											</div>
										</li>
										<li>
											<div class="dropdown-item">
												<input class="form-check-input" type="checkbox" data-target="5"
													value="show_controls" id="show_hide" name="show_hide" checked />
												<label class="form-check-label" for="Checkme1">Controls</label>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</div>

					</div>
					<div class="card-body tm-responsive">
						<table id="example" class="display table table-hover" style="width:100%">
							<thead class="">
								<tr class="">
									<th class="">
										Name
									</th>
									<th class="">
										Date
									</th>
									<th class="">
										Status
									</th>
									<th class="">
										Views
									</th>
									<th class="">
										Layout
									</th>
									<th class="" style="text-align: right;">
										Controls
									</th>
								</tr>
							</thead>
							<tbody class="">
							<?php if (!empty($cookieValue) && isset($badges) ) { ?>
									<?php foreach($badges as $result){ 
										foreach($result['data'] as $res){
												$token = esc_html($res['token']);
											?>

								<tr class="">
									<td class="">

										<b><?php echo esc_html($res['name']); ?></b>

									</td>
									<td>
										<?php echo esc_html($res['created_at']); ?>
									</td>
									<td class="">
										<?php if($res['status'] == 'active'){?>
											 <span class="active">Active<span>
										<?php }else{ ?>
											 <span class="inactive">Inactive<span>
										<?php } ?>
									</td>
									<td class="">
										<?php echo esc_html($res['views']); ?>
									</td>
									<td class="">
										<span class="type">
											<?php 
											if($res['type'] == 'badge'){
												echo esc_html("Badge");
											}else if($res['type'] == 'ribbon'){
												echo esc_html("Ribbon");
											}else if($res['type'] == 'floating-badge'){
												echo esc_html("Floating-Badge");
											} 
											?> 
										</span>
									</td>
									<td class="" style="text-align: right;">
										<div class="dropdown dropleft">
											<button type="button" class="btn" data-bs-toggle="dropdown">
												<i class="fa-solid fa-ellipsis-vertical"></i>
											</button>
											<div class="dropdown-menu" aria-labelledby="#dropdownMenuButton">
												<div class="dropdown-item pointer" onclick="CopyToClipboard(<?php echo esc_html($i);?>)">
													Copy Shortcode</div>
												<textarea id="div_id<?php echo esc_html($i);?>" style="display: none;"> <?php $token = $res['token']; $type = "Badge Widget"; if($res['type'] == "floating-badge"){ $type = "Floating Badge"; }$badge = "[trustmetrics_badge token='$token' type='$type']"; echo esc_html($badge); ?> </textarea>
											</div>
										</div>
									</td>
								</tr>
								<?php $i = $i+1;} }?>
								<?php } else { ?>

								<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
				</section>
			</div>
		</div>
	</div>
	<?php trustmetrics_enqueue_footer_scripts(); ?>
	<?php
		trustmetrics_enqueue_badge_script();
	?>
</body>

</html>