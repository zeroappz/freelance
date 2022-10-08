<?php
overall_header(__("Post Project"));
?>
<div id="post_ad_email_exist" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-align-top my-mfp-zoom-in mfp-ready"
     tabindex="-1" style="display: none">
    <div class="mfp-container mfp-inline-holder">
        <div class="mfp-content">
            <div class="zoom-anim-dialog dialog-with-tabs popup-dialog">
                <ul class="popup-tabs-nav" style="pointer-events: none;">
                    <li class="active"><a href="#exist_acc"><?php _e("Link to existing accounts") ?></a></li>
                </ul>
                <div class="popup-tabs-container">
                    <div class="popup-tab-content" id="exist_acc" style="">
                        <form accept-charset="utf-8" id="email_exists_login">
                            <p id="email_exists_success" style="display: none;">
                                <span class="status-available"><?php _e("Account Linked Successful. Redirecting...") ?></span>
                            </p>

                            <p><span id="quickad_email_already_linked"></span>
                                <br><?php _e("Enter your password below to link accounts:") ?></p>

                            <p id="email_exists_error" style="display: none;"></p>

                            <div class="form-group">
                                <span><?php _e("Username") ?>:</span>
                                <strong id="quickad_username_display"></strong>
                            </div>
                            <div class="form-group">
                                <span><?php _e("Email Address") ?>:</span>
                                <strong id="quickad_email_display"></strong>
                            </div>
                            <div>
                                <span><?php _e("Password") ?>:</span>
                                <input type="password" class="with-border margin-bottom-0" id="password"
                                       name="password">
                                <a href="<?php url("LOGIN") ?>?fstart=1" target="_blank" id="fb_forgot_password_btn">
                                    <small><?php _e("Forgot Password?") ?></small>
                                </a>
                            </div>
                            <div>
                                <input type="hidden" name="email" id="email" value=""/>
                                <input type="hidden" name="username" id="username" value=""/>
                                <button id="link_account" type="button" value="Submit" class="button ripple-effect">
                                    <?php _e("Link Account") ?>
                                </button>
                            </div>
                        </form>
                        <div id="email_exists_user">
                            <p><?php _e("The email address you entered is linked with a Job Seeker account. Please change the email address or login with an Employer account") ?></p>
                            <button type="button" class="button ripple-effect" id="change-email">
                                <?php _e("Change Email Address") ?>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="mfp-close"></button>
            </div>
        </div>
    </div>
    <div class="mfp-bg my-mfp-zoom-in mfp-ready"></div>
</div>

