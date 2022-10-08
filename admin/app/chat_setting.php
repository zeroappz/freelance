<?php
require_once('../includes.php');
$message = "";
if(isset($_POST['submit']))
{
    if(!check_allow()){
        ?>
        <script>
            $(document).ready(function(){
                $('#sa-title').trigger('click');
            });
        </script>
    <?php

    }
    else {

        function install_chat_setting($code){
            global $config;
            // Set API Key
            $buyer_email = '';
            $installing_version = 'pro';
            $site_url = $config['site_url'];

            $url = "https://bylancer.com/api/api.php?verify-purchase=" . $code . "&version=" . $installing_version . "&site_url=" . $site_url . "&email=" . $buyer_email;
            // Open cURL channel
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            //Set the user agent
            $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
            // Decode returned JSON
            $output = json_decode(curl_exec($ch), true);
            // Close Channel
            curl_close($ch);

            return $output;
        }

        switch ($_POST['chat_type']){
            case 'quickchat_websocket' :

                update_option("socket_host",$_POST['socket_host']);
                update_option("socket_port",$_POST['socket_port']);

                if(isset($_POST['quickchat_socket_on_off'])){
                    $quickchat_socket_purchase = get_option("quickchat_socket_purchase_code");
                    if($quickchat_socket_purchase == NULL) {
                        $message .= '<span style="color:red;">( Enter Your Valid Quickchat Purchase Code.)</span>';
                    }
                    else{
                        update_option("quickchat_socket_on_off",$_POST['quickchat_socket_on_off']);
                    }
                }
                else{
                    update_option("quickchat_socket_on_off","off");
                }

                if(isset($_POST['quickchat_socket_purchase_code'])){
                    if($_POST['quickchat_socket_purchase_code'] != "") {
                        $code = $_POST['quickchat_socket_purchase_code'];
                        $output = install_chat_setting($code);

                        if ($output['success']) {
                            if(isset($config['quickchat_socket_secret_file']) && $config['quickchat_socket_secret_file'] != ""){
                                $fileName = $config['quickchat_socket_secret_file'];
                            }else{
                                $fileName = get_random_string();
                            }
                            file_put_contents('../plugins/quickchat-socket/function/' . $fileName . '.php', $output['data']);
                            $success = true;
                            update_option("quickchat_socket_secret_file",$fileName);
                            update_option("quickchat_socket_purchase_code",$_POST['quickchat_socket_purchase_code']);
                            $message = 'Quickchat Purchase code verified successfully';
                            transfer("chat_setting.php",$message);
                            exit;
                        } else {
                            $error = $output['error'];
                            $message .= '<span style="color:red;">'.$error.'</span>';
                        }
                    }
                }

                break;
            case 'quickchat_ajax' :
                if(isset($_POST['quickchat_ajax_on_off'])){
                    $quickchat_ajax_purchase = get_option("quickchat_ajax_purchase_code");
                    if($quickchat_ajax_purchase == NULL) {
                        $message .= '<span style="color:red;">( Enter Your Valid Quickchat Purchase Code.)</span>';
                    }
                    else{
                        update_option("quickchat_ajax_on_off",$_POST['quickchat_ajax_on_off']);
                    }
                }
                else{
                    update_option("quickchat_ajax_on_off","off");
                }

                if(isset($_POST['quickchat_ajax_purchase_code'])){
                    if($_POST['quickchat_ajax_purchase_code'] != "") {
                        $code = $_POST['quickchat_ajax_purchase_code'];
                        $output = install_chat_setting($code);

                        if ($output['success']) {
                            if(isset($config['quickchat_ajax_secret_file']) && $config['quickchat_ajax_secret_file'] != ""){
                                $fileName = $config['quickchat_ajax_secret_file'];
                            }else{
                                $fileName = get_random_string();
                            }
                            file_put_contents('../plugins/quickchat-ajax/' . $fileName . '.php', $output['data']);
                            $success = true;
                            update_option("quickchat_ajax_secret_file",$fileName);
                            update_option("quickchat_ajax_purchase_code",$_POST['quickchat_ajax_purchase_code']);
                            $message = 'Quickchat Purchase code verified successfully';
                            transfer("chat_setting.php",$message);
                            exit;
                        } else {
                            $error = $output['error'];
                            $message .= '<span style="color:red;">'.$error.'</span>';
                        }
                    }
                }

                break;
            case 'wchat' :
                if(isset($_POST['wchat_on_off'])){
                    $wchat_purchase = get_option("wchat_purchase_code");
                    if($wchat_purchase == "") {
                        $message .= '<span style="color:red;">( Enter Your Valid Wchat Purchase Code.)</span>';
                    }
                    else{
                        update_option("wchat_on_off",$_POST['wchat_on_off']);
                    }
                }
                else{
                    update_option("wchat_on_off","off");
                }

                if(isset($_POST['wchat_purchase_code'])){
                    if($_POST['wchat_purchase_code'] != "") {
                        $code = $_POST['wchat_purchase_code'];
                        $output = install_chat_setting($code);

                        if ($output['success']) {
                            if(isset($config['wchat_secret_file']) && $config['wchat_secret_file'] != ""){
                                $fileName = $config['wchat_secret_file'];
                            }else{
                                $fileName = get_random_string();
                            }
                            file_put_contents('../plugins/wchat/' . $fileName . '.php', $output['data']);
                            $success = true;
                            update_option("wchat_secret_file",$fileName);
                            update_option("wchat_purchase_code",$_POST['wchat_purchase_code']);
                            $message = 'Wchat Purchase code verified successfully';
                            transfer("chat_setting.php",$message);
                            exit;
                        } else {
                            $error = $output['error'];
                            $message .= '<span style="color:red;">'.$error.'</span>';
                        }
                    }
                }

                break;
            case 'zechat' :
                if(isset($_POST['zechat_on_off'])){
                    $zechat_purchase = get_option("zechat_purchase_code");
                    if($zechat_purchase == NULL) {
                        $message .= '<span style="color:red;">( Enter Your Valid Zechat Purchase Code.)</span>';
                    }
                    else{
                        update_option("zechat_on_off",$_POST['zechat_on_off']);
                    }
                }
                else{
                    update_option("zechat_on_off","off");
                }

                if(isset($_POST['zechat_purchase_code'])){
                    if($_POST['zechat_purchase_code'] != "") {
                        $code = $_POST['zechat_purchase_code'];
                        $output = install_chat_setting($code);

                        if ($output['success']) {
                            if(isset($config['zechat_secret_file']) && $config['zechat_secret_file'] != ""){
                                $fileName = $config['zechat_secret_file'];
                            }else{
                                $fileName = get_random_string();
                            }
                            file_put_contents('../plugins/zechat/' . $fileName . '.php', $output['data']);
                            $success = true;
                            update_option("zechat_secret_file",$fileName);
                            update_option("zechat_purchase_code",$_POST['zechat_purchase_code']);
                            $message = 'Zechat Purchase code verified successfully';
                            transfer("chat_setting.php",$message);
                            exit;
                        } else {
                            $error = $output['error'];
                            $message .= '<span style="color:red;">'.$error.'</span>';
                        }

                    }
                }

                break;
        }
    }
}

