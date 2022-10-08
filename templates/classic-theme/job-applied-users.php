<?php
overall_header(__("Applied Users"));
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
        <h3><?php _e("Applied Users") ?></h3>
        <!-- Breadcrumbs -->
        <nav id="breadcrumbs" class="dark">
            <ul>
                <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                <li><?php _e("Applied Users") ?></li>
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
                    <h3><i class="icon-feather-users"></i> <?php _e("Applied Users") ?> &ndash; <?php _esc($product_name)?></h3>
                </div>
                <?php
                if(!$totalitem) {
                    echo '<div class="content with-padding text-center">'.__("No users found.").'</div>';
                }
                ?>
            </div>
            <div class="listings-container margin-top-35">
                <?php foreach ($items as $item){ ?>
                    <div class="job-listing">
                        <div class="job-listing-details">
                            <div class="job-listing-company-logo">
                                <a href="<?php url("PROFILE") ?>/<?php _esc($item['username'])?>">
                                    <img src="<?php _esc($config['site_url'])?>storage/profile/<?php _esc($item['image'])?>" alt="<?php _esc($item['name'])?>">
                                </a>
                            </div>
                            <div class="job-listing-description">
                                <h3 class="job-listing-title"><a href="<?php url("PROFILE") ?>/<?php _esc($item['username'])?>"><?php _esc($item['name'])?></a>
                                    <?php
                                    if($item['sex'] == 'Male') {
                                        echo '<span class="gender-badge male" title="'.__("Male").'" data-tippy-placement="top"><i class="la la-mars"></i></span>';
                                    } elseif ($item['sex'] == 'Female') {
                                        echo '<span class="gender-badge female" title="'.__("Female").'" data-tippy-placement="top"><i class="la la-venus"></i></span>';
                                    } elseif ($item['sex'] == 'Other') {
                                        echo '<span class="gender-badge other" title="'.__("Other").'" data-tippy-placement="top"><i class="la la-transgender"></i></span>';
                                    }
                                    ?>
                                </h3>

                                <p class="job-listing-text read-more-toggle" data-read-more="<?php _e("Read more") ?>" data-read-less="<?php _e("Read less") ?>"><?php _esc($item['description'])?></p>
                            </div>
                            <?php if($item['resume'] != ""){ ?>
                                <a href="<?php _esc($item['resume'])?>" class="job-type" download=""><i
                                            class="icon-feather-download"></i> <?php _e("Resume") ?></a>
                            <?php }?>
                        </div>
                        <div class="job-listing-footer with-icon">
                            <ul>
                                <?php if($item['city'] != ""){ ?>
                                    <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?></li>
                                    <?php
                                }
                                if ($item['category'] != "") {
                                    echo '<li><i class="icon-feather-folder"></i>';
                                    _esc($item['category']);
                                    if ($item['subcategory'] != "") {
                                        echo " / ";
                                        _esc($item['subcategory']);
                                    }
                                    echo "</li>";
                                }
                                if($item['salary_min'] != "0"){ ?>
                                    <li data-tippy-placement="top" title="<?php _e("Salary per month.") ?>"><i
                                                class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> -
                                        <?php if($item['salary_max'] != "") {
                                            _esc($item['salary_max']);
                                        }?>
                                    </li>
                                <?php }?>
                            </ul>
                            <span class="fav-icon set-user-fav <?php if($item['favorite'] == '1') { echo 'added'; }?>"
                                  data-favuser-id="<?php _esc($item['user_id'])?>" data-userid="<?php _esc($user_id)?>"
                                  data-action="setFavUser"></span>

                        </div>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <?php if($totalitem != "0"){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Pagination -->
                            <div class="pagination-container margin-top-20 margin-bottom-60">
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
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Row / End -->
<?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>