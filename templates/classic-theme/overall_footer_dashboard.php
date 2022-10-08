
<!-- Footer -->
<div class="dashboard-footer-spacer"></div>
<div class="small-footer margin-top-15">
    <div class="small-footer-copyrights">
        <?php _esc($config['copyright_text']);?>
    </div>
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
<!-- Footer / End -->
</div>
</div>
<!-- Dashboard Content / End -->
</div>
<!-- Dashboard Container / End -->
</div>
<!-- Wrapper / End -->
<script>
    $(document).ready(function () {
        $("#header-container").removeClass('transparent-header').addClass('dashboard-header not-sticky');
    });
</script>

<script>
    var error = "";
    function checkAvailabilityUsername() {
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>check_availability.php",
            data: 'username=' + $("#username").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $("#user-availability-status").html(data);
                }
                else {
                    error = 0;
                    $("#user-availability-status").html("");
                }
            },
            error: function () {
            }
        });
    }
    function checkAvailabilityEmail() {
        jQuery.ajax({
            url: "<?php _esc($config['app_url'])?>check_availability.php",
            data: 'email=' + $("#email").val(),
            type: "POST",
            success: function (data) {
                if (data != "success") {
                    error = 1;
                    $("#email-availability-status").html(data);
                }
                else {
                    error = 0;
                    $("#email-availability-status").html("");
                }
                $("#loaderIcon").hide();
            },
            error: function () {
            }
        });
    }
    function checkAvailabilityPassword() {
        var length = $('#password').val().length;
        if (length != 0) {
            var PASSLENG = "<?php _e("Password must be between 4 and 20 characters long") ?>";
            if (length < 5 || length > 21) {
                $("#password-availability-status").html("<span class='status-not-available'>" + PASSLENG + "</span>");
            }
            else {
                $("#password-availability-status").html("");
            }
        }

    }

    function checkRePassword(){
        if($('#password').val() != $('#re_password').val()){
            var PASS = "<?php _e("The passwords you entered did not match") ?>";
            $("#password-availability-status").html("<span class='status-not-available'>" + PASS + "</span>");
        }else{
            $("#password-availability-status").html("");
        }
    }
</script>
<!-- Footer Code -->

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
    var LANG_PROJECT_CLOSED = "<?php _e("Project has been closed") ?>";
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
