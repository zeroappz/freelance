<!-- Footer -->
<div id="footer">
    <div class="footer-middle-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-5 col-md-12">
                    <div class="footer-logo">
                        <img src="<?php _esc($config['site_url']);?>storage/logo/<?php _esc($config['site_logo_footer']);?>" alt="Footer Logo">
                    </div>
                    <p><?php _esc($config['footer_text']);?></p>
                </div>
                <div class="col-xl-1 col-lg-1">
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4">
                    <div class="footer-links">
                        <h3><?php _e("My Account") ?></h3>
                        <ul>
                            <?php
                            if($is_login) {
                                if($usertype == 'user') {
                                    echo '<li><a href="'.url("RESUMES",false).'">'.__("My Resumes").'</a></li>';
                                    echo '<li><a href="'.url("SEARCH_PROJECTS",false).'">'.__("Browse Projects").'</a></li>';
                                }else {
                                    echo '<li><a href="'.url("MYCOMPANIES",false).'">'.__("My Companies").'</a></li>';
                                    echo '<li><a href="'.url("MYPROJECTS",false).'">'.__("My Projects").'</a></li>';
                                }
                                echo '<li><a href="'.url("LOGOUT",false).'">'.__("Logout").'</a></li>';
                            }else {
                                echo '<li><a href="'.url("LOGIN",false).'">'.__("Login").'</a></li>';
                                echo '<li><a href="'.url("SIGNUP",false).'">'.__("Register").'</a></li>';
                                echo '<li><a href="'.url("POST-PROJECT",false).'">'.__("Post Project").'</a></li>';
                                echo '<li><a href="'.url("POST-JOB",false).'">'.__("Post a Job").'</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4">
                    <div class="footer-links">
                        <h3><?php _e("Helpful Links") ?></h3>
                        <ul>
                            <?php
                            if($config['country_type'] == "multi") {
                                echo '<li><a href="'.url("COUNTRIES",false).'">'.__("Countries").'</a></li>';
                            }
                            if($config['blog_enable']) {
                                echo '<li><a href="'.url("BLOG",false).'">'.__("Blog").'</a></li>';
                            }
                            ?>

                            <li><a href="<?php url("FEEDBACK") ?>"><?php _e("Feedback") ?></a></li>
                            <li><a href="<?php url("CONTACT") ?>"><?php _e("Contact") ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-2 col-lg-2 col-md-4">
                    <div class="footer-links">
                        <h3><?php _e("Information") ?></h3>
                        <ul>
                            <li><a href="<?php url("FAQ") ?>"><?php _e("FAQ") ?></a></li>
                            <?php
                            if($config['testimonials_enable']) {
                                echo '<li><a href="'.url("TESTIMONIALS",false).'">'.__("Testimonials").'</a></li>';
                            }
                            foreach($htmlpages as $html){
                                echo '<li><a href="'.$html['link'].'">'.$html['title'].'</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="footer-rows-left">
                        <div class="footer-row">
                            <span class="footer-copyright-text"><?php _esc($config['copyright_text']);?></span>
                        </div>
                    </div>
                    <div class="footer-rows-right">
                        <div class="footer-row">
                            <ul class="footer-social-links">
                                <?php
                                if($config['facebook_link'] != "")
                                    echo '<li><a href="'._esc($config['facebook_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>';
                                if($config['twitter_link'] != "")
                                    echo '<li><a href="'._esc($config['twitter_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>';
                                if($config['instagram_link'] != "")
                                    echo '<li><a href="'._esc($config['instagram_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-instagram"></i></a></li>';
                                if($config['linkedin_link'] != "")
                                    echo '<li><a href="'._esc($config['linkedin_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>';
                                if($config['pinterest_link'] != "")
                                    echo '<li><a href="'._esc($config['pinterest_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-pinterest"></i></a></li>';
                                if($config['youtube_link'] != "")
                                    echo '<li><a href="'._esc($config['youtube_link'],false).'" target="_blank" rel="nofollow"><i class="fa fa-youtube"></i></a></li>';
                                ?>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<!-- Wrapper / End -->

<?php
if($config['cookie_consent']) {
?>
<!-- Cookie constent -->
<div class="cookieConsentContainer">
    <div class="cookieTitle">
        <h3><?php _e("Cookies") ?></h3>
    </div>
    <div class="cookieDesc">
        <p><?php _e("This website uses cookies to ensure you get the best experience on our website.") ?>
            <?php
            if($config['cookie_link'] != "")
                echo '<a href="'._esc($config['cookie_link'],false).'">'.__("Cookie Policy").'</a>';
            ?>
        </p>
    </div>
    <div class="cookieButton">
        <a href="javascript:void(0)" class="button cookieAcceptButton"><?php _e("Accept") ?></a>
    </div>
</div>
<?php
}
if(!$is_login){
?>
<!-- Sign In Popup -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs popup-dialog">
        <ul class="popup-tabs-nav">
            <li><a href="#login"><?php _e("Login") ?></a></li>
        </ul>
        <div class="popup-tabs-container">
            <div class="popup-tab-content" id="login">
                <div class="welcome-text">
                    <h3><?php _e("Welcome Back!") ?></h3>
                    <span><?php _e("Don't have an account?") ?> <a href="<?php url("SIGNUP") ?>"><?php _e("Sign Up Now!") ?></a></span>
                </div>
                <?php
                if($config['facebook_app_id'] != "" || $config['google_app_id'] != ""){
                    ?>
                    <div class="social-login-buttons">
                        <?php
                        if($config['facebook_app_id'] != ""){
                        ?>
                        <button class="facebook-login ripple-effect" onclick="fblogin()"><i class="fa fa-facebook"></i> <?php _e("Log In via Facebook") ?></button>
                        <?php
                        }
                        if($config['google_app_id'] != ""){
                            ?>
                        <button class="google-login ripple-effect" onclick="gmlogin()"><i class="fa fa-google"></i> <?php _e("Log In via Google") ?></button>
                        <?php } ?>
                    </div>
                    <div class="social-login-separator"><span><?php _e("or") ?></span></div>
                <?php } ?>


                <form id="login-form" method="post" action="<?php _esc($config['site_url']) ?>login?ref=<?php _esc($ref_url) ?>">
                    <div id="login-status" class="notification error" style="display:none"></div>
                    <div class="input-with-icon-left">
                        <i class="la la-user"></i>
                        <input type="text" class="input-text with-border" name="username" id="username"
                               placeholder="<?php _e("Username") ?> / <?php _e("Email Address") ?>" required/>
                    </div>

                    <div class="input-with-icon-left">
                        <i class="la la-unlock"></i>
                        <input type="password" class="input-text with-border" name="password" id="password"
                               placeholder="<?php _e("Password") ?>" required/>
                    </div>
                    <a href="<?php url("LOGIN") ?>?fstart=1" class="forgot-password"><?php _e("Forgot Password?") ?></a>
                    <button id="login-button" class="button full-width button-sliding-icon ripple-effect" type="submit" name="submit"><?php _e("Login") ?> <i class="icon-feather-arrow-right"></i></button>
                </form>
            </div>
    </div>
</div>
<?php
}
?>

<script>
    var session_uname = "<?php _esc($username)?>";
    var session_uid = "<?php _esc($user_id)?>";
    var session_img = "<?php _esc($userpic)?>";
    // Language Var
    var LANG_ERROR_TRY_AGAIN = "<?php _e("Error: Please try again.") ?>";
    var LANG_LOGGED_IN_SUCCESS = "<?php _e("Logged in successfully. Redirecting...") ?>";
    var LANG_ERROR = "<?php _e("Error") ?>";
    var LANG_CANCEL = "<?php _e("Cancel") ?>";
    var LANG_DELETED = "<?php _e("Deleted") ?>";
    var LANG_ARE_YOU_SURE = "<?php _e("Are you sure?") ?>";
    var LANG_YOU_WANT_DELETE = "<?php _e("You want to delete this job") ?>";
    var LANG_YES_DELETE = "<?php _e("Yes, delete it") ?>";
    var LANG_PROJECT_DELETED = "<?php _e("Project has been deleted") ?>";
    var LANG_RESUME_DELETED = "<?php _e("Resume Deleted.") ?>";
    var LANG_EXPERIENCE_DELETED = "<?php _e("Experience Deleted.") ?>";
    var LANG_COMPANY_DELETED = "<?php _e("Company Deleted.") ?>";
    var LANG_SHOW = "<?php _e("Show") ?>";
    var LANG_HIDE = "<?php _e("Hide") ?>";
    var LANG_HIDDEN = "<?php _e("Hidden") ?>";
    var LANG_TYPE_A_MESSAGE = "<?php _e("Type a message") ?>";
    var LANG_ADD_FILES_TEXT = "<?php _e("Add files to the upload queue and click the start button.") ?>";
    var LANG_ENABLE_CHAT_YOURSELF = "<?php _e("Could not able to chat yourself.") ?>";
    var LANG_JUST_NOW = "<?php _e("Just now") ?>";
    var LANG_PREVIEW = "<?php _e("Preview") ?>";
    var LANG_SEND = "<?php _e("Send") ?>";
    var LANG_FILENAME = "<?php _e("Filename") ?>";
    var LANG_STATUS = "<?php _e("Status") ?>";
    var LANG_SIZE = "<?php _e("Size") ?>";
    var LANG_DRAG_FILES_HERE = "<?php _e("Drag files here") ?>";
    var LANG_STOP_UPLOAD = "<?php _e("Stop Upload") ?>";
    var LANG_ADD_FILES = "<?php _e("Add files") ?>";
    var LANG_CHATS = "<?php _e("Chats") ?>";
    var LANG_NO_MSG_FOUND = "<?php _e("No message found") ?>";
    var LANG_ONLINE = "<?php _e("Online") ?>";
    var LANG_OFFLINE = "<?php _e("Offline") ?>";
    var LANG_TYPING = "<?php _e("Typing...") ?>";
    var LANG_GOT_MESSAGE = "<?php _e("You got a message") ?>";

    if ($("body").hasClass("rtl")) {
        var rtl = true;
    }else{
        var rtl = false;
    }
</script>
<!-- Scripts
================================================== -->
<script src="<?php _esc(TEMPLATE_URL);?>/js/mmenu.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/chosen.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/tippy.all.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/simplebar.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/bootstrap-slider.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/bootstrap-select.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/snackbar.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/clipboard.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/counterup.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/magnific-popup.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/jquery.cookie.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/slick.min.js"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/user-ajax.js?ver=<?php _esc($config['version']);?>"></script>
<script src="<?php _esc(TEMPLATE_URL);?>/js/custom.js?ver=<?php _esc($config['version']);?>"></script>
<link href="<?php _esc(TEMPLATE_URL);?>/css/select2.min.css" rel="stylesheet"/>
<script src="<?php _esc(TEMPLATE_URL);?>/js/select2.min.js"></script>
<script>
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
<?php
if($is_login){
    if($config['quickchat_socket_on_off'] == "on"){
        ?>
        <script>
            var ws_protocol = window.location.href.indexOf("https://")==0?"wss":"ws";
            var ws_host = '<?php _esc($config['socket_host'])?>';
            var ws_port = '<?php _esc($config['socket_port'])?>';
            var WEBSOCKET_URL = ws_protocol+'://'+ws_host+':'+ws_port+'/quickchat';
            var filename = "<?php _esc($config['quickchat_socket_secret_file'])?>.php";
            var plugin_directory = "plugins/quickchat-socket/"+filename;
        </script>
        <link type="text/css" rel="stylesheet" media="all" href="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatcss/chatbox.css"/>
        <div id="quickchat-rtl"></div>
        <script>
            if ($("body").hasClass("rtl")) {
                $('#quickchat-rtl').append('<link rel="stylesheet" type="text/css" href="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatcss/chatbox-rtl.css">');
                var rtl = true;
            }else{
                var rtl = false;
            }
        </script>
        <!--Websocket Version Js-->
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatjs/quickchat-websocket.js"></script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/plugins/smiley/js/emojione.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/plugins/smiley/smiley.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatjs/lightbox.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatjs/chatbox.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/assets/chatjs/chatbox_custom.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/plugins/uploader/plupload.full.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/plugins/uploader/jquery.ui.plupload/jquery.ui.plupload.js"></script>
        <table id="lightbox" style="display: none;height: 100%">
            <tr><td height="10px"><p><img src="<?php _esc($config['site_url']);?>plugins/quickchat-socket/plugins/images/close-icon-white.png" width="30px" style="cursor: pointer"/></p></td></tr>
            <tr><td valign="middle"><div id="content"><img src="#"/></div></td></tr>
        </table>
        <?php
    }
    else if($config['quickchat_ajax_on_off'] == "on"){
        ?>
        <script>
            var filename = "<?php _esc($config['quickchat_ajax_secret_file'])?>.php";
            var plugin_directory = "plugins/quickchat-ajax/"+filename;
        </script>
        <link type="text/css" rel="stylesheet" media="all" href="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/assets/chatcss/chatbox.css"/>
        <div id="quickchat-rtl"></div>
        <script>
            if ($("body").hasClass("rtl")) {
                $('#quickchat-rtl').append('<link rel="stylesheet" type="text/css" href="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/assets/chatcss/chatbox-rtl.css">');
                var rtl = true;
            }else{
                var rtl = false;
            }
        </script>
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/plugins/smiley/js/emojione.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/plugins/smiley/smiley.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/assets/chatjs/lightbox.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/assets/chatjs/chatbox.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/assets/chatjs/chatbox_custom.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/plugins/uploader/plupload.full.min.js"></script>
        <script type="text/javascript" src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/plugins/uploader/jquery.ui.plupload/jquery.ui.plupload.js"></script>
        <table id="lightbox" style="display: none;height: 100%">
            <tr><td height="10px"><p><img src="<?php _esc($config['site_url']);?>plugins/quickchat-ajax/plugins/images/close-icon-white.png" width="30px" style="cursor: pointer"/></p></td></tr>
            <tr><td valign="middle"><div id="content"><img src="#"/></div></td></tr>
        </table>

        <?php
    }
}
?>
</body>
</html>
