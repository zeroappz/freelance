<?php
overall_header($pagetitle);
?>
<form method="get" action="<?php url("FREELANCERS") ?>" name="locationForm" id="ListingForm">
    <div id="titlebar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2><?php _e("We found") ?> <?php _esc($usersfound)?> <?php _e("Freelancers") ?></h2>
                    <!-- Breadcrumbs -->
                    <nav id="listing-breadcrumbs">
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
                                    <span class="change-text"><?php _e("Select") ?> <?php _e("Category") ?></span><i
                                            class="fa fa-navicon"></i>
                                </a>
                                <?php _esc($cat_dropdown) ?>
                            </div>
                        </div>
                        <div class="intro-search-field">
                            <input id="keywords" type="text" name="keywords" placeholder="<?php _e("Name or Keywords") ?>"
                                   value="<?php _esc($keywords) ?>">
                        </div>
                        <div class="intro-search-button">
                            <input type="hidden" id="input-maincat" name="cat" value="<?php _esc($maincat) ?>"/>
                            <input type="hidden" id="input-subcat" name="subcat" value="<?php _esc($subcat) ?>"/>
                            <button class="button ripple-effect"><?php _e("Search") ?></button>
                        </div>
                    </div>
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
                    <div class="sidebar-widget">
                        <h3><?php _e("Age") ?></h3>
                        <div class="range-widget">
                            <div class="range-inputs">
                                <input type="text" placeholder="<?php _e("Min") ?>" name="age_range1" value="<?php _esc($age_range1) ?>">
                                <input type="text" placeholder="<?php _e("Max") ?>" name="age_range2" value="<?php _esc($age_range2) ?>">
                            </div>
                            <button type="submit" class="button"><i class="icon-feather-arrow-right"></i></button>
                        </div>
                    </div>
                    <div class="sidebar-widget">
                        <h3><?php _e("Expected Salary") ?></h3>
                        <div class="range-widget">
                            <div class="range-inputs">
                                <input type="text" placeholder="<?php _e("Min") ?>" name="range1" value="<?php _esc($range1) ?>">
                                <input type="text" placeholder="<?php _e("Max") ?>" name="range2" value="<?php _esc($range2) ?>">
                            </div>
                            <button type="submit" class="button"><i class="icon-feather-arrow-right"></i></button>
                        </div>
                        <small><?php _e("Salary per month.") ?></small>
                    </div>
                    <div class="sidebar-widget">
                        <h3><?php _e("Gender") ?></h3>
                        <div class="radio margin-right-20">
                            <input class="with-gap" type="radio" name="gender" id="All" value="" <?php if($gender == "") { echo "checked"; }?> />
                            <label for="All"><span class="radio-label"></span><?php _e("All") ?></label>
                        </div><br>
                        <div class="radio margin-right-20">
                            <input class="with-gap" type="radio" name="gender" id="Male" value="Male" <?php if($gender == "Male") { echo "checked"; }?> />
                            <label for="Male"><span class="radio-label"></span><?php _e("Male") ?></label>
                        </div><br>
                        <div class="radio margin-right-20">
                            <input class="with-gap" type="radio" name="gender" id="Female" value="Female" <?php if($gender == "Female") { echo "checked"; }?> />
                            <label for="Female"><span class="radio-label"></span><?php _e("Female") ?></label>
                        </div><br>
                        <div class="radio margin-right-20">
                            <input class="with-gap" type="radio" name="gender" id="Other" value="Other" <?php if($gender == "Other") { echo "checked"; }?> />
                            <label for="Other"><span class="radio-label"></span><?php _e("Other") ?></label>
                        </div>
                    </div>
                    <div class="sidebar-widget">
                        <button class="button full-width ripple-effect"><?php _e("Advanced Search") ?></button>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-8">

                <h3 class="page-title"><?php _e("Search Results") ?></h3>

                <div class="notify-box margin-top-15">
                    <span class="font-weight-600"><?php _esc($usersfound) ?> <?php _e("Users Found") ?></span>
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
                                    <div class="star-rating" data-rating="<?php _esc($item['rating']) ?>"></div><br>
                                    <p class="job-listing-text"><?php _esc($item['description'])?></p>
                                </div>
                                <?php if($usertype == 'employer') { ?>
                                    <span class="fav-icon set-user-fav <?php if($item['favorite'] == 'employer') { echo 'added'; }?>" data-favuser-id="<?php _esc($item['id'])?>" data-userid="<?php _esc($user_id)?>" data-action="setFavUser"></span>
                                <?php }?>
                            </div>
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="la la-map-marker"></i> <?php _esc($item['city'])?></li>
                                    <?php
                                    if($item['category'] != ""){
                                        echo '<li><i class="icon-feather-folder"></i>';
                                        _esc($item['category']);
                                        if($item['subcategory'] != ""){
                                            echo " / ";
                                            _esc($item['subcategory']);
                                        }
                                        echo "</li>";
                                    }
                                    if($item['salary_min'] != 0){ ?>
                                        <li data-tippy-placement="top" title="<?php _e("Salary per month.") ?>"><i class="la la-credit-card"></i> <?php _esc($item['salary_min'])?>
                                            <?php if($item['salary_max'] != '') {
                                                echo '- '._esc($item['salary_max'],false);
                                            }?></li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <?php if($usersfound != "0"){ ?>
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
    var getMaincatId = '{MAINCAT}';
    var getSubcatId = '{SUBCAT}';

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