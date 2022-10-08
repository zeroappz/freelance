<?php
overall_header(__("Notifications"));
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
                <h3><?php _e("Notifications") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Notifications") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <div class="headline">
                            <h3><i class="icon-material-baseline-notifications-none"></i> <?php _e("Notifications") ?></h3>
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

                            <div class="row">
                                <div class="col-md-12">
                                    <!-- Pagination -->
                                    <div class="pagination-container margin-top-20">
                                        <nav class="pagination">
                                            <ul>
                                                <?php
                                                foreach($pages as $page) {
                                                    if ($page['current'] == 0){
                                                        ?>
                                                        <li><a href="<?php _esc($page['link'])?>"><?php _esc($page['title'])?></a></li>
                                                    <?php }else{
                                                        ?>
                                                        <li><a href="#" class="current-page"><?php _esc($page['title'])?></a></li>
                                                    <?php }
                                                }
                                                ?>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
