<?php
overall_header(__("My Experiences"));
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
                <h3><?php _e("My Experiences") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("My Experiences") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">
                        <div class="headline">
                            <h3><i class="icon-feather-award"></i> <?php _e("My Experiences") ?></h3>
                            <a href="<?php url("ADD_EXPERIENCE") ?>" class="button ripple-effect"><?php _e("Add New Experience") ?></a>
                        </div>
                        <?php
                        if(!$totalitem) {
                            echo '<div class="content with-padding text-center">'.__("No result found.").'</div>';
                        }
                        ?>
                    </div>
                    <div class="listings-container margin-top-35">
                        <?php foreach ($items as $item){ ?>
                            <div class="job-listing experience-row" data-item-id="<?php _esc($item['id'])?>">
                                <div class="job-listing-details">
                                    <div class="job-listing-description">
                                        <h4 class="job-listing-company"><?php _esc($item['company'])?></h4>
                                        <h3 class="job-listing-title"><?php _esc($item['title'])?></h3>
                                        <p class="job-listing-text read-more-toggle" data-read-more="<?php _e("Read more") ?>" data-read-less="<?php _e("Read less") ?>"><?php _esc($item['description'])?></p>
                                    </div>
                                    <a href="<?php url("EDIT_EXPERIENCE") ?>/<?php _esc($item['id'])?>" class="" data-tippy-placement="top" title="<?php _e("Edit") ?>"><i class="icon-feather-edit"></i></a>
                                    <a href="#" class="margin-left-10 ajax-delete-experience" data-tippy-placement="top" title="<?php _e("Delete") ?>"><i class="icon-feather-trash-2"></i></a>
                                </div>
                                <div class="job-listing-footer with-icon">
                                    <ul>
                                        <li><i class="la la-clock-o"></i> <?php _esc($item['start_date'])?> - <?php _esc($item['end_date'])?></li>
                                        <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?></li>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>