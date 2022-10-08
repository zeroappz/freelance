<?php
overall_header();
global $config;
?>
<!-- Intro Banner
================================================== -->
<!-- add class "disable-gradient" to enable consistent background overlay -->
<div class="intro-banner <?php _esc($config['banner_overlay']);?>" data-background-image="<?php _esc($config['site_url']);?>storage/banner/<?php _esc($config['home_banner']);?>">
    <!-- Transparent Header Spacer -->
    <div class="transparent-header-spacer"></div>
    <div class="container">
        <!-- Intro Headline -->
        <div class="row">
            <div class="col-md-12">
                <div class="banner-headline">
                    <h3>
                        <strong><?php _e("Hire the best freelancers for any job, online.") ?></strong>
                        <br>
                        <span><?php _e("Work with the best freelance talent from around the world on our secure and cost-effective platform.") ?></span>
                    </h3>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row">
            <div class="col-md-12">
                <form autocomplete="off" method="get" action="<?php url("LISTING") ?>" accept-charset="UTF-8">
                    <div class="intro-banner-search-form margin-top-95">
                    <!-- Search Field -->
                    <div class="intro-search-field">
                        <label for="intro-keywords" class="field-title ripple-effect"><?php _e("Find Work") ?></label>
                        <input id="intro-keywords" type="text" class="qucikad-ajaxsearch-input"
                               placeholder="<?php _e("Project Title or Keywords") ?>" data-prev-value="0"
                               data-noresult="<?php _e("More Results For") ?>">
                        <i class="qucikad-ajaxsearch-close fa fa-times-circle" aria-hidden="true" style="display: none;"></i>
                        <div id="qucikad-ajaxsearch-dropdown" size="0" tabindex="0">
                            <ul>
                                <?php
                                foreach($category as $cat){
                                    ?>
                                    <li class="qucikad-ajaxsearch-li-cats" data-catid="<?php echo $cat['slug']; ?>">
                                        <?php
                                        echo ($cat['picture'] == '') ? '<i class="qucikad-as-caticon '.$cat['icon'].'"></i>' : '<img src="'.$cat['picture'].'"/>';
                                        ?>
                                        <span class="qucikad-as-cat"><?php echo $cat['name']; ?></span>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>

                            <div style="display:none" id="def-cats">

                            </div>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="intro-search-button">
                        <button class="button ripple-effect"><?php _e("Search") ?></button>
                    </div>
                </div>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-md-12">
                <ul class="intro-stats margin-top-45 hide-under-992px">
                    <li>
                        <strong class="counter"><?php _esc($total_projects);?></strong>
                        <span><?php _e("Projects Posted") ?></span>
                    </li>
                    <li>
                        <strong class="counter"><?php _esc($total_jobs);?></strong>
                        <span><?php _e("Jobs Posted") ?></span>
                    </li>
                    <li>
                        <strong class="counter"><?php _esc($total_freelancer);?></strong>
                        <span><?php _e("Freelancers") ?></span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<!-- Content
================================================== -->
<!-- Category Boxes -->
<?php if($config['show_categories_home']){ ?>
<!-- Category Boxes -->
<div class="section gray padding-top-65 padding-bottom-45">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-headline centered margin-bottom-15">
                    <h3><?php _e("Project Categories") ?></h3>
                </div>
                <div class="categories-container">
                    <?php foreach($category as $cat){ ?>
                        <a href="<?php echo $cat['link']; ?>" class="category-box">
                            <div class="category-box-icon">
                                <?php
                                if($cat['picture'] == '') {
                                    echo '<div class="category-icon"><i class="'.$cat['icon'].'"></i></div>';
                                } else{
                                    echo '<div class="category-icon"><img src="'.$cat['picture'].'"/></div>';
                                }
                                ?>
                            </div>
                            <div class="category-box-counter"><?php echo $cat['main_ads_count']; ?></div>
                            <div class="category-box-content">
                                <h3><?php echo $cat['name']; ?> <small>(<?php echo $cat['main_ads_count']; ?>)</small></h3>
                            </div>
                            <div class="category-box-arrow">
                                <i class="fa fa-chevron-right"></i>
                            </div>
                        </a>
                   <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- Category Boxes / End -->


    <!-- Icon Boxes -->
    <div class="section padding-top-65 padding-bottom-65">
        <div class="container">
            <div class="row">

                <div class="col-xl-12">
                    <!-- Section Headline -->
                    <div class="section-headline centered margin-top-0 margin-bottom-5">
                        <h3><?php _e('How It Works?')?></h3>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box with-line">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class="icon-line-awesome-lock"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e('Create an Account')?></h3>
                        <p><?php _e('Bring to the table win-win survival strategies to ensure proactive domination going forward.')?></p>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box with-line">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class="icon-line-awesome-legal"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e('Post a Task')?></h3>
                        <p><?php _e('Efficiently unleash cross-media information without. Quickly maximize return on investment.')?></p>
                        <h3></h3>
                        <p></p>
                    </div>
                </div>

                <div class="col-xl-4 col-md-4">
                    <!-- Icon Box -->
                    <div class="icon-box">
                        <!-- Icon -->
                        <div class="icon-box-circle">
                            <div class="icon-box-circle-inner">
                                <i class=" icon-line-awesome-trophy"></i>
                                <div class="icon-box-check"><i class="icon-material-outline-check"></i></div>
                            </div>
                        </div>
                        <h3><?php _e('Choose an Expert')?></h3>
                        <p><?php _e('Nanotechnology immersion along the information highway will close the loop on focusing solely.')?></p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Icon Boxes / End -->

<!-- Latest Projects -->
<div class="section gray margin-top-45 padding-top-65 padding-bottom-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">

                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-35">
                    <h3><?php _e("Latest Projects") ?></h3>
                    <a href="<?php url("SEARCH_PROJECTS") ?>" class="headline-link"><?php _e("Browse All Projects") ?></a>
                </div>

                <!-- Jobs Container -->
                <div class="tasks-list-container compact-list margin-top-35">
                    <?php
                    foreach($items as $project){
                    ?>
                    <!-- Task -->
                    <a href="<?php _esc($project['link']);?>" class="task-listing <?php if($project['highlight']){ echo 'highlight';} ?>">
                        <!-- Job Listing Details -->
                        <div class="task-listing-details">
                            <!-- Details -->
                            <div class="task-listing-description">
                                <h3 class="task-listing-title"><?php _esc($project['product_name']);?></h3>
                                <?php if($project['featured'] == 1){ ?>
                                <div class="dashboard-status-button blue"> <?php _e("Featured") ?></div>
                                <?php }
                                if($project['urgent'] == 1){ ?>
                                <div class="dashboard-status-button yellow"> <?php _e("Urgent") ?></div>
                                <?php } ?>
                                <ul class="task-icons">
                                    <li><i class="icon-material-outline-gavel"></i> <?php _esc($project['bids_count']);?> Bids</li>
                                    <li><i class="icon-material-outline-account-balance-wallet"></i> <?php _esc($project['avg_bid']);?> <?php _e("Avg bid") ?></li>
                                    <li><i class="icon-material-outline-access-time"></i> <?php _esc($project['created_at']);?></li>
                                </ul>
                                <div class="task-tags margin-top-15">
                                    <?php _esc($project['skills']);?>
                                </div>
                            </div>
                        </div>
                        <div class="task-listing-bid">
                            <div class="task-listing-bid-inner">
                                <div class="task-offers">
                                    <strong><?php _esc($project['salary_min']);?> - <?php _esc($project['salary_max']);?> </strong>
                                    <span><?php _esc($project['salary_type']);?></span>
                                </div>
                                <span class="button button-sliding-icon ripple-effect"><?php _e("Bid Now") ?> <i class="icon-material-outline-arrow-right-alt"></i></span>
                            </div>
                        </div>
                    </a>
                   <?php } ?>

                </div>
                <!-- Jobs Container / End -->

            </div>
        </div>
    </div>
</div>
<!-- Latest Projects / End -->
<?php if($config['show_latest_jobs_home']){ ?>
<!-- Latest Jobs -->
<div class="section padding-top-65 padding-bottom-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-headline margin-top-0 margin-bottom-35">
                    <h3><?php _e("Latest Jobs") ?></h3>
                    <a href="<?php url("LISTING") ?>" class="headline-link"><?php _e("Browse All Jobs") ?></a>
                </div>
                <div class="listings-container compact-list-layout margin-top-35">
                    <?php
                    foreach($item2 as $job){
                    ?>
                        <div class="job-listing <?php if($job['highlight']){ echo 'highlight'; } ?>">
                            <div class="job-listing-details">
                                <div class="job-listing-company-logo">
                                    <img src="<?php _esc($config['site_url']);?>storage/products/<?php _esc($job['image'])?>"
                                         alt="<?php _esc($job['product_name'])?>">
                                </div>
                                <div class="job-listing-description">
                                    <h3 class="job-listing-title"><a href="<?php _esc($job['link'])?>"><?php _esc($job['product_name'])?></a>
                                        <?php if($job['featured'] == 1){ ?>
                                            <div class="dashboard-status-button blue"> <?php _e("Featured") ?></div>
                                        <?php }
                                        if($job['urgent'] == 1){ ?>
                                            <div class="dashboard-status-button yellow"> <?php _e("Urgent") ?></div>
                                        <?php } ?>
                                    </h3>

                                    <div class="job-listing-footer">
                                        <ul>
                                            <?php if($config['company_enable']){ ?>
                                            <li><i class="la la-building"></i> <?php _esc($job['company_name'])?></li>
                                            <?php } ?>
                                            <li><i class="la la-map-marker"></i> <?php _esc($job['location'])?></li>
                                            <?php if($job['salary_min'] != 0){ ?>
                                                <li><i class="la la-credit-card"></i> <?php _esc($job['salary_min'])?>
                                                    -<?php _esc($job['salary_max'])?> <?php _e("Per") ?> <?php _esc($job['salary_type'])?></li>
                                            <?php } ?>
                                            <li><i class="la la-clock-o"></i> <?php _esc($job['created_at'])?></li>
                                        </ul>
                                    </div>
                                </div>
                                <span class="job-type"><?php _esc($job['product_type'])?></span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Latest Jobs / End -->
<?php } ?>

<!-- Highest Rated Freelancers -->
<div class="section gray padding-top-65 padding-bottom-70 full-width-carousel-fix">
    <div class="container">
        <div class="row">

            <div class="col-xl-12">
                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-25">
                    <h3><?php _e('Highest Rated Freelancers')?></h3>
                    <a href="<?php url("FREELANCERS") ?>" class="headline-link"><?php _e('Browse All Freelancers')?></a>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="default-slick-carousel freelancers-container freelancers-grid-layout">

                    <!--Freelancer -->
                    <?php
                    foreach($freelancers as $freelancer){
                    ?>
                    <div class="freelancer">
                        <!-- Overview -->
                        <div class="freelancer-overview">
                            <div class="freelancer-overview-inner">
                                <!-- Avatar -->
                                <div class="freelancer-avatar">
                                    <div class="verified-badge"></div>
                                    <a href="<?php url("PROFILE") ?>/<?php _esc($freelancer['username']) ?>">
                                        <img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($freelancer['image']) ?>" alt="<?php _esc($freelancer['name']) ?>">
                                    </a>
                                </div>

                                <!-- Name -->
                                <div class="freelancer-name">
                                    <h4><a href="<?php url("PROFILE") ?>/<?php _esc($freelancer['username']) ?>"><?php _esc($freelancer['name']) ?> <div class="flag flag-<?php _esc($freelancer['country_code']) ?>"></a></h4>
                                    <?php
                                    if($freelancer['category'] != ""){
                                        echo "<span>";
                                        _esc($freelancer['category']);
                                        if($freelancer['subcategory'] != ""){
                                            echo " / ";
                                            _esc($freelancer['subcategory']);
                                        }
                                        echo "</span>";
                                    }
                                    ?>
                                </div>
                                <!-- Rating -->
                                <div class="freelancer-rating">
                                    <div class="star-rating" data-rating="<?php _esc($freelancer['rating']) ?>"></div>
                                </div>
                            </div>
                        </div>
                        <!-- Details -->
                        <div class="freelancer-details">
                            <div class="freelancer-details-list">
                                <ul>
                                    <li><?php _e("Hourly Rate") ?> <strong><?php _esc($freelancer['hourly_rate']) ?></strong></li>
                                    <li><?php _e("Won Bid") ?> <strong><?php _esc($freelancer['win_project']) ?></strong></li>
                                    <li><?php _e("Rehired") ?> <strong><?php _esc($freelancer['rehired']) ?></strong></li>
                                </ul>
                            </div>
                            <a href="<?php url("PROFILE") ?>/<?php _esc($freelancer['username']) ?>" class="button button-sliding-icon ripple-effect"><?php _e("View Profile") ?> <i class="icon-material-outline-arrow-right-alt"></i></a>
                        </div>
                    </div>
                    <?php } ?>
                    <!-- Freelancer / End -->
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Highest Rated Freelancers / End-->

<?php if($config['show_membershipplan_home']){ ?>
<!-- Membership Plans -->
<div class="section padding-top-60 padding-bottom-75">
    <div class="container">
        <div class="row">

            <div class="col-xl-12">
                <!-- Section Headline -->
                <div class="section-headline centered margin-top-0 margin-bottom-75">
                    <h3><?php _e("Membership Plan") ?></h3>
                </div>
            </div>


            <div class="col-xl-12">
                <form name="form1" method="post" action="<?php url("MEMBERSHIP") ?>">
                    <div class="billing-cycle-radios margin-bottom-70">
                        <?php
                        if($total_monthly){
                        ?>
                        <div class="radio billed-monthly-radio">
                            <input id="radio-monthly" name="billed-type" type="radio" value="monthly" checked="">
                            <label for="radio-monthly"><span class="radio-label"></span> <?php _e("Monthly") ?></label>
                        </div>
                        <?php
                        }
                        if($total_annual){
                        ?>
                        <div class="radio billed-yearly-radio">
                            <input id="radio-yearly" name="billed-type" type="radio" value="yearly">
                            <label for="radio-yearly"><span class="radio-label"></span> <?php _e("Yearly") ?></label>
                        </div>
                        <?php
                        }
                        if($total_lifetime){
                        ?>
                        <div class="radio billed-lifetime-radio">
                            <input id="radio-lifetime" name="billed-type" type="radio" value="lifetime">
                            <label for="radio-lifetime"><span class="radio-label"></span> <?php _e("Lifetime") ?></label>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Pricing Plans Container -->
                    <div class="pricing-plans-container">
                        <?php
                        foreach($sub_types as $plan){
                        ?>
                            <!-- Plan -->
                            <div class='pricing-plan <?php if(isset($plan['recommended']) && $plan['recommended']=="yes"){ echo 'recommended';} ?>'>

                                <?php
                                if(isset($plan['recommended']) && $plan['recommended']=="yes"){
                                    echo '<div class="recommended-badge">'.__("Recommended").'</div> ';
                                }
                                ?>
                                <h3><?php _esc($plan['title'])?></h3>
                                <?php
                                if($plan['id']=="free" || $plan['id']=="trial"){
                                    ?>
                                    <div class="pricing-plan-label"><strong>
                                            <?php
                                            if($plan['id']=="free")
                                                _e("Free");
                                            else
                                                _e("Trial");
                                            ?>
                                        </strong></div>

                                    <?php
                                }
                                else{
                                    if($total_monthly != 0)
                                        echo '<div class="pricing-plan-label billed-monthly-label"><strong>'._esc($plan['monthly_price'],false).'</strong>/ '.__("Monthly").'</div>';
                                    if($total_annual != 0)
                                        echo '<div class="pricing-plan-label billed-yearly-label"><strong>'._esc($plan['annual_price'],false).'</strong>/ '.__("Yearly").'</div>';
                                    if($total_lifetime != 0)
                                        echo '<div class="pricing-plan-label billed-lifetime-label"><strong>'._esc($plan['lifetime_price'],false).'</strong>/ '.__("Lifetime").'</div>';
                                }
                                ?>

                                <div class="pricing-plan-features">
                                    <strong><?php _e("Features of") ?> <?php _esc($plan['title'])?></strong>
                                    <ul>
                                        <li><?php _e("Project Fee") ?> <?php _esc($plan['freelancer_commission'])?>%</li>
                                        <li><?php _esc($plan['bids'])?> <?php _e("Bids") ?></li>
                                        <li><?php _esc($plan['skills'])?> <?php _e("Skills") ?></li>
                                        <?php _esc($plan['custom_settings'])?>
                                    </ul>
                                </div>
                                <?php
                                if($plan['Selected'] == 0){
                                    echo '<button type="submit" class="button full-width margin-top-20 ripple-effect" name="upgrade" value="'._esc($plan['id'],false).'">'.__("Upgrade").'</button>';
                                }
                                if($plan['Selected'] == 1){
                                    echo '<a href="javascript:void(0);" class="button full-width margin-top-20 ripple-effect">'.__("Current Plan").'</a>';
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Membership Plans / End-->
<?php } ?>

<!-- Testimonials -->
<?php if($config['testimonials_enable'] && $config['show_testimonials_home']){ ?>
<div class="section gray padding-top-65 padding-bottom-55">

    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <!-- Section Headline -->
                <div class="section-headline centered margin-top-0 margin-bottom-5">
                    <h3><?php _e("Testimonials") ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Carousel -->
    <div class="fullwidth-carousel-container margin-top-20">
        <div class="testimonial-carousel testimonials">

            <!-- Item -->
            <?php
            foreach($testimonials as $testimonial){
            ?>
            <div class="fw-carousel-review">
                <div class="testimonial-box">
                    <div class="testimonial-avatar">
                        <img src="<?php _esc($config['site_url']);?>storage/testimonials/<?php _esc($testimonial['image']) ?>" alt="<?php _esc($testimonial['name']) ?>">
                    </div>
                    <div class="testimonial-author">
                        <h4><?php _esc($testimonial['name']) ?></h4>
                        <span><?php _esc($testimonial['designation']) ?></span>
                    </div>
                    <div class="testimonial"><?php _esc($testimonial['content']) ?></div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
    <!-- Categories Carousel / End -->

</div>
<?php } ?>
<!-- Testimonials / End -->

<!-- Counters -->
<div class="section padding-top-70 padding-bottom-75">
    <div class="container">
        <div class="row">

            <div class="col-xl-12">
                <div class="counters-container">

                    <!-- Counter -->
                    <div class="single-counter">
                        <i class="icon-line-awesome-legal"></i>
                        <div class="counter-inner">
                            <h3><span class="counter"><?php _esc($total_projects);?></span></h3>
                            <span class="counter-title"><?php _e("Projects Posted") ?></span>
                        </div>
                    </div>
                    <!-- Counter -->
                    <div class="single-counter">
                        <i class="icon-line-awesome-suitcase"></i>
                        <div class="counter-inner">
                            <h3><span class="counter"><?php _esc($total_jobs);?></span></h3>
                            <span class="counter-title"><?php _e("Jobs Posted") ?></span>
                        </div>
                    </div>
                    <!-- Counter -->
                    <div class="single-counter">
                        <i class="icon-line-awesome-user"></i>
                        <div class="counter-inner">
                            <h3><span class="counter"><?php _esc($total_freelancer);?></span></h3>
                            <span class="counter-title"><?php _e("Freelancers") ?></span>
                        </div>
                    </div>
                    <!-- Counter -->
                    <div class="single-counter">
                        <i class="icon-line-awesome-trophy"></i>
                        <div class="counter-inner">
                            <h3><?php _esc($config['currency_sign']);?><span class="counter"><?php _esc($community_earning);?></span></h3>
                            <span class="counter-title"><?php _e("Community Earnings") ?></span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- Counters / End -->

<!-- Recent Blog Posts -->
<?php if($config['blog_enable'] && $config['show_blog_home']){ ?>
<div class="section gray padding-top-65 padding-bottom-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">

                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-45">
                    <h3><?php _e("Recent Blog") ?></h3>
                    <a href="<?php url("BLOG") ?>" class="headline-link"><?php _e('View Blog')?></a>
                </div>

                <div class="row">
                    <!-- Blog Post Item -->
                    <?php
                    foreach($recent_blog as $blog){
                        ?>
                    <div class="col-xl-4">
                        <a href="<?php _esc($blog['link']) ?>" class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="<?php _esc($config['site_url']);?>storage/blog/<?php _esc($blog['image']) ?>"
                                     alt="{RECENT_BLOG.title}">
                                <span class="blog-item-tag"><?php _esc($blog['author']) ?></span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li><?php _esc($blog['created_at']) ?></li>
                                    </ul>
                                    <h3><?php _esc($blog['title']) ?></h3>
                                    <p><?php _esc($blog['description']) ?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php } ?>
                    <!-- Blog post Item / End -->
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- Recent Blog Posts / End -->

<?php if($config['show_partner_logo_home']){ ?>
<div class="section border-top padding-top-45 padding-bottom-45">
    <!-- Logo Carousel -->
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <!-- Carousel -->
                <div class="col-md-12">
                    <div class="logo-carousel">
                        <?php
                        $dir = ROOTPATH.'/storage/partner/';
                        $i = 0;
                        foreach (glob($dir . '*') as $path) {
                            ?>
                            <div class="carousel-item">
                                <img src="<?php _esc($config['site_url']);?>storage/partner/<?php _esc(basename($path))?>">
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- Carousel / End -->
            </div>
        </div>
    </div>
</div>
<?php } ?>
    <script>
        var transparent_header = "<?php _esc($config['transparent_header'])?>";
        $(document).ready(function () {
            if(transparent_header != '0'){
                $("#wrapper").addClass('wrapper-with-transparent-header');
                $("#header-container").addClass('transparent-header');
            }
        });
    </script>
<?php
overall_footer();
?>