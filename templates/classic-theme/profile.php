<?php overall_header($pagetitle) ?>
<!-- Titlebar
================================================== -->
<div class="single-page-header freelancer-header" data-background-image="<?php _esc(TEMPLATE_URL);?>/images/single-freelancer.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-page-header-inner">
                    <div class="left-side">
                        <div class="header-image freelancer-avatar"><img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($userimage);?>" alt="<?php _esc($fullname);?>"></div>
                        <div class="header-details">
                            <h3><?php _esc($fullname);?> @ <?php _esc($profileusername);?> <span><?php _e("Member Since:") ?> <?php _esc($created);?></span></h3>
                            <ul>
                                <li><div class="star-rating" data-rating="<?php _esc($average_rating);?>"></div></li>
                                <li class="hidden"><img class="flag" src="<?php _esc($config['site_url']);?>includes/assets/plugins/flags/images/<?php _esc($user_country_code);?>.png" alt=""> <?php _esc($user_country);?></li>
                                <?php if($userstatus == "1"){
                                   echo '<li><div class="verified-badge-with-title">'.__("Verified").'</div></li>';
                                }?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Page Content
================================================== -->
<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <!-- Page Content -->
            <div class="single-page-section">
                <h3 class="margin-bottom-25"><?php _e("About Me") ?></h3>
                <?php _esc($about);?>
            </div>
            <?php if($total_experiences){ ?>
            <div class="boxed-list margin-bottom-60" id="all-jobs">
                <div class="boxed-list-headline">
                    <h3><i class="icon-feather-award"></i> <?php _e("Experiences") ?></h3>
                </div>
                <div class="listings-container compact-list-layout">
                    <?php foreach ($experiences as $experience){ ?>
                        <div class="job-listing">
                            <div class="job-listing-details">
                                <div class="job-listing-description">
                                    <h4 class="job-listing-company"><?php _esc($experience['company']);?></h4>
                                    <h3 class="job-listing-title"><?php _esc($experience['title']);?></h3>
                                    <p class="job-listing-text read-more-toggle" data-read-more="<?php _e("Read more") ?>" data-read-less="<?php _e("Read less") ?>"><?php _esc($experience['description']);?></p>
                                </div>
                            </div>
                            <div class="job-listing-footer margin-top-10">
                                <ul>
                                    <li><i class="la la-clock-o"></i> <?php _esc($experience['start_date']);?> - <?php _esc($experience['end_date']);?></li>
                                    <li><i class="la la-map-marker"></i> <?php _esc($experience['city']);?></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <!-- Boxed List -->
            <div class="boxed-list margin-bottom-60">
                <div class="boxed-list-headline">
                    <h3><i class="icon-material-outline-thumb-up"></i> <?php _e("Rating") ?></h3>
                </div>
                <!-- **** Start reviews **** -->
                <div class="listings-container compact-list-layout starReviews">
                    <!-- Show current reviews -->
                    <ul class="show-reviews boxed-list-ul rating-list"><div class="loader" style="margin: 0 auto;"></div></ul>
                    <!-- This is where your product ID goes -->
                    <div id="review-productId" class="review-productId" style="display: none"><?php _esc($userid);?></div>

                    <script type="text/javascript">
                        var LANG_ADDREVIEWS     = "{LANG_ADDREVIEWS}";
                        var LANG_SUBMITREVIEWS  = "{LANG_SUBMITREVIEWS}";
                        var LANG_HOW_WOULD_RATE = "{LANG_HOW_WOULD_RATE}";
                        var LANG_REVIEWS        = "<?php _e("Reviews") ?>";
                        var LANG_YOURREVIEWS    = "{LANG_YOURREVIEWS}";
                        var LANG_ENTER_REVIEW   = "{LANG_ENTER_REVIEW}";
                        var LANG_STAR           = "{LANG_STAR}";
                    </script>
                    <!-- jQuery Form Validator -->
                    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.34/jquery.form-validator.min.js"></script>
                    <!-- jQuery Barrating plugin -->
                    <script src="<?php _esc($config['site_url']);?>plugins/starreviews/assets/js/jquery.barrating.js"></script>
                    <!-- jQuery starReviews -->
                    <script src="<?php _esc($config['site_url']);?>plugins/starreviews/assets/js/starReviews.js"></script>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            /* Activate our reviews */
                            $().reviews('.starReviews');
                        });
                    </script>
                </div>
                <!-- **** End reviews **** -->

                <!-- Pagination -->
                <div class="clearfix"></div>
                <div class="pagination-container margin-top-40 margin-bottom-10 d-none">
                    <nav class="pagination">
                        <ul>
                            <li><a href="#" class="ripple-effect current-page">1</a></li>
                            <li><a href="#" class="ripple-effect">2</a></li>
                            <li class="pagination-arrow"><a href="#" class="ripple-effect"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
                        </ul>
                    </nav>
                </div>
                <div class="clearfix"></div>
                <!-- Pagination / End -->

            </div>
            <!-- Boxed List / End -->

            <?php if($totalitem){ ?>
            <div class="boxed-list margin-bottom-60" id="all-jobs">
                <div class="boxed-list-headline">
                    <h3><i class="icon-feather-briefcase"></i> <?php _e("All Jobs") ?></h3>
                </div>
                <div class="listings-container compact-list-layout margin-top-30">
                    <?php foreach ($items as $item){ ?>
                        <a href="<?php _esc($item['link'])?>" class="job-listing">
                            <div class="job-listing-details">
                                <div class="job-listing-description">
                                    <h3 class="job-listing-title"><?php _esc($item['name'])?>
                                        <?php
                                        if($item['featured']=="1") {
                                            echo '<div class="dashboard-status-button blue">'.__("Featured").'</div>';
                                        }
                                        if($item['urgent']=="1") {
                                            echo '<div class="dashboard-status-button yellow">'.__("Urgent").'</div>';
                                        }
                                        if($item['highlight']=="1") {
                                            echo '<div class="dashboard-status-button red">'.__("Highlight").'</div>';
                                        }
                                        ?>
                                    </h3>
                                    <div class="job-listing-footer">
                                        <ul>
                                            <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?></li>
                                            <?php if($item['salary_min'] != "0"){ ?>
                                                <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> - <?php _esc($item['salary_max'])?> <?php _e("Per") ?> <?php _esc($item['salary_type'])?></li>
                                            <?php }?>
                                            <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                        </ul>
                                    </div>
                                </div>
                                <span class="job-type"><?php _esc($item['product_type'])?></span>
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <?php if($show_paging){ ?>
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
                <?php } ?>
            </div>
            <?php } ?>
        </div>


        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                <?php if($profile_usertype == "user"){ ?>
                    <!-- Profile Overview -->
                    <div class="profile-overview">
                        <div class="overview-item"><strong><?php _esc($hourly_rate)?></strong><span><?php _e("Hourly Rate") ?></span></div>
                        <div class="overview-item"><strong><?php _esc($win_project)?></strong><span><?php _e("Won Bid") ?></span></div>
                        <div class="overview-item"><strong><?php _esc($rehired)?></strong><span><?php _e("Rehired") ?></span></div>
                    </div>
                    <!-- Freelancer Indicators -->
                    <div class="sidebar-widget">
                        <div class="freelancer-indicators">

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong><?php _esc($project_completed)?>%</strong>
                                <div class="indicator-bar" data-indicator-percentage="<?php _esc($project_completed)?>"><span></span></div>
                                <span><?php _e("Project Completed") ?></span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong><?php _esc($recommendation_percentage)?>%</strong>
                                <div class="indicator-bar" data-indicator-percentage="<?php _esc($recommendation_percentage)?>"><span></span></div>
                                <span><?php _e("Recommendation") ?></span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong><?php _esc($on_budget_percentage)?>%</strong>
                                <div class="indicator-bar" data-indicator-percentage="<?php _esc($on_budget_percentage)?>"><span></span></div>
                                <span><?php _e("On Time") ?></span>
                            </div>

                            <!-- Indicator -->
                            <div class="indicator">
                                <strong><?php _esc($on_time_percentage)?>%</strong>
                                <div class="indicator-bar" data-indicator-percentage="<?php _esc($on_time_percentage)?>"><span></span></div>
                                <span><?php _e("On Budget") ?></span>
                            </div>
                        </div>
                    </div>
                <?php }else{ ?>
                    <div class="sidebar-widget">
                        <div class="profile-overview">
                            <div class="overview-item"><strong><?php _esc($open_projects)?></strong><span><?php _e("Open Projects")?></span></div>
                            <div class="overview-item"><strong><?php _esc($completed_projects)?></strong><span><?php _e("Completed Projects")?></span></div>
                        </div>
                        <div class="profile-overview">
                            <div class="overview-item"><strong><?php _esc($total_projects)?></strong><span><?php _e("Total Projects")?></span></div>
                            <div class="overview-item"><strong><?php _esc($posted_jobs)?></strong><span><?php _e("Active Jobs")?></span></div>
                        </div>
                    </div>
                <?php } ?>

                <!-- Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Social Profiles") ?></h3>
                    <div class="freelancer-socials margin-top-25">
                        <ul>
                            <?php
                            if($facebook != "") {
                                echo '<li><a href="'._esc($facebook,false).'" data-button-color="#3b5998" title="'.__("Facebook").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-facebook"></i></a></li>';
                            }
                            if($twitter != "") {
                                echo '<li><a href="'._esc($twitter,false).'" data-button-color="#1da1f2" title="'.__("Twitter").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-twitter"></i></a></li>';
                            }
                            if($linkedin != "") {
                                echo '<li><a href="'._esc($linkedin,false).'" data-button-color="#0077b5" title="'.__("Linkedin").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-linkedin"></i></a></li>';
                            }
                            if($youtube != "") {
                                echo '<li><a href="'._esc($youtube,false).'" data-button-color="#ff0000" title="'.__("Youtube").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-youtube"></i></a></li>';
                            }
                            if($instagram != "") {
                                echo '<li><a href="'._esc($instagram,false).'" data-button-color="#e1306c" title="'.__("Instagram").'" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-instagram"></i></a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <?php if(!$usertype == "user") { ?>
                    <!-- Widget -->
                    <div class="sidebar-widget">
                        <h3><?php _e("Skills") ?></h3>
                        <div class="task-tags">
                            <?php _esc($skills)?>
                        </div>
                    </div>
                <?php } ?>
                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Bookmark share") ?></h3>

                    <!-- Bookmark Button -->
                    <button class="bookmark-button margin-bottom-25">
                        <span class="bookmark-icon"></span>
                        <span class="bookmark-text"><?php _e("Bookmark") ?></span>
                        <span class="bookmarked-text"><?php _e("Bookmarked") ?></span>
                    </button>

                    <!-- Copy URL -->
                    <div class="copy-url">
                        <input id="copy-url" type="text" value="" class="with-border">
                        <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="<?php _e("Copy to Clipboard") ?>" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
                    </div>

                    <!-- Share Buttons -->
                    <div class="share-buttons margin-top-25">
                        <div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
                        <div class="share-buttons-content">
                            <span><?php _e("Interesting?") ?> <strong><?php _e("Share It!") ?></strong></span>
                            <ul class="share-buttons-icons">
                                <li><a href="mailto:?subject=<?php _esc($username)?>&body=<?php _esc($item_link)?>" data-button-color="#dd4b39" title="<?php _e("Share on Email") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-envelope"></i></a></li>
                                <li><a href="https://facebook.com/sharer/sharer.php?u=<?php _esc($item_link)?>" data-button-color="#3b5998" title="<?php _e("Share on Facebook") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="icon-brand-facebook-f"></i></a></li>
                                <li><a href="https://twitter.com/share?url=<?php _esc($item_link)?>&text=<?php _esc($item_title)?>" data-button-color="#1da1f2" title="<?php _e("Share on Twitter") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php _esc($item_link)?>" data-button-color="#0077b5" title="<?php _e("Share on LinkedIn") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/pin/create/bookmarklet/?&url=<?php _esc($item_link)?>&description=<?php _esc($item_title)?>" data-button-color="#bd081c" title="<?php _e("Share on Pinterest") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
                                <li><a href="https://web.whatsapp.com/send?text=<?php _esc($item_link)?>" data-button-color="#25d366" title="<?php _e("Share on WhatsApp") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<!-- Spacer -->
<div class="margin-top-15"></div>
<!-- Spacer / End-->
<?php overall_footer(); ?>