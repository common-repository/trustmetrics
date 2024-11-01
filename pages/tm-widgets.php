<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once('common.php');
	// Check if tm_token is set in the COOKIE
	if (isset($token)) {
		$cookieValue = sanitize_text_field($token);
		$widgets = [];

		$search = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
		$status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
		$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : '';

		$filter = array(
			"search" => $search,
			"status" => $status,
			"type"   => $type,
		);
	
		$url = TRUSTMETRICS_API_ENDPOINT . 'api/widgets?' . http_build_query($filter);
	
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
			$widgetPage1 = json_decode($body, true);
			$page = ceil($widgetPage1['total'] / 20);
	
			if ($page > 1) {
				for ($i = 1; $i <= $page; $i++) {
					$url = TRUSTMETRICS_API_ENDPOINT . 'api/widgets?page=' . $i;
	
					// Make the HTTP GET request for each page
					$response = wp_remote_get(
						$url,
						array(
							'headers' => $headers,
						)
					);
	
					// Get the response body for each page
					$widgets[$i] = json_decode(wp_remote_retrieve_body($response), true);
				}
			} else {
				$widgets[1] = $widgetPage1;
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
		trustmetrics_enqueue_widget_styles();
	?>
	<title>Widget</title>
</head>

<body>
	<div class="tm-container">
		<?php include_once('sticky-header.php') ?>
		<?php $i = 1;?>
		<div class="widget-page">
			<div class="container-fluid">
				<div class="row bg-dash">
					<div class="col-sm-2">
						<h1 class="dashboard float-left">Widgets</h1>
					</div>
					<div class="col-sm-6"></div>
					<div class="col-sm-4">
						<div class="alert alert-success" role="alert" style="display: none;">
							<strong>Success</strong> <br>Widget Embed Script Copied to Clipboard.
						</div>
					</div>
				</div>
				
				<section>
					<form action="" method="GET" name="filterForm" id="filterForm">
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
									<div class="col-md-2">	<a type="button" id="clear-search" style="<?php if ($filter['search'] != '') { ?> display:block <?php } else { ?> display:none <?php }?>" onclick="clear_search()">&times;</a> </div>
								</div>	
							</div>
							<div class="col ">
								<div class="row">
									<div class="col-md-10">
										<select name="status" id="status" class="select" placeholder="Status" onchange="filter()">
											<option value="" class="form-control" disabled selected>Status</option>
											<option value="active" class="form-control" <?php if ($filter['status'] == 'active') { echo esc_html('selected'); }?>>Active</option>
											<option value="inactive" class="form-control" <?php if ($filter['status'] == 'inactive') { echo esc_html('selected'); }?>>Inactive</option>
										</select>
									</div>
									
									<div class="col-md-2">	<a type="button" id="clear-status"  style="<?php if ($filter['status'] != '') { ?> display:block <?php } else {?> display:none`
										 <?php } ?>" onclick="clear_status()">&times;</a> </div>
								</div>
							</div>
							<div class="col">
								<div class="row">
									<div class="col-md-10">
										<select name="type" id="type" class="select" onchange="filter()">
										<option value="" class="form-control" disabled selected>Layout</option>
										<option class="form-control" value="floating-widget" <?php if ($filter['type'] == 'floating-widget') { echo esc_html('selected'); }?>>Floating Widget</option>
										<option class="form-control" value="slider" <?php if ($filter['type'] == 'slider') { echo esc_html('selected'); }?>>Review Slider</option>
										<option class="form-control" value="box-slider" <?php if ($filter['type'] == 'box-slider') { echo esc_html('selected'); }?>>Box Slider</option>
										<option class="form-control" value="vertical-slider" <?php if ($filter['type'] == 'vertical-slider') { echo esc_html('selected'); }?>>Vertical Slider</option>
										<option class="form-control" value="carousel" <?php if ($filter['type'] == 'carousel') { echo esc_html('selected'); }?>>Carousel Slider</option>
										<option class="form-control" value="popup" <?php if ($filter['type'] == 'popup') { echo esc_html('selected'); }?>>Popup</option>
										<option class="form-control" value="pagination" <?php if ($filter['type'] == 'pagination') { echo esc_html('selected'); }?>>Pagination</option>
										<option class="form-control" value="grid" <?php if ($filter['type'] == 'grid') { echo esc_html('selected'); }?>>Grid</option>
										<option class="form-control" value="video-widget" <?php if ($filter['type'] == 'video-widget') { echo esc_html('selected'); }?>>Video Testimonial</option>
										<option class="form-control" value="source-widget" <?php if ($filter['type'] == 'source-widget') { echo esc_html('selected'); }?>>Source Widget</option>
										<option class="form-control" value="3d-carousel" <?php if ($filter['type'] == '3d-carousel') { echo esc_html('selected'); }?>>3D Carousel Widget</option>
										<option class="form-control" value="highlights-widget" <?php if ($filter['type'] == 'highlights-widget') { echo esc_html('selected'); }?>>Highlights Widget</option>
										</select>
									</div>
									<div class="col-md-2">	<a id="clear-type" type="button"  style="<?php if ($filter['type'] != '') { ?> display:block <?php } else { ?> display:none <?php } ?>" onclick="clear_type()">&times;</a> </div>
								</div>
							</div>
							<div class="col">
								<a href="<?php echo esc_url(site_url('/wp-admin/admin.php?page=Widgets')); ?>" class="clear" id="clear" style="<?php if ($filter['search'] != "" || $filter['status'] != "" || $filter['type'] != "") { ?> display:block <?php }else{ ?> display:none <?php } ?>" onclick="resetForm()">&times; Clear</a>
							</div>
							
						</div>
						<input type="hidden" name="page" value="Widgets">
					</form>
				</section>

				<div class="card elevation-1 px-0 pt-3 mt-4 mx-1">
						<div class="card-header">
							<div class="row">
								<div class="col-md-6">
									<p class="lead business">Total Widgets (<?php if($cookieValue != null && !empty($cookieValue) && isset($response)){
											echo esc_html($widgetPage1['total']); } ?>)
									</p>
								</div>
								<div class="col-md-6">
									<div class="dropdown list_view tm-showhide">
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
							<table id="example" class="table table-hover display" style="width:100%">
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
									<?php if (!empty($cookieValue) && isset($widgets) ) { ?>
									<?php foreach($widgets as $result){ 
										foreach($result['data'] as $res){
												$token = esc_html($res['token']);
											?>
											<tr class="">
												<td class="">
													<b><?php echo esc_html($res['name']); ?></b>
												</td>
												<td>
													<?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $res['created_at'] ) ) ); ?>
												</td>
												<td class="">
													<?php if($res['status'] == 'active'){ ?>
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
															$type = esc_html($res['type']);

															switch ($type) {
																case 'floating-widget':
																	echo esc_html__("Floating-Widget",'trustmetrics');
																	break;
																case 'slider':
																	echo esc_html__("Slider",'trustmetrics');
																	break;
																case 'box-slider':
																	echo esc_html__("Box-Slider",'trustmetrics');
																	break;
																case 'vertical-slider':
																	echo esc_html__("Vertical-Slider",'trustmetrics');
																	break;
																case 'carousel':
																	echo esc_html__("Carousel",'trustmetrics');
																	break;
																case 'popup':
																	echo esc_html__("Popup",'trustmetrics');
																	break;
																case 'pagination':
																	echo esc_html__("Pagination",'trustmetrics');
																	break;
																case 'grid':
																	echo esc_html__("Grid",'trustmetrics');
																	break;
																case 'video-widget':
																	echo esc_html__("Video-Widget",'trustmetrics');
																	break;
																case 'source-widget':
																	echo esc_html__("Source-Widget",'trustmetrics');
																	break;
																case '3D-carousel':
																	echo esc_html__("3D-Carousel",'trustmetrics');
																	break;
																case 'highlights-widget':
																	echo esc_html__("Highlights-Widget",'trustmetrics');
																	break;
																default:
																	// Handle unknown types, if necessary
																	break;
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
															<div class="dropdown-item pointer hover" onclick="CopyToClipboard(<?php echo esc_html($i);?>)">Copy Shortcode
															</div>
															<textarea id="div_id<?php echo esc_html($i);?>" style="display: none;"> <?php $token = $res['token']; if($res['type'] == 'floating-widget'){ $widget = "[trustmetrics_badge token='".$token."' type='Floating Widget']"; }else{ $type = $res['type']; $widget = "[trustmetrics_widget token='".$token."' type='".$type."']"; } echo esc_html($widget); ?></textarea>
														</div>
													</div>
												</td>
											</tr>
									<?php $i = $i+1;}}?>
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
		trustmetrics_enqueue_widget_script();
	?>

</body>

</html>