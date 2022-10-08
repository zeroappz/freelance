<?php
overall_header(__("Dashboard"));
?>
<!-- Dashboard Container -->
<div class="dashboard-container">
    <?php
    include_once TEMPLATE_PATH.'/dashboard_sidebar.php';
    ?>
    <!-- Dashboard Content
    ================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner" >

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3><?php _e("Dashboard") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Dashboard") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Fun Facts Container -->
            <div class="fun-facts-container">
                <?php if($usertype == "user") { ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Won Bid") ?></span>
                            <h4><?php _esc($win_project)?></h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
                    </div>
                    <div class="fun-fact" data-fun-fact-color="#b81b7f">
                        <div class="fun-fact-text">
                            <span><?php _e("Completed Projects") ?></span>
                            <h4><?php _esc($completed_projects)?></h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
                    </div>

                    <?php } elseif($usertype == "employer"){ ?>
                    <div class="fun-fact" data-fun-fact-color="#36bd78">
                        <div class="fun-fact-text">
                            <span><?php _e("Projects Posted") ?></span>
                            <h4><?php _esc($posted_project)?></h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-material-outline-gavel"></i></div>
                    </div>
                    <div class="fun-fact" data-fun-fact-color="#b81b7f">
                        <div class="fun-fact-text">
                            <span><?php _e("Jobs Posted") ?></span>
                            <h4><?php _esc($posted_jobs)?></h4>
                        </div>
                        <div class="fun-fact-icon"><i class="icon-material-outline-business-center"></i></div>
                    </div>

                <?php } ?>
                <div class="fun-fact" data-fun-fact-color="#efa80f">
                    <div class="fun-fact-text">
                        <span><?php _e("Reviews") ?></span>
                        <h4><?php _esc($review_count)?></h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-material-outline-rate-review"></i></div>
                </div>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 ">
                    <div class="dashboard-box margin-top-20">
                        <div class="content with-padding">
                            <div class="row dashboard-profile">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="dashboard-avatar-box">
                                        <img src="<?php _esc($config['site_url'])?>storage/profile/<?php _esc($avatar)?>" alt="<?php _e("Name") ?>">
                                        <div>
                                            <h2><?php _esc($authorname)?></h2>
                                            <small>@ <?php _esc($username)?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12 text-right">
                                <span class="dashboard-badge"><strong><?php _esc($config['currency_sign'])?><?php _esc($balance)?></strong><i
                                            class="fa fa-money"></i> <?php _e("Balance") ?></span>
                                    <span class="dashboard-badge"><strong>
                                            <?php
                                            if($sub_title != ""){
                                                _esc($sub_title);
                                            }else{
                                                _e("Free");
                                            }
                                            ?>
                                    </strong><i class="icon-feather-gift"></i> <?php _e("Membership") ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!$usertype){ ?>
                    <div class="dashboard-box js-accordion-item active">
                        <!-- Headline -->
                        <div class="headline js-accordion-header">
                            <h3><i class="icon-feather-user"></i> <?php _e("Account Details") ?></h3>
                        </div>
                        <div class="content with-padding js-accordion-body">
                            <form method="post" accept-charset="UTF-8">
                                <div class="row">
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("User Type") ?> *</h5>
                                            <select name="user-type" class="with-border selectpicker" required="">
                                                <option><?php _e("Select") ?></option>
                                                <option value="1"><?php _e("Job Seeker") ?></option>
                                                <option value="2"><?php _e("Employer") ?></option>
                                            </select>
                                            <span id="type-availability-status">
                                            <?php if($type_error != ""){ _esc($type_error) ; }?></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="submit_type"
                                        class="button ripple-effect"><?php _e("Save Changes") ?></button>
                            </form>

                        </div>
                    </div>
                    <?php } ?>

                    <div class="dashboard-box js-accordion-item active">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("News Feed") ?></h3>
                        </div>
                        <div class="content">
                            <div class="header-notifications-content with-padding">
                                <ul>
                                    <?php
                                    foreach($notification as $note){
                                        $id = $note['product_id'];
                                        $sender = $note['sender_name'];
                                        $title = $note['product_title'];
                                        $msg = $note['message'];
                                        ?>
                                        <li class="notifications-not-read">
                                            <?php if($note['type'] == "milestone_created"){ ?>
                                            <a href="<?php url("MILESTONE") ?>/<?php _esc($id) ?>">
                                                <span class="notification-icon"><i class="icon-material-outline-assignment"></i></span>
                                                <span class="notification-text"><strong><?php _esc($sender) ?></strong> <?php _e("created a milestone") ?> <?php _esc($msg) ?> <?php _e("for") ?> <span class="color"><?php _esc($title) ?></span></span>
                                            </a>
                                            <?php }elseif($note['type'] == "milestone_request_release"){ ?>
                                            <a href="<?php url("MILESTONE") ?>/<?php _esc($id) ?>">
                                                <span class="notification-icon"><i class="icon-material-outline-assignment"></i></span>
                                                <span class="notification-text"><strong><?php _esc($sender) ?></strong> <?php _e("Request for release") ?> <?php _e("Milestone") ?> <?php _esc($msg) ?> <?php _e("for") ?> <span class="color"><?php _esc($title) ?></span></span>
                                            </a>
                                            <?php }elseif($note['type'] == "milestone_released"){ ?>
                                            <a href="<?php url("MILESTONE") ?>/<?php _esc($id) ?>">
                                                <span class="notification-icon"><i class="icon-material-outline-assignment"></i></span>
                                                <span class="notification-text"><strong><?php _esc($sender) ?></strong> <?php _e("Milestone") ?> <?php _e("Released") ?> <?php _esc($msg) ?> <?php _e("for") ?> <span class="color"><?php _esc($title) ?></span></span>
                                            </a>
                                            <?php }elseif($note['type'] == "deposit"){ ?>
                                            <a href="javascript:void(0);">
                                                <span class="notification-icon"><i class="icon-material-outline-monetization-on"></i></span>
                                                <span class="notification-text"><strong><?php _esc($sender) ?></strong> <?php _e("Deposit") ?> <?php _esc($msg) ?> <?php _e("to") ?> <span class="color"><?php _e("Wallet") ?></span></span>
                                            </a>
                                            <?php }else{ ?>

                                            </a>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->

<?php
include_once TEMPLATE_PATH.'/overall_footer_dashboard.php';
