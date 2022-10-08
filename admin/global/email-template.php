<?php
require_once('../includes.php');

$default_shortcodes = [
    [
        'title' => 'Site Title',
        'code' => '{SITE_TITLE}'
    ],
    [
        'title' => 'Site URL',
        'code' => '{SITE_URL}'
    ]
];
$email_template = [
    [
        'id' => 'signup-details',
        'title' => 'User account details email',
        'subject' => 'email_sub_signup_details',
        'message' => 'email_message_signup_details',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'User id',
                'code' => '{USER_ID}'
            ],
            [
                'title' => 'Username',
                'code' => '{USERNAME}'
            ],
            [
                'title' => 'User full name',
                'code' => '{USER_FULLNAME}'
            ],
            [
                'title' => 'User email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'Password',
                'code' => '{PASSWORD}'
            ]
        ]),
    ],
    [
        'id' => 'create-account',
        'title' => 'Create account confirmation email',
        'subject' => 'email_sub_signup_confirm',
        'message' => 'email_message_signup_confirm',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'User id',
                'code' => '{USER_ID}'
            ],
            [
                'title' => 'Username',
                'code' => '{USERNAME}'
            ],
            [
                'title' => 'User full name',
                'code' => '{USER_FULLNAME}'
            ],
            [
                'title' => 'User email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'Registration Confirmation Link',
                'code' => '{CONFIRMATION_LINK}'
            ]
        ]),
    ],
    [
        'id' => 'forgot-pass',
        'title' => 'Forgot Password Email',
        'subject' => 'email_sub_forgot_pass',
        'message' => 'email_message_forgot_pass',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'User id',
                'code' => '{USER_ID}'
            ],
            [
                'title' => 'Username',
                'code' => '{USERNAME}'
            ],
            [
                'title' => 'User full name',
                'code' => '{USER_FULLNAME}'
            ],
            [
                'title' => 'User email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'Forgot password reset link',
                'code' => '{FORGET_PASSWORD_LINK}'
            ]
        ]),
    ],
    [
        'id' => 'contact_us',
        'title' => 'Contact Us Email',
        'subject' => 'email_sub_contact',
        'message' => 'email_message_contact',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'User full name',
                'code' => '{NAME}'
            ],
            [
                'title' => 'User email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'Contact email subject',
                'code' => '{CONTACT_SUBJECT}'
            ],
            [
                'title' => 'Contact email message',
                'code' => '{MESSAGE}'
            ]
        ]),
    ],
    [
        'id' => 'feedback',
        'title' => 'Feedback Email',
        'subject' => 'email_sub_feedback',
        'message' => 'email_message_feedback',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'User full name',
                'code' => '{NAME}'
            ],
            [
                'title' => 'User Email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'User phone number',
                'code' => '{PHONE}'
            ],
            [
                'title' => 'Feedback email subject',
                'code' => '{FEEDBACK_SUBJECT}'
            ],
            [
                'title' => 'Feedback email message',
                'code' => '{MESSAGE}'
            ]
        ]),
    ],
    [
        'id' => 'report',
        'title' => 'Report violation email',
        'subject' => 'email_sub_report',
        'message' => 'email_message_report',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Sender username',
                'code' => '{USERNAME}'
            ],
            [
                'title' => 'Sender full name',
                'code' => '{NAME}'
            ],
            [
                'title' => 'Sender email id',
                'code' => '{EMAIL}'
            ],
            [
                'title' => 'Violator username',
                'code' => '{USERNAME2}'
            ],
            [
                'title' => 'Violation subject',
                'code' => '{VIOLATION}'
            ],
            [
                'title' => 'Violation URL(LINK)',
                'code' => '{URL}'
            ],
            [
                'title' => 'Violation message',
                'code' => '{DETAILS}'
            ]
        ]),
    ],
    [
        'id' => 'ad_approve',
        'title' => 'Job listing approve email',
        'subject' => 'email_sub_ad_approve',
        'message' => 'email_message_ad_approve',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Seller full name',
                'code' => '{SELLER_NAME}'
            ],
            [
                'title' => 'Seller email',
                'code' => '{SELLER_EMAIL}'
            ],
            [
                'title' => 'Job listing title',
                'code' => '{ADTITLE}'
            ],
            [
                'title' => 'Job listing link',
                'code' => '{ADLINK}'
            ]
        ]),
    ],
    [
        'id' => 're_ad_approve',
        'title' => 'Re-submit job listing approve email',
        'subject' => 'email_sub_re_ad_approve',
        'message' => 'email_message_re_ad_approve',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Seller full name',
                'code' => '{SELLER_NAME}'
            ],
            [
                'title' => 'Seller email',
                'code' => '{SELLER_EMAIL}'
            ],
            [
                'title' => 'Job listing title',
                'code' => '{ADTITLE}'
            ],
            [
                'title' => 'Job listing link',
                'code' => '{ADLINK}'
            ]
        ]),
    ],
    [
        'id' => 'contact_to_seller',
        'title' => 'Apply for job email',
        'subject' => 'email_sub_contact_seller',
        'message' => 'email_message_contact_seller',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Seller full name',
                'code' => '{SELLER_NAME}'
            ],
            [
                'title' => 'Seller email',
                'code' => '{SELLER_EMAIL}'
            ],
            [
                'title' => 'Job listing title',
                'code' => '{ADTITLE}'
            ],
            [
                'title' => 'Job listing link',
                'code' => '{ADLINK}'
            ],
            [
                'title' => 'Sender full name',
                'code' => '{SENDER_NAME}'
            ],
            [
                'title' => 'Sender email',
                'code' => '{SENDER_EMAIL}'
            ],
            [
                'title' => 'Sender profile link',
                'code' => '{SENDER_PROFILE}'
            ],
            [
                'title' => 'Resume download link',
                'code' => '{RESUME_LINK}'
            ],
            [
                'title' => 'Sender message',
                'code' => '{MESSAGE}'
            ],
        ]),
    ],
    [
        'id' => 'project_awarded',
        'title' => 'Freelancer : Project Awarded',
        'subject' => 'email_sub_freelancer_project_awarded',
        'message' => 'emailHTML_freelancer_project_awarded',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Freelancer name',
                'code' => '{FREELANCER_NAME}'
            ]
        ]),
    ],
    [
        'id' => 'project_revoke',
        'title' => 'Freelancer : Project Revoked By Employer',
        'subject' => 'email_sub_freelancer_project_revoke',
        'message' => 'emailHTML_freelancer_project_revoke',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Freelancer name',
                'code' => '{FREELANCER_NAME}'
            ]
        ]),
    ],
    [
        'id' => 'project_accepted',
        'title' => 'Employer : Project Accepted By Freelancer',
        'subject' => 'email_sub_employer_project_accepted',
        'message' => 'emailHTML_employer_project_accepted',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Employer name',
                'code' => '{EMPLOYER_NAME}'
            ]
        ]),
    ],
    [
        'id' => 'project_approval_reject',
        'title' => 'Employer : Project Approval Rejected By Freelancer',
        'subject' => 'email_sub_employer_project_approval_reject',
        'message' => 'emailHTML_employer_project_approval_reject',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Employer name',
                'code' => '{EMPLOYER_NAME}'
            ]
        ]),
    ],
    [
        'id' => 'milestone_created',
        'title' => 'Freelancer : Milestone Created By Employer',
        'subject' => 'email_sub_milestone_created',
        'message' => 'emailHTML_milestone_created',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Milestone title',
                'code' => '{MILESTONE_TITLE}'
            ],
            [
                'title' => 'Milestone amount',
                'code' => '{MILESTONE_AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'milestone_release',
        'title' => 'Freelancer : Milestone Release By Employer',
        'subject' => 'email_sub_milestone_released',
        'message' => 'emailHTML_milestone_released',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Milestone title',
                'code' => '{MILESTONE_TITLE}'
            ],
            [
                'title' => 'Milestone amount',
                'code' => '{MILESTONE_AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'milestone_release_request',
        'title' => 'Employer : Milestone request to release by freelancer',
        'subject' => 'email_sub_milestone_request_to_release',
        'message' => 'emailHTML_milestone_request_to_release',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Milestone title',
                'code' => '{MILESTONE_TITLE}'
            ],
            [
                'title' => 'Milestone amount',
                'code' => '{MILESTONE_AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'got_rating',
        'title' => 'Got Rating on project',
        'subject' => 'email_sub_got_rating',
        'message' => 'emailHTML_got_rating',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Project title',
                'code' => '{PROJECT_TITLE}'
            ],
            [
                'title' => 'Project link',
                'code' => '{PROJECT_LINK}'
            ],
            [
                'title' => 'Rating stars number',
                'code' => '{RATING}'
            ],
            [
                'title' => 'Comment on rating',
                'code' => '{COMMENT}'
            ]
        ]),
    ],
    [
        'id' => 'withdraw_accepted',
        'title' => 'Withdraw : Request Accepted by Admin',
        'subject' => 'email_sub_withdraw_accepted',
        'message' => 'emailHTML_withdraw_accepted',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Withdrawal amount',
                'code' => '{AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'withdraw_rejected',
        'title' => 'Withdraw : Request Rejected By Admin',
        'subject' => 'email_sub_withdraw_rejected',
        'message' => 'emailHTML_withdraw_rejected',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Withdrawal amount',
                'code' => '{AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'new_withdraw_request',
        'title' => 'Admin : New Withdraw Request',
        'subject' => 'email_sub_withdraw_request',
        'message' => 'emailHTML_withdraw_request',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Withdrawal amount',
                'code' => '{AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'amount_deposit',
        'title' => 'Admin : User Deposit Amount To Wallet',
        'subject' => 'email_sub_amount_deposit',
        'message' => 'emailHTML_amount_deposit',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Deposit amount',
                'code' => '{AMOUNT}'
            ]
        ]),
    ],
    [
        'id' => 'job_newsletter_email',
        'title' => 'Send new job notification to subscriber',
        'subject' => 'email_sub_post_notification',
        'message' => 'email_message_post_notification',
        'shortcodes' => array_merge($default_shortcodes,[
            [
                'title' => 'Job listing title',
                'code' => '{ADTITLE}'
            ],
            [
                'title' => 'Job listing link',
                'code' => '{ADLINK}'
            ]
        ]),
    ]
];
?>
<style>
    #quickad-tbs .note-toolbar.panel-heading {
        padding: 0 10px 5px;
    }
