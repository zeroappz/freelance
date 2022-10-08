<?php
require_once('../includes.php');
?>

<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Site setting</h4>
            </div>
            <div class="card-block">
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">


                        <div id="quickad-tbs" class="wrap">
                            <div class="quickad-tbs-body">

                                <div class="row">
                                    <div id="quickad-sidebar" class="col-sm-4">
                                        <ul class="quickad-nav" role="tablist">
                                            <li class="quickad-nav-item active" data-target="#quickad_settings_general" data-toggle="tab">General</li>
                                            <li class="quickad-nav-item" data-target="#quickad_home_page" data-toggle="tab">Home Page Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_theme_setting" data-toggle="tab">Theme Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_logo_watermark" data-toggle="tab">Logo</li>
                                            <li class="quickad-nav-item" data-target="#quick_map" data-toggle="tab">Map</li>
                                            <li class="quickad-nav-item" data-target="#quickad_live_location" data-toggle="tab">Live Location Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_billing_details" data-toggle="tab">Billing Details</li>
                                            <li class="quickad-nav-item" data-target="#quickad_international" data-toggle="tab">International</li>
                                            <li class="quickad-nav-item" data-target="#quickad_email" data-toggle="tab">Email Setting</li>
                                            <li class="quickad-nav-item" data-target="#project_setting" data-toggle="tab">Project Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_frontend_submission" data-toggle="tab">Job Post Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_social_login_setting" data-toggle="tab">Social Login Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_recaptcha" data-toggle="tab">Google reCAPTCHA</li>
                                            <li class="quickad-nav-item" data-target="#quickad_blog" data-toggle="tab">Blog Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_testimonials" data-toggle="tab">Testimonials Setting</li>
                                            <li class="quickad-nav-item" data-target="#quickad_purchase_code" data-toggle="tab">Purchase Code</li>
                                        </ul>
                                    </div>

                                    <div id="quickad_settings_controls" class="col-sm-8">
                                        <div class="panel panel-default quickad-main">
                                            <div class="panel-body">
                                                <div class="tab-content">

                                                    <div class="tab-pane active" id="quickad_settings_general">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_settings_general">
                                                            <div class="form-group">
                                                                <label for="site_url">Site Url </label>
                                                                <p class="help-block">The site url is the url where you installed Script. Must be add slash / at the end of url like http://example.com/</p>
                                                                <div>
                                                                    <input name="site_url" class="form-control" type="Text" id="site_url" value="<?php echo get_option("site_url"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="site_title">Site Title </label>
                                                                <p class="help-block">The site title is what you would like your website to be known as, this will be used in emails and in the title of your webpages.</p>
                                                                <div>
                                                                    <input name="site_title" class="form-control" type="Text" id="site_title" value="<?php echo get_option("site_title"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Meta Keywords:</label>
                                                                <div>
                                                                    <input name="meta_keywords" type="text" class="form-control" value="<?php echo get_option("meta_keywords"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Meta Description:</label>
                                                                <div>
                                                                    <input name="meta_description" type="text" class="form-control" value="<?php echo get_option("meta_description"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Verify Email Message to Non-active Users</label>
                                                                <select name="non_active_msg" id="non_active_msg" class="form-control">
                                                                    <option value="1" <?php if(get_option("non_active_msg") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("non_active_msg") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Allow Non-active users to post/apply job</label>
                                                                <p class="help-block">When disallow, an error message will be shown to non-active users to verify their email address.</p>
                                                                <select name="non_active_allow" id="non_active_allow" class="form-control">
                                                                    <option value="1" <?php if(get_option("non_active_allow") == '1'){ echo "selected"; } ?>>Allow</option>
                                                                    <option value="0" <?php if(get_option("non_active_allow") == '0'){ echo "selected"; } ?>>Disallow</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Job Seekers Enable/disable</label>
                                                                <p class="help-block">Show/hide job seekers page.</p>
                                                                <select name="job_seeker_enable" id="job_seeker_enable" class="form-control">
                                                                    <option value="1" <?php if(get_option("job_seeker_enable") == '1'){ echo "selected"; } ?>>Enable</option>
                                                                    <option value="0" <?php if(get_option("job_seeker_enable") == '0'){ echo "selected"; } ?>>Disable</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Resume Enable/disable</label>
                                                                <p class="help-block">Disable, if you don't want users to upload resume.</p>
                                                                <select name="resume_enable" id="resume_enable" class="form-control">
                                                                    <option value="1" <?php if(get_option("resume_enable") == '1'){ echo "selected"; } ?>>Enable</option>
                                                                    <option value="0" <?php if(get_option("resume_enable") == '0'){ echo "selected"; } ?>>Disable</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="resume_files">Resume Files</label>
                                                                <p class="help-block">Enter extensions of files for resume separated by commas.</p>
                                                                <div>
                                                                    <input name="resume_files" class="form-control" type="Text" id="resume_files" value="<?php echo get_option("resume_files"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Companies Enable/disable</label>
                                                                <p class="help-block">Disable, if you want employers to post the job.</p>
                                                                <select name="company_enable" id="company_enable" class="form-control">
                                                                    <option value="1" <?php if(get_option("company_enable") == '1'){ echo "selected"; } ?>>Enable</option>
                                                                    <option value="0" <?php if(get_option("company_enable") == '0'){ echo "selected"; } ?>>Disable</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Company Registarion No Enable/disable</label>
                                                                <p class="help-block">Enable, if you want employers to add company registarion no.</p>
                                                                <select name="reg_no_enable" id="reg_no_enable" class="form-control">
                                                                    <option value="1" <?php if(get_option("reg_no_enable") == '1'){ echo "selected"; } ?>>Enable</option>
                                                                    <option value="0" <?php if(get_option("reg_no_enable") == '0'){ echo "selected"; } ?>>Disable</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="delete_expired">Delete Ad after expire</label>
                                                                <p class="help-block">Expired ads will be deleted in 3 days if you select yes. Otherwise its stored for seo perpouse.</p>
                                                                <select name="delete_expired" id="delete_expired" class="form-control">
                                                                    <option value="1" <?php if(get_option("delete_expired") == '1'){ echo "selected"; } ?>>Yes Delete</option>
                                                                    <option value="0" <?php if(get_option("delete_expired") == '0'){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="delete_expired">Cron job run time (In seconds)</label>
                                                                <p class="help-block">Please enter time in seconds for example: 60 = 1 minutes<br>
                                                                    3600 = 1 Hour.</p>
                                                                <input name="cron_exec_time" class="form-control" type="text" id="cron_exec_time" value="<?php echo get_option("cron_exec_time"); ?>">
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="inputPassword4">Allow User Language Selection</label>
                                                                <select name="userlangsel" class="form-control" id="userlangsel">
                                                                    <option value="1" <?php if(get_option("userlangsel") == 1){ echo "selected"; } ?>>Yes</option>
                                                                    <option value="0" <?php if(get_option("userlangsel") == 0){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Term & Condition Page Link:</label>
                                                                <div>
                                                                    <input name="termcondition_link" type="text" class="form-control" value="<?php echo get_option("termcondition_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Privacy Policy Page Link:</label>
                                                                <div>
                                                                    <input name="privacy_link" type="text" class="form-control" value="<?php echo get_option("privacy_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Cookie Policy Page Link:</label>
                                                                <div>
                                                                    <input name="cookie_link" type="text" class="form-control" value="<?php echo get_option("cookie_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cookie_consent">Show/hide Cookie Consent Box</label>
                                                                <select name="cookie_consent" class="form-control" id="userthemesel">
                                                                    <option value="1" <?php if(get_option("cookie_consent") == 1){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("cookie_consent") == 0){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group hidden">
                                                                <label for="inputPassword4">Allow User Theme Selection</label>
                                                                <select name="userthemesel" class="form-control" id="userthemesel">
                                                                    <option value="1" <?php if(get_option("userthemesel") == 1){ echo "selected"; } ?>>Yes</option>
                                                                    <option value="0" <?php if(get_option("userthemesel") == 0){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div> 

                                                            <div class="form-group hidden">
                                                                <label for="inputPassword4">Theme/Color switcher</label>
                                                                <select name="color_switcher" class="form-control" id="color_switcher">
                                                                    <option value="1" <?php if(get_option("color_switcher") == 1){ echo "selected"; } ?>>On</option>
                                                                    <option value="0" <?php if(get_option("color_switcher") == 0){ echo "selected"; } ?>>Off</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="transfer_filter">Transfer Filter</label>
                                                                <p class="help-block">Whether you should be shown a transfer screen between saving admin pages or not</p>
                                                                <select name="transfer_filter" class="form-control" id="transfer_filter">
                                                                    <option value="1" <?php if(get_option("transfer_filter") == 1){ echo "selected"; } ?>>Yes</option>
                                                                    <option value="0" <?php if(get_option("transfer_filter") == 0){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="temp_php">Enable PHP in Template (Must be on always)</label>
                                                                <select name="temp_php" id="temp_php" class="form-control">
                                                                    <option value="1" <?php if(get_option("temp_php") == 1){ echo "selected"; } ?>>Yes</option>
                                                                    <option value="0" <?php if(get_option("temp_php") == 0){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group <?php if(get_option("quickad_debug") == 0){ echo "hidden"; } ?> ">
                                                                <label for="quickad_debug">Enable Developement Mode</label>
                                                                <select name="quickad_debug" id="quickad_debug" class="form-control">
                                                                    <option value="1" <?php if(get_option("quickad_debug") == 1){ echo "selected"; } ?>>Yes</option>
                                                                    <option value="0" <?php if(get_option("quickad_debug") == 0){ echo "selected"; } ?>>No</option>
                                                                </select>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="general_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_home_page">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_home_page">
                                                            <div class="form-group">
                                                                <label for="home_page">Home Page</label>
                                                                <select name="home_page" id="home_page" class="form-control">
                                                                    <option value="home-freelance" <?php if($config['home_page'] == 'home-freelance'){ echo 'selected'; } ?>>Freelancer Home</option>
                                                                    <option value="home-job" <?php if($config['home_page'] == 'home-job'){ echo 'selected'; } ?>>Jobs Home</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="smtp_auth">Home Page Header Sticky/Unsticky</label>

                                                                <select name="header_sticky" id="header_sticky" class="form-control">
                                                                    <option value="sticky" <?php if(get_option("header_sticky") == 'sticky'){ echo "selected"; } ?>>Sticky</option>
                                                                    <option value="unsticky" <?php if(get_option("header_sticky") == 'unsticky'){ echo "selected"; } ?>>Unsticky</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="smtp_auth">Home Page Transparant Header</label>

                                                                <select name="transparent_header" id="transparent_header" class="form-control">
                                                                    <option value="transparent-header" <?php if(get_option("transparent_header") == 'transparent-header'){ echo "selected"; } ?>>Transparant</option>
                                                                    <option value="0" <?php if(get_option("transparent_header") == ''){ echo "selected"; } ?>>White</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="smtp_auth">Home Page Banner Overlay</label>

                                                                <select name="banner_overlay" id="banner_overlay" class="form-control">
                                                                    <option value="dark-overlay" <?php if(get_option("banner_overlay") == 'dark-overlay'){ echo "selected"; } ?>>Dark Overlay</option>
                                                                    <option value="" <?php if(get_option("banner_overlay") == ''){ echo "selected"; } ?>>White Overlay</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Search section on home page</label>
                                                                <select name="show_search_home" id="show_search_home" class="form-control">
                                                                    <option value="1" <?php if(get_option("show_search_home") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("show_search_home") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Category section on home page</label>
                                                                <select name="show_categories_home" id="show_categories_home" class="form-control">
                                                                    <option value="1" <?php if(get_option("show_categories_home") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("show_categories_home") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Featured Job section on home page</label>
                                                                <select name="show_featured_jobs_home" id="show_featured_jobs_home" class="form-control">
                                                                    <option value="1" <?php if(get_option("show_featured_jobs_home") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("show_featured_jobs_home") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Latest Job section on home page</label>
                                                                <select name="show_latest_jobs_home" id="show_latest_jobs_home" class="form-control">
                                                                    <option value="1" <?php if(get_option("show_latest_jobs_home") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("show_latest_jobs_home") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Show Membership Plan On Home Page:</label>
                                                                <div>
                                                                    <select name="show_membershipplan_home" id="show_membershipplan_home" class="form-control">
                                                                        <option <?php if(get_option("show_membershipplan_home") == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option("show_membershipplan_home") == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="site_title">Show/hide Company Partners Logo section on home page</label>
                                                                <select name="show_partner_logo_home" id="show_latest_jobs_home" class="form-control">
                                                                    <option value="1" <?php if(get_option("show_partner_logo_home") == '1'){ echo "selected"; } ?>>Show</option>
                                                                    <option value="0" <?php if(get_option("show_partner_logo_home") == '0'){ echo "selected"; } ?>>Hide</option>
                                                                </select>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="home_page_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_theme_setting">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_theme_setting">
                                                            <div class="form-group">
                                                                <label class="">Theme Color:</label>
                                                                <div>
                                                                    <input name="theme_color" type="color" class="form-control" value="<?php echo get_option("theme_color"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="smtp_auth">Phone/Email Login user show/hide</label>
                                                                <p class="help-block">Phone number / Email-Id, show only logged in user or show to Publicly.</p>
                                                                <select name="contact_validation" id="contact_validation" class="form-control">
                                                                    <option value="1" <?php if(get_option("contact_validation") == '1'){ echo "selected"; } ?>>Show Logged User</option>
                                                                    <option value="0" <?php if(get_option("contact_validation") == '0'){ echo "selected"; } ?>>Show Publicly</option>
                                                                </select>
                                                            </div>

                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label class="">Contact Address:</label>
                                                                <div>
                                                                    <input name="contact_address" type="text" class="form-control" value="<?php echo get_option("contact_address"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Contact Email:</label>
                                                                <div>
                                                                    <input name="contact_email" type="text" class="form-control" value="<?php echo get_option("contact_email"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Contact Phone:</label>
                                                                <div>
                                                                    <input name="contact_phone" type="text" class="form-control" value="<?php echo get_option("contact_phone"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="">Copyright Text:</label>
                                                                <div>
                                                                    <input name="copyright_text" type="text" class="form-control" value="<?php echo get_option("copyright_text"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Footer Text:</label>
                                                                <div>
                                                                    <textarea name="footer_text" class="form-control"><?php echo get_option("footer_text"); ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer Facebook Page Link:</label>
                                                                <div>
                                                                    <input name="facebook_link" type="text" class="form-control" value="<?php echo get_option("facebook_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer Twitter Page Link:</label>
                                                                <div>
                                                                    <input name="twitter_link" type="text" class="form-control" value="<?php echo get_option("twitter_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer Instagram Page Link:</label>
                                                                <div>
                                                                    <input name="instagram_link" type="text" class="form-control" value="<?php echo get_option("instagram_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer LinkedIn Page Link:</label>
                                                                <div>
                                                                    <input name="linkedin_link" type="text" class="form-control" value="<?php echo get_option("linkedin_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer Pinterest Page Link:</label>
                                                                <div>
                                                                    <input name="pinterest_link" type="text" class="form-control" value="<?php echo get_option("pinterest_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Footer Youtube Page/Video Link:</label>
                                                                <div>
                                                                    <input name="youtube_link" type="text" class="form-control" value="<?php echo get_option("youtube_link"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group hidden">
                                                                <label>External Javascript or Css In header:</label>
                                                                <p class="help-block">You can add Any javascript code and style css. Like Google Analytics code. This code will paste on head part.</p>
                                                                <div>
                                                                    <textarea name="external_code" type="text" class="form-control" rows="5"><?php echo get_option("external_code"); ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <button name="theme_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_logo_watermark">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_logo_watermark" enctype="multipart/form-data">
                                                            <!-- Favicon upload-->
                                                            <div class="form-group">

                                                                <label class="control-label">Favicon Icon<code>*</code></label>
                                                                <div class="screenshot"><img class="redux-option-image" id="favicon_uploader" src="<?php echo $config['site_url'];?>storage/logo/<?php echo $config['site_favicon']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                                                <input class="form-control input-sm" type="file" name="favicon" onchange="readURL(this,'favicon_uploader')">
                                                                <span class="help-block">Ideal Size 16x16 PX</span>
                                                            </div>

                                                            <!-- Site Logo upload-->
                                                            <div class="form-group">
                                                                <label class="control-label">Logo<code>*</code></label>
                                                                <div class="screenshot"><img class="redux-option-image" id="image_logo_uploader" src="<?php echo $config['site_url'];?>storage/logo/<?php echo $config['site_logo']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                                                <input class="form-control input-sm" type="file" name="file" onchange="readURL(this,'image_logo_uploader')">
                                                                <span class="help-block">Ideal Size 170x60 PX</span>
                                                            </div>
                                                            <!-- Site Logo upload-->
                                                            <div class="form-group">
                                                                <label class="control-label">Footer Logo<code>*</code></label>
                                                                <div class="screenshot"><img class="redux-option-image" id="image_flogo_uploader" src="<?php echo $config['site_url'];?>storage/logo/<?php echo $config['site_logo_footer']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                                                <input class="form-control input-sm" type="file" name="footer_logo" onchange="readURL(this,'image_flogo_uploader')">
                                                                <span class="help-block">Display in the footer</span>
                                                            </div>

                                                            <!-- Home Banner upload-->
                                                            <div class="form-group">
                                                                <label class="control-label">Home Banner<code>*</code></label>
                                                                <div class="screenshot"><img class="redux-option-image" id="home_banner" src="<?php echo $config['site_url'];?>storage/banner/<?php echo $config['home_banner']?>" alt="" target="_blank" rel="external" width="400px"></div>
                                                                <input class="form-control input-sm" type="file" name="banner" onchange="readURL(this,'home_banner')">
                                                            </div>
                                                            <!-- Home Banner upload-->

                                                            <!-- Admin Logo upload-->
                                                            <div class="form-group">
                                                                <label class="control-label">Admin Logo</label>
                                                                <div class="screenshot"><img class="redux-option-image" id="adminlogo" src="<?php echo $config['site_url'];?>storage/logo/<?php echo $config['site_admin_logo']?>" alt="" target="_blank" rel="external"  style="border: 2px solid #eee;background-color: #000;max-width: 100%"></div>
                                                                <input class="form-control input-sm" type="file" name="adminlogo" onchange="readURL(this,'adminlogo')">
                                                                <span class="help-block">Ideal Size 235x62 PX</span>
                                                            </div>

                                                            <!-- Admin Logo upload-->
                                                            <div class="panel-footer">
                                                                <button name="logo_watermark" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="quick_map">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quick_map">
                                                            <div class="form-group">
                                                                <label>Address Field :</label>
                                                                <div>
                                                                    <select name="post_address_mode" class="form-control">
                                                                        <option <?php if(get_option("post_address_mode") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("post_address_mode") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="map_type">Map Type (Google/OpenStreet)</label>
                                                                <select name="map_type" id="map_type" class="form-control">
                                                                    <option value="google" <?php if(get_option('map_type') == 'google'){ echo "selected"; } ?>>Google Map</option>
                                                                    <option value="openstreet" <?php if(get_option('map_type') == 'openstreet'){ echo "selected"; } ?>>OpenStreet Map</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gmap_api_key">OpenStreet Access Token</label>
                                                                <p class="help-block"><a href="https://account.mapbox.com/access-tokens/" target="_blank">Get MapBox Access Token For OpenStreet Map</a></p>
                                                                <input name="openstreet_access_token" class="form-control" type="Text" id="openstreet_access_token" value="<?php echo get_option('openstreet_access_token'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="gmap_api_key">Google Map API Key</label>
                                                                <p class="help-block"><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Get API Key</a></p>
                                                                <input name="gmap_api_key" class="form-control" type="Text" id="gmap_api_key" value="<?php echo get_option('gmap_api_key'); ?>">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Google Map Color:</label>
                                                                <div>
                                                                    <input name="map_color" type="color" class="form-control" value="<?php echo get_option('map_color'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Home Map Latitude:</label>
                                                                <div>
                                                                    <input name="home_map_latitude" type="text" class="form-control" value="<?php echo get_option('home_map_latitude'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Home Map Longitude:</label>
                                                                <div>
                                                                    <input name="home_map_longitude" type="text" class="form-control" value="<?php echo get_option('home_map_longitude'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Contact Map Latitude:</label>
                                                                <div>
                                                                    <input name="contact_latitude" type="text" class="form-control" value="<?php echo get_option('contact_latitude'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Contact Map Longitude:</label>
                                                                <div>
                                                                    <input name="contact_longitude" type="text" class="form-control" value="<?php echo get_option('contact_longitude'); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <button name="quick_map" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="quickad_live_location">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_live_location">
                                                            <div class="form-group">
                                                                <label for="location_track_icon">Live Location Track Icon</label>
                                                                <p class="help-block"><i class="fa fa-crosshairs"></i>This icon Show on Home search where field and post ads location field.</p>
                                                                <div>
                                                                    <select name="location_track_icon" class="form-control">
                                                                        <option <?php if(get_option('location_track_icon') == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option('location_track_icon') == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="auto_detect_location">Auto Detect Location </label>
                                                                <div>
                                                                    <select name="auto_detect_location" class="form-control">
                                                                        <option <?php if(get_option('live_location_api') == 'yes'){ echo "selected"; } ?> value="yes">On</option>
                                                                        <option <?php if(get_option('live_location_api') == 'no'){ echo "selected"; } ?> value="no">Off</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="live_location_api">Live Location Api </label>
                                                                <div>
                                                                    <select name="live_location_api" class="form-control">
                                                                        <option <?php if(get_option('live_location_api') == 'geo_ip_db'){ echo "selected"; } ?> value="geo_ip_db">Geo IP Database</option>
                                                                        <option <?php if(get_option('live_location_api') == 'ip_api'){ echo "selected"; } ?> value="ip_api">IP API</option>
                                                                        <option <?php if(get_option('live_location_api') == 'gps'){ echo "selected"; } ?> value="gps">Google GPS</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="live_location_track" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="quickad_billing_details">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_billing_details">
                                                            <p>These details will be used for the invoice.</p>
                                                            <div class="form-group">
                                                                <label class="">Invoice Number Prefix</label>
                                                                <div>
                                                                    <input name="invoice_nr_prefix" type="text" class="form-control" value="<?php echo get_option("invoice_nr_prefix"); ?>" placeholder="Ex: INV-">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Name</label>
                                                                <div>
                                                                    <input name="invoice_admin_name" type="text" class="form-control" value="<?php echo get_option("invoice_admin_name"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Email</label>
                                                                        <div>
                                                                            <input name="invoice_admin_email" type="text" class="form-control" value="<?php echo get_option("invoice_admin_email"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Phone</label>
                                                                        <div>
                                                                            <input name="invoice_admin_phone" type="text" class="form-control" value="<?php echo get_option("invoice_admin_phone"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Address</label>
                                                                <div>
                                                                    <input name="invoice_admin_address" type="text" class="form-control" value="<?php echo get_option("invoice_admin_address"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">City</label>
                                                                        <div>
                                                                            <input name="invoice_admin_city" type="text" class="form-control" value="<?php echo get_option("invoice_admin_city"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="form-group">
                                                                        <label class="">State/Province</label>
                                                                        <div>
                                                                            <input name="invoice_admin_state" type="text" class="form-control" value="<?php echo get_option("invoice_admin_state"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <div class="form-group">
                                                                        <label class="">ZIP Code</label>
                                                                        <div>
                                                                            <input name="invoice_admin_zipcode" type="text" class="form-control" value="<?php echo get_option("invoice_admin_zipcode"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="">Country</label>
                                                                <div>
                                                                    <select class="form-control" name="invoice_admin_country">
                                                                        <?php
                                                                        $country = get_country_list();
                                                                        foreach ($country as $value){
                                                                            echo '<option value="'.$value['code'].'" '. (($value['code'] == get_option('invoice_admin_country'))? 'selected':'') .'>'.$value['asciiname'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Tax Type</label>
                                                                        <div>
                                                                            <input name="invoice_admin_tax_type" type="text" class="form-control" value="<?php echo get_option("invoice_admin_tax_type"); ?>" placeholder="Ex: VAT">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Tax ID</label>
                                                                        <div>
                                                                            <input name="invoice_admin_tax_id" type="text" class="form-control" value="<?php echo get_option("invoice_admin_tax_id"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Custom Field Name</label>
                                                                        <div>
                                                                            <input name="invoice_admin_custom_name_1" type="text" class="form-control" value="<?php echo get_option("invoice_admin_custom_name_1"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Custom Field Value</label>
                                                                        <div>
                                                                            <input name="invoice_admin_custom_value_1" type="text" class="form-control" value="<?php echo get_option("invoice_admin_custom_value_1"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Custom Field Name</label>
                                                                        <div>
                                                                            <input name="invoice_admin_custom_name_2" type="text" class="form-control" value="<?php echo get_option("invoice_admin_custom_name_2"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label class="">Custom Field Value</label>
                                                                        <div>
                                                                            <input name="invoice_admin_custom_value_2" type="text" class="form-control" value="<?php echo get_option("invoice_admin_custom_value_2"); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <button name="billing_details" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="quickad_international">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_international">

                                                            <div class="form-group">
                                                                <label class="">Site Country Type:</label>
                                                                <div>
                                                                    <select name="country_type" class="form-control">
                                                                        <option <?php if(get_option("country_type") == 'single'){ echo "selected"; } ?> value="single">Single Country</option>
                                                                        <option <?php if(get_option("country_type") == 'multi'){ echo "selected"; } ?> value="multi">Multi Countries</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="specific_country">Default Country</label>
                                                                <p class="help-block">When user first time visit your website. Then the site run for that choosen default country.</p>
                                                                <div>
                                                                    <select  class="js-select2 form-control" name="specific_country" id="specific_country" style="width: 100%;">
                                                                        <?php

                                                                        $country = get_country_list(get_option("specific_country"));
                                                                        foreach ($country as $value){
                                                                            echo '<option value="'.$value['code'].'" '.$value['selected'].'>'.$value['asciiname'].'</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="timezone">Timezone</label>
                                                                <p class="help-block">Set your website timezone.</p>
                                                                <div>
                                                                    <select name="timezone" id="timezone" class="js-select2 form-control"  style="width: 100%;">
                                                                        <?php
                                                                        $timezone = get_timezone_list(get_option("timezone"));

                                                                        foreach ($timezone as $value) {
                                                                            $id = $value['id'];
                                                                            $country_code = $value['country_code'];
                                                                            $time_zone_id = $value['time_zone_id'];
                                                                            $selected = $value['selected'];
                                                                            echo '<option value="'.$time_zone_id.'" '.$selected.' data-tokens="'.$time_zone_id.'">'.$time_zone_id.'</option>';
                                                                        }

                                                                        ?>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="currency">Currency</label>
                                                                <p class="help-block">This is default currecny which used for payment method.</p>
                                                                <div>
                                                                    <select name="currency" id="currency"  class="js-select2 form-control" style="width: 100%;">
                                                                        <?php
                                                                        $currency = get_currency_list(get_option("currency_code"));

                                                                        foreach ($currency as $value)
                                                                        {
                                                                            $id          = $value['id'];
                                                                            $code        = $value['code'];
                                                                            $name       = $value['name'];
                                                                            $html_code   = $value['html_entity'];
                                                                            $selected =  $value['selected'];

                                                                            echo '<option value="'.$id.'" '.$selected.' data-tokens="'.$name.'">'.$name.' ('.$html_code.')</option>';
                                                                        }

                                                                        ?>

                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="inputEmail3">Language</label>
                                                                <select name="lang" id="lang" class="js-select2 form-control" style="width: 100%;">
                                                                    <?php
                                                                    $langs = array();

                                                                    if ($handle = opendir(ROOTPATH.'/includes/lang/'))
                                                                    {
                                                                        while (false !== ($file = readdir($handle)))
                                                                        {
                                                                            if ($file != "." && $file != "..")
                                                                            {
                                                                                $lang2 = str_replace('.php','',$file);
                                                                                $lang2 = str_replace('lang_','',$lang2);

                                                                                $langs[] = $lang2;
                                                                            }
                                                                        }
                                                                        closedir($handle);
                                                                    }

                                                                    sort($langs);

                                                                    foreach ($langs as $key => $lang2)
                                                                    {
                                                                        if(get_option("lang") == $lang2)
                                                                        {
                                                                            echo '<option value="'.$lang2.'" selected>'.ucwords($lang2).'</option>';
                                                                        }
                                                                        else
                                                                        {
                                                                            echo '<option value="'.$lang2.'">'.ucwords($lang2).'</option>';
                                                                        }
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="international" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_email">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_email">

                                                            <div class="form-group">
                                                                <label for="admin_email">Admin Email</label>
                                                                <p class="help-block">This is the email address that the contact and report emails will be sent to, aswell as being the from address in signup and notification emails.</p>
                                                                <div>
                                                                    <input name="admin_email" class="form-control" type="Text" id="admin_email" value="<?php echo get_option("admin_email");?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="email_template">E-mail Content Type </label>
                                                                <p class="help-block">Text-plain or HTML content chooser.</p>
                                                                <div>
                                                                    <select name="email_template" id="email_template" class="form-control">
                                                                        <option <?php if(get_option("email_template") == '0'){ echo "selected"; } ?> value="0">HTML</option>
                                                                        <option <?php if(get_option("email_template") == '1'){ echo "selected"; } ?> value="1">Text</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="email_type">E-Mail Sending Method</label>
                                                                <p class="help-block">E-Mail connection and sending method. SMTP is a commonly used method. But if you have trouble with SMTP connections, you can choose different method.<br><br>

                                                                    <strong>IMPORTANT:</strong> If you use foreign SMTP accounts on your server you may get SMTP connection errors, if your hosting service provider block foreign e-mail account connections.</p>
                                                                <div>
                                                                    <select name="email_type" id="email_type" class="form-control">
                                                                        <option value="smtp" <?php if(get_option("email_type") == 'smtp'){ echo "selected"; } ?>>SMTP</option>
                                                                        <option value="mail" <?php if(get_option("email_type") == 'mail'){ echo "selected"; } ?>>PHPMail</option>
                                                                        <option value="aws" <?php if(get_option("email_type") == 'aws'){ echo "selected"; } ?>>Amazon SES</option>
                                                                        <option value="mandrill" <?php if(get_option("email_type") == 'mandrill'){ echo "selected"; } ?>>Mandrill</option>
                                                                        <option value="sendgrid" <?php if(get_option("email_type") == 'sendgrid'){ echo "selected"; } ?>>SendGrid</option>
                                                                    </select>
                                                                </div>
                                                            </div>



                                                            <div style="margin-top: 30px;">
                                                                <div class="mailMethod-smtp mailMethods" <?php if($config['email_type'] != 'smtp'){ echo 'style="display: none;"'; } ?>>
                                                                    <h4 class="text-warning">SMTP</h4><hr>
                                                                    <div class="form-group">
                                                                        <label for="smtp_host">SMTP Host</label>
                                                                        <p class="help-block">This is the host address for your smtp server, this is only needed if you are using SMTP as the Email Send Type.</p>
                                                                        <div>
                                                                            <input name="smtp_host" type="Text" class="form-control" id="smtp_host" value="<?php echo get_option("smtp_host");?>">
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="smtp_host">SMTP Port</label>
                                                                        <input name="smtp_port" type="Text" class="form-control" id="smtp_port" value="<?php echo get_option("smtp_port");?>">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="smtp_username">SMTP Username</label>
                                                                        <p class="help-block">This is the username for your smtp server, this is only needed if you are using SMTP as the Email Send Type.</p>
                                                                        <input name="smtp_username" class="form-control" type="Text" id="smtp_username" value="<?php echo get_option("smtp_username");?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="smtp_password">SMTP Password</label>
                                                                        <p class="help-block">This is the password for your smtp server, this is only needed if you are using SMTP as the Email Send Type.</p>
                                                                        <input name="smtp_password" type="password" class="form-control" id="smtp_password" value="<?php echo get_option("smtp_password");?>">
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="smtp_secure">SMTP Encryption</label>
                                                                        <p class="help-block">If your e-mail service provider supported secure connections, you can choose security method on list. </p>
                                                                        <select name="smtp_secure" id="smtp_secure" class="form-control">
                                                                            <option value="0" <?php if(get_option("smtp_secure") == '0'){ echo "selected"; } ?>>Off</option>
                                                                            <option value="1" <?php if(get_option("smtp_secure") == '1'){ echo "selected"; } ?>>SSL</option>
                                                                            <option value="2" <?php if(get_option("smtp_secure") == '2'){ echo "selected"; } ?>>TLS</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="smtp_auth">SMTP Auth</label>
                                                                        <p class="help-block">SMTP Authentication, often abbreviated SMTP AUTH, is an extension of the Simple Mail Transfer Protocol whereby an SMTP client may log in using an authentication mechanism chosen among those supported by the SMTP server.</p>
                                                                        <select name="smtp_auth" id="smtp_auth" class="form-control">
                                                                            <option value="true" <?php if(get_option("smtp_auth") == 'true'){ echo "selected"; } ?>>On</option>
                                                                            <option value="false" <?php if(get_option("smtp_auth") == 'false'){ echo "selected"; } ?>>Off</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="mailMethod-aws mailMethods" <?php if($config['email_type'] != 'aws'){ echo 'style="display: none;"'; } ?>>
                                                                    <h4 class="text-warning">Amazon SES</h4><hr>
                                                                    <div class="form-group">
                                                                        <label for="aws_host">AWS Region</label>
                                                                        <input name="aws_host" type="Text" class="form-control" id="aws_host" value="<?php echo get_option("aws_host");?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="aws_access_key">AWS SMTP Username</label>
                                                                        <p class="help-block">
                                                                            Note: Your SMTP user name and password are not the same as your AWS access key ID and secret access key. Do not attempt to use your AWS credentials to authenticate yourself against the SMTP endpoint. For more information about credential types, <a href="https://docs.aws.amazon.com/console/ses/using-credentials" target="_blank">click here.</a></p>
                                                                        <input name="aws_access_key" class="form-control" type="Text" id="aws_access_key" value="<?php echo get_option("aws_access_key");?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="aws_secret_key">AWS SMTP Password</label>
                                                                        <p class="help-block"></p>
                                                                        <input name="aws_secret_key" type="password" class="form-control" id="aws_secret_key" value="<?php echo get_option("aws_secret_key");?>">
                                                                    </div>

                                                                </div>
                                                                <div class="mailMethod-mandrill mailMethods" <?php if($config['email_type'] != 'mandrill'){ echo 'style="display: none;"'; } ?>>
                                                                    <h4 class="text-warning">Mandrill</h4><hr>
                                                                    <div class="form-group">
                                                                        <label for="mandrill_user">Mandrill Username</label>
                                                                        <input name="mandrill_user" class="form-control" type="Text" id="mandrill_user" value="<?php echo get_option("mandrill_user");?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="mandrill_key">Mandrill API Key</label>
                                                                        <input name="mandrill_key" type="Text" class="form-control" id="mandrill_key" value="<?php echo get_option("mandrill_key");?>">
                                                                    </div>
                                                                </div>
                                                                <div class="mailMethod-sendgrid mailMethods" <?php if($config['email_type'] != 'sendgrid'){ echo 'style="display: none;"'; } ?>>
                                                                    <h4 class="text-warning">SendGrid</h4><hr>
                                                                    <div class="form-group">
                                                                        <label for="sendgrid_user">SendGrid Username</label>
                                                                        <input name="sendgrid_user" class="form-control" type="Text" id="sendgrid_user" value="<?php echo get_option("sendgrid_user");?>">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="sendgrid_pass">SendGrid Password</label>
                                                                        <input name="sendgrid_pass" type="password" class="form-control" id="sendgrid_pass" value="<?php echo get_option("sendgrid_pass");?>">
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="email_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_frontend_submission">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_frontend_submission">
                                                            <div class="form-group">
                                                                <h4>Modify Post form fields</h4>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Post Without Login :</label>
                                                                <div>
                                                                    <select name="post_without_login" class="form-control">
                                                                        <option <?php if(get_option("post_without_login") == '1'){ echo "selected"; } ?> value="1">
                                                                            Enable (User can post without login.)
                                                                        </option>
                                                                        <option <?php if(get_option("post_without_login") == '0'){ echo "selected"; } ?> value="0">
                                                                            Disable (User required login to post ad.)
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ads Auto Approve :</label>
                                                                <div>
                                                                    <select name="post_auto_approve" class="form-control">
                                                                        <option <?php if(get_option("post_auto_approve") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("post_auto_approve") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label>Premium Listing Option :</label>
                                                                <div>
                                                                    <select name="post_premium_listing" class="form-control">
                                                                        <option <?php if(get_option("post_premium_listing") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("post_premium_listing") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Description Editor :</label>
                                                                <div>
                                                                    <select name="post_desc_editor" class="form-control">
                                                                        <option <?php if(get_option("post_desc_editor") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("post_desc_editor") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Job Image Field :</label>
                                                                <p class="help-block">When disabled, company image will be used.</p>
                                                                <div>
                                                                    <select name="job_image_field" class="form-control">
                                                                        <option <?php if(get_option("job_image_field") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("job_image_field") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Tags Field :</label>
                                                                <div>
                                                                    <select name="post_tags_mode" class="form-control">
                                                                        <option <?php if(get_option("post_tags_mode") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("post_tags_mode") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="panel-footer">
                                                                <button name="frontend_submission" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="project_setting">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#project_setting">
                                                            <div class="form-group">
                                                                <h4>Project Settings</h4>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Minimum Deposit Amount: <?php _esc($config['currency_code']); ?></label>
                                                                <p class="help-block">This is the amount for user can't deposit money below this. Amount should be higher or equal to this when deposit.</p>

                                                                <div>
                                                                    <input name="payment_minimum_deposit" type="number" class="form-control" value="<?php echo get_option("payment_minimum_deposit"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Minimum Withdraw Amount: <?php _esc($config['currency_code']); ?></label>
                                                                <p class="help-block">This is the amount for user can't able to create withdrawal request if user wallet is lower than this amount.</p>

                                                                <div>
                                                                    <input name="payment_minimum_withdraw" type="number" class="form-control" value="<?php echo get_option("payment_minimum_withdraw"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="project_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>
                                                    <div class="tab-pane" id="quickad_social_login_setting">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_social_login_setting">
                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Facebook app id:</label>
                                                                <div>
                                                                    <input name="facebook_app_id" type="text" class="form-control" value="<?php echo get_option("facebook_app_id"); ?>">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->

                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Facebook app secret:</label>
                                                                <div>
                                                                    <input name="facebook_app_secret" type="text" class="form-control" value="<?php echo get_option("facebook_app_secret"); ?>">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->

                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Facebook callback url:</label>
                                                                <p class="help-block">Use this redirect url in facebook app.</p>
                                                                <div>
                                                                    <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/facebook/index.php">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->

                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Google+ app id:</label>
                                                                <div>
                                                                    <input name="google_app_id" type="text" class="form-control" value="<?php echo get_option("google_app_id"); ?>">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->

                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Google+ app secret:</label>
                                                                <div>
                                                                    <input name="google_app_secret" type="text" class="form-control" value="<?php echo get_option("google_app_secret"); ?>">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->
                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <label>Google+ callback url:</label>
                                                                <div>
                                                                    <input type="text" class="form-control" disabled value="<?php echo $config['site_url']; ?>includes/social_login/google/index.php">
                                                                </div>
                                                            </div>
                                                            <!--Default Horizontal Form-->

                                                            <div class="panel-footer">
                                                                <button name="social_login_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_recaptcha">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_recaptcha">
                                                            <div class="form-group">
                                                                <h4>Get reCAPTCHA API keys</h4>
                                                                <p class="help-block">For adding reCAPTCHA to your site, you need to register your site and get reCAPTCHA API keys.<br>Register your site at Google from this link – <a href="https://www.google.com/recaptcha/admin" target="_blank">Click Here</a>.</p>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>reCAPTCHA Enable/Disable:</label>
                                                                <div>
                                                                    <select name="recaptcha_mode" id="recaptcha_mode" class="form-control">
                                                                        <option <?php if(get_option("recaptcha_mode") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("recaptcha_mode") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>reCAPTCHA Public Key:</label>
                                                                <div>
                                                                    <input name="recaptcha_public_key" type="text" class="form-control" value="<?php echo get_option("recaptcha_public_key"); ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>reCAPTCHA Private Key:</label>
                                                                <div>
                                                                    <input name="recaptcha_private_key" type="text" class="form-control" value="<?php echo get_option("recaptcha_private_key"); ?>">
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="recaptcha_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_blog">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_blog">
                                                            <div class="form-group">
                                                                <label>Blog Enable/Disable:</label>
                                                                <div>
                                                                    <select name="blog_enable" id="blog_enable" class="form-control">
                                                                        <option <?php if(get_option("blog_enable") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("blog_enable") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Blog Banner Image:</label>
                                                                <div>
                                                                    <select name="blog_banner" id="blog_banner" class="form-control">
                                                                        <option <?php if(get_option("blog_banner") == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option("blog_banner") == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Show Blog On Home Page:</label>
                                                                <div>
                                                                    <select name="show_blog_home" id="show_blog_home" class="form-control">
                                                                        <option <?php if(get_option("show_blog_home") == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option("show_blog_home") == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Comment Enable/Disable:</label>
                                                                <div>
                                                                    <select name="blog_comment_enable" id="blog_comment_enable" class="form-control">
                                                                        <option <?php if(get_option("blog_comment_enable") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("blog_comment_enable") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Comment Approval:</label>
                                                                <div>
                                                                    <select name="blog_comment_approval" id="blog_comment_approval" class="form-control">
                                                                        <option <?php if(get_option("blog_comment_approval") == '1'){ echo "selected"; } ?> value="1">Disable Auto Approve Comments</option>
                                                                        <option <?php if(get_option("blog_comment_approval") == '2'){ echo "selected"; } ?> value="2">Auto Approve Login Users Comments</option>
                                                                        <option <?php if(get_option("blog_comment_approval") == '3'){ echo "selected"; } ?> value="3">Auto Approve All Comments</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Who Can Comment:</label>
                                                                <p class="help-block">Non-login users have to enter their name and email address.</p>
                                                                <div>
                                                                    <select name="blog_comment_user" id="blog_comment_user" class="form-control">
                                                                        <option <?php if(get_option("blog_comment_user") == '1'){ echo "selected"; } ?> value="1">Everyone</option>
                                                                        <option <?php if(get_option("blog_comment_user") == '0'){ echo "selected"; } ?> value="0">Only Login Users</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="blog_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_testimonials">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_testimonials">
                                                            <div class="form-group">
                                                                <label>Testimonials Enable/Disable:</label>
                                                                <div>
                                                                    <select name="testimonials_enable" id="testimonials_enable" class="form-control">
                                                                        <option <?php if(get_option("testimonials_enable") == '1'){ echo "selected"; } ?> value="1">Enable</option>
                                                                        <option <?php if(get_option("testimonials_enable") == '0'){ echo "selected"; } ?> value="0">Disable</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Show On Blog Page:</label>
                                                                <div>
                                                                    <select name="show_testimonials_blog" id="show_testimonials_blog" class="form-control">
                                                                        <option <?php if(get_option("show_testimonials_blog") == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option("show_testimonials_blog") == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Show On Home Page:</label>
                                                                <div>
                                                                    <select name="show_testimonials_home" id="show_testimonials_home" class="form-control">
                                                                        <option <?php if(get_option("show_testimonials_home") == '1'){ echo "selected"; } ?> value="1">Show</option>
                                                                        <option <?php if(get_option("show_testimonials_home") == '0'){ echo "selected"; } ?> value="0">Hide</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="panel-footer">
                                                                <button name="testimonials_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                    <div class="tab-pane" id="quickad_purchase_code">
                                                        <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=SaveSettings" id="#quickad_purchase_code">
                                                            <!--Default Horizontal Form-->
                                                            <div class="form-group">
                                                                <h4>Instructions</h4>
                                                                <p class="help-block">Verify the purchase code you will have access to free updates of Quicklancer. Updates may contain functionality improvements and important security fixes. <br>For more information on where to find your purchase code see this <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-can-I-find-my-Purchase-Code-" target="_blank">page</a>.</p>
                                                            </div>
                                                            <?php
                                                            if(isset($config['purchase_key']) && $config['purchase_key'] != ""){
                                                                ?>
                                                                <div class="alert alert-success">
                                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                                                    <strong>Success!</strong>   Purchase code verified.
                                                                </div>

                                                            <?php
                                                            }
                                                            ?>
                                                            <div class="form-group">
                                                                <label>Quickad Classified Purchase Code:</label>
                                                                <div>
                                                                    <input name="purchase_key" type="text" class="form-control" value="">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Buyer Email:</label>
                                                                <div>
                                                                    <input name="buyer_email" type="text" class="form-control" value="">
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <button name="valid_purchase_setting" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>

                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- /.row -->
            </div>
            <!-- .card-block -->
        </div>
        <!-- .card -->
        <!-- End Partial Table -->

    </div>
    <!-- .container-fluid -->
    <!-- End Page Content -->

</main>


<?php include("../footer.php"); ?>
<script>
    var url = window.location.href;
    var activeTab = url.substring(url.indexOf("#") + 1);
    if(url.indexOf("#") > -1){
        if(activeTab.length > 0){
            $(".quickad-nav-item").removeClass("active");
            $(".tab-pane").removeClass("active in");
            $("li[data-target = #"+activeTab+"]").addClass("active");
            $("#" + activeTab).addClass("active in");
            $('a[href="#'+ activeTab +'"]').tab('show')
        }
    }
</script>
<script>
    $(".save-changes").on('click',function(){
        $(".save-changes").addClass("bookme-progress");
    });
    // wait for the DOM to be loaded
    $(document).ready(function() {
        // bind 'myForm' and provide a simple callback function
        $('form').ajaxForm(function(data) {
            if (data == 0) {
                alertify.error("Unknown Error generated.");
            } else {
                data = JSON.parse(data);
                if (data.status == "success") {
                    alertify.success(data.message);
                }
                else {
                    alertify.error(data.message);
                }
            }
            $(".save-changes").removeClass('bookme-progress');
        });

        /* Mail Method Changer */
        $("#email_type").on('change',function(){
            $(".mailMethods").hide();
            $(".mailMethod-"+$(this).val()).fadeIn('fast');
        });
    });
</script>
<!-- Page JS Code -->
<script>
    $(function()
    {
        // Init page helpers (BS Datepicker + BS Colorpicker + Select2 + Masked Input + Tags Inputs plugins)
        App.initHelpers('select2');
    });
</script>
</body></html>