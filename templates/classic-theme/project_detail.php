<?php
overall_header($item_title, $meta_desc, '', true)
?>
<!-- Titlebar
================================================== -->
<div class="single-page-header" data-background-image="<?php _esc(TEMPLATE_URL);?>/images/single-task.jpg">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-page-header-inner">
                    <div class="left-side">
                        <div class="header-image">
                            <a href="<?php _esc($item_authorlink);?>">
                                <img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($item_authorimg);?>" alt="<?php _esc($item_authorname);?>">
                            </a></div>
                        <div class="header-details">
                            <h3><?php _esc($item_title);?></h3>
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
                            <h5><div class="star-rating" data-rating="<?php _esc($average_rating);?>"></div></h5>
                            <ul>
                                <li><a href="<?php _esc($item_authorlink);?>"><i class="icon-material-outline-business"></i> <?php _esc($item_authorname);?></a></li>
                                <li class="hidden"><img src="<?php _esc($config['site_url']);?>includes/assets/plugins/flags/images/<?php _esc($user_country);?>.png" alt=""> Germany</li>
                                <li><div class="verified-badge-with-title"><?php _e("Verified") ?></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="salary-box">
                            <div class="salary-type"><?php _e("Budget") ?></div>
                            <div class="salary-amount"><?php _esc($item_salary_min);?> - <?php _esc($item_salary_max);?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Project Content
