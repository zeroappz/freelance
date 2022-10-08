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
                <h3><?php _e("My Projects") ?></h3>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?php url("INDEX") ?>"><?php _e("Home") ?></a></li>
                        <li><?php _e("My Projects") ?></li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <!-- Dashboard Box -->
                <div class="col-xl-12">
                    <div class="dashboard-box">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-assignment"></i> <?php _e("My Projects") ?></h3>
                            <form method="get" action="" class="d-flex" id="form_search">
                                <div class="margin-right-10">
                                    <select class="with-border padding-top-0 padding-bottom-0 margin-bottom-0" name="status" id="project_status">
                                        <option value=""><?php _e("Status") ?></option>
                                        <option value="open"><?php _e("Open") ?></option>
                                        <option value="under_development"><?php _e("Under Development") ?></option>
                                        <option value="completed"><?php _e("Completed") ?></option>
                                        <option value="final_review_pending"><?php _e("Final Review Pending") ?></option>
                                        <option value="closed"><?php _e("Closed") ?></option>
                                        <option value="incomplete"><?php _e("Incomplete") ?></option>
                                    </select>
                                </div>
                                <div class="input-with-icon">
                                    <input class="with-border margin-bottom-0" name="keywords" value="<?php _esc($keywords)?>" type="text" placeholder="<?php _e("Search") ?>...">
                                    <i class="icon-feather-search"></i>
                                </div>
                            </form>
                        </div>
                        <!-- Content Start -->
                        <div class="content">
                            <ul class="dashboard-box-list" id="js-table-list">
                                <?php foreach($items as $item){ ?>
                                    <li class="ajax-item-listing" data-item-id="<?php _esc($item['id'])?>">
                                        <!-- Project Listing -->
                                        <div class="job-listing width-adjustment">
                                            <!-- Project Listing Details -->
                                            <div class="job-listing-details">
                                                <!-- Details -->
                                                <div class="job-listing-description">
                                                    <h3 class="job-listing-title">
                                                        <a href="<?php _esc($item['link'])?>"><?php _esc($item['product_name'])?></a>

                                                        <?php
                                                        if($item['status'] == "open")
                                                            echo '<div class="dashboard-status-button green">'.__("Open").'</div>';
                                                        if($item['status'] == "pending_for_approval")
                                                            echo '<div class="dashboard-status-button blue">'.__("Pending For Approval").'</div>';
                                                        if($item['status'] == "under_development")
                                                            echo '<div class="dashboard-status-button yellow">'.__("Under Development").'</div>';
                                                        if($item['status'] == "completed")
                                                            echo '<div class="dashboard-status-button green">'.__("Completed").'</div>';
                                                        if($item['status'] == "final_review_pending")
                                                            echo '<div class="dashboard-status-button yellow">'.__("Final Review Pending").'</div>';
                                                        if($item['status'] == "closed")
                                                            echo '<div class="dashboard-status-button red">'.__("Closed").'</div>';
                                                        if($item['status'] == "incomplete")
                                                            echo '<div class="dashboard-status-button red">'.__("Incomplete").'</div>';
                                                        if($item['featured']=="1")
                                                            echo '<div class="dashboard-status-button blue">'.__("Featured").'</div>';
                                                        if($item['urgent']=="1")
                                                            echo '<div class="dashboard-status-button yellow">'.__("Urgent").'</div>';
                                                        if($item['highlight']=="1")
                                                            echo '<div class="dashboard-status-button red">'.__("Highlight").'</div>';
                                                        ?>
                                                    </h3>

                                                    <!-- Project Listing Footer -->
                                                    <div class="job-listing-footer">
                                                        <ul>
                                                            <li><i class="icon-material-outline-access-time"></i> <?php _esc($item['created_at'])?></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Task Details -->
                                        <ul class="dashboard-task-info margin-bottom-5">
                                            <li><strong><?php _esc($item['bids_count'])?></strong><span><?php _e("Bids") ?></span></li>
                                            <li><strong><?php _esc($config['currency_sign'])?><?php _esc($item['avg_bid'])?></strong><span><?php _e("Avg. Bid") ?></span></li>
                                            <li><strong><?php _esc($config['currency_sign'])?><?php _esc($item['salary_min'])?>-<?php _esc($config['currency_sign'])?><?php _esc($item['salary_max'])?></strong><span><?php _esc($item['salary_type'])?></span></li>
                                        </ul>

                                        <!-- Buttons -->
                                        <div class="buttons-to-right always-visible">
                                            <?php
                                            if($item['rated'] == "1"){
                                                echo '<a href="#small-dialog-2" class="write_rating button ripple-effect" 
                                            data-project-id="'._esc($item['id'],false).'" 
                                            data-project-title="'._esc($item['product_name'],false).'">
                                            <i class="icon-material-outline-thumb-up"></i> '.__("Leave a Review").'</a>';
                                            }
                                            if($usertype == "employer"){
                                                if($item['status'] == 'open' || $item['status'] == 'pending_for_approval'){
                                                    echo '<a href="'.url("BIDDER",false).'/'._esc($item['id'],false).'" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> '.__("Manage Bidders").' <span class="button-info">'._esc($item['bids_count'],false).'</span></a>';
                                                }elseif($item['status'] == 'under_development'){
                                                    echo '<a href="'.url("MILESTONE",false).'/'._esc($item['id'],false).'" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> '.__("Milestone").' </a>';
                                                }

                                                if($item['status'] == 'open'){
                                                    echo '<a href="#" data-ajax-action="closeMyProject" class="button gray ripple-effect ico item-js-close" data-tippy-placement="top" title="'.__("Close").'"><i class="icon-close"></i></a>';
                                                }
                                            }else{
                                                if($item['freelancer_id'] == $user_id && $item['status'] == 'pending_for_approval'){
                                                    echo '<a href="'._esc($item['link'],false).'" class="button green ripple-effect"><i class="icon-feather-award"></i> '.__("Accept/Deny Offer").'</a>';
                                                }
                                                if($item['freelancer_id'] == $user_id && $item['status'] == 'under_development'){
                                                    echo '<a href="'.url("MILESTONE",false).'/'._esc($item['id'],false).'" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> '.__("Milestone").' </a>';
                                                }
                                            }
                                            ?>
                                        </div>
                                    </li>

                                <?php } ?>
                                <?php if(!$adsfound){ ?>
                                    <li class="ajax-item-listing">
                                        <!-- Project Listing -->
                                        <div class="job-listing width-adjustment">
                                            <!-- Project Listing Details -->
                                            <div class="job-listing-details">
                                                <?php _e("No result found.") ?>
                                            </div>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>

                        </div>
                        <!-- Content End -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <!-- Pagination -->
                            <div class="pagination-container margin-top-20">
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
                </div>
            </div>
            <!-- Row / End -->
            <!-- Leave a Review for Freelancer Popup
================================================== -->
            <div id="small-dialog-2" class="zoom-anim-dialog mfp-hide dialog-with-tabs popup-dialog">

                <!--Tabs -->
                <div class="sign-in-form">

                    <ul class="popup-tabs-nav"></ul>
                    <div class="popup-tabs-container">
                        <!-- Tab -->
                        <div class="popup-tab-content" id="tab2">
                            <!-- Welcome Text -->
                            <div class="welcome-text">
                                <h3><?php _e("Leave a Review") ?></h3>
                                    <div><?php _e("Rate for the project") ?> <span id="project_link"></span> </div>
                            </div>
                            <div id="rating-status" class="notification error" style="display:none"></div>
                            <!-- Form -->
                            <form method="post" id="leave-review-form" action="">
                                <?php if($usertype == 'employer'){ ?>
                                    <div class="feedback-yes-no">
                                        <strong><?php _e("Was this delivered on budget?") ?></strong>
                                        <div class="radio">
                                            <input id="radio-1" name="on_budget" type="radio" value="yes" checked required>
                                            <label for="radio-1"><span class="radio-label"></span> <?php _e("Yes") ?></label>
                                        </div>

                                        <div class="radio">
                                            <input id="radio-2" name="on_budget" type="radio" value="no" required>
                                            <label for="radio-2"><span class="radio-label"></span> <?php _e("No") ?></label>
                                        </div>
                                    </div>

                                    <div class="feedback-yes-no">
                                        <strong><?php _e("Was this delivered on time?") ?></strong>
                                        <div class="radio">
                                            <input id="radio-3" name="on_time" type="radio" value="yes" checked required>
                                            <label for="radio-3"><span class="radio-label"></span> <?php _e("Yes") ?></label>
                                        </div>

                                        <div class="radio">
                                            <input id="radio-4" name="on_time" type="radio" value="no" required>
                                            <label for="radio-4"><span class="radio-label"></span> <?php _e("No") ?></label>
                                        </div>
                                    </div>

                                    <div class="feedback-yes-no">
                                        <strong><?php _e("I recommend this freelancer.?") ?></strong>
                                        <div class="radio">
                                            <input id="radio-5" name="recommendation" type="radio" value="yes" checked required>
                                            <label for="radio-5"><span class="radio-label"></span> <?php _e("Yes") ?></label>
                                        </div>

                                        <div class="radio">
                                            <input id="radio-6" name="recommendation" type="radio" value="no" required>
                                            <label for="radio-6"><span class="radio-label"></span> <?php _e("No") ?></label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="feedback-yes-no">
                                    <strong><?php _e("Your Rating") ?></strong>
                                    <div class="leave-rating">
                                        <input type="radio" name="rating" id="rating-radio-1" value="5" checked required>
                                        <label for="rating-radio-1" class="icon-material-outline-star"></label>
                                        <input type="radio" name="rating" id="rating-radio-2" value="4" required>
                                        <label for="rating-radio-2" class="icon-material-outline-star"></label>
                                        <input type="radio" name="rating" id="rating-radio-3" value="3" required>
                                        <label for="rating-radio-3" class="icon-material-outline-star"></label>
                                        <input type="radio" name="rating" id="rating-radio-4" value="2" required>
                                        <label for="rating-radio-4" class="icon-material-outline-star"></label>
                                        <input type="radio" name="rating" id="rating-radio-5" value="1" required>
                                        <label for="rating-radio-5" class="icon-material-outline-star"></label>
                                    </div><div class="clearfix"></div>
                                </div>

                                <textarea class="with-border" placeholder="<?php _e("Comment") ?>" name="message" id="message" cols="7" required></textarea>
                                <input name="project_id" id="project_id" value="" type="hidden"/>
                                <!-- Button -->
                                <button id="submit_button" class="margin-top-15 button button-sliding-icon ripple-effect" type="submit"><?php _e("Leave a Review") ?> <i class="icon-material-outline-arrow-right-alt"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Leave a Review Popup / End -->
            <script>
                $(document).on('change', "#project_status" ,function(e){
                    $('#form_search').submit();
                });
                $(document).on('click', ".write_rating" ,function(e){
                    e.stopPropagation();
                    e.preventDefault();

                    var project_id = $(this).data('project-id'),
                        project_title = $(this).data('project-title');
                    $('#project_id').val(project_id);
                    $('#project_title').text(project_title);

                    var project_link = "<a href='<?php url("PROJECT",false)?>/"+project_id+"'>"+project_title+"</a>"
                    $('#project_link').html(project_link);
                    $.magnificPopup.open({
                        items: {
                            src: '#small-dialog-2',
                            type: 'inline',
                            fixedContentPos: false,
                            fixedBgPos: true,
                            overflowY: 'auto',
                            closeBtnInside: true,
                            preloader: false,
                            midClick: true,
                            removalDelay: 300,
                            mainClass: 'my-mfp-zoom-in'
                        }
                    });
                });
                $("#leave-review-form").on('submit',function (e) {
                    e.preventDefault();
                    var data = new FormData(this);
                    var action = 'write_rating';
                    $('#submit_button').addClass('button-progress').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: ajaxurl+'?action='+action,
                        data: data,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function (response) {
                            if(response.success){
                                $("#rating-status").addClass('success').removeClass('error').html('<p>'+response.message+'</p>').slideDown();
                                location.reload();
                            }
                            else {
                                $("#rating-status").removeClass('success').addClass('error').html('<p>'+response.message+'</p>').slideDown();
                            }
                            $('#submit_button').removeClass('button-progress').prop('disabled', false);
                        }
                    });
                    return false;
                });
            </script>
            <?php include_once TEMPLATE_PATH.'/overall_footer_dashboard.php'; ?>

