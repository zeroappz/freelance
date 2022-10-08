<?php
overall_header($item_title, $meta_desc, $meta_image, true)
?>
<div id="titlebar">
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-12">
                <h2><?php _esc($item_title);?>
                    <?php
                    if($item_featured=="1") {
                        echo '<div class="dashboard-status-button blue">'.__("Featured").'</div>';
                    }
                    if($item_urgent=="1") {
                        echo '<div class="dashboard-status-button yellow">'.__("Urgent").'</div>';
                    }
                    if($item_highlight=="1") {
                        echo '<div class="dashboard-status-button red">'.__("Highlight").'</div>';
                    }
                    ?>
                </h2>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="listing_job">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><a href="<?php _esc($item_catlink)?>"><?php _esc($item_category)?></a></li>
                        <?php if($item_sub_category){
                            echo '<li><a href="'._esc($item_subcatlink,false).'">'._esc($item_sub_category,false).'</a></li>';
                        }?>

                    </ul>
                </nav>
            </div>
            <div class="col-md-3 col-sm-12">
                <div class="right-side">
                    <?php if($is_login){
                        if($usertype == 'user'){
                            if($item_application_url != ''){
                                echo '<a href="'._esc($item_application_url,false).'" class="button ripple-effect" target="_blank" rel="nofollow">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                            }else{
                                if($already_applied == ''){
                                    echo '<button class="button green disabled" disabled><i class="icon-feather-check"></i> '.__("Already Applied").'</button>';
                                }else{
                                    echo '<a href="#apply-now-dialog" class="button ripple-effect popup-with-zoom-anim">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                                }
                            }
                        }
                    }else{
                        echo '<a href="#sign-in-dialog" class="button ripple-effect popup-with-zoom-anim">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                    }?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <?php if($item_image){ ?>
            <div class="job-header">
                <div class="header-image"><img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item_image)?>" alt="<?php _esc($item_title)?>"></div>
                <div class="header-details">
                    <h3><?php _esc($item_title)?></h3>
                </div>
                <?php
                if($item_featured=="1") {
                    echo '<div class="dashboard-status-button blue margin-left-10">'.__("Featured").'</div>';
                }
                if($item_urgent=="1") {
                    echo '<div class="dashboard-status-button yellow margin-left-10">'.__("Urgent").'</div>';
                }
                if($item_highlight=="1") {
                    echo '<div class="dashboard-status-button red margin-left-10">'.__("Highlight").'</div>';
                }
                ?>
            </div>
            <?php } ?>
            <div class="single-page-section">
                <h3><?php _e("Job Overview") ?></h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="la la-map-marker"></i>
                            <span><?php _e("Location") ?></span>
                            <h5><?php _esc($item_city)?>, <?php _esc($item_state)?></h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="la la-suitcase"></i>
                            <span><?php _e("Job Type") ?></span>
                            <h5><?php _esc($item_product_type)?></h5>
                        </div>
                    </div>
                    <?php if($item_salary_min != "0") { ?>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="la la-credit-card"></i>
                            <span><?php _e("Salary") ?></span>
                            <h5><?php _esc($item_salary_min)?> - <?php _esc($item_salary_max)?> <?php _e("Per") ?> <?php _esc($item_salary_type)?>
                                <?php if($item_negotiate != "") { ?>
                                <div class="dashboard-status-button green"><?php _esc($item_negotiate)?></div>
                                <?php } ?>
                            </h5>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="la la-clock-o"></i>
                            <span><?php _e("Date Posted") ?></span>
                            <h5><?php _esc($item_created)?></h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="single-page-section">
                <h3><?php _e("Additional Details") ?></h3>
                <div class="row">
                    <?php if(isset($item_phone) && $item_hide_phone == "no") { ?>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="la la-phone"></i>
                            <span><?php _e("Phone Number") ?></span>
                            <h5><?php _esc($item_phone)?></h5>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="icon-feather-hash"></i>
                            <span><?php _e("Job ID") ?></span>
                            <h5><?php _esc($item_id)?></h5>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="job-property">
                            <i class="icon-feather-eye"></i>
                            <span><?php _e("Job Views") ?></span>
                            <h5><?php _esc($item_view)?></h5>
                        </div>
                    </div>
                    <?php if($item_customfield != "0") {
                        foreach ($item_custom as $custom){
                        ?>
                        <div class="col-md-6">
                            <div class="job-property">
                                <i class="icon-feather-chevron-right"></i>
                                <span><?php _esc($custom['title'])?></span>
                                <h5><?php _esc($custom['value'])?></h5>
                            </div>
                        </div>
                    <?php }
                    }
                    foreach ($item_custom_checkbox as $custom_checkbox){
                    ?>
                        <div class="col-md-6">
                            <div class="job-property">
                                <i class="icon-feather-chevron-right"></i>
                                <span><?php _esc($custom_checkbox['title'])?></span>
                                <h5 class="row"><?php _esc($custom_checkbox['value'])?></h5>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php foreach ($item_custom_textarea as $custom_textarea){ ?>
                    <div class="job-property">
                        <i class="icon-feather-chevron-right"></i>
                        <span><?php _esc($custom_textarea['title'])?></span>
                        <h5><?php _esc($custom_textarea['value'])?></h5>
                    </div>
                <?php } ?>

            </div>

            <div class="single-page-section">
                <h3><?php _e("Job Description") ?></h3>
                <div class="user-html"><?php _esc($item_desc);?></div>
            </div>
            <?php if($show_tag){ ?>
            <div class="single-page-section">
                <h3><?php _e("Tags") ?></h3>
                <div class="job-tags">
                    <?php _esc($item_tag);?>
                </div>
            </div>
            <?php } ?>
            <div class="single-page-section">
                <h3><?php _e("Location") ?></h3>
                <div id="single-job-map-container">
                    <div class="map-widget map" id="singleListingMap" data-latitude="<?php _esc($item_lat);?>" data-longitude="<?php _esc($item_long);?>"></div>
                    <?php if($item_location != "") { ?>
                    <br><span><a href="https://maps.google.com/?q=<?php _esc($item_location);?>" target="_blank" rel="nofollow"><?php _esc($item_location);?></a></span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <div class="job-detail-box">
                        <div class="job-detail-box-headline text-center">
                            <?php if($config['company_enable']){
                                _e("Company Details");
                            }else{
                                _e("Employer Details");
                            } ?>
                        </div>
                        <div class="job-detail-box-inner">
                            <div class="job-company-logo">
                                <a href="<?php _esc($company_link);?>">
                                    <img src="<?php _esc($company_image);?>" alt="<?php _esc($company_name);?>">
                                </a>
                            </div>
                            <h2><a href="<?php _esc($company_link);?>"><?php _esc($company_name);?></a></h2>
                            <ul>
                                <?php
                                if($company_city != "") {
                                    echo '<li><i class="icon-feather-map-pin"></i> <span>'._esc($company_city,false).', '._esc($company_state,false).'</span></li>';
                                }
                                if(!$hide_contact) {
                                    if($company_phone != "") {
                                        echo '<li><i class="icon-feather-phone-call"></i> <span><a href="tel:'._esc($company_phone,false).'" rel="nofollow">'._esc($company_phone,false).'</a></span></li>';
                                    }
                                    if($company_email != ""){
                                        echo '<li><i class="icon-feather-mail"></i> <span><a href="mailto:'._esc($company_email,false).'" rel="nofollow">'._esc($company_email,false).'</a></span></li>';
                                    }
                                }
                                if($company_website != ""){
                                    echo '<li><i class="icon-feather-link"></i> <span><a href="'._esc($company_website,false).'" rel="nofollow">'._esc($company_website,false).'</a></span></li>';
                                }
                                if($config['reg_no_enable'] && $company_reg_no != ""){
                                    echo '<li><i class="icon-feather-file-text"></i> <span>'._esc($company_reg_no,false).'</span></li>';
                                }
                                ?>
                            </ul>
                            <?php if($is_login){
                                if($usertype == 'user'){

                                    if($item_application_url != ''){
                                        echo '<a href="'._esc($item_application_url,false).'" class="button ripple-effect" target="_blank" rel="nofollow">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                                    }else{
                                        if($already_applied == ''){
                                            echo '<button class="button green disabled" disabled><i class="icon-feather-check"></i> '.__("Already Applied").'</button>';
                                        }else{
                                            echo '<a href="#apply-now-dialog" class="button ripple-effect popup-with-zoom-anim">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                                        }
                                    }

                                    if($config['quickchat_socket_on_off'] == 'on' || $config['quickchat_ajax_on_off'] == 'on'){
                                        echo '<button type="button" 
                                        class="button ripple-effect full-width margin-top-10 start_zechat zechat-hide-under-768px"
                                    data-chatid="'._esc($item_authorid,false).'_'._esc($item_id,false).'"
                                    data-postid="'._esc($item_id,false).'"
                                    data-userid="'._esc($item_authorid,false).'"
                                    data-username="'._esc($item_authoruname,false).'"
                                    data-fullname="'._esc($item_authorname,false).'"
                                    data-userimage="'._esc($item_authorimg,false).'"
                                    data-userstatus="'._esc($item_authoronline,false).'"
                                    data-posttitle="'._esc($item_title,false).'"
                                    data-postlink="'._esc($item_link,false).'">'.__("Chat now").' 
                                    <i class="icon-feather-message-circle"></i></button>';

                                        echo '<a href="'._esc($quickchat_url,false).'" 
                                        class="button ripple-effect full-width margin-top-10 zechat-show-under-768px">
                                        '.__("Chat now").' <i class="icon-feather-message-circle"></i></a>';
                                    }
                                }
                            }else{
                                echo '<a href="#sign-in-dialog" class="button ripple-effect popup-with-zoom-anim">'.__("Apply Now").' <i class="icon-feather-arrow-right"></i></a>';
                                echo ' <a href="#sign-in-dialog" class="button ripple-effect popup-with-zoom-anim full-width margin-top-10">'.__("Login to chat").' <i class="icon-feather-message-circle"></i></a>';
                            }?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Bookmark or Share") ?></h3>

                    <?php if($usertype == 'user'){ ?>
                    <!-- Bookmark Button -->
                    <button class="bookmark-button fav-button margin-bottom-25 set-item-fav <?php if($item_favorite == '1'){ echo 'added'; } ?>" data-item-id="<?php _esc($item_id)?>" data-userid="<?php _esc($user_id)?>" data-action="setFavAd">
                        <span class="bookmark-icon"></span>
                        <span class="fav-text"><?php _e("Bookmark") ?></span>
                        <span class="added-text"><?php _e("Saved") ?></span>
                    </button>
                    <?php } ?>
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
                                <li><a href="mailto:?subject=<?php _esc($item_title)?>&body=<?php _esc($item_link)?>" data-button-color="#dd4b39" title="<?php _e("Share on Email") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-envelope"></i></a></li>
                                <li><a href="https://facebook.com/sharer/sharer.php?u=<?php _esc($item_link)?>" data-button-color="#3b5998" title="<?php _e("Share on Facebook") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://twitter.com/share?url=<?php _esc($item_link)?>&text=<?php _esc($item_title)?>" data-button-color="#1da1f2" title="<?php _e("Share on Twitter") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php _esc($item_link)?>" data-button-color="#0077b5" title="<?php _e("Share on LinkedIn") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="https://pinterest.com/pin/create/bookmarklet/?&url=<?php _esc($item_link)?>&description=<?php _esc($item_title)?>" data-button-color="#bd081c" title="<?php _e("Share on Pinterest") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-pinterest-p"></i></a></li>
                                <li><a href="https://web.whatsapp.com/send?text=<?php _esc($item_link)?>" data-button-color="#25d366" title="<?php _e("Share on WhatsApp") ?>" data-tippy-placement="top" rel="nofollow" target="_blank"><i class="fa fa-whatsapp"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="sidebar-widget">
                    <h3><?php _e("More Info") ?></h3>
                    <ul class="related-links">
                        <?php if($config['company_enable']){ ?>
                        <li>
                            <a href="<?php _esc($company_link)?>#all-jobs"><i class="la la-suitcase"></i> <?php _e("More jobs by") ?> <?php _esc($company_name)?></a>
                        </li>
                        <?php } ?>
                        <li>
                            <a href="<?php _esc($user_link)?>#all-jobs"><i class="la la-user"></i> <?php _e("More jobs by") ?> <?php _esc($user_name)?></a>
                        </li>
                        <li>
                            <a href="<?php url("REPORT") ?>"><i class="la la-exclamation-triangle"></i> <?php _e("Report this job") ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <?php if($total_items != '0'){ ?>
        <div class="col-md-12 margin-top-30">
            <div class="single-page-section">
                <h3 class="margin-bottom-25"><?php _e("Similar Jobs") ?></h3>
                <div class="listings-container grid-layout">
                    <?php foreach ($items as $item){ ?>
                        <div class="job-listing">
                            <div class="job-listing-details">
                                <div class="job-listing-company-logo">
                                    <img src="<?php _esc($config['site_url'])?>storage/products/<?php _esc($item['image'])?>" alt="<?php _esc($item['company_name'])?>">
                                </div>
                                <div class="job-listing-description">
                                    <h4 class="job-listing-company"><?php _esc($item['company_name'])?></h4>
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
        <?php } ?>
    </div>
</div>

<div id="apply-now-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs popup-dialog">
    <ul class="popup-tabs-nav">
        <li><a href="#tab"><?php _e("Apply Now") ?></a></li>
    </ul>
    <div class="popup-tabs-container">
        <div class="popup-tab-content" id="tab">
            <?php if($show_apply_form){ ?>
            <form method="post" action="" accept-charset="UTF-8" enctype="multipart/form-data">
                <?php
                if($error != ''){
                    echo '<span class="status-not-available">'.$error.'</span>';
                }
                ?>
                <div class="submit-field">
                    <h5><?php _e("Message") ?> *</h5>
                    <textarea cols="30" rows="3" class="with-border" name="message" required=""></textarea>
                </div>

                <?php if($resume_enable){ ?>
                <div class="submit-field">
                    <h5><?php _e("Resumes") ?> *</h5>
                    <ul>
                        <?php foreach ($resumes as $resume){ ?>
                            <li>
                                <div class="radio">
                                    <input id="resume-<?php _esc($resume['id'])?>" name="resume" class="resume-file" type="radio" value="<?php _esc($resume['id'])?>">
                                    <label for="resume-<?php _esc($resume['id'])?>"><span class="radio-label"></span> <?php _esc($resume['name'])?> - <a href="<?php _esc($config['site_url'])?>storage/resumes/<?php _esc($resume['filename'])?>" download=""><?php _e("Download") ?></a></label>
                                </div>
                            </li>
                        <?php } ?>
                        <li>
                            <div class="radio">
                                <input id="resume-0" name="resume" class="new-resume resume-file" type="radio" value="0" checked>
                                <label for="resume-0"><span class="radio-label"></span> <?php _e("Add New Resume") ?></label>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="uploadButton resume-upload-button">
                    <input class="uploadButton-input" type="file" id="resume" name="resume_file"/>
                    <label class="uploadButton-button ripple-effect" for="resume"><?php _e("Upload Resume") ?></label>
                    <span class="uploadButton-file-name"><?php _e("Only pdf, doc, docx, rtf, rtx, ppt, pptx, jpeg, jpg, bmp, png file types allowed.") ?></span>
                </div>
                <?php }?>
                <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" name="submit" type="submit"><?php _e("Apply Now") ?> <i class="icon-feather-arrow-right"></i></button>
            </form>
            <?php }else{?>
            <h2 class="margin-bottom-20"><?php _e("Notify") ?></h2>
            <p><?php _e("Your email address is not verified. Please verify your email address first.") ?></p>
            <?php }?>
        </div>
    </div>
</div>
<script async="async">
    $('.resume-file').on('change',function(){
        if($('.new-resume').is(':checked')){
            $('.resume-upload-button').slideDown('fast');
        }else{
            $('.resume-upload-button').slideUp('fast');
        }
    });
    $('.resume-file').trigger('change');
</script>

<?php if($error != ""){ ?>
    <script>
        $(window).on('load',function () {
            $('.apply-dialog-button').trigger('click');
        });
    </script>
<?php }?>

<?php
if($config['post_address_mode']){
    if($config['map_type']=="google"){
        ?>
        <link href="<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/map-marker.css" type="text/css" rel="stylesheet">
        <script type='text/javascript' src='//maps.google.com/maps/api/js?key=<?php _esc($config['gmap_api_key'])?>&#038;libraries=places%2Cgeometry&#038;ver=2.2.1'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/richmarker-compiled.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/markerclusterer_packed.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/gmapAdBox.js'></script>
        <script type='text/javascript' src='<?php _esc($config['site_url']);?>includes/assets/plugins/map/google/maps.js'></script>
        <script>
            var element = "singleListingMap";
            var getCity = false;
            var _latitude = '<?php _esc($latitude)?>';
            var _longitude = '<?php _esc($longitude)?>';
            var color = '<?php _esc($map_color)?>';
            var site_url = '<?php _esc($config['site_url']);?>';
            var path = site_url;
            simpleMap(_latitude, _longitude, element);
        </script>
        <?php
    }else{
        ?>
        <script>
            var openstreet_access_token = '<?php _esc($config['openstreet_access_token'])?>';
        </script>
        <link rel="stylesheet" href="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/css/style.css">
        <!-- Leaflet // Docs: https://leafletjs.com/ -->
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet.min.js"></script>

        <!-- Leaflet Maps Scripts (locations are stored in leaflet-quick.js) -->
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-markercluster.min.js"></script>
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-gesture-handling.min.js"></script>
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-quick.js"></script>

        <!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-autocomplete.js"></script>
        <script src="<?php _esc($config['site_url']);?>includes/assets/plugins/map/openstreet/leaflet-control-geocoder.js"></script>

        <?php
    }
}
overall_footer();
?>