?>
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid p-y-md">
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4>Chat Setting</h4>
            </div>
            <div class="card-block">
                <div class="form-body">
                    <div>
                        <div class="text-left"><?php echo $message; ?></div>
                    </div>

                    <div id="quickad-tbs" class="wrap">
                        <div class="quickad-tbs-body">
                            <div class="row">
                                <div id="quickad-sidebar" class="col-sm-4">
                                    <ul class="quickad-nav" role="tablist">
                                        <li class="quickad-nav-item hidden" data-target="#quickchat_websocket" data-toggle="tab">Quickchat WebSocket</li>
                                        <li class="quickad-nav-item active" data-target="#quickchat_ajax" data-toggle="tab">Quickchat Php Ajax</li>
                                        <li class="quickad-nav-item" data-target="#wchat" data-toggle="tab">Wchat</li>
                                        <li class="quickad-nav-item" data-target="#zechat" data-toggle="tab">Zechat</li>
                                    </ul>
                                </div>
                                <div id="quickad_settings_controls" class="col-sm-8">
                                    <div class="panel panel-default quickad-main">
                                        <div class="panel-body">
                                            <div class="tab-content">

                                                <div class="tab-pane hidden" id="quickchat_websocket">
                                                    <div class="form-group">
                                                        <h4>Quickchat WebSocket</h4>
                                                        <p class="help-block">For more information on how to start websocket see this <a href="https://bylancer.ticksy.com/article/15905/" target="_blank">article</a>.</p>
                                                    </div>
                                                    <form action="#quickchat_websocket" name="form2" class="form form-horizontal" method="post">

                                                        <?php
                                                        if(isset($config['quickchat_socket_purchase_code']) && $config['quickchat_socket_purchase_code'] != ""){
                                                            ?>
                                                            <div class="form-group bt-switch">
                                                                <label class="col-sm-4 control-label">Quickchat WebSocket on/off:</label>
                                                                <div class="col-sm-6">
                                                                    <label class="css-input switch switch-success">
                                                                        <input  name="quickchat_socket_on_off" type="checkbox" <?php if (get_option("quickchat_socket_on_off") == 'on') { echo "checked"; } ?> /><span></span>
                                                                    </label>

                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Host:</label>
                                                                <div class="col-sm-6">
                                                                    <input name="socket_host" type="text" class="form-control" value="<?php echo get_option("socket_host"); ?>" autocomplete="false">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-sm-4 control-label">Port:</label>
                                                                <div class="col-sm-6">
                                                                    <input name="socket_port" type="text" class="form-control" value="<?php echo get_option("socket_port"); ?>" autocomplete="false">
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>





                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Quickchat WebSocket Purchase Code:</label>
                                                            <div class="col-sm-6">
                                                                <?php
                                                                if(isset($config['quickchat_socket_purchase_code']) && $config['quickchat_socket_purchase_code'] != ""){
                                                                    ?>
                                                                    <div class="alert alert-success">
                                                                        <strong>Success!</strong> Quickchat Purchase code verified, you can on/off</div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <input name="quickchat_socket_purchase_code" type="password" class="form-control" value="" autocomplete="false">
                                                                <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codecanyon.net/user/bylancer/portfolio" target="_blank">Buy Quickchat WebSocket</a></span>
                                                            </div>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <input name="chat_type" type="hidden" class="form-control" value="quickchat_websocket">
                                                            <button name="submit" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                            <button class="btn btn-default" type="reset">Reset</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="tab-pane active" id="quickchat_ajax">
                                                    <div class="form-group">
                                                        <h4>Quickchat Php Ajax</h4>
                                                    </div>
                                                    <form action="#quickchat_ajax" name="form2" class="form form-horizontal" method="post">
                                                        <?php
                                                        if(isset($config['quickchat_ajax_purchase_code']) && $config['quickchat_ajax_purchase_code'] != ""){
                                                            ?>
                                                            <div class="form-group bt-switch">
                                                                <label class="col-sm-4 control-label">Quickchat on/off:</label>
                                                                <div class="col-sm-6">
                                                                    <label class="css-input switch switch-success">
                                                                        <input  name="quickchat_ajax_on_off" type="checkbox" <?php if (get_option("quickchat_ajax_on_off") == 'on') { echo "checked"; } ?> /><span></span>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Quickchat Purchase Code:</label>
                                                            <div class="col-sm-6">
                                                                <?php
                                                                if(isset($config['quickchat_ajax_purchase_code']) && $config['quickchat_ajax_purchase_code'] != ""){
                                                                    ?>
                                                                    <div class="alert alert-success">
                                                                        <strong>Success!</strong> Quickchat Purchase code verified, you can on/off</div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <input name="quickchat_ajax_purchase_code" type="password" class="form-control" value="">
                                                                <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codentheme.com/item/quickchat-realtime-ajax-chat-messaging-plugin/" target="_blank">Buy Quickchat realtime AJAX chat</a></span>
                                                            </div>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <input name="chat_type" type="hidden" class="form-control" value="quickchat_ajax">
                                                            <button name="submit" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                            <button class="btn btn-default" type="reset">Reset</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="tab-pane" id="wchat">
                                                    <div class="form-group">
                                                        <h4>Wchat</h4>
                                                    </div>
                                                    <form action="#wchat" name="form2" class="form form-horizontal" method="post">
                                                        <?php
                                                        if(isset($config['wchat_purchase_code']) && $config['wchat_purchase_code'] != ""){
                                                            ?>
                                                            <div class="form-group bt-switch">
                                                                <label class="col-sm-4 control-label">Wchat on/off:</label>
                                                                <div class="col-sm-6">
                                                                    <label class="css-input switch switch-success">
                                                                        <input  name="wchat_on_off" type="checkbox" <?php if (get_option("wchat_on_off") == 'on') { echo "checked"; } ?> /><span></span>
                                                                    </label>

                                                                </div>
                                                            </div>

                                                        <?php
                                                        }
                                                        ?>
                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Wchat Purchase Code:</label>
                                                            <div class="col-sm-6">
                                                                <?php
                                                                if(isset($config['wchat_purchase_code']) && $config['wchat_purchase_code'] != ""){
                                                                    ?>
                                                                    <div class="alert alert-success">
                                                                        <strong>Success!</strong> Wchat Purchase code verified, you can on/off</div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <input name="wchat_purchase_code" type="password" class="form-control" value="">
                                                                <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codecanyon.net/item/wchat-fully-responsive-phpajax-chat/18047319?clickthrough_id=18047319&license=regular&open_purchase_for_item_id=18047319&purchasable=source&redirect_back=true&ref=bylancer&utm_source=item_desc_link" target="_blank">Buy Wchat</a></span>
                                                            </div>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <input name="chat_type" type="hidden" class="form-control" value="wchat">
                                                            <button name="submit" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
                                                            <button class="btn btn-default" type="reset">Reset</button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="tab-pane" id="zechat">
                                                    <div class="form-group">
                                                        <h4>Zechat</h4>
                                                    </div>
                                                    <form action="#zechat" name="form2" class="form form-horizontal" method="post">
                                                        <?php
                                                        if(isset($config['zechat_purchase_code']) && $config['zechat_purchase_code'] != ""){
                                                            ?>
                                                            <div class="form-group bt-switch">
                                                                <label class="col-sm-4 control-label">Zechat on/off:</label>
                                                                <div class="col-sm-6">
                                                                    <label class="css-input switch switch-success">
                                                                        <input  name="zechat_on_off" type="checkbox" <?php if (get_option("zechat_on_off") == 'on') { echo "checked"; } ?> /><span></span>
                                                                    </label>

                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>

                                                        <div class="form-group">
                                                            <label class="col-sm-4 control-label">Zechat Purchase Code:</label>
                                                            <div class="col-sm-6">
                                                                <?php
                                                                if(isset($config['zechat_purchase_code']) && $config['zechat_purchase_code'] != ""){
                                                                    ?>
                                                                    <div class="alert alert-success">
                                                                        <strong>Success!</strong> Zechat Purchase code verified, you can on/off</div>
                                                                <?php
                                                                }
                                                                ?>
                                                                <input name="zechat_purchase_code" type="password" class="form-control" value="">
                                                                <span class="font-14"><code style="color: green">Get Purchase code From Here.</code><a href="https://codecanyon.net/item/facebook-style-php-ajax-chat-zechat/16491266?clickthrough_id=16491266&license=regular&open_purchase_for_item_id=16491266&purchasable=source&redirect_back=true&ref=bylancer&utm_source=item_desc_link" target="_blank">Buy Zechat</a></span>
                                                            </div>
                                                        </div>

                                                        <div class="panel-footer">
                                                            <input name="chat_type" type="hidden" class="form-control" value="zechat">
                                                            <button name="submit" type="submit" class="btn btn-primary btn-radius save-changes">Save</button>
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
    $(".save-changes").click(function(){
        $(".save-changes").addClass("bookme-progress");
    });

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
</body>

</html>