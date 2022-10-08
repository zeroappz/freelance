<?php
overall_header($pagetitle);
?>
<!-- Search
================================================== -->

<form method="get" action="<?php url("SEARCH_PROJECTS") ?>" name="locationForm" id="ListingForm">
    <div id="titlebar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php _e("We found") ?> <?php _esc($adsfound) ?> <?php _e("Projects") ?></h2>
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
                            <input id="keywords" type="text" name="keywords" placeholder="<?php _e("Project Title or Keywords") ?>" value="<?php _esc($keywords) ?>">
                        </div>
                        <div class="intro-search-button">
                            <input type="hidden" id="input-maincat" name="cat" value="<?php _esc($maincat) ?>"/>
                            <input type="hidden" id="input-subcat" name="subcat" value="<?php _esc($subcat) ?>"/>
                            <input type="hidden" id="input-filter" name="filter" value="<?php _esc($filter) ?>"/>
                            <input type="hidden" id="input-sort" name="sort" value="<?php _esc($sort) ?>"/>
                            <input type="hidden" id="input-order" name="order" value="<?php _esc($order) ?>"/>
                            <button class="button ripple-effect"><?php _e("Search") ?></button>
                        </div>
                    </div>
                    <div class="hide-under-768px margin-top-20">
                        <ul class="categories-list">
                            <?php foreach ($subcatlist as $sub_c){
                                echo '<li><a href="'._esc($sub_c['project_link'],false).'">'._esc($sub_c['name'],false).'</a></li>';
                            }?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Content
    ================================================== -->
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <!-- Enable Filters Button -->
                <div class="filter-button-container">
                    <button href="javascript:void(0);" type="button" class="enable-filters-button">
                        <i class="enable-filters-button-icon"></i>
                        <span class="show-text"><?php _e("Advanced Search") ?></span>
                        <span class="hide-text"><?php _e("Advanced Search") ?></span>
                    </button>
                </div>
                <div class="sidebar-container search-sidebar">
                    <div class="sidebar-widget">
                        <h3><?php _e("Project Type") ?></h3>
                        <ul>
                            <li>
                                <div class="radio">
                                    <input id="fixed_price" name="salary-type" type="radio" value="0" <?php if($salary_type == "0") { echo "checked"; }?>>
                                    <label for="fixed_price"><span class="radio-label"></span> <?php _e("Fixed Price") ?></label>
                                </div>
                            </li>
                            <li>
                                <div class="radio">
                                    <input id="hourly_price" name="salary-type" type="radio" value="1" <?php if($salary_type == "1") { echo "checked"; }?>>
                                    <label for="hourly_price"><span class="radio-label"></span> <?php _e("Hourly Price") ?></label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="sidebar-widget">
                        <h3><?php _e("Budget") ?></h3>
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
            <div class="col-xl-9 col-lg-8 content-left-offset">

                <h3 class="page-title"><?php _e("Search Results") ?></h3>

                <div class="notify-box margin-top-15">

                    <span class="font-weight-600"><?php _esc($adsfound) ?> <?php _e("Projects Found") ?></span>

                    <div class="sort-by">
                        <span><?php _e("Sort by:") ?></span>
                        <select class="selectpicker hide-tick" id="sort-filter">
                            <option data-filter-type="sort" data-filter-val="id" data-order="desc"><?php _e("Newest") ?></option>
                            <option data-filter-type="sort" data-filter-val="title" data-order="desc"><?php _e("Name") ?></option>
                            <option data-filter-type="sort" data-filter-val="date" data-order="desc"><?php _e("Date") ?></option>
                        </select>
                    </div>
                </div>
                <!-- Tasks Container -->
                <div class="tasks-list-container compact-list margin-top-35">
                    <?php foreach ($items as $item){ ?>
                        <!-- Project -->
                        <a href="<?php _esc($item['link'])?>" class="task-listing">

                            <!-- Job Listing Details -->
                            <div class="task-listing-details">

                                <!-- Details -->
                                <div class="task-listing-description">
                                    <h3 class="task-listing-title"><?php _esc($item['product_name'])?></h3>
                                    <ul class="task-icons">
                                        <li><i class="icon-material-outline-gavel"></i> <?php _esc($item['bids_count'])?> <?php _e("Bids") ?></li>
                                        <li><i class="icon-material-outline-access-time"></i> <?php _esc($item['created_at'])?></li>
                                    </ul>
                                    <p class="task-listing-text"><?php _esc($item['description'])?></p>
                                    <div class="task-tags">
                                        <?php _esc($item['skills'])?>
                                    </div>
                                </div>

                            </div>

                            <div class="task-listing-bid">
                                <div class="task-listing-bid-inner">
                                    <div class="task-offers">
                                        <strong><?php _esc($config['currency_sign'])?><?php _esc($item['salary_min'])?> - <?php _esc($config['currency_sign'])?><?php _esc($item['salary_max'])?> </strong>
                                        <span><?php _esc($item['salary_type'])?></span>
                                    </div>
                                    <span class="button button-sliding-icon ripple-effect"><?php _e("Bid Now") ?> <i class="icon-material-outline-arrow-right-alt"></i></span>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
                <!-- Tasks Container / End -->


                <!-- Pagination -->
                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Pagination -->
                        <div class="pagination-container margin-top-60 margin-bottom-60">
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
                <!-- Pagination / End -->

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