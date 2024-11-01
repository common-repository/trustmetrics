<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="container-fluid tm-sticky-header">
    <?php if (isset($dashdata['company'])) : ?>
        <?php if ($dashdata['company']['owner']['subscription']['stripe_status'] == "trialing") : ?>
            <div class="tm-alert-message d-flex align-items-center justify-content-center">
                <?php if ($dashdata['company']['owner']['PendingDays'] > 1) : ?>
                    <i class="fa-solid fa-circle-exclamation"></i> <div class="alert-msg"> Trial will expire after <?php echo esc_html($dashdata['company']['owner']['PendingDays']) ?> Days&nbsp;<a class="upgrade-btn" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/billing" target="_blank" style="color:black">Upgrade Now</a></div>
                <?php elseif ($dashdata['company']['owner']['PendingDays'] == 1) : ?>
                    <i class="fa-solid fa-circle-exclamation"></i> <div class="alert-msg"> Your Trial will expire Today&nbsp;<a class="upgrade-btn" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/billing" target="_blank" style="color:black">Upgrade Now</a> </div>
                <?php elseif ($dashdata['company']['owner']['PendingDays'] == 0 && $dashdata['company']['owner']['hasAccessToPlatform']) : ?>
                    <i class="fa-solid fa-circle-exclamation"></i> <div class="alert-msg"> Your Trial will expire Today&nbsp;<a class="upgrade-btn" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/billing" target="_blank" style="color:black">Upgrade Now</a></div>
                <?php elseif ($dashdata['company']['owner']['PendingDays'] == 0 && !$dashdata['company']['owner']['hasAccessToPlatform']) : ?>
                    <i class="fa-solid fa-circle-exclamation"></i> <div class="alert-msg"> Your Trial expired&nbsp;<a class="upgrade-btn" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/billing" target="_blank" style="color:black">Upgrade Now</a></div>
                <?php endif; ?>
            </div>
        <?php elseif ($dashdata['company']['owner']['user_subscription']['plan']['product_name'] == "Free Plan") : ?>
            <div class="tm-alert-message d-flex align-items-center justify-content-center">
            <i class="fa-solid fa-circle-exclamation"></i> <div class="alert-msg">  Your plan has been ended. To continue using Trustmetrics paid services,&nbsp;<a class="upgrade-btn" href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>settings/billing" target="_blank" style="color:black">Upgrade Plan</a></div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="tm-sticky-header-centent row">
        <div class="col-11 col-md-10 px-0 order-2 order-md-1">
            <form style="display:none;" action="" method="post" id="tm-Form">
					<?php wp_nonce_field(); ?>	
                <input type="hidden" name="company_id" class="form-control" id="company-select" />
                <input type="hidden" name="page" value="Dashboard">
            </form>
            <div class="dropdown">
                <a style="margin-top: 5px;" class="btn btn-company dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php
                    if (isset($dashdata['company']['logo']) && $dashdata['company']['logo'] != "") {
                        ?>
                        <img src="<?php echo esc_url(isset($dashdata['company']['logo']) ? $dashdata['company']['logo'] : ''); ?>" alt="" class="" width="20" height="20" style="border-radius: 20px;">
                    <?php
                    } else {
                        ?>
                        <i class="fa fa-home" aria-hidden="true"></i>&nbsp;
                        <?php
                    }
                        ?>
                        &nbsp;<?php if(isset($dashdata['company']['name'])){ echo esc_html($dashdata['company']['name']); }; 
                        ?>
                </a>
                <ul class="dropdown-menu tm-dropdown-menu">
                    <?php
                    if (isset($result['companies'])) {
                        foreach ($result['companies'] as $company) {
                            ?>
                            <li style="<?php if(isset($dashdata['company']) && ($dashdata['company']['id'] == $company['id'])){ ?> display:flex <?php } ?>">
                                <a onclick="clickCom(this)" class="tm-company dropdown-item <?php if(isset($dashdata['company']) && ($dashdata['company']['id'] == $company['id'])) { ?> active company-name <?php }else{ ?> inactive <?php } ?>" data-val="<?php echo esc_attr($company['id']); ?>" href="javascript:void(0)">
                                    <?php
                                    if (isset($company['logo']) && $company['logo'] != "") {
                                        ?>
                                        <img src="<?php echo esc_url($company['logo']); ?>" alt="" class="" width="20" height="20" style="border-radius: 20px;">
                                    <?php
                                    } else {
                                        ?>
                                        <i class="fa fa-home" aria-hidden="true"></i>
                                    <?php
                                    }
                                    ?>
                                    &nbsp;<?php if(isset($company['name'])){ echo esc_html($company['name']); } ?>
                                </a>
                                <?php
                                if (isset($dashdata['company']) && ($dashdata['company']['id'] == $company['id'])) {
                                    ?>
                                    <a href="<?php echo esc_url(TRUSTMETRICS_API_ENDPOINT_FRONTEND); ?>business/update" target="_blank" class="tm-company dropdown-item <?php if(isset($dashdata['company']) && ($dashdata['company']['id'] == $company['id'])) { ?> active edit-btn <?php } ?>">Edit</a>
                                <?php
                                }
                                ?>
                            </li>
                    <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-1 col-md-2 px-0 order-3">
            <form class="ml-auto" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="logout">
                <button data-bs-toggle="tooltip" data-bs-placement="left" title="Logout" type="submit" class="btn btn-lg px-1 ml-auto" style="border: none; margin-top: 3px; float: right;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path fill="#a6a6a6" d="M19 3H5c-1.11 0-2 .89-2 2v4h2V5h14v14H5v-4H3v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2m-8.92 12.58L11.5 17l5-5l-5-5l-1.42 1.41L12.67 11H3v2h9.67l-2.59 2.58Z" />
                    </svg>&nbsp;&nbsp;
                </button>
            </form>

        </div>
    </div>
</div>