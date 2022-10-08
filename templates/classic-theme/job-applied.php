<?php
overall_header(__("Applied Jobs"));
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
                <h3><?php _e("Applied Jobs") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Applied Jobs") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-briefcase"></i> <?php _e("Applied Jobs") ?></h3>
                        </div>
                        <?php
                        if(!$totalitem) {
                            echo '<div class="content with-padding text-center">'.__("No jobs found.").'</div>';
                        }
                        ?>
                    </div>
                    <div class="listings-container margin-top-30">
                        <?php foreach($items as $item){ ?>
                            <div class="job-listing fav-listing">
                                <div class="job-listing-details">
                                    <div class="job-listing-company-logo">
                                        <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>" alt="<?php _esc($item['product_name'])?>">
                                    </div>
                                    <div class="job-listing-description">
                                        <?php if($config['company_enable']){ ?>
                                            <h4 class="job-listing-company"><?php _esc($item['company_name'])?></h4>
                                        <?php } ?>
                                        <h3 class="job-listing-title"><a href="<?php _esc($item['link'])?>"><?php _esc($item['product_name'])?></a>
                                        <p class="job-listing-text"><?php _esc($item['desc'])?></p>
                                    </div>
                                    <span class="job-type"><?php _esc($item['product_type'])?></span>
                                </div>
                                <div class="job-listing-footer with-icon">
                                    <ul>
                                        <li><i class="la la-map-marker"></i> <?php _esc($item['location'])?></li>
                                        <?php if($item['salary_min'] != "0"){ ?>
                                            <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> - <?php _esc($item['salary_max'])?> <?php _e("Per") ?> <?php _esc($item['salary_type'])?></li>
                                        <?php }?>
                                        <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                    </ul>
                                    <span class="fav-icon added set-item-fav" data-item-id="<?php _esc($item['id'])?>" data-userid="<?php _esc($user_id)?>" data-action="removeFavAd"></span>
                                </div>
                            </div>
                        <?php } ?>

                        <!-- Pagination -->
                        <div class="clearfix"></div>
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
            <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>