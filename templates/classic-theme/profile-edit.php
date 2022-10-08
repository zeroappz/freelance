<?php
overall_header(__(""));
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
                <h3><?php _e("Profile edit") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("Profile edit") ?></li>
                    </ul>
                </nav>
            </div>


            <!-- Row -->
            <div class="row">
                <div class="col-xl-12 col-md-12 ">
                    <div class="dashboard-box js-accordion-item active">
                        <!-- Headline -->
                        <div class="headline js-accordion-header">
                            <h3><i class="icon-feather-user"></i> <?php _e("Profile Details") ?></h3>
                        </div>
                        <div class="content with-padding js-accordion-body">
                            <?php if(!$usertype == "user") { ?>
                            <form method="post" accept-charset="UTF-8">
                                <div class="row">
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("User Type") ?> *</h5>
                                            <select name="user-type" class="with-border selectpicker" required="">
                                                <option><?php _e("Select") ?></option>
                                                <option value="1"><?php _e("Job Seeker") ?></option>
                                                <option value="2"><?php _e("Employer") ?></option>
                                            </select>
                                            <span id="type-availability-status"><?php if($type_error != ""){ _esc($type_error) ; }?></span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="submit_type"
                                        class="button ripple-effect"><?php _e("Save Changes") ?></button>
                            </form>
                            <?php }else{ ?>
                            <form method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Name") ?> *</h5>

                                            <div class="input-with-icon-left">
                                                <i class="la la-user"></i>
                                                <input type="text" class="with-border" name="name" value="<?php _esc($authorname)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if($usertype == "user"){
                                    //This Function is called for set default currency code
                                    set_user_currency($config['specific_country']);
                                    ?>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Hourly Rate") ?> *</h5>
                                            <div class="input-with-icon">
                                                <input class="with-border margin-bottom-0" type="number" placeholder="<?php _e("Hourly Rate") ?>"
                                                       name="hourly_rate" value="<?php _esc($hourly_rate)?>" >
                                                <i class="currency"><?php _esc($config['currency_sign'])?></i>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Gender") ?></h5>
                                            <div class="radio margin-right-20">
                                                <input class="with-gap" type="radio" name="gender" id="Male" value="Male" <?php if($gender == 'Male') echo "checked"; ?> />
                                                <label for="Male"><span class="radio-label"></span><?php _e("Male") ?></label>
                                            </div>
                                            <div class="radio margin-right-20">
                                                <input class="with-gap" type="radio" name="gender" id="Female" value="Female" <?php if($gender == 'Female') echo "checked"; ?> />
                                                <label for="Female"><span class="radio-label"></span><?php _e("Female") ?></label>
                                            </div>
                                            <div class="radio margin-right-20">
                                                <input class="with-gap" type="radio" name="gender" id="Other" value="Other" <?php if($gender == 'Other') echo "checked"; ?> />
                                                <label for="Other"><span class="radio-label"></span><?php _e("Other") ?></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Phone Number") ?> *</h5>

                                            <div class="input-with-icon-left">
                                                <i class="la la-phone"></i>
                                                <input type="number" name="phone" class="with-border margin-bottom-0"
                                                       value="<?php _esc($phone)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Avatar") ?></h5>

                                            <div class="uploadButton">
                                                <input class="uploadButton-input" type="file" accept="images/*" id="avatar"
                                                       name="avatar"/>
                                                <label class="uploadButton-button ripple-effect"
                                                       for="avatar"><?php _e("Upload Avatar") ?></label>
                                                <span class="uploadButton-file-name"><?php _e("Use 150x150px image for perfect look.") ?></span>
                                            </div>
                                            <span id="email-availability-status">
                                                <?php if($avatar_error != ""){ _esc($avatar_error) ; }?>
                                            </span>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Tagline") ?></h5>
                                            <input type="text" name="tagline" class="with-border margin-bottom-0"
                                                   value="<?php _esc($tagline)?>">
                                            <small><?php _e("It will be shown on the job seeker search page.  (Max 200 characters)") ?></small>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("About Me") ?></h5>
                                            <textarea class="with-border" id="pageContent" name="aboutme" ><?php _esc($aboutme)?></textarea>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("City") ?></h5>
                                            <select id="jobcity" class="with-border" name="city" data-size="7" title="<?php _e("Select") ?> <?php _e("City") ?>">
                                                <option value="0" selected="selected"><?php _e("Select") ?> <?php _e("City") ?></option>
                                                <?php if($city != '')
                                                    echo '<option value="'._esc($city,false).'" selected="selected">'._esc($cityname,false).'</option>';
                                                ?>
                                            </select>
                                        </div>
                                        <div class="submit-field">
                                            <h5><?php _e("Address") ?></h5>
                                            <textarea class="with-border" name="address"><?php _esc($address)?></textarea>
                                        </div>

                                    </div>
                                    <?php if($usertype == "user") { ?>
                                        <div class="col-xl-6 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Category") ?></h5>
                                                <select class="selectpicker with-border" name="category" id="category" data-subcat="<?php _esc($subcat)?>">
                                                    <option value=""><?php _e("Select category") ?></option>
                                                    <?php foreach ($categories as $category){ ?>
                                                    <option value="<?php _esc($category['id'])?>" <?php _esc($category['selected'])?>><?php _esc($category['name'])?></option>
                                                    <?php } ?>
                                                </select>
                                                <small><?php _e("It will be used for job seeker search.") ?></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Sub Category") ?></h5>
                                                <select class="selectpicker with-border" name="subcategory" id="sub_category">
                                                    <option value="">-</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Skills") ?> (<?php _e("MAX") ?> <?php _esc($skills_limit)?>) *</h5>
                                                <select name="skills[]" class="selectpicker with-border" data-live-search="true" data-size="7" data-max-options="<?php _esc($skills_limit)?>" multiple>
                                                    <?php foreach ($skills as $skill){ ?>
                                                        <option value="<?php _esc($skill['id'])?>" <?php _esc($skill['selected'])?>><?php _esc($skill['title'])?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-xl-4 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Expected Salary") ?></h5>
                                                <div class="input-with-icon">
                                                    <input class="with-border margin-bottom-0" type="number" placeholder="<?php _e("Min") ?>"
                                                           name="salary_min" value="<?php _esc($salary_min)?>" >
                                                    <i class="currency"><?php _esc($user_currency_sign)?></i>
                                                </div>
                                                <small><?php _e("Salary per month.") ?></small>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-12">
                                            <div class="submit-field">
                                                <h5>&nbsp;</h5>
                                                <div class="input-with-icon">
                                                    <input class="with-border margin-bottom-0" type="number" placeholder="<?php _e("Max") ?>"
                                                           name="salary_max" value="<?php _esc($salary_max)?>">
                                                    <i class="currency"><?php _esc($user_currency_sign)?></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-12">
                                            <div class="submit-field">
                                                <h5><?php _e("Date of Birth") ?></h5>
                                                <input type="text" class="with-border margin-bottom-0" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true" data-date-language="<?php _esc($config['lang_code']) ?>" name="dob" value="<?php _esc($dob)?>" <?php if($lang_direction == "rtl"){ echo 'data-date-rtl="true"'; }?> >
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Website") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="la la-link"></i>
                                                <input type="url" name="website" class="with-border margin-bottom-0"
                                                       value="<?php _esc($website)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Facebook") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="fa fa-facebook"></i>
                                                <input type="url" name="facebook" class="with-border margin-bottom-0"
                                                       value="<?php _esc($facebook)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Twitter") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="fa fa-twitter"></i>
                                                <input type="url" name="twitter" class="with-border margin-bottom-0"
                                                       value="<?php _esc($twitter)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Instagram") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="fa fa-instagram"></i>
                                                <input type="url" name="instagram" class="with-border margin-bottom-0"
                                                       value="<?php _esc($instagram)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Linkedin") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="fa fa-linkedin"></i>
                                                <input type="url" name="linkedin" class="with-border margin-bottom-0"
                                                       value="<?php _esc($linkedin)?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-12">
                                        <div class="submit-field">
                                            <h5><?php _e("Youtube") ?></h5>
                                            <div class="input-with-icon-left">
                                                <i class="fa fa-youtube-play"></i>
                                                <input type="url" name="youtube" class="with-border margin-bottom-0"
                                                       value="<?php _esc($youtube)?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" name="submit"
                                        class="button ripple-effect"><?php _e("Save Changes") ?></button>
                            </form>
                            <?php } ?>
                        </div>
                    </div>

                </div>

            </div>
            <!-- Row / End -->

            <link href="<?php _esc(TEMPLATE_URL);?>/css/select2.min.css" rel="stylesheet"/>
            <script src="<?php _esc(TEMPLATE_URL);?>/js/select2.min.js"></script>
            <script>
                $(document).ready(function () {
                    $("#header-container").removeClass('transparent-header').addClass('dashboard-header not-sticky');

                });

                jQuery(function($) {
                    $(".range-slider-single").slider();
                    $("#category").on('change', function(){
                        var catid = $(this).val();
                        var selectid = $(this).data('subcat');
                        var data = { action: "getsubcatbyid", catid: catid, selectid : selectid };
                        $.ajax({
                            type: "POST",
                            url: ajaxurl,
                            data: data,
                            success: function(result){
                                $("#sub_category").html(result).selectpicker('refresh');
                            }
                        });
                    });
                    $("#category").trigger('change');
                });

                /* Get and Bind cities */
                $('#jobcity').select2({
                    ajax: {
                        url: ajaxurl + '?action=searchCityFromCountry',
                        dataType: 'json',
                        delay: 50,
                        data: function (params) {
                            return {
                                q: params.term, /* search term */
                                page: params.page
                            };
                        },
                        processResults: function (data, params) {
                            /*
                             // parse the results into the format expected by Select2
                             // since we are using custom formatting functions we do not need to
                             // alter the remote JSON data, except to indicate that infinite
                             // scrolling can be used
                             */
                            params.page = params.page || 1;

                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 10) < data.totalEntries
                                }
                            };
                        },
                        cache: true
                    },
                    escapeMarkup: function (markup) { return markup; }, /* let our custom formatter work */
                    minimumInputLength: 2,
                    templateResult: function (data) {
                        return data.text;
                    },
                    templateSelection: function (data, container) {
                        return data.text;
                    }
                });

            </script>
            <link href="<?php _esc(TEMPLATE_URL);?>/css/bootstrap-datepicker3.min.css" rel="stylesheet"/>
            <script src="<?php _esc(TEMPLATE_URL);?>/js/bootstrap-datepicker.min.js"></script>

            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>
