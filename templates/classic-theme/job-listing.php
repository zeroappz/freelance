<?php
overall_header($pagetitle);
?>
<form method="get" action="<?php url("LISTING") ?>" name="locationForm" id="ListingForm">
    <div id="titlebar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php _e("We found") ?> <?php _esc($adsfound) ?> <?php _e("Jobs") ?></h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="listing_job">
                        <ul>
                            <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                            <?php
                            if($maincategory != ""){
                                echo '<li>'._esc($maincategory,false).'</li>';
                            }
                            if($subcategory != ""){
                                echo '<li>'._esc($subcategory,false).'</li>';
                            }
                            if($maincategory == "" && $subcategory == ""){
                                echo '<li>'.__("All Categories").'</li>';
                            }
                            ?>
                        </ul>
                    </nav>
                    <div class="intro-banner-search-form listing-page margin-top-30">
                        <!-- Search Field -->
                        <div class="intro-search-field">
                            <div class="dropdown category-dropdown">
                                <a data-toggle="dropdown" href="#">
                                    <span class="change-text"><?php _e("Select") ?> <?php _e("Category") ?></span><i class="fa fa-navicon"></i>
                                </a>
                                <?php _esc($cat_dropdown) ?>
                            </div>
                        </div>
                        <div class="intro-search-field">
                            <input id="keywords" type="text" name="keywords" placeholder="<?php _e("Job Title or Keywords") ?>" value="<?php _esc($keywords) ?>">
                        </div>
                        <div class="intro-search-field with-autocomplete">
                            <div class="input-with-icon">
                                <input type="text" id="searchStateCity" name="location" placeholder="<?php _e("Where?") ?>">
                                <i class="icon-feather-map-pin"></i>
                                <input type="hidden" name="placetype" id="searchPlaceType" value="">
                                <input type="hidden" name="placeid" id="searchPlaceId" value="">
                                <input type="hidden" id="input-maincat" name="cat" value="<?php _esc($maincat) ?>"/>
                                <input type="hidden" id="input-subcat" name="subcat" value="<?php _esc($subcat) ?>"/>
                                <input type="hidden" id="input-filter" name="filter" value="<?php _esc($filter) ?>"/>
                                <input type="hidden" id="input-sort" name="sort" value="<?php _esc($sort) ?>"/>
                                <input type="hidden" id="input-order" name="order" value="<?php _esc($order) ?>"/>
                            </div>
                        </div>
                        <div class="intro-search-button">
                            <button class="button ripple-effect"><?php _e("Search") ?></button>
                        </div>
                    </div>
                    <div class="hide-under-768px margin-top-20">
                        <ul class="categories-list">
                            <?php foreach ($subcatlist as $sub_c){
                                echo '<li><a href="'._esc($sub_c['link'],false).'">'._esc($sub_c['name'],false).'</a></li>';
                            }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a class="popup-with-zoom-anim hidden" href="#citiesModal" id="change-city"><?php _e("city") ?></a>
    <div class="zoom-anim-dialog mfp-hide popup-dialog big-dialog" id="citiesModal">
        <div class="popup-tab-content padding-0">
            <div class="quick-states" id="country-popup" data-country-id="<?php _esc($default_country_id) ?>" style="display: block;">
                <div id="regionSearchBox" class="title clr">
                    <div class="clr">
                        <div class="locationrequest smallBox br5 col-sm-4">
                            <div class="rel input-container">
                                <div class="input-with-icon">
                                    <input id="inputStateCity" class="with-border" type="text" placeholder="<?php _e("Type your city name") ?>">
                                    <i class="la la-map-marker"></i>
                                </div>
                                <div id="searchDisplay"></div>
                                <div class="suggest bottom abs small br3 error hidden"><span
                                            class="target abs icon"></span>

                                    <p></p>
                                </div>
                            </div>
                            <div id="lastUsedCities" class="last-used binded" style="display: none;"><?php _e("Last visited:") ?>
                                <ul id="last-locations-ul">
                                </ul>
                            </div>
                        </div>
                        <?php if($config['country_type'] == "multi"){ ?>
                            <span style="line-height: 30px;">
                                <span class="flag flag-<?php _esc($user_country) ?>"></span> <a href="#countryModal" class="popup-with-zoom-anim"><?php _e("Change Country") ?></a>
                            </span>
                        <?php } ?>
                    </div>
                </div>
                <div class="popular-cities clr">
                    <p><?php _e("Popular cities:") ?></p>

                    <div class="list row">

                        <ul class="col-lg-12 col-md-12 popularcity">
                            <?php foreach ($popularcity as $city){
                                _esc($city['tpl']);
                            }?>
                        </ul>
                    </div>
                </div>
                <div class="viewport">
                    <div class="full" id="getCities">
                        <div class="col-sm-12 col-md-12 loader" style="display: none"></div>
                        <div id="results" class="animate-bottom">
                            <ul class="column cities">
                                <?php foreach ($statelist as $state){
                                    _esc($state['tpl']);
                                }?>
                            </ul>
                        </div>
                    </div>
                    <div class="table full subregionslinks hidden" id="subregionslinks"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <div class="filter-button-container">
                    <a href="javascript:void(0);" class="enable-filters-button">
                        <i class="enable-filters-button-icon"></i>
                        <span class="show-text"><?php _e("Advanced Search") ?></span>
                        <span class="hide-text"><?php _e("Advanced Search") ?></span>
                    </a>
                </div>
                <div class="sidebar-container search-sidebar">
                    <!-- Job Types -->
                    <div class="sidebar-widget">
                        <h3><?php _e("Job Type") ?></h3>
                        <ul>
                            <?php foreach ($posttypes as $posttype){ ?>
                                <li>
                                    <div class="checkbox">
                                        <input type="checkbox" id="job_type_<?php _esc($posttype['id']) ?>" name="job-type" value="<?php _esc($posttype['id']) ?>" <?php if($job_type == $posttype['id']) { echo "checked"; }?> >
                                        <label for="job_type_<?php _esc($posttype['id']) ?>"><span class="checkbox-icon"></span> <?php _esc($posttype['title']) ?></label>
                                    </div>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                    <div class="sidebar-widget">
                        <h3><?php _e("Salary Type") ?></h3>
                        <ul>
                            <?php foreach ($salarytypes as $salarytype){ ?>
                                <li>
                                    <div class="checkbox">
                                        <input type="checkbox" id="salary_type_<?php _esc($salarytype['id']) ?>" name="salary-type" value="<?php _esc($salarytype['id']) ?>" <?php if($salary_type == $salarytype['id']) { echo "checked"; }?> >
                                        <label for="salary_type_<?php _esc($salarytype['id']) ?>"><span class="checkbox-icon"></span> <?php _esc($salarytype['title']) ?></label>
                                    </div>
                                </li>
                            <?php }?>
                        </ul>
                    </div>
                    <div class="sidebar-widget">
                        <h3><?php _e("Salary") ?></h3>
                        <div class="range-widget">
                            <div class="range-inputs">
                                <input type="text" placeholder="<?php _e("Min") ?>" name="range1" value="<?php _esc($range1) ?>">
                                <input type="text" placeholder="<?php _e("Max") ?>" name="range2" value="<?php _esc($range2) ?>">
                            </div>
                            <button type="submit" class="button"><i class="icon-feather-arrow-right"></i></button>
                        </div>
                    </div>
                    <?php
                    foreach ($customfields as $customfield){
                        if($customfield['type']=="text-field"){
                            echo '<div class="sidebar-widget">
                                <h3 class="label-title">'._esc($customfield['title'],false).'</h3>
                                '._esc($customfield['textbox'],false).'
                            </div>';
                        }
                        if($customfield['type']=="textarea"){
                            echo '<div class="sidebar-widget">
                                <h3 class="label-title">'._esc($customfield['title'],false).'</h3>
                                '._esc($customfield['textarea'],false).'
                            </div>';
                        }
                        if($customfield['type']=="radio-buttons"){
                            echo '<div class="sidebar-widget">
                                <h3 class="label-title">'._esc($customfield['title'],false).'</h3>
                                '._esc($customfield['radio'],false).'
                            </div>';
                        }
                        if($customfield['type']=="drop-down"){
                            echo '<div class="sidebar-widget">
                                <h3 class="label-title">'._esc($customfield['title'],false).'</h3>
                                <select class="selectpicker with-border" name="custom['._esc($customfield["id"],false).']">
                                    <option value="" selected>'.__("Select").' '._esc($customfield["title"],false).'</option>
                                    '._esc($customfield["selectbox"],false).'
                                </select>
                            </div>';
                        }
                        if($customfield['type']=="checkboxes"){
                            echo '<div class="sidebar-widget">
                                <h3 class="label-title">'._esc($customfield['title'],false).'</h3>
                                '._esc($customfield['checkbox'],false).'
                            </div>';
                        }
                    }
                    ?>
                    <div class="sidebar-widget">
                        <button class="button full-width ripple-effect"><?php _e("Advanced Search") ?></button>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">

                <h3 class="page-title"><?php _e("Search Results") ?></h3>

                <div class="notify-box margin-top-15">
                    <span class="font-weight-600"><?php _esc($adsfound) ?> <?php _e("Jobs Found") ?></span>

                    <div class="sort-by">
                        <span><?php _e("Sort by:") ?></span>
                        <select class="selectpicker hide-tick" id="sort-filter">
                            <option data-filter-type="sort" data-filter-val="id" data-order="desc"><?php _e("Newest") ?></option>
                            <option data-filter-type="sort" data-filter-val="title" data-order="desc"><?php _e("Name") ?></option>
                            <option data-filter-type="sort" data-filter-val="date" data-order="desc"><?php _e("Date") ?></option>
                        </select>
                    </div>
                </div>

                <div class="listings-container margin-top-35">
                    <?php foreach ($items as $item){ ?>
                        <div class="job-listing <?php if($item['highlight']){ echo 'highlight';}?>">
                            <div class="job-listing-details">
                                <div class="job-listing-company-logo">
                                    <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>" alt="<?php _esc($item['product_name'])?>">
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
                                    <p class="job-listing-text"><?php _esc($item['description'])?></p>
                                </div>
                                <span class="job-type"><?php _esc($item['product_type'])?></span>
                            </div>
                            <div class="job-listing-footer with-icon">
                                <ul>
                                    <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?>, <?php _esc($item['state'])?></li>
                                    <?php if($item['salary_min'] != "0"){ ?>
                                    <li><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?> - <?php _esc($item['salary_max'])?> <?php _e("Per") ?> <?php _esc($item['salary_type'])?></li>
                                    <?php }?>
                                    <li><i class="la la-clock-o"></i> <?php _esc($item['created_at'])?></li>
                                </ul>

                                <?php if($usertype == 'user') { ?>
                                    <span class="fav-icon set-item-fav <?php if($item['favorite']) { echo 'added'; }?>" data-item-id="<?php _esc($item['id'])?>" data-userid="<?php _esc($user_id)?>" data-action="setFavAd"></span>
                                <?php }?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>

                    <?php if($adsfound != "0"){ ?>
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
    </div>
</form>
<script type="text/javascript">

    $('#sort-filter').on('change', function (e) {
        var $item = $(this).find(':selected');

        var filtertype = $item.data('filter-type');
        var filterval = $item.data('filter-val');
        $('#input-' + filtertype).val(filterval);
        $('#input-order').val($item.data('order'));
        $('#ListingForm').submit();
    });

    var getMaincatId = '<?php _esc($maincat) ?>';
    var getSubcatId = '<?php _esc($subcat) ?>';

    $(window).bind("load", function () {
        if (getMaincatId != "") {
            $('li a[data-cat-type="maincat"][data-ajax-id="' + getMaincatId + '"]').trigger('click');
        } else if (getSubcatId != "") {
            $('li ul li a[data-cat-type="subcat"][data-ajax-id="' + getSubcatId + '"]').trigger('click');
        } else {
            $('li a[data-cat-type="all"]').trigger('click');
        }
    });
</script>
<?php overall_footer(); ?>