================================================== -->
<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <!-- Description -->
            <div class="single-page-section">
                <h3 class="margin-bottom-25"><?php _e("Project Description") ?></h3>
                <div class="user-html <?php if($item_showmore == '1'){ echo 'show-more'; }?>">
                    <?php _esc($item_desc);?>
                    <?php if($item_showmore == '1'){ ?>
                        <a href="#" class="show-more-button"><?php _e("Show More") ?> <i class="fa fa-angle-down"></i></a>
                    <?php } ?>

                </div>
            </div>

            <?php
            foreach ($item_custom_textarea as $custom_textarea){ ?>
                <div class="single-page-section">
                    <h3><?php _esc($custom_textarea['title'])?></h3>
                    <div><?php _esc($custom_textarea['value'])?></div>
                </div>
            <?php } ?>
            <!-- Atachments -->
            <div class="single-page-section d-none">
                <h3><?php _e("Attachments") ?></h3>
                <div class="attachments-container">
                    <a href="#" class="attachment-box ripple-effect"><span>Project Brief</span><i>PDF</i></a>
                </div>
            </div>

            <!-- Skills -->
            <div class="single-page-section">
                <h3><?php _e("Skills") ?></h3>
                <div class="task-tags">
                    <?php foreach ($skills as $skill){
                        echo '<span><a href="'._esc($skill['link'],false).'">'._esc($skill['name'],false).'</a></span>';
                    }?>
                </div>
            </div>
            <div class="clearfix"></div>

            <!-- Freelancers Bidding -->
            <div class="boxed-list dashboard-box margin-bottom-60">
                <div class="boxed-list-headline">
                    <h3><i class="icon-material-outline-group"></i> <?php _e("Freelancers Bidding") ?> (<?php _esc($total_bid);?>)</h3>
                </div>
                <ul class="boxed-list-ul" id="js-table-list">

                    <?php foreach ($bids as $bid){ ?>
                        <li class="ajax-item-listing <?php if($bid['user_id'] == $item_freelancer_id){ echo 'awarded'; } ?>" data-item-id="<?php _esc($bid['id']);?>">
                            <div class="bid">
                                <!-- Avatar -->
                                <div class="bids-avatar">
                                    <div class="freelancer-avatar">
                                        <div class="verified-badge"></div>
                                        <a href="<?php url("PROFILE") ?>/<?php _esc($bid['username']);?>">
                                            <img src="<?php _esc($config['site_url']);?>storage/profile/<?php _esc($bid['image']);?>" alt="<?php _esc($bid['name']);?>">
                                        </a>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="bids-content dashboard-box-list">
                                    <!-- Name -->
                                    <div class="freelancer-name">
                                        <h4><a href="<?php url("PROFILE") ?>/<?php _esc($bid['username']);?>"><?php _esc($bid['name']);?></a>
                                            <div class="flag flag-<?php _esc($bid['country_code']);?>" data-tippy-placement="top" title="<?php _esc($bid['country']);?>"></div>
                                            <?php
                                            if($bid['user_id'] == $item_freelancer_id) {
                                                echo '<div class="dashboard-status-button green"><i class="icon-feather-award"></i> ' . __("Awarded") . '</div>';
                                                if($item_status == 'pending_for_approval'){
                                                    echo '<div class="dashboard-status-button yellow">' . __("Pending For Approval") . '</div>';
                                                }
                                            }
                                            ?>
                                        </h4>

                                        <div class="star-rating" data-rating="<?php _esc($bid['rating']) ?>"></div><br>
                                        <?php if($bid['user_id'] == $user_id || $user_id == $item_authorid){ ?>
                                        <div class="margin-top-3 <?php if($bid['showmore'] == '1'){ echo 'show-more'; } ?>">
                                            <?php _esc($bid['description']);?>
                                            <?php if($bid['showmore'] == '1'){
                                                echo '<a href="#" class="show-more-button">'.__("Show More").' <i class="fa fa-angle-down"></i></a>';
                                            }?>
                                        </div>
                                        <?php }?>
                                        <!-- Buttons -->
                                        <div class="margin-top-10">
                                            <?php
                                            if($bid['user_id'] == $item_freelancer_id){
                                                if($user_id == $item_authorid && $item_status == 'pending_for_approval'){
                                                    echo '<a href="#" data-ajax-action="reject_bid_approval" data-alert-message="'.__("Are you sure you want to revoke this bid.").'" class="button red ripple-effect item-ajax-button"><i class="icon-feather-award"></i> '.__("Revoke").'</a>';
                                                }
                                                if($user_id == $item_freelancer_id && $item_status == 'pending_for_approval'){
                                                    echo '<a href="#" data-ajax-action="accept_bid" data-alert-message="'.__("Are you sure you want to accept.").'" class="button green ripple-effect item-ajax-button"><i class="icon-feather-award"></i> '.__("Accept Offer").'</a>';
                                                    echo '<a href="#" data-ajax-action="reject_bid_approval" data-alert-message="'.__("Are you sure you want to deny offer.").'" class="button red ripple-effect item-ajax-button"><i class="icon-feather-award"></i> '.__("Deny Offer").'</a>';
                                                }
                                            }
                                            if($usertype == 'employer' && $user_id == $item_authorid){
                                                if($bid['user_id'] != $item_freelancer_id && ($item_status == 'open' || $item_status == 'pending_for_approval')){
                                                    echo '<a href="#small-dialog-1" class="accept-offer button green ripple-effect" 
                                                    data-bidid="'._esc($bid['id'],false).'" 
                                                    data-amount="'._esc($bid['amount'],false).'" 
                                                    data-userid="'._esc($bid['user_id'],false).'" 
                                                    data-fullname="'._esc($bid['name'],false).'">
                                                    <i class="icon-material-outline-check"></i> '.__("Accept Offer").'</a>';
                                                }

                                                if($config['quickchat_socket_on_off'] == 'on' || $config['quickchat_ajax_on_off'] == 'on'){
                                                    echo '<a href="javascript:void(0);" 
                                        class="button dark ripple-effect start_zechat zechat-hide-under-768px"
                                    data-chatid="'._esc($bid['user_id'],false).'_'._esc($item_id,false).'"
                                    data-postid="'._esc($item_id,false).'"
                                    data-userid="'._esc($bid['user_id'],false).'"
                                    data-username="'._esc($bid['username'],false).'"
                                    data-fullname="'._esc($bid['name'],false).'"
                                    data-userimage="'._esc($bid['image'],false).'"
                                    data-userstatus="'._esc($bid['online'],false).'"
                                    data-posttitle="'._esc($item_title,false).'"
                                    data-postlink="'._esc($item_link,false).'">'.__("Chat now").' 
                                    <i class="icon-feather-message-circle"></i></a>';

                                                    echo '<a href="'._esc($bid['quickchat_url'],false).'" 
                                        class="button dark ripple-effect zechat-show-under-768px">
                                        '.__("Chat now").' <i class="icon-feather-message-circle"></i></a>';
                                                }
                                            }
                                            if($usertype == 'user' && $bid['user_id'] == $user_id && ($item_status == 'open' || $item_status == 'pending_for_approval')){ ?>
                                                <a href="#small-dialog" class="apply-now-button button dark ripple-effect popup-with-zoom-anim">
                                                    <?php if($already_applied == '1'){
                                                        echo '<i class="icon-feather-edit"></i> '.__("Edit Bid");
                                                    }else{
                                                        echo '<i class="icon-feather-arrow-right"></i> '.__("Bid Now");
                                                    } ?>
                                                </a>
                                           <?php } ?>
                                        </div>

                                    </div>
                                </div>

                                <!-- Bid -->
                                <div class="bids-bid">
                                    <div class="bid-rate">
                                        <div class="rate"><?php _esc($bid['amount'])?></div>
                                        <span><?php _e("in") ?> <?php _esc($bid['days'])?> <?php _e("days") ?></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php } ?>

                    <?php if(!$total_bid){ ?>
                    <li class="ajax-item-listing">
                        <div class="text-center"><?php _e("This project has no proposals yet. <br> Be the first to place a bid on this project!") ?></div>
                    </li>
                    <?php } ?>
                </ul>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                <?php
                if($is_login){
                    if($usertype == 'user' && ($item_status == 'open' || $item_status == 'pending_for_approval')){?>
                        <a href="#small-dialog" class="apply-now-button button dark ripple-effect popup-with-zoom-anim">
                            <?php if($already_applied == '1'){
                                echo '<i class="icon-feather-edit"></i> '.__("Edit Bid");
                            }else{
                                echo '<i class="icon-feather-arrow-right"></i> '.__("Bid Now");
                            } ?>
                        </a>
                        <?php
                    }
                }else{
                    echo '<a href="#sign-in-dialog" class="apply-now-button ripple-effect popup-with-zoom-anim full-width">'.__("Bid Now").' <i class="icon-feather-arrow-right"></i></a>';
                    echo '<a href="#sign-in-dialog" class="apply-now-button ripple-effect popup-with-zoom-anim full-width margin-top-10">'.__("Login to chat").' <i class="icon-feather-message-circle"></i></a>';
                }
                ?>

                <div class="sidebar-widget">
                    <div class="job-overview">
                        <div class="job-overview-headline"><?php _e("Project Summary") ?></div>
                        <li class="job-overview-inner">
                            <ul>
                                <li>
                                    <i class="icon-material-outline-location-on"></i>
                                    <span><?php _e("Status") ?></span>
                                    <h5>
                                        <?php
                                        if($item_status == "open")
                                            echo '<div class="dashboard-status-button green">'.__("Open").'</div>';
                                        if($item_status == "pending_for_approval")
                                            echo '<div class="dashboard-status-button yellow">'.__("Pending For Approval").'</div>';
                                        if($item_status == "under_development")
                                            echo '<div class="dashboard-status-button blue">'.__("Under Development").'</div>';
                                        if($item_status == "completed")
                                            echo '<div class="dashboard-status-button green">'.__("Completed").'</div>';
                                        if($item_status == "final_review_pending")
                                            echo '<div class="dashboard-status-button yellow">'.__("Final Review Pending").'</div>';
                                        if($item_status == "closed")
                                            echo '<div class="dashboard-status-button red">'.__("Closed").'</div>';
                                        if($item_status == "incomplete")
                                            echo '<div class="dashboard-status-button red">'.__("Incomplete").'</div>';
                                        ?>
                                    </h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-location-on"></i>
                                    <span><?php _e("Category") ?></span>
                                    <h5><a href="<?php _esc($item_catlink)?>"><?php _esc($item_category)?></a></h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-business-center"></i>
                                    <span><?php _e("Project Type") ?></span>
                                    <h5><?php _esc($item_salary_type)?></h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-local-atm"></i>
                                    <span><?php _e("Budget") ?></span>
                                    <h5><?php _esc($item_salary_min)?> - <?php _esc($item_salary_max)?></h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-access-time"></i>
                                    <span><?php _e("Date Posted") ?></span>
                                    <h5><?php _esc($item_created)?></h5>
                                </li>
                                <li>
                                    <i class="icon-feather-hash"></i>
                                    <span><?php _e("Project ID") ?></span>
                                    <h5><?php _esc($item_id)?></h5>
                                </li>
                                <li>
                                    <i class="icon-feather-eye"></i>
                                    <span><?php _e("Project Views") ?></span>
                                    <h5><?php _esc($item_view)?></h5>
                                </li>
                                <?php
                                if($item_customfield != "0") {
                                    foreach ($item_custom as $custom){ ?>
                                        <li>
                                            <i class="icon-feather-chevron-right"></i>
                                            <span><?php _esc($custom['title'])?></span>
                                            <h5><?php _esc($custom['value'])?></h5>
                                        </li>
                                    <?php }
                                }
                                foreach ($item_custom_checkbox as $custom_checkbox){
                                ?>
                                    <li>
                                        <i class="icon-feather-chevron-right"></i>
                                        <span><?php _esc($custom_checkbox['title'])?></span>
                                        <h5 class="row"><?php _esc($custom_checkbox['value'])?></h5>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                </div>
            </div>

                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <h3><?php _e("Bookmark or Share") ?></h3>
                    <?php if($usertype == 'user'){ ?>
                    <!-- Bookmark Button -->
                    <button class="bookmark-button fav-button margin-bottom-25 set-item-fav <?php if($item_favorite == '1') { echo 'added'; }?>" data-item-id="<?php _esc($item_id)?>" data-userid="<?php _esc($user_id)?>" data-action="setFavAd">
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

            </div>
        </div>
    </div>
<!-- End Project Content
================================================== -->
</div>

<!-- Spacer -->
<div class="margin-top-15"></div>
<!-- Spacer / End-->
<!-- Apply Bid for a Project popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs popup-dialog">
    <ul class="popup-tabs-nav">
        <li><a href="#tab"><?php _e("Place a Bid on this Project") ?></a></li>
    </ul>
    <div class="popup-tabs-container">
        <div class="popup-tab-content" id="tab">
            <?php if($show_apply_form){ ?>
            <p><?php _e("You will be able to edit your bid until the project is awarded to someone.") ?></a></p>
            <form method="post" action="" accept-charset="UTF-8" enctype="multipart/form-data">
                <?php
                if($error != ''){
                    echo '<span class="status-not-available">'.$error.'</span>';
                }
                ?>
                <?php
                //This Function is called for set default currency code
                set_user_currency($config['specific_country']);
                ?>
                <div class="submit-field">
                    <h5><?php _e("Bid Amount") ?> *</h5>
                    <div class="input-with-icon">
                        <input class="with-border margin-bottom-0" type="number" placeholder="<?php _e("Bid Amount") ?>"
                               name="amount" value="<?php _esc($bid_amount)?>" >
                        <i class="currency"><?php _esc($config['currency_sign'])?></i>
                    </div>
                    <p class="help-message"><?php _e("Site comission fee") ?> <?php _esc($freelancer_commission)?>%
                        <i class="help-icon" data-tippy-placement="right" title="<?php _e("If you are awarded the project, and you accept, you will be charged a project fee on milestone payment release. Fee calculated as per site comission.") ?>"></i></p>
                </div>

                <div class="submit-field">
                    <h5><?php _e("Delivery with in days") ?> *</h5>
                    <input class="with-border" name="days" value="<?php _esc($bid_days)?>"/>
                </div>
                <div class="submit-field">
                    <h5><?php _e("Describe your proposal") ?> *</h5>
                    <textarea cols="30" rows="3" class="with-border" name="message" required=""><?php _esc($bid_message)?></textarea>
                </div>

                <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" name="submit" type="submit">
                    <?php if($already_applied == '1'){
                        echo '<i class="icon-feather-edit"></i> '.__("Edit Bid");
                    }else{
                        echo '<i class="icon-feather-arrow-right"></i> '.__("Bid Now");
                    } ?>
                </button>
            </form>
            <?php }else{?>
                <h2 class="margin-bottom-20"><?php _e("Notify") ?></h2>
                <p><?php _e("Your email address is not verified. Please verify your email address first.") ?></p>
            <?php }?>
        </div>
    </div>
</div>
<!-- Apply Bid for a Project popup / End -->

<!-- Employer Bid Acceptance Popup
================================================== -->
<div id="small-dialog-1" class="zoom-anim-dialog mfp-hide dialog-with-tabs  popup-dialog">

    <!--Tabs -->
    <div class="sign-in-form">

        <ul class="popup-tabs-nav">
            <li><a href="#tab1"><?php _e("Accept Offer") ?></a></li>
        </ul>

        <div class="popup-tabs-container">

            <!-- Tab -->
            <div class="popup-tab-content" id="tab">
                <form id="accept-bid-form" method="post" action="#">

                    <div id="accept-bid-status" class="notification error" style="display:none"></div>
                    <!-- Welcome Text -->
                    <div class="welcome-text">
                        <h3><?php _e("Accept Offer From") ?> <span class="bidder-name"></span></h3>
                        <div class="bid-acceptance margin-top-15"></div>

                    </div>


                    <input name="bid_id" class="bid-id" value="" type="hidden"/>
                    <div class="radio">
                        <input id="radio-1" name="radio" type="radio" required>
                        <label for="radio-1"><span class="radio-label"></span> <?php _e("I have read and agree to the Terms and Conditions") ?></label>
                    </div>


                    <!-- Button -->
                    <button id="accept-bid-button" class="margin-top-15 button full-width button-sliding-icon ripple-effect" type="submit"> <?php _e("Accept") ?> <i class="icon-material-outline-arrow-right-alt"></i></button>
                </form>
            </div>

        </div>
    </div>
</div>
<!-- Employer Bid Acceptance Popup / End -->


<script>
    var LANG_ACCEPT_REQ_SENT = "<?php _e("Accept request sent to freelancer") ?>";
</script>
<?php if($error != ""){ ?>
    <script>
        $(window).on('load',function () {
            $('.apply-dialog-button').trigger('click');
        });
    </script>
<?php }?>
<script src="<?php _esc(TEMPLATE_URL);?>/js/clipboard.min.js"></script>
<?php overall_footer(); ?>