</style>
<link href="../assets/js/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">

<!-- Page Content -->
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Email Notifications</h4>
                <div class="pull-right">
                    <a class="btn btn-sm btn-warning" href="setting.php#quickad_email">Email Configuration Setting</a>
                </div>
            </div>

            <div class="card-block">
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <form method="post" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=saveEmailTemplate" id="saveEmailTemplate">
                                <div class="panel panel-default quickad-main">
                                    <div class="panel-body">

                                        <div id="quickad-tbs" class="wrap">
                                            <div class="quickad-tbs-body">
                                                <div class="panel panel-default quickad-main">
                                                    <div class="panel-body">
                                                        <h4 class="quickad-block-head">
                                                            <span class="quickad-category-title">Email Template</span>
                                                        </h4>

                                                        <div class="quickad-margin-top-xlg">
                                                            <?php
                                                            $i = 1;
                                                            foreach($email_template as $template){
                                                                ?>
                                                                <div class="panel panel-default quickad-js-collapse">
                                                                    <div class="panel-heading" role="tab" id="s_<?php _esc($template['id'])?>">
                                                                        <div class="row">
                                                                            <div class="col-sm-8 col-xs-10">
                                                                                <div class="quickad-flexbox">
                                                                                    <div class="quickad-flex-cell quickad-vertical-middle" style="width: 1%">
                                                                                        <i class="quickad-js-handle quickad-icon quickad-icon-draghandle quickad-margin-right-sm quickad-cursor-move ui-sortable-handle" title="Reorder" style="display: none;"></i>
                                                                                    </div>
                                                                                    <div class="quickad-flex-cell quickad-vertical-middle">
                                                                                        <a role="button" class="panel-title quickad-js-service-title collapsed" data-toggle="collapse" data-parent=".panel-group" href="#service_<?php _esc($template['id'])?>" aria-expanded="false" >
                                                                                            <?php _esc($i)?>. <?php _esc($template['title'])?></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div id="service_<?php _esc($template['id'])?>" class="panel-collapse collapse" role="tabpanel" style="height: 0px;" aria-expanded="false">
                                                                        <div class="panel-body">

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Subject</label>
                                                                                        <input name="<?php _esc($template['subject'])?>" placeholder="Email Subject" class="form-control" type="text" value="<?php echo get_option($template['subject']) ?>">

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mailMethods mailMethod-0">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label for="pageContent">Message</label>
                                                                                        <textarea name="<?php _esc($template['message'])?>" rows="6" class="form-control summernote" placeholder="Enter Message"><?php echo get_option($template['message']) ?></textarea>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="form-group">
                                                                                        <label>Short Codes</label>
                                                                                        <table class="quickad-codes">
                                                                                            <tbody>
                                                                                            <?php foreach($template['shortcodes'] as $shortcode){ ?>
                                                                                                <tr>
                                                                                                    <td><input value="<?php _esc($shortcode['code'])?>" readonly="readonly" onclick="this.select()">- <?php _esc($shortcode['title'])?></td>
                                                                                                </tr>
                                                                                            <?php } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php  $i++; }  ?>

                                                            <div class="panel-footer">
                                                                <div class="pull-left">
                                                                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#test-email-notification">Test Email Notifications</button>
                                                                </div>
                                                                <button name="email_setting" type="submit" class="btn btn-success btn-radius save-changes">Save</button>
                                                                <button class="btn btn-default" type="reset">Reset</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </form>
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