<!-- Titlebar
================================================== -->
<div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2><?php _e("Post Project") ?></h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Post Project") ?></li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<div class="section">
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

                        <p><?php _e("Posted successfully uploaded. Please wait for approval. Thanks") ?></p>
                    </div>
                </div>
                <form id="post_job_form" action="<?php url("POST-PROJECT") ?>?action=post_job" method="post"
                      enctype="multipart/form-data" accept-charset="UTF-8">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-feather-briefcase"></i><?php _e("Project Details") ?></h3>
                        </div>
                        <div class="content with-padding padding-bottom-10">
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="submit-field">
                                        <h5><?php _e("Choose a name for your project") ?> *</h5>
                                        <input type="text" class="with-border" name="title">
                                    </div>

                                    <div class="submit-field">
                                        <h5><?php _e("Tell us more about your project") ?> *</h5>
                                        <p><?php _e("Start with a bit about yourself or your business, and include an overview of what you need done.") ?></p>
                                        <textarea cols="30" rows="5" class="with-border text-editor" name="content"></textarea>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("Select Category which fit your project requirements") ?> *</h5>
                                        <select class="selectpicker with-border" data-size="7" name="catid">
                                            <?php foreach ($category as $cat){
                                                echo '<option value="'._esc($cat['id'],false).'">'._esc($cat['name'],false).'</option>';
                                            }?>
                                        </select>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("What skills are required?") ?> *</h5>
                                        <p><?php _e("Enter up to 5 skills that best describe your project. Freelancers will use these skills to find projects they are most interested and experienced in.") ?></p>
                                        <select name="subcatid[]" class="selectpicker" data-live-search="true" data-size="7" data-max-options="5" multiple >
                                            <?php foreach ($skills as $skill){
                                               echo '<option value="'._esc($skill['id'],false).'">'._esc($skill['title'],false).'</option>';
                                            }?>
                                        </select>
                                    </div>

                                    <div class="submit-field">
                                        <h5><?php _e("How do you want to pay?") ?> *</h5>
                                        <span class="radio-btn">
                                            <input id="fixed_price" class="projecttypRadio" type="radio" name="salary_type" value="0" onFocus="return show1();" checked="checked"/>
                                            <label for="fixed_price"><?php _e("Fixed Price") ?></label>
                                        </span>
                                        <span class="radio-btn">
                                            <input id="hourly_price" class="projecttypRadio" type="radio" name="salary_type" value="1" onFocus="return show1();"/>
                                            <label for="hourly_price"><?php _e("Hourly Price") ?></label>
                                        </span>
                                    </div>
                                    <div class="submit-field">
                                        <h5><?php _e("What is your estimated budget?") ?> (<?php _esc($config['currency_code'])?>)</h5>

                                        <?php
                                        //This Function is called for set default currency code
                                        set_user_currency($config['specific_country']);
                                        ?>
                                        <div class="row">
                                            <div class="col-xl-4 col-md-12">
                                                <div class="input-with-icon">
                                                    <input class="with-border" type="text" placeholder="<?php _e("Min") ?>"
                                                           name="salary_min">
                                                    <i class="currency"><?php _esc($config['currency_sign'])?></i>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-12">
                                                <div class="input-with-icon">
                                                    <input class="with-border" type="text" placeholder="<?php _e("Max") ?>"
                                                           name="salary_max">
                                                    <i class="currency"><?php _esc($config['currency_sign'])?></i>
                                                </div>
                                            </div>
                                            <div id="show1" style="display:none; margin-top: 6px;"><span class="fbold">/<?php _e("hrs") ?></span></div>
                                        </div>
                                    </div>


                                    <div id="ResponseCustomFields">
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
                                    </div>

                                    <div class="submit-field d-none">
                                        <h5>Attachments</h5>
                                        <!-- Attachments -->
                                        <div class="attachments-container margin-top-0 margin-bottom-0">
                                            <div class="attachment-box ripple-effect">
                                                <span>Cover Letter</span>
                                                <i>PDF</i>
                                                <button class="remove-attachment" data-tippy-placement="top" title="Remove"></button>
                                            </div>
                                            <div class="attachment-box ripple-effect">
                                                <span>Contract</span>
                                                <i>DOCX</i>
                                                <button class="remove-attachment" data-tippy-placement="top" title="Remove"></button>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>

                                        <!-- Upload Button -->
                                        <div class="uploadButton margin-top-0">
                                            <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload" multiple/>
                                            <label class="uploadButton-button ripple-effect" for="upload">Upload Files</label>
                                            <span class="uploadButton-file-name">Maximum file size: 10 MB</span>
                                        </div>

                                    </div>

                                    <?php if($config['post_tags_mode'] == "1"){ ?>
                                    <div class="submit-field form-group">
                                        <h5><?php _e("Tags") ?></h5>
                                        <input class="with-border" type="text" name="tags">
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
                                            <input type="text" class="with-border" name="user_name">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-12">
                                    <div class="submit-field">
                                        <h5><?php _e("Email Address") ?> *</h5>

                                        <div class="input-with-icon-left">
                                            <i class="la la-envelope"></i>
                                            <input type="email" class="with-border" name="user_email">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <?php if($config['post_premium_listing']){ ?>
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
                                        <label for="free"><?php _e("Free") ?> <?php _e("Project") ?></label>
                                    </div>
                                    <div class="payment-tab-content">
                                        <p><?php _e("Free to post, your project will go live instantly and start receiving bids within seconds.") ?></p>
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
                                                                class="dashboard-status-button blue"><?php _e("Highlight") ?></span></label>
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
                    <?php } ?>
                    <input type="hidden" name="submit">

                    <div class="row margin-top-30 margin-bottom-80" style="align-items: center;">
                        <div class="col-6">
                            <button type="submit" id="submit_job_button" name="Submit" class="button ripple-effect big">
                                <i class="icon-feather-plus"></i> <?php _e("Post Project") ?></button>
                        </div>
                        <div class="col-6">
                            <div id="ad_total_cost_container" class="text-right" style="display: none">
                                <strong>
                                    <?php _e("Total") ?>:
                                    <span class="currency-sign"><?php _esc($user_currency_sign)?></span>
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
        } else {
            $('.new-company').slideUp('fast');
        }
    });
    $('#company-select').trigger('change');

    function show1(){
        var ele = document.getElementById("show1");
        if(ele.style.display == "block") {
            ele.style.display = "none";
        }
        else {
            ele.style.display = "block";
        }
    }
</script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.form.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/job_post.js"></script>
<?php overall_footer(); ?>