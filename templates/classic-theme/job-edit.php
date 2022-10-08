<?php overall_header(__($header_text)); ?>
<!-- Select Category Modal -->
<div class="zoom-anim-dialog mfp-hide popup-dialog big-dialog" id="categoryModal">
    <div class="popup-tab-content padding-0 tg-thememodal tg-categorymodal">
        <div class="tg-thememodaldialog">
            <div class="tg-thememodalcontent">
                <div class="tg-title">
                    <strong><?php _e("Select") ?> <?php _e("Category") ?></strong>
                </div>
                <div id="tg-dbcategoriesslider"
                     class="tg-dbcategoriesslider tg-categories owl-carousel select-category post-option">
                    <?php foreach ($category as $cat){ ?>
                        <div class="tg-category <?php _esc($cat['selected'])?>" data-ajax-catid="<?php _esc($cat['id'])?>"
                             data-ajax-action="getsubcatbyidList" data-cat-name="<?php _esc($cat['name'])?>"
                             data-sub-cat="<?php _esc($cat['sub_cat'])?>">
                            <div class="tg-categoryholder">
                                <div class="margin-bottom-10">
                                    <?php
                                    if($cat['picture'] == ""){
                                        echo '<i class="'._esc($cat['icon'],false).'"></i>';
                                    }else{
                                        echo '<img src="'._esc($cat['picture'],false).'" alt="'._esc($cat['name'],false).'"/>';
                                    }
                                    ?>
                                </div>
                                <h3><a href="javascript:void()"><?php _esc($cat['name'])?></a></h3>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <ul class="tg-subcategories" style="display: none">
                    <li>
                        <div class="tg-title">
                            <strong><?php _e("Select") ?> <?php _e("Sub Category") ?></strong>

                            <div id="sub-category-loader" style="visibility:hidden"></div>
                        </div>
                        <div class=" tg-verticalscrollbar tg-dashboardscrollbar">
                            <ul id="sub_category">

                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Select Category Modal -->

<div id="titlebar" class="margin-bottom-0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Post a Job") ?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Post a Job") ?></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<div class="section gray">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 col-md-12">
                <div id="post_error"></div>
                <div class="payment-confirmation-page dashboard-box margin-top-0 padding-top-0 margin-bottom-50"
                     style="display: none">
                    <div class="headline">
                        <h3><?php _e("Success") ?></h3>
                    </div>
                    <div class="content with-padding padding-bottom-10">
                        <i class="la la-check-circle"></i>

                        <h2 class="margin-top-30"><?php _e("Success") ?></h2>

                        <p><?php _e("Your job successfully uploaded. Please wait for approval. Thanks") ?></p>
                    </div>
                </div>
                <form id="post_job_form" action="<?php url("EDIT-JOB") ?>?action=edit_ad" method="post"
                      enctype="multipart/form-data" accept-charset="UTF-8">
                    <?php if($config['company_enable']){ ?>
                        <div class="dashboard-box margin-top-0 margin-bottom-30">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="la la-building"></i> <?php _e("Company Information") ?></h3>
                            </div>
                            <div class="content with-padding padding-bottom-10">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Company") ?> *</h5>
                                            <select id="company-select" class="selectpicker with-border"
                                                    title="<?php _e("Select") ?> <?php _e("Company") ?>" data-size="7" name="company">
                                                <?php
                                                foreach ($companies as $company){
                                                    $selected = ($company_id == $company['id'])? "selected" : "";
                                                    echo '<option value="'._esc($company['id'],false).'" '.$selected.'>'._esc($company['title'],false).'</option>';
                                                } ?>
                                                <option value="0"><?php _e("[+] New Company") ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 new-company" style="display: none">
                                        <div class="submit-field">
                                            <h5><?php _e("Company Name") ?> *</h5>
                                            <input type="text" class="with-border" name="company_name">
                                        </div>
                                        <?php if($config['reg_no_enable']){ ?>
                                            <div class="submit-field">
                                                <h5><?php _e("Registration no.") ?> *</h5>
                                                <input type="text" class="with-border" id="reg_no" name="reg_no">
                                            </div>
                                        <?php } ?>
                                        <div class="submit-field">
                                            <h5><?php _e("Logo") ?></h5>

                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="image/*" id="upload"
                                                       name="company_logo"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="upload"><?php _e("Upload Logo") ?></label>
                                                <span class="uploadButton-file-name"><?php _e("Use 200x200px size for better view.") ?></span>
                                            </div>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Company Description") ?> *</h5>
                                            <textarea cols="30" rows="5" class="with-border" name="company_desc"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-briefcase"></i> <?php _e("Job Details") ?></h3>
                        </div>
                        <div class="content with-padding padding-bottom-10">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group text-center">
                                        <a href="#categoryModal" id="choose-category"
                                           class="button popup-with-zoom-anim"><i class="icon-feather-check-circle"></i>
                                            &nbsp;<?php _e("Edit Category") ?></a>
                                    </div>

                                    <div class="form-group selected-product" id="change-category-btn"
                                        <?php if($category == ""){ ?> style='display: none' <?php } ?> >
                                        <ul class="select-category list-inline">
                                            <li id="main-category-text"><?php _esc($category)?></li>
                                            <li id="sub-category-text"
                                                <?php if($subcategory == ""){ ?> style='display: none' <?php } ?> >
                                                <?php _esc($subcategory)?></li>
                                            <li class="active"><a href="#categoryModal" class="popup-with-zoom-anim"><i
                                                            class="icon-feather-edit"></i> <?php _e("Edit") ?></a></li>
                                        </ul>

                                        <input type="hidden" id="input-maincatid" name="catid" value="<?php _esc($catid)?>">
                                        <input type="hidden" id="input-subcatid" name="subcatid" value="<?php _esc($subcatid)?>">
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Title") ?> *</h5>
                                        <input type="text" class="with-border" name="title" value="<?php _esc($title)?>">
                                    </div>

                                    <?php if($config['job_image_field']){ ?>
                                        <div class="submit-field">
                                            <h5><?php _e("Image") ?></h5>
                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="image/*" id="job_image"
                                                       name="job_image"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="job_image"><?php _e("Upload Image") ?></label>
                                                <span class="uploadButton-file-name"><?php _e("Use 200x200px size for better view.") ?></span>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="submit-field">
                                        <h5><?php _e("Description") ?> *</h5>
                                        <textarea cols="30" rows="5" class="with-border text-editor"
                                                  name="content"><?php _esc($description)?></textarea>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Job Type") ?> *</h5>
                                        <select class="selectpicker with-border" data-size="7" name="job_type">
                                            <?php
                                            foreach ($posttypes as $posttype){
                                                $selected = ($product_type == $posttype['id'])? "selected" : "";
                                                echo '<option value="'._esc($posttype['id'],false).'" '.$selected.'>'._esc($posttype['title'],false).'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Salary") ?></h5>

                                        <div class="row">
                                            <div class="col-xl-4 col-md-12">
                                                <div class="input-with-icon">
                                                    <input class="with-border" type="text" placeholder="<?php _e("Min") ?>"
                                                           name="salary_min" value="<?php _esc($salary_min)?>">
                                                    <i class="currency"><?php _esc($user_currency_sign)?></i>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-12">
                                                <div class="input-with-icon">
                                                    <input class="with-border" type="text" placeholder="<?php _e("Max") ?>"
                                                           name="salary_max" value="<?php _esc($salary_max)?>">
                                                    <i class="currency"><?php _esc($user_currency_sign)?></i>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-12">
                                                <select class="selectpicker with-border margin-bottom-16" data-size="7"
                                                        name="salary_type">
                                                    <?php
                                                    foreach ($salarytypes as $salarytype){
                                                        $selected = ($salary_type == $salarytype['id'])? "selected" : "";
                                                        echo '<option value="'._esc($salarytype['id'],false).'" '.$selected.'>'._esc($salarytype['title'],false).'</option>';
                                                    } ?>
                                                </select>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="negotiable" name="negotiable" value="1"
                                                        <?php if($negotiable == "1") { echo "checked"; }?> >
                                                    <label for="negotiable"><span
                                                                class="checkbox-icon"></span> <?php _e("Negotiable") ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="ResponseCustomFields">
                                        <?php
                                        foreach ($customfields as $customfield){
                                            if($customfield['type']=="text-field"){
                                                echo '<div class="submit-field">
                                                <h5>'._esc($customfield['title'],false).'</h5>
                                                    '._esc($customfield['textbox'],false).'
                                                </div>';
                                            }
                                            if($customfield['type']=="textarea"){
                                                echo '<div class="submit-field">
                                                    <h5>'._esc($customfield['title'],false).'</h5>
                                                    '._esc($customfield['textarea'],false).'
                                                </div>';
                                            }
                                            if($customfield['type']=="radio-buttons"){
                                                echo '<div class="submit-field">
                                                    <h5>'._esc($customfield['title'],false).'</h5>
                                                    '._esc($customfield['radio'],false).'
                                                </div>';
                                            }
                                            if($customfield['type']=="drop-down"){
                                                echo '<div class="submit-field">
                                                    <h5>'._esc($customfield['title'],false).'</h5>
                                                    <select class="selectpicker with-border quick-custom-field" 
                                                    name="custom['._esc($customfield["id"],false).']"
                                                    data-name="'._esc($customfield["id"],false).'" 
                                                    data-req="'._esc($customfield["required"],false).'">
                                                        <option value="" selected>'.__("Select").' '._esc($customfield["title"],false).'</option>
                                                        '._esc($customfield["selectbox"],false).'
                                                    </select>
                                                    <div class="quick-error">'.__("This field is required.").'</div>
                                                </div>';
                                            }
                                            if($customfield['type']=="checkboxes"){
                                                echo '<div class="submit-field">
                                                    <h5>'._esc($customfield['title'],false).'</h5>
                                                    '._esc($customfield['checkbox'],false).'
                                                </div>';
                                            }
                                        }
                                        ?>
                                    </div>

                                    <div class="submit-field">
                                        <h5><?php _e("Phone Number") ?></h5>

                                        <div class="row">
                                            <div class="col-xl-6 col-md-12">
                                                <div class="input-with-icon-left">
                                                    <i class="flag-img"><img src="<?php _esc($config['site_url'])?>includes/assets/plugins/flags/images/<?php _esc($user_country)?>.png"></i>
                                                    <input type="text" class="with-border" name="phone" value="<?php _esc($phone)?>">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-md-12">
                                                <div class="checkbox margin-top-12">
                                                    <input type="checkbox" name="hide_phone" id="phone" value="1"
                                                        <?php if($hidephone == "1") { echo "checked"; }?> >
                                                    <label for="phone"><span
                                                                class="checkbox-icon"></span> <?php _e("Hide from users") ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("City") ?> *</h5>
                                        <select id="jobcity" class="with-border" name="city" data-size="7"
                                                title="<?php _e("Select") ?> <?php _e("City") ?>">
                                            <option value="0" selected="selected"><?php _e("Select") ?> <?php _e("City") ?></option>
                                            <?php if($city != ""){
                                                echo '<option value="'._esc($city,false).'" selected="selected">'._esc($cityname,false).'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    <?php if($config['post_address_mode']){ ?>
                                        <div class="submit-field">
                                            <h5><?php _e("Address") ?></h5>
                                            <div class="input-with-icon">
                                                <div id="autocomplete-container" data-autocomplete-tip="<?php _e("type and hit enter") ?>">
                                                    <input class="with-border" type="text" placeholder="<?php _e("Address") ?>" name="location" id="address-autocomplete" value="<?php _esc($location)?>">
                                                </div>
                                                <div class="geo-location"><i class="la la-crosshairs"></i></div>
                                            </div>
                                            <div class="map shadow" id="singleListingMap" data-latitude="<?php _esc($latitude)?>" data-longitude="<?php _esc($longitude)?>"  style="height: 200px" data-map-icon="map-marker"></div>
                                            <small><?php _e("Drag the map marker to exact address.") ?></small>
                                            <input type="hidden" id="latitude" name="latitude" value="<?php _esc($latitude)?>"/>
                                            <input type="hidden" id="longitude" name="longitude" value="<?php _esc($longitude)?>"/>
                                        </div>
                                    <?php } ?>
                                    <div class="submit-field form-group">
                                        <h5><?php _e("Application Url") ?></h5>

                                        <div class="input-with-icon">
                                            <input class="with-border" type="text" placeholder="http://"
                                                   name="application_url" value="<?php _esc($application_url)?>">
                                            <i class="la la-link"></i>
                                        </div>
                                        <small><?php _e("Candidates will follow this URL address to apply for the job.") ?></small>
                                    </div>
                                    <?php if($config['post_tags_mode']){ ?>
                                        <div class="submit-field form-group">
                                            <h5><?php _e("Tags") ?></h5>
                                            <input class="with-border" type="text" name="tags" value="<?php _esc($tags)?>">
                                            <small><?php _e("Enter the tags separated by commas.") ?></small>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(!$is_login){ ?>
                        <div class="dashboard-box">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-user"></i> <?php _e("User Details") ?></h3>
                            </div>
                            <div class="content with-padding padding-bottom-10">
                                <div class="row">
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Full Name") ?> *</h5>

                                            <div class="input-with-icon-left">
                                                <i class="la la-user"></i>
                                                <input type="text" class="with-border" name="user_name" value="<?php _esc($seller_name)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Email Address") ?> *</h5>

                                            <div class="input-with-icon-left">
                                                <i class="la la-envelope"></i>
                                                <input type="email" class="with-border" name="user_email" value="<?php _esc($seller_email)?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }
                    if($config['post_premium_listing']){ ?>
                        <div class="dashboard-box">
                            <div class="headline">
                                <h3><i class="icon-feather-zap"></i> <?php _e("Make your Job Premium") ?>
                                    <small>(<?php _e("Optional") ?>)</small>
                                </h3>
                            </div>
                            <div class="content with-padding">
                                <div class="payment">

                                    <div class="payment-tab payment-tab-active">
                                        <div class="payment-tab-trigger">
                                            <input checked id="free" name="make_premium" type="radio" value="0">
                                            <label for="free"><?php _e("Free Job") ?></label>
                                        </div>
                                        <div class="payment-tab-content">
                                            <p><?php _e("Your job will go live after check by reviewer.") ?></p>
                                        </div>
                                    </div>

                                    <div class="payment-tab">
                                        <div class="payment-tab-trigger">
                                            <input type="radio" name="make_premium" id="make_premium" value="1">
                                            <label for="make_premium"><?php _e("Premium") ?> <span
                                                        class="dashboard-status-button green pull-right"><?php _e("Recommended") ?></span></label>
                                        </div>

                                        <div class="payment-tab-content">
                                            <p><?php _e("You can optionally select some upgrades to get the best results.") ?></p>

                                            <div class="row premium-plans">
                                                <div class="col-lg-3">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="featured" name="featured" value="1"
                                                               onchange="fillPrice(this,<?php _esc($featured_fee)?>);">
                                                        <label for="featured"><span class="checkbox-icon"></span> <span
                                                                    class="dashboard-status-button blue"><?php _e("Featured") ?></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 premium-plans-text">
                                                    <?php _e("Featured jobs attract higher-quality viewer and are displayed prominently in the Featured jobs section home page.") ?>
                                                </div>
                                                <div class="col-lg-3 premium-plans-price">
                                                    <?php _esc($user_currency_sign)?><?php _esc($featured_fee)?> <?php _e("for") ?> <?php _esc($featured_duration)?> <?php _e("days") ?>
                                                </div>
                                            </div>
                                            <div class="row premium-plans">
                                                <div class="col-lg-3">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="urgent" name="urgent" value="1"
                                                               onchange="fillPrice(this,<?php _esc($urgent_fee)?>);">
                                                        <label for="urgent"><span class="checkbox-icon"></span> <span
                                                                    class="dashboard-status-button yellow"><?php _e("Urgent") ?></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 premium-plans-text">
                                                    <?php _e("Make your job stand out and let viewer know that your job is time sensitive.") ?>
                                                </div>
                                                <div class="col-lg-3 premium-plans-price">
                                                    <?php _esc($user_currency_sign)?><?php _esc($urgent_fee)?> <?php _e("for") ?> <?php _esc($urgent_duration)?> <?php _e("days") ?>
                                                </div>
                                            </div>
                                            <div class="row premium-plans">
                                                <div class="col-lg-3">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="highlight" name="highlight" value="1"
                                                               onchange="fillPrice(this,<?php _esc($highlight_fee)?>);">
                                                        <label for="highlight"><span class="checkbox-icon"></span> <span
                                                                    class="dashboard-status-button red"><?php _e("Highlight") ?></span></label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 premium-plans-text">
                                                    <?php _e("Make your job highlighted with border in listing search result page. Easy to focus.") ?>
                                                </div>
                                                <div class="col-lg-3 premium-plans-price">
                                                    <?php _esc($user_currency_sign)?><?php _esc($highlight_fee)?> <?php _e("for") ?> <?php _esc($highlight_duration)?> <?php _e("days") ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    if($resubmit){ ?>
                        <div class="dashboard-box">
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-feather-user"></i> <?php _e("Message to the Reviewer") ?></h3>
                            </div>
                            <div class="content with-padding padding-bottom-10">
                                <div class="submit-field">
                                    <h5><?php _e("Comments") ?> *</h5>
                                    <textarea class="with-border" name="comments" required=""></textarea>
                                    <small><?php _e("You must give a brief description of any changes you have made.") ?></small>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <input type="hidden" name="product_id" value="<?php _esc($item_id)?>">
                    <input type="hidden" name="submit">

                    <div class="row margin-top-30 margin-bottom-80" style="align-items: center;">
                        <div class="col-6">
                            <button type="submit" id="submit_job_button" name="Submit" class="button ripple-effect big"><i
                                        class="icon-feather-plus"></i> <?php _e("Post a Job") ?></button>
                        </div>
                        <div class="col-6">
                            <div id="ad_total_cost_container" class="text-right" style="display: none">
                                <strong>
                                    <?php _e("Total") ?>:
                                    <span class="currency-sign"><?php _esc($config['currency_sign'])?></span>
                                    <span id="totalPrice">0</span>
                                    <span class="currency-code"><?php _esc($config['currency_code'])?></span>
                                </strong>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xl-4 hide-under-992px">
                <div class="dashboard-box margin-top-0">
                    <!-- Headline -->
                    <div class="headline">
                        <h3><i class="icon-feather-alert-circle"></i> <?php _e("Tips!") ?></h3>
                    </div>
                    <div class="content with-padding padding-bottom-10">
                        <ul class="list-2">
                            <li><?php _e("Enter a brief description of the company and job.") ?></li>
                            <li><?php _e("Add your company logo.") ?></li>
                            <li><?php _e("Choose the correct category and sub-category of the job.") ?></li>
                            <li><?php _e("Check again before submit the job.") ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="<?php _esc(TEMPLATE_URL);?>/css/category-modal.css" type="text/css" rel="stylesheet">
<link href="<?php _esc(TEMPLATE_URL);?>/css/owl.post.carousel.css" type="text/css" rel="stylesheet">
<link href="<?php _esc(TEMPLATE_URL);?>/css/select2.min.css" rel="stylesheet"/>
<script src="<?php _esc(TEMPLATE_URL);?>/js/select2.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/<?php _esc($config['lang_code']) ?>.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/owl.carousel-category.min.js"></script>

<script>
    var ajaxurl = "<?php _esc($config['app_url'])?>user-ajax.php";
    var lang_edit_cat = "<i class='icon-feather-check-circle'></i> &nbsp;<?php _e("Edit Category") ?>";

    $('#company-select').on('change', function () {
        if ($('#company-select').val() == 0) {
            $('.new-company').slideDown('fast');
            ;
        } else {
            $('.new-company').slideUp('fast');
        }
    });
    $('.company-select').trigger('change');
</script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.form.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/job_post.js"></script>

<?php if($config['post_desc_editor'] == "1"){ ?>
    <!-- CRUD FORM CONTENT - crud_fields_scripts stack -->
    <link media="all" rel="stylesheet" type="text/css" href="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/styles/simditor.css" />
    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/scripts/mobilecheck.js"></script>
    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/scripts/module.js"></script>
    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/scripts/uploader.js"></script>
    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/scripts/hotkeys.js"></script>
    <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/scripts/simditor.js"></script>
    <script>
        (function() {
            $(function() {
                var $preview, editor, mobileToolbar, toolbar, allowedTags;
                Simditor.locale = 'en-US';
                toolbar = ['bold','italic','underline','|','ol','ul','blockquote','table','link'];
                mobileToolbar = ["bold", "italic", "underline", "ul", "ol"];
                if (mobilecheck()) {
                    toolbar = mobileToolbar;
                }
                allowedTags = ['br','span','a','img','b','strong','i','strike','u','font','p','ul','ol','li','blockquote','pre','h1','h2','h3','h4','hr','table'];
                editor = new Simditor({
                    textarea: $('.text-editor'),
                    placeholder: '',
                    toolbar: toolbar,
                    pasteImage: false,
                    defaultImage: '<?php _esc($config['site_url'])?>includes/assets/plugins/simditor/images/image.png',
                    upload: false,
                    allowedTags: allowedTags
                });
                $preview = $('#preview');
                if ($preview.length > 0) {
                    return editor.on('valuechanged', function(e) {
                        return $preview.html(editor.getValue());
                    });
                }
            });
        }).call(this);
    </script>
<?php } ?>

<?php
if($config['post_address_mode']){
    if($config['map_type']=="google"){
        ?>
        <link href="<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/map-marker.css" type="text/css" rel="stylesheet">
        <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/jquery-migrate-1.2.1.min.js'></script>
        <script type='text/javascript' src='//maps.google.com/maps/api/js?key=<?php _esc($config['gmap_api_key'])?>&#038;libraries=places%2Cgeometry&#038;ver=2.2.1'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/richmarker-compiled.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/markerclusterer_packed.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/gmapAdBox.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url'])?>includes/assets/plugins/map/google/maps.js'></script>
        <script>
            var element = "singleListingMap";
            var getCity = false;
            var _latitude = '<?php _esc($latitude)?>';
            var _longitude = '<?php _esc($longitude)?>';
            var color = '<?php _esc($map_color)?>';
            var site_url = '<?php _esc($config['site_url'])?>';
            var path = site_url;
            simpleMap(_latitude, _longitude, element);

            $('#jobcity').on('change', function() {
                var data = $("#jobcity option:selected").val();
                var custom_data= $("#jobcity").select2('data')[0];
                var latitude = custom_data.latitude;
                var longitude = custom_data.longitude;
                var address = custom_data.text;
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
                simpleMap(latitude, longitude, element, true, address);
            });
        </script>
    <?php }else{ ?>
        <script>
            var openstreet_access_token = '<?php _esc($config['openstreet_access_token'])?>';
        </script>
        <link rel="stylesheet" href="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/css/style.css">
        <!-- Leaflet // Docs: https://leafletjs.com/ -->
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet.min.js"></script>

        <!-- Leaflet Maps Scripts (locations are stored in leaflet-quick.js) -->
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-markercluster.min.js"></script>
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-gesture-handling.min.js"></script>
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-quick.js"></script>

        <!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-autocomplete.js"></script>
        <script src="<?php _esc($config['site_url'])?>includes/assets/plugins/map/openstreet/leaflet-control-geocoder.js"></script>
        <script>
            $('#jobcity').on('change', function() {
                var data = $("#jobcity option:selected").val();
                var custom_data= $("#jobcity").select2('data')[0];
                var latitude = custom_data.latitude;
                var longitude = custom_data.longitude;
                console.log(custom_data);
                var address = custom_data.text;
                $('#latitude').val(latitude);
                $('#longitude').val(longitude);
                if (document.getElementById("singleListingMap") !== null && singleListingMap) {
                    $("#address-autocomplete").val(address);
                    var newLatLng = new L.LatLng(latitude, longitude);
                    singleListingMapMarker.setLatLng(newLatLng);
                    singleListingMap.flyTo(newLatLng, 10);
                }
            });
        </script>
    <?php }
}?>
<?php overall_footer();?>
