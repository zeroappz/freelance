<?php
overall_header(__(""));
?>
<!-- Dashboard Container -->
<div class="dashboard-container">

    <?php include_once TEMPLATE_PATH.'/dashboard_sidebar.php'; ?>

    <!-- Dashboard Content
    ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("Proposals") ?></h3>
                <span class="margin-top-7"><?php _e("Bids") ?> <?php _e("for") ?> <a href="<?php _esc($project_link)?>"><?php _esc($project_name)?></a></span>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Proposals") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <!-- Content Start -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-supervisor-account"></i> <?php _esc($totalitem)?> <?php _e("Proposals") ?> </h3>
                        </div>

                        <div class="content">
                            <ul class="dashboard-box-list">
                                <?php foreach($bids as $bid){?>
                                    <li class="ajax-item-listing <?php if($bid['user_id'] == $bid['freelancer_id']){ echo 'awarded'; } ?>" data-item-id="<?php _esc($bid['id'])?>">
                                        <div class="bid">
                                            <!-- Avatar -->
                                            <div class="bids-avatar">
                                                <div class="freelancer-avatar">
                                                    <div class="verified-badge"></div>
                                                    <a href="<?php url("PROFILE") ?>/<?php _esc($bid['username'])?>">
                                                        <img src="<?php _esc($config['site_url'])?>storage/profile/<?php _esc($bid['image'])?>" alt="<?php _esc($bid['name'])?>">
                                                    </a>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="bids-content dashboard-box-list">
                                                <!-- Name -->
                                                <div class="freelancer-name">
                                                    <h4><a href="<?php url("PROFILE") ?>/<?php _esc($bid['username'])?>"><?php _esc($bid['name'])?></a>
                                                        <div class="flag flag-<?php _esc($bid['country_code'])?>" data-tippy-placement="top" title="<?php _esc($bid['country'])?>"></div>
                                                        <?php
                                                        if($bid['user_id'] == $bid['freelancer_id']) {
                                                            echo '<div class="dashboard-status-button green"><i class="icon-feather-award"></i> ' . __("Awarded") . '</div>';
                                                            if($project_status == 'pending_for_approval'){
                                                                echo '<div class="dashboard-status-button yellow">' . __("Pending For Approval") . '</div>';
                                                            }
                                                        }
                                                        ?>
                                                    </h4>
                                                    <?php if($bid['rating'] != '0.0'){ ?>
                                                        <div class="star-rating" data-rating="<?php _esc($bid['rating'])?>"></div><br>
                                                    <?php } ?>

                                                    <div class="margin-top-20 <?php if($bid['showmore'] == '1'){ echo 'show-more'; } ?>">
                                                        <?php _esc($bid['description'])?>
                                                        <?php if($bid['showmore'] == '1'){ ?> <a href="#" class="show-more-button"><?php _e("Show More") ?> <i class="fa fa-angle-down"></i></a>  <?php } ?>
                                                    </div>
                                                    <!-- Buttons -->
                                                    <div class="buttons-to-right always-visible margin-top-25 margin-bottom-0">

                                                        <?php
                                                        if($bid['user_id'] == $bid['freelancer_id']){
                                                            if($project_status == 'pending_for_approval'){

                                                            }
                                                        }else{
                                                            echo '<a href="#small-dialog-1" class="accept-offer button ripple-effect" 
                                                            data-bidid="'._esc($bid['id'],false).'" 
                                                            data-amount="'._esc($bid['amount'],false).'" 
                                                            data-userid="'._esc($bid['user_id'],false).'" 
                                                            data-fullname="'._esc($bid['name'],false).'">
                                                            <i class="icon-material-outline-check"></i> 
                                                            '.__("Accept Offer").'</a>';
                                                        }

                                                        if($config['quickchat_socket_on_off'] == "on" || $config['quickchat_ajax_on_off'] == "on"){
                                                            echo '<a href="javascript:void(0);" class="button dark ripple-effect start_zechat zechat-hide-under-768px"
                                                           data-chatid="'._esc($bid['user_id'],false).'_'._esc($project_id,false).'"
                                                           data-postid="'._esc($project_id,false).'"
                                                           data-userid="'._esc($bid['user_id'],false).'"
                                                           data-username="'._esc($bid['username'],false).'"
                                                           data-fullname="'._esc($bid['name'],false).'"
                                                           data-userimage="'._esc($bid['image'],false).'"
                                                           data-userstatus="'._esc($bid['online'],false).'"
                                                           data-posttitle="'._esc($project_name,false).'"
                                                           data-postlink="'._esc($project_link,false).'"><i class="icon-feather-mail"></i> '.__("Chat now").'</a>';
                                                            echo '<a href="'._esc($bid['quickchat_url'],false).'" class="button dark ripple-effect zechat-show-under-768px"><i class="icon-feather-mail"></i>  '.__("Chat now").'</a>';
                                                        }
                                                        ?>
                                                    </div>

                                                </div>
                                            </div>

                                            <!-- Bid -->
                                            <div class="bids-bid">
                                                <div class="bid-rate">
                                                    <div class="rate"><?php _esc($bid['amount'])?></div>
                                                    <span><?php _e("in") ?> <?php _esc($bid['days'])?> <?php _e("days") ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>


                                <?php } ?>

                            </ul>

                        </div>
                        <!-- Content End -->
                    </div>
                </div>
            </div>
            <!-- Row / End -->


            <!-- Bid Acceptance Popup
            ================================================== -->
            <div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs  popup-dialog">

                <!--Tabs -->
                <div class="sign-in-form">

                    <ul class="popup-tabs-nav">
                        <li><a href="#tab1"><?php _e("Accept Offer") ?></a></li>
                    </ul>

                    <div class="popup-tabs-container">

                        <!-- Tab -->
                        <div class="popup-tab-content" id="tab">
                            <form id="accept-bid-form" method="post" action="#">

                                <div id="accept-bid-status" class="notification error" style="display:none"></div>
                                <!-- Welcome Text -->
                                <div class="welcome-text">
                                    <h3><?php _e("Accept Offer From") ?> <span class="bidder-name"></span></h3>
                                    <div class="bid-acceptance margin-top-15"></div>

                                </div>


                                <input name="bid_id" class="bid-id" value="" type="hidden"/>
                                <div class="radio">
                                    <input id="radio-1" name="radio" type="radio" required>
                                    <label for="radio-1"><span class="radio-label"></span> <?php _e("I have read and agree to the Terms and Conditions") ?></label>
                                </div>


                                <!-- Button -->
                                <button id="accept-bid-button" class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit"> Accept <i class="icon-material-outline-arrow-right-alt"></i></button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Bid Acceptance Popup / End -->

            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
