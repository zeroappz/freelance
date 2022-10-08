<?php
overall_header();
global $config;
?>

<?php
if($config['show_search_home']){
?>
<!-- Intro Banner
================================================== -->
<!-- add class "disable-gradient" to enable consistent background overlay -->
<div class="intro-banner <?php _esc($config['banner_overlay']);?>" data-background-image="<?php _esc($config['site_url']);?>storage/banner/<?php _esc($config['home_banner']);?>">
    <!-- Transparent Header Spacer -->
    <div class="transparent-header-spacer"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-headline">
                    <h3><strong><?php _e("Hire / Work") ?></strong>
                        <br>
                        <span><?php _e("Hire Freelancers &amp; Find Freelance Jobs Online") ?></span></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form autocomplete="off" method="get" action="<?php url("LISTING") ?>" accept-charset="UTF-8">
                    <div class="intro-banner-search-form margin-top-45">
                        <div class="intro-search-field">
                            <input id="intro-keywords" type="text" class="qucikad-ajaxsearch-input"
                                   placeholder="<?php _e("Job Title or Keywords") ?>" data-prev-value="0"
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
                        <div class="intro-search-field live-location-search with-autocomplete">
                            <div class="input-with-icon">
                                <input type="text" id="searchStateCity" name="location" placeholder="<?php _e("Where?") ?>">
                                <i class="icon-feather-map-pin"></i>
                                <div data-option="<?php echo $config['auto_detect_location']; ?>" class="loc-tracking"><i class="la la-crosshairs"></i></div>
                                <input type="hidden" name="latitude" id="latitude" value="">
                                <input type="hidden" name="longitude" id="longitude" value="">
                                <input type="hidden" name="placetype" id="searchPlaceType" value="">
                                <input type="hidden" name="placeid" id="searchPlaceId" value="">
                                <input type="hidden" id="input-keywords" name="keywords" value="">
                                <input type="hidden" id="input-maincat" name="cat" value=""/>
                                <input type="hidden" id="input-subcat" name="subcat" value=""/>
                            </div>
                        </div>
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
                        <strong class="counter"><?php _esc($total_jobs);?></strong>
                        <span><?php _e("Jobs Posted") ?></span>
                    </li>
                    <li>
                        <strong class="counter"><?php _esc($total_projects);?></strong>
                        <span><?php _e("Projects Posted") ?></span>
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
<?php } ?>

<?php if($config['show_categories_home']){ ?>
<!-- Category Boxes -->
<div class="section padding-top-65 padding-bottom-45">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-headline centered margin-bottom-15">
                    <h3><?php _e("Job Categories") ?></h3>
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

<?php if($config['show_featured_jobs_home']){ ?>
<!-- Features Jobs -->
<div class="section gray padding-top-65 padding-bottom-65">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="section-headline margin-top-0 margin-bottom-35">
                    <h3><?php _e("Featured Jobs") ?></h3>
                    <a href="<?php url("LISTING") ?>" class="headline-link"><?php _e("Browse All Jobs") ?></a>
                </div>
                <div class="listings-container grid-layout margin-top-35">
                    <?php foreach($items as $item){ ?>
                        <div class="job-listing <?php if($item['highlight'] == '1') echo "highlight"; ?>">
                            <div class="job-listing-details">
                                <div class="job-listing-company-logo">
                                    <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>"
                                         alt="<?php _esc($item['product_name'])?>">
                                </div>
                                <div class="job-listing-description">
                                    <?php if($config['company_enable']){ ?>
                                        <h4 class="job-listing-company"><?php _esc($item['company_name'])?></h4>
                                    <?php } ?>

                                    <h3 class="job-listing-title"><a href="<?php _esc($item['link'])?>"><?php _esc($item['product_name'])?></a>
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
                                </div>
                                <span class="job-type"><?php _esc($item['product_type'])?></span>
                            </div>
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="la la-map-marker"></i> <?php _esc($item['location'])?></li>
                                    <?php if($item['salary_min'] != "0"){ ?>
                                        <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> - <?php _esc($item['salary_max'])?> <?php _e("Per") ?> <?php _esc($item['salary_type'])?></li>
                                    <?php }?>
                                    <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Featured Jobs / End -->
<?php } ?>

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
                    <?php foreach($item2 as $item){ ?>
                        <div class="job-listing <?php if($item['highlight'] == '1') echo "highlight"; ?>">
                            <div class="job-listing-details">
                                <div class="job-listing-company-logo">
                                    <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>"
                                         alt="<?php _esc($item['product_name'])?>">
                                </div>
                                <div class="job-listing-description">
                                    <h3 class="job-listing-title"><a href="<?php _esc($item['link'])?>"><?php _esc($item['product_name'])?></a>
                                        <?php if($item['featured'] == 1){ ?>
                                            <div class="dashboard-status-button blue"> <?php _e("Featured") ?></div>
                                        <?php }
                                        if($item['urgent'] == 1){ ?>
                                            <div class="dashboard-status-button yellow"> <?php _e("Urgent") ?></div>
                                        <?php } ?>
                                    </h3>
                                    <div class="job-listing-footer">
                                        <ul>
                                            <?php if($config['company_enable']){ ?>
                                                <h4 class="job-listing-company"><?php _esc($item['company_name'])?></h4>
                                            <?php } ?>
                                            <li><i class="la la-map-marker"></i> <?php _esc($item['location'])?></li>
                                            <?php if($item['salary_min'] != "0"){ ?>
                                                <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> - <?php _esc($item['salary_max'])?> <?php _e("Per") ?> <?php _esc($item['salary_type'])?></li>
                                            <?php }?>
                                            <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                        </ul>
                                    </div>
                                </div>
                                <span class="job-type"><?php _esc($item['product_type'])?></span>
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