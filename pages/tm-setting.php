<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
        require_once('common.php');
        $url = TRUSTMETRICS_API_ENDPOINT . 'api/user/review/request/pending';
        $token = get_transient('trustmetrics_custom_auth_token');
        $headers = array(
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        );

        $response = wp_remote_post(
            $url,
            array(
                'headers' => $headers,
            )
        );

        $pending_requests = json_decode(wp_remote_retrieve_body($response), true);
        
?>

<!DOCTYPE html>
<html lang="en">

<?php if(isset($token)){ ?>
<head>
	<?php 
        include_once('tm-header.php');
        trustmetrics_enqueue_setting_styles();
    ?>
	<title><?php echo esc_html__('Trustmetrics Setting', 'trustmetrics'); ?></title>
    
</head>

<body>
<div class="tm-container" >
	<?php include_once('sticky-header.php') ?>
    <div class="container-fluid">
        <div id="main">
            <div class="row row-offcanvas row-offcanvas-left">
                <div class="col-12">
                    <div class="row bg-dash setting-head">
                        <div class="col-sm-5 col-md-6 col-lg-7">
                            <p class="setting">Settings</p>
                        </div>
                        <div class="col-sm-7 col-md-6 col-lg-5">
                            <div class="row">
                                <div class="col-6 pr-0" style="<?php if(isset($result) && isset($result['company']['owner']['user_subscription']['plan']['product_name']) && ($result['company']['owner']['user_subscription']['plan']['product_name'] == "AppSumo Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Free Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Startup Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Agency Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Pro Plan" || ($result['company']['owner']['user_subscription']['plan']['product_name'] == "Enterprise Plan" && $result['company']['role']['user_role'] == 2 || $result['company']['role']['user_role'] == 3) || $result['company']['role']['user_role'] == 2)){ ?> margin-left:230px <?php } ?>">
                                    <p class="setting-item">
                                    <span class="label">
                                        <?php
                                            if (isset($result) && isset($result['company']['owner']['user_subscription']['plan']['product_name'])) {
                                                echo esc_html($result['company']['owner']['user_subscription']['plan']['product_name']);
                                            }
                                            ?>
                                            <?php
                                            if (isset($dashdata['company'])) {
                                                if ($dashdata['company']['owner']['subscription']['stripe_status'] == "canceled") {
                                                    echo esc_html__("(Canceled)", 'trustmetrics');
                                                } else if ($dashdata['company']['owner']['subscription']['stripe_status'] == "trialing") {
                                                    echo esc_html__("(Trial)", 'trustmetrics');
                                                } else {
                                                    echo esc_html__("(Active)", 'trustmetrics');
                                                }
                                            }
                                         ?>
                                    </span>

                                    <span class="value">
                                        <?php
                                            if (isset($result) && isset($result['company']['owner']['user_subscription']['plan']['product_name']) && $result['company']['owner']['user_subscription']['plan']['product_name'] != "Free Plan") {
                                                echo esc_html($pending_requests['sent']) . "/";
                                            }
                                            if (isset($result) && isset($result['company']) && $result['company']['owner']['user_subscription']['plan']['product_name'] != "Free Plan") {
                                                
                                                printf(
                                                    esc_html(
                                                        _n(
                                                            '%s Review Request',
                                                            '%s Review Requests',
                                                            $dashdata['company']['owner']['subscription']['monthly_limit'],
                                                            'trustmetrics'
                                                        )
                                                    ),
                                                    esc_html( number_format_i18n( $dashdata['company']['owner']['subscription']['monthly_limit'] ) )
                                                );
                                            }
                                        ?>
                                    </span>

                                    </p>
                                </div>
                                <?php if(isset($result) && isset($result['company']['owner']['user_subscription']['plan']['product_name']) && ($result['company']['owner']['user_subscription']['plan']['product_name'] == "AppSumo Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Free Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Startup Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Agency Plan" || $result['company']['owner']['user_subscription']['plan']['product_name'] == "Rockethub Pro Plan" || ($result['company']['owner']['user_subscription']['plan']['product_name'] == "Enterprise Plan" && $result['company']['role']['user_role'] == 2 || $result['company']['role']['user_role'] == 3) || $result['company']['role']['user_role'] == 2)){ }else{ ?>
                                    <div class="col-6 pr-0 setting-col-last">
                                        <p class="setting-item-last">
                                            <span class="label" style="margin-left: 10px">
                                                <?php
                                                if (isset($dashdata['company']) && $dashdata['company']['owner']['subscription']['stripe_status'] == "canceled") {
                                                    echo esc_html__("Subscription Ends", 'trustmetrics');
                                                } else if (isset($dashdata['company']) && $dashdata['company']['owner']['subscription']['stripe_status'] == "trialing") {
                                                    echo esc_html__("Trial Ends", 'trustmetrics');
                                                } else {
                                                    echo esc_html__("Next Payment", 'trustmetrics');
                                                }
                                                ?>
                                            </span>
                                            <span class="value" style="margin-left: 10px">
                                                <?php
                                                echo esc_html(date("d M y", strtotime($dashdata['company']['owner']['subscription']['stripe_plan_expires_at'])));
                                                ?>
                                            </span>
                                        </p> 
                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                        
                    </div>
					
                    <div class="row">
                        <div class="col-12 px-0">
                            <ul class="nav mb-3 px-4 bg-dash" id="settingTab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" id="profile-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="pills-home" aria-selected="true">Profile</a>
                                </li>
                               
                              </ul>
							  
												
                              <div class="tab-content px-4" id="settingTabContent">
                                <!-- PROFILE SECTION -->
                                
								<div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                 <div class="profile-form">
								  <div class="card shadow-sm px-0 py-0">
								   <div style="font-weight: 500; color: #252F59;" class="card-header profile-header bg-white py-3 text-center">
                                    Profile
                                    </div>
									<div class="card-body">
							     	  
                                       <form action="" method="post" id="update-user-form">
									  <div class="form-group pb-2 pt-1">
                                        <label for="firstName">First Name</label>
                                        <input type="text" class="form-control" name="name" value="<?php if (isset($admin_detail->name)) echo esc_html($admin_detail->name); ?>">
                                      </div>
                                    <div class="form-group pb-2 pt-3">
                                       <label for="lastName">Last Name</label>
                                       <input type="text" class="form-control" name="last_name" value="<?php if (isset($admin_detail->last_name)) echo esc_html($admin_detail->last_name); ?>">
                                     </div>
                                    <div class="form-group pb-2 pt-3">
                                     <label for="email">Email address</label>
                                     <input type="email" class="form-control" id="email" value="<?php if (isset($admin_detail->email)) echo esc_html($admin_detail->email); ?>" readonly style="background: white; color: #8d8e8e;" />
                                    </div>
									<?php $token= "";
                                        if(isset($token)){
                                            $token= get_transient('trustmetrics_custom_auth_token');
                                        }  
                                        // Check if the token is set and valid
                                       if (!empty($token)  ) {
                                          ?>
                                        <div class="mt-4">
                                            <button type="submit" class="btn ml-1" style="background-color:#6cd37d;color:white" disabled>SAVE CHANGES</button>
                                            <a class="btn" style="background-color:#4a90e2;color:white" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/profile/change-password" target="_blank">CHANGE PASSWORD</a>
                                         </div>
                                         <?php } else { ?>
                                        <div class="mt-2">
                                       <button type="submit" class="btn" style="background-color:#4a90e2;color:white" disabled>CHANGE PASSWORD</button>
                                       <button type="submit" class="btn ml-1" disabled style="background-color:#6cd37d;color:white">SAVE CHANGES</button>
                                     </div>
                                       <?php } ?>
			                                									
                                     </form>
									 
									 
									 </div>
									</div>
										  
                                    </div>
									
                                </div>
						
                        </div>
					
                    </div>
					
               </div>
			   
			   
               
            </div>

        </div>
        <!--/.container-->
    </div>
    </div>
    <?php trustmetrics_enqueue_footer_scripts(); ?>
    

</body>
<?php } ?>
</html>