<div class="modal fade" tabindex=-1 role="dialog" id="test-email-notification">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="test_notification_send" action="<?php echo ADMINURL;?>ajax_sidepanel.php?action=testEmailTemplate" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <div class="modal-title">Test Email Notifications</div>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="test_to_name">To name</label>
                                <input id="test_to_name" name="test_to_name" class="form-control" type="text"/>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="test_to_email">To email</label>
                                <input id="test_to_email" name="test_to_email" class="form-control" type="text"/>
                            </div>
                        </div>
                    </div>

                    <div id="quickad-tbs">
                        <div class="btn-group quickad-margin-bottom-lg quickad-services-holder">
                            <button class="btn btn-default btn-block dropdown-toggle quickad-flexbox" data-toggle="dropdown">
                                <div class="quickad-flex-cell text-left" style="width: 100%">Notification templates (10)</div>
                                <div class="quickad-flex-cell">
                                    <div class="quickad-margin-left-md"><span class="caret"></span></div>
                                </div>
                            </button>
                            <ul class="dropdown-menu" style="width: 570px">
                                <li class="quickad-padding-horizontal-md">
                                    <a class="checkbox checkbox-success"  href="#">
                                        <input type="checkbox" class="quickad-check-all-entities" value="any" id="all-template">
                                        <label for="all-template">All templates </label>
                                    </a>
                                </li>
                                <li role="separator" class="divider"></li>
                                <?php
                                foreach($email_template as $template){
                                    ?>
                                    <li class="quickad-padding-horizontal-md">
                                        <a class="checkbox checkbox-success" href="#">
                                            <input id="<?php _esc($template['id'])?>" name="<?php _esc($template['id'])?>" type="checkbox" class="quickad-js-check-entity" value="any" >
                                            <label for="<?php _esc($template['id'])?>"><?php _esc($template['title'])?> </label>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="test-email-notification" class="btn btn-lg btn-success ladda-button" data-style="zoom-in"
                            data-spinner-size="40"><span class="ladda-label">Send</span></button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include("../footer.php"); ?>

<script>
    $(".save-changes").on('click',function(){
        $(".save-changes").addClass("bookme-progress");
    });
    $(".ladda-button").on('click',function(){
        $(".ladda-button").addClass("bookme-progress");
    });
    /* Mail Method Changer */
    $("#email_template").on('change',function(){
        $(".mailMethods").hide();
        $(".mailMethod-"+$(this).val()).show();
    });

    $(document).on('change', '.quickad-check-all-entities', function () {
        $(this).parents('.quickad-services-holder').find('.quickad-js-check-entity').prop('checked', $(this).prop('checked'));
    });
    // wait for the DOM to be loaded
    $(document).ready(function() {
        // bind 'myForm' and provide a simple callback function
        $('#saveEmailTemplate').ajaxForm(function(data) {
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
    });

    // Test Notification send ajax
    $(document).ready(function() {
        // bind 'myForm' and provide a simple callback function
        $('#test_notification_send').ajaxForm(function(data) {
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
            $(".ladda-button").removeClass('bookme-progress');
        });
    });
</script>
<!-- include tinymce css/js -->
<script src="../assets/js/plugins/tinymce/tinymce.min.js"></script>
<script>
    $(document).ready(function() {
        tinymce.init({
            selector: '.summernote',
            plugins: 'quickbars image lists code table codesample',
            toolbar: 'blocks | forecolor backcolor | bold italic underline strikethrough | link image blockquote codesample | align bullist numlist | code ',
        });
    });
</script>
</body>
</html>