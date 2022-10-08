<?php
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

sec_session_start();
if (isset($_GET['action'])){
    if ($_GET['action'] == "write_rating") { write_rating(); }
    if ($_GET['action'] == "create_milestone") { create_milestone(); }
    if ($_GET['action'] == "release_milestone") { release_milestone(); }
    if ($_GET['action'] == "cancel_milestone") { cancel_milestone(); }
    if ($_GET['action'] == "request_release_milestone") { request_release_milestone(); }
    if ($_GET['action'] == "closeMyProject") { closeMyProject(); }
    if ($_GET['action'] == "email_contact_seller") { email_contact_seller(); }
    if ($_GET['action'] == "deleteMyAd") { deleteMyAd(); }
    if ($_GET['action'] == "deleteResume") { deleteResume(); }
    if ($_GET['action'] == "deleteExperience") { deleteExperience(); }
    if ($_GET['action'] == "deleteCompany") { deleteCompany(); }
    if ($_GET['action'] == "deleteResumitAd") { deleteResumitAd(); }
    if ($_GET['action'] == "openlocatoionPopup") { openlocatoionPopup(); }
    if ($_GET['action'] == "getlocHomemap") { getlocHomemap(); }
    if ($_GET['action'] == "searchCityFromCountry") {searchCityFromCountry();}
    if ($_GET['action'] == "submitBlogComment") {submitBlogComment();}
}

if(isset($_POST['action'])){
    if ($_POST['action'] == "accept_bid") { accept_bid(); }
    if ($_POST['action'] == "reject_bid_approval") { reject_bid_approval(); }
    if ($_POST['action'] == "removeImage") { removeImage(); }
    if ($_POST['action'] == "hideItem") { hideItem(); }
    if ($_POST['action'] == "removeAdImg") { removeAdImg(); }
    if ($_POST['action'] == "setFavAd") {setFavAd();}
    if ($_POST['action'] == "removeFavAd") {removeFavAd();}
    if ($_POST['action'] == "setFavUser") {setFavUser();}
    if ($_POST['action'] == "getsubcatbyidList") { getsubcatbyidList(); }
    if ($_POST['action'] == "getsubcatbyid") {getsubcatbyid();}
    if ($_POST['action'] == "getCustomFieldByCatID") {getCustomFieldByCatID();}
    if ($_POST['action'] == "getStateByCountryID") {getStateByCountryID();}
    if ($_POST['action'] == "getCityByStateID") {getCityByStateID();}
    if ($_POST['action'] == "getCityidByCityName") {getCityidByCityName();}
    if ($_POST['action'] == "ModelGetStateByCountryID") {ModelGetStateByCountryID();}
    if ($_POST['action'] == "ModelGetCityByStateID") {ModelGetCityByStateID();}
    if ($_POST['action'] == "searchStateCountry") {searchStateCountry();}
    if ($_POST['action'] == "searchCityStateCountry") {searchCityStateCountry();}
    if ($_POST['action'] == "ajaxlogin") {ajaxlogin();}
    if ($_POST['action'] == "email_verify") {email_verify();}
    if ($_POST['action'] == "quickad_ajax_home_search") {quickad_ajax_home_search();}
}

function ajaxlogin(){
    global $config,$lang;
    $loggedin = userlogin($_POST['username'], $_POST['password']);

    if(!is_array($loggedin))
    {
        echo __("Username or Password not found");
    }
    elseif($loggedin['status'] == 2)
    {
        echo __("This account has been banned");
    }
    else
    {
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of the user.
        $user_id = preg_replace("/[^0-9]+/", "", $loggedin['id']); // XSS protection as we might print this value
        $_SESSION['user']['id']  = $user_id;
        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $loggedin['username']); // XSS protection as we might print this value
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['login_string'] = hash('sha512', $loggedin['password'] . $user_browser);
        $_SESSION['user']['user_type'] = $loggedin['user_type'];
        update_lastactive();

        echo "success";
    }
    die();

}

function email_verify(){
    global $config,$lang;

    if(checkloggedin())
    {
        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm",$_SESSION['user']['id']);

        $respond = __("Sent");
        echo '<a class="button gray" href="javascript:void(0);">'.$respond.'</a>';
        die();

    }
    else
    {
        header("Location: ".$config['site_url']."login");
        exit;
    }
}

function write_rating(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }


    $rating = validate_input($_POST["rating"]);
    $message = validate_input($_POST["message"]);
    $project_id = validate_input($_POST['project_id']);
    $message = strtr($message, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />'));

    if(rating_exist($project_id)){
        $result['success'] = false;
        $result['message'] = __("Invalid rating call");
        die(json_encode($result));
    }


    if (empty($rating) or !is_numeric($rating)) {
        $result['success'] = false;
        $result['message'] = __("Rating Required");
        die(json_encode($result));
    }

    if(empty($message)) {
        $result['success'] = false;
        $result['message'] = __("Message is required");
        die(json_encode($result));
    }

    $project = ORM::for_table($config['db']['pre'].'project')
        ->select_many('freelancer_id','user_id')
        ->find_one($project_id);

    $freelancer_id = $project['freelancer_id'];
    $employer_id = $project['user_id'];
    $now = date("Y-m-d H:i:s");
    $save_reviews = ORM::for_table($config['db']['pre'].'reviews')->create();
    $save_reviews->project_id = $project_id;
    $save_reviews->employer_id = $employer_id;
    $save_reviews->freelancer_id = $freelancer_id;
    $save_reviews->rated_by = $_SESSION['user']['user_type'];
    $save_reviews->comments = $message;
    $save_reviews->rating = $rating;
    $save_reviews->created_at = $now;
    $save_reviews->save();

    // save review to database
    if ($save_reviews->id()) {

        if($_SESSION['user']['user_type'] == 'employer'){
            $on_budget = ($_POST["on_budget"] == 'yes')? 'yes' : 'no';
            $on_time = ($_POST["on_time"] == 'yes')? 'yes' : 'no';
            $recommendation = ($_POST["recommendation"] == 'yes')? 'yes' : 'no';

            $update_project = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'id' => $project_id,
                    'user_id' => $_SESSION['user']['id']
                ))
                ->find_one();
            $update_project->on_budget = $on_budget;
            $update_project->on_time = $on_time;
            $update_project->recommendation = $recommendation;
            $update_project->save();
        }

        /*EMAIL-11: GOT RATING ON PROJECT*/
        if($_SESSION['user']['user_type'] == 'employer'){
            $name = $_SESSION['user']['username'];
            $user_id = $freelancer_id;
        }else{
            $user_id = $employer_id;
        }

        $userdata = get_user_data(null,$user_id);
        $user_email = $userdata['email'];
        $user_fullname = $userdata['name'];

        $info = ORM::for_table($config['db']['pre'] . 'project')
            ->select('product_name')
            ->where('id', $project_id)
            ->find_one();
        $project_title = $info['product_name'];

        $html = $config['email_sub_got_rating'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
        $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
        $html = str_replace ('{RATING}', $rating, $html);
        $html = str_replace ('{RATING_COMMENT}', $message, $html);
        $email_subject = $html;

        $html = $config['emailHTML_got_rating'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
        $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
        $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
        $html = str_replace ('{RATING}', $rating, $html);
        $html = str_replace ('{RATING_COMMENT}', $message, $html);
        $email_body = $html;

        email($user_email,$user_fullname,$email_subject,$email_body);
        /*EMAIL-11: GOT RATING ON PROJECT*/

        $result['success'] = true;
        $result['message'] = __("Thank you for rating");
    } else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }

    die(json_encode($result));
}

function create_milestone(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if (empty($_POST["title"])) {
        $result['success'] = false;
        $result['message'] = __("Milestone title required.");
        die(json_encode($result));
    }

    if (empty($_POST["amount"])) {
        $result['success'] = false;
        $result['message'] = __("Milestone amount required.");
        die(json_encode($result));
    }

    if(!is_numeric($_POST["amount"])) {
        $result['success'] = false;
        $result['message'] = __("Amount, must be numeric.");
        die(json_encode($result));
    }

    if(isset($_POST['id']))
    {
        $project_id = (int) validate_input($_POST['id']);

        if($_SESSION['user']['user_type'] == 'employer'){
            $project = ORM::for_table($config['db']['pre'] . 'project')
                ->select_many('product_name','user_id','freelancer_id')
                ->where('id', $project_id)
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one();
        }else{
            $project = ORM::for_table($config['db']['pre'] . 'project')
                ->select_many('product_name','user_id','freelancer_id')
                ->where('id', $project_id)
                ->where('freelancer_id', $_SESSION['user']['id'])
                ->find_one();
        }
        if(!empty($project)){
            $project_title = $project['product_name'];
            $employer_id = $project['user_id'];
            $freelancer_id = $project['freelancer_id'];
        }else {
            $result['success'] = false;
            $result['message'] = __("Project does not belong to you");
            die(json_encode($result));
        }
        // Get membership details
        $group_info = get_user_membership_settings();

        if($_SESSION['user']['user_type'] == 'employer'){
            $user_data = get_user_data(null,$_SESSION['user']['id']);
            $employer_balance = $user_data['balance'];
            $employer_name = $user_data['name'];
            $amount = validate_input($_POST['amount']);
            $employer_commission = $group_info['employer_commission'];

            if($employer_commission != 0){
                $comission = (($amount/100)*$employer_commission);
            }else{
                $comission = 0;
            }
            $t_amount = $_POST['amount'] + $comission;
            if($employer_balance < $t_amount)
            {
                $result['success'] = false;
                $result['message'] = __("Wallet balance must be grater than").' '.$config['currency_sign'].$t_amount.'.';
                die(json_encode($result));
            }
            else
            {
                $deducted = $employer_balance - $_POST['amount'];
                //Minus From Employer Account

                $now = date("Y-m-d H:i:s");
                $user_update = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
                $user_update->set('balance', $deducted);
                $user_update->save();

                if($employer_commission != 0){
                    $comission = (($amount/100)*$employer_commission);
                    minus_balance($employer_id,$comission);
                }

                if($comission > 0){
                    //Update Amount in Admin balance table
                    $balance = ORM::for_table($config['db']['pre'].'balance')->find_one(1);
                    $current_amount=$balance['current_balance'];
                    $total_earning=$balance['total_earning'];

                    $updated_amount=($comission+$current_amount);
                    $total_earning=($comission+$total_earning);

                    $balance->current_balance = $updated_amount;
                    $balance->total_earning = $total_earning;
                    $balance->save();
                }

                $now = date("Y-m-d H:i:s");
                $milestone_title = validate_input($_POST['title']);
                $milestone_amount = validate_input($_POST['amount']);
                $create_milestone = ORM::for_table($config['db']['pre'].'milestone')->create();
                $create_milestone->title = $milestone_title;
                $create_milestone->amount = $milestone_amount;
                $create_milestone->status = 'funded';
                $create_milestone->created_by = $_SESSION['user']['user_type'];
                $create_milestone->project_id = $project_id;
                $create_milestone->employer_id = $employer_id;
                $create_milestone->freelancer_id = $freelancer_id;
                $create_milestone->start_date = $now;
                $create_milestone->save();

                $milestone_id = $create_milestone->id();

                $SenderName = ucfirst($employer_name);
                $SenderId = $_SESSION['user']['id'];
                $OwnerName = '';
                $OwnerId = $freelancer_id;
                $productId = $project_id;
                $productTitle = $project_title;
                $type = 'milestone_created';
                $message = $milestone_amount." ".$config['currency_code'];
                add_firebase_notification($SenderName,$SenderId,$OwnerName,$OwnerId,$productId,$productTitle,$type,$message);


                $ip = encode_ip($_SERVER, $_ENV);
                $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
                $trans_insert->product_name = $productTitle;
                $trans_insert->product_id = $project_id;
                $trans_insert->seller_id = $SenderId;
                $trans_insert->status = 'success';
                $trans_insert->amount = $milestone_amount;
                $trans_insert->transaction_gatway = 'Wallet';
                $trans_insert->transaction_ip = $ip;
                $trans_insert->transaction_time = time();
                $trans_insert->transaction_description = 'milestone_created';
                $trans_insert->transaction_method = 'milestone_created';
                $trans_insert->save();

                /*EMAIL-13: Freelancer : Milestone Created*/
                $html = $config['email_sub_milestone_created'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
                $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
                $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
                $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
                $email_subject = $html;

                $html = $config['emailHTML_milestone_created'];
                $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
                $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
                $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
                $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
                $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
                $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
                $email_body = $html;

                //Get Freelancer Data
                $info = ORM::for_table($config['db']['pre'] . 'user')
                    ->select_many('email','name')
                    ->where('id', $freelancer_id)
                    ->findOne();

                if(!empty($info)){
                    $freelancer_email = $info['email'];
                    $freelancer_name = $info['name'];
                }

                email($freelancer_email,$freelancer_name,$email_subject,$email_body);
                /*EMAIL-13: Freelancer : Milestone Created*/


                $result['success'] = true;
                $result['message'] = __("The money has been moved into an escrow payment");
            }
        }
    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }

    die(json_encode($result));
}

function release_milestone(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if(isset($_POST['id']) && checkloggedin() && $_SESSION['user']['user_type'] == 'employer') {
        $milestone_id = $_POST['id'];
        $now = date("Y-m-d H:i:s");
        $milestone = ORM::for_table($config['db']['pre'] . 'milestone')
            ->where(array(
                'id'=> validate_input($milestone_id),
                'employer_id'=> validate_input($_SESSION['user']['id'])
            ))
            ->find_one();

        if (!empty($milestone)) {

            $milestone->set('status', 'paid');
            $milestone->set('request', '2');
            $milestone->set('end_date', $now);
            $milestone->save();

            $user_data = get_user_data(null,$_SESSION['user']['id']);
            $employer_balance = $user_data['balance'];
            $employer_name = $user_data['name'];

            $freelancer_id = $milestone['freelancer_id'];
            $employer_id = $milestone['employer_id'];
            $project_id = $milestone['project_id'];
            $amount = $milestone['amount'];
            $milestone_title = $milestone['title'];
            $milestone_amount = $milestone['amount'];

            $check_paid = ORM::for_table($config['db']['pre'] . 'milestone')
                ->select('amount')
                ->where(array(
                    'status'=> 'paid',
                    'project_id'=> $project_id,
                    'employer_id'=> validate_input($_SESSION['user']['id'])
                ))
                ->find_many();
            if (!empty($check_paid)) {
                $epaid = 0;
                foreach ($check_paid as $info) {
                    $epaid = $epaid + $info['amount'];
                }

                $bid = ORM::for_table($config['db']['pre'] . 'bids')
                    ->select('amount')
                    ->where(array(
                        'project_id'=> $project_id,
                        'user_id'=> $freelancer_id
                    ))
                    ->findOne();

                $project_amount = $bid['amount'];

                if($epaid >= $project_amount){
                    $update_project = ORM::for_table($config['db']['pre'].'project')
                        ->where(array(
                            'id' => $project_id,
                            'user_id' => $employer_id,
                            'freelancer_id' => $freelancer_id
                        ))
                        ->find_one();
                    $update_project->set('status', 'completed');
                    $update_project->save();
                }
            }


            /*$deducted = $employer_balance - $amount;
            $now = date("Y-m-d H:i:s");
            $user_update = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
            $user_update->set('balance', $deducted);
            $user_update->save();*/
            //Minus From Freelancer Account
            $group_info = get_user_membership_settings();
            $freelancer_commission = $group_info['freelancer_commission'];
            if($freelancer_commission != 0){
                $comission = (($amount/100)*$freelancer_commission);
                minus_balance($freelancer_id,$comission);
            }

            if($comission > 0){
                //Update Amount in Admin balance table
                $balance = ORM::for_table($config['db']['pre'].'balance')->find_one(1);
                $current_amount=$balance['current_balance'];
                $total_earning=$balance['total_earning'];

                $updated_amount=($comission+$current_amount);
                $total_earning=($comission+$total_earning);

                $balance->current_balance = $updated_amount;
                $balance->total_earning = $total_earning;
                $balance->save();
            }

            $update_balance = ORM::for_table($config['db']['pre'].'user')
                ->where('id' , $freelancer_id)
                ->find_one();
            $update_balance->set_expr('balance', 'balance+'.$amount);
            $update_balance->save();

            $project = ORM::for_table($config['db']['pre'].'project')
                ->select('product_name')
                ->where('id' , $project_id)
                ->find_one();
            $project_title = $project['product_name'];
            $ip = encode_ip($_SERVER, $_ENV);
            $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
            $trans_insert->product_name = $project_title;
            $trans_insert->product_id = $project_id;
            $trans_insert->seller_id = $employer_id;
            $trans_insert->status = 'success';
            $trans_insert->amount = $amount;
            $trans_insert->transaction_gatway = 'Wallet';
            $trans_insert->transaction_ip = $ip;
            $trans_insert->transaction_time = time();
            $trans_insert->transaction_description = "Milestone Released";
            $trans_insert->transaction_method = 'milestone_released';
            $trans_insert->save();

            $SenderName = ucfirst($employer_name);
            $SenderId = $_SESSION['user']['id'];
            $OwnerName = '';
            $OwnerId = $freelancer_id;
            $productId = $project_id;
            $productTitle = $project_title;
            $type = 'milestone_released';
            $message = $milestone_amount." ".$config['currency_code'];
            add_firebase_notification($SenderName,$SenderId,$OwnerName,$OwnerId,$productId,$productTitle,$type,$message);

            /*EMAIL-14: Freelancer : Milestone Release*/
            $html = $config['email_sub_milestone_released'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
            $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
            $email_subject = $html;

            $html = $config['emailHTML_milestone_released'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
            $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
            $email_body = $html;

            //Get Freelancer Data
            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select_many('email','name')
                ->where('id', $freelancer_id)
                ->findOne();

            if(!empty($info)){
                $freelancer_email = $info['email'];
                $freelancer_name = $info['name'];
            }

            email($freelancer_email,$freelancer_name,$email_subject,$email_body);
            /*EMAIL-14: Freelancer : Milestone Release*/

            $result['success'] = true;
            $result['message'] = __("The milestone fund has been realesed.");
        }else{
            $result['success'] = false;
            $result['message'] = __("Project does not belong to you");
        }
    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }
    die(json_encode($result));
}

function cancel_milestone(){
    global $config,$lang;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if(isset($_POST['id']) && checkloggedin() && $_SESSION['user']['user_type'] == 'user') {
        $milestone_id = validate_input($_POST['id']);
        $now = date("Y-m-d H:i:s");
        $milestone = ORM::for_table($config['db']['pre'] . 'milestone')
            ->where(array(
                'id'=> validate_input($milestone_id),
                'freelancer_id'=> validate_input($_SESSION['user']['id'])
            ))
            ->find_one();

        if (!empty($milestone)) {

            $milestone->set('status', 'cancel');
            $milestone->set('request', '2');
            $milestone->set('end_date', $now);
            $milestone->save();

            $freelancer_id = $milestone['freelancer_id'];
            $employer_id = $milestone['employer_id'];
            $project_id = $milestone['project_id'];
            $amount = $milestone['amount'];

            $employer = ORM::for_table($config['db']['pre'].'user')
                ->where('id' , $employer_id)
                ->find_one();
            $employer->set_expr('balance', 'balance+'.$amount);
            $employer->save();

            $project = ORM::for_table($config['db']['pre'].'project')
                ->select('product_name')
                ->where('id' , $project_id)
                ->find_one();

            $ip = encode_ip($_SERVER, $_ENV);
            $trans_insert = ORM::for_table($config['db']['pre'].'transaction')->create();
            $trans_insert->product_name = $project['product_name'];
            $trans_insert->product_id = $project_id;
            $trans_insert->seller_id = $employer_id;
            $trans_insert->status = 'success';
            $trans_insert->amount = $amount;
            $trans_insert->transaction_gatway = 'Wallet';
            $trans_insert->transaction_ip = $ip;
            $trans_insert->transaction_time = time();
            $trans_insert->transaction_description = "Milestone Calceled";
            $trans_insert->transaction_method = 'milestone_cancel';
            $trans_insert->save();

            $result['success'] = true;
            $result['message'] = __("The milestone fund has been canceled.");
        }else{
            $result['success'] = false;
            $result['message'] = __("Project does not belong to you");
        }
    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }
    die(json_encode($result));
}

function request_release_milestone(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if(isset($_POST['id']) && checkloggedin() && $_SESSION['user']['user_type'] == 'user') {
        $milestone_id = validate_input($_POST['id']);
        $now = date("Y-m-d H:i:s");
        $milestone = ORM::for_table($config['db']['pre'] . 'milestone')
            ->where(array(
                'id'=> validate_input($milestone_id),
                'freelancer_id'=> validate_input($_SESSION['user']['id'])
            ))
            ->find_one();

        if (!empty($milestone)) {
            $milestone->set('status', 'request');
            $milestone->set('request', '1');
            $milestone->set('end_date', $now);
            $milestone->save();

            $freelancer_id = $milestone['freelancer_id'];
            $employer_id = $milestone['employer_id'];
            $project_id = $milestone['project_id'];
            $milestone_amount = $milestone['amount'];
            $milestone_title = $milestone['title'];

            $employer = ORM::for_table($config['db']['pre'].'user')
                ->select_many('email','name')
                ->find_one($employer_id);
            $project = ORM::for_table($config['db']['pre'].'project')
                ->select('product_name')
                ->find_one($project_id);
            $project_title = $project['product_name'];
            $employer_name = ucfirst($employer['name']);
            $employer_email = $employer['email'];
            $SenderName = ucfirst($employer['name']);
            $SenderId = $_SESSION['user']['id'];
            $OwnerName = '';
            $OwnerId = $employer_id;
            $productId = $project_id;
            $productTitle = $project_title;
            $type = 'milestone_request_release';
            $message = $milestone_amount." ".$config['currency_code'];
            add_firebase_notification($SenderName,$SenderId,$OwnerName,$OwnerId,$productId,$productTitle,$type,$message);

            /*EMAIL-15: Employer : Milestone Request to Release*/
            $html = $config['email_sub_milestone_request_to_release'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
            $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
            $email_subject = $html;

            $html = $config['emailHTML_milestone_request_to_release'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{MILESTONE_TITLE}', $milestone_title, $html);
            $html = str_replace ('{MILESTONE_AMOUNT}', $milestone_amount, $html);
            $email_body = $html;

            email($employer_email,$employer_name,$email_subject,$email_body);
            /*EMAIL-15: Employer : Milestone Request to Release*/

            $result['success'] = true;
            $result['message'] = __("The milestone fund has been realesed.");
        }else{
            $result['success'] = false;
            $result['message'] = __("Project does not belong to you");
        }
    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }
    die(json_encode($result));
}

function accept_bid(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if(isset($_POST['id']))
    {
        $bid_id = validate_input($_POST['id']);
        $info = ORM::for_table($config['db']['pre'] . 'bids')
            ->where('id', validate_input($bid_id))
            ->find_one();

        if (!empty($info)) {
            $bid_amount = $info['amount'];
            $freelancer_id = $info['user_id'];
            $project_id = $info['project_id'];
        }
        if($_SESSION['user']['user_type'] == 'employer'){

            $info = ORM::for_table($config['db']['pre'] . 'project')
                ->select('product_name')
                ->where('id', $project_id)
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one();

            if(!empty($info)){
                $project_title = $info['product_name'];
            }else {
                $result['success'] = false;
                $result['message'] = __("Project does not belong to you");
            }

            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select('email')
                ->where('id', $freelancer_id)
                ->findOne();

            if(!empty($info)){
                $freelancer_email = $info['provider_email'];
                $freelancer_name = $info['name'];
            }

            $checkstamp=md5("Bylancer:".$freelancer_id.":".$project_id.":".time());

            $product = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'id' => $project_id,
                    'user_id' => $_SESSION['user']['id']
                ))
                ->find_one();
            $product->set('status', 'pending_for_approval');
            $product->set('freelancer_id', $freelancer_id);
            $product->set('checkstamp', $checkstamp);
            $product->save();

            /*EMAIL-10: Freelancer : Project Awarded*/
            $html = $config['email_sub_freelancer_project_awarded'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{FREELANCER_NAME}', $freelancer_name, $html);
            $email_subject = $html;

            $html = $config['emailHTML_freelancer_project_awarded'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{FREELANCER_NAME}', $freelancer_name, $html);
            $email_body = $html;

            email($freelancer_email,$freelancer_name,$email_subject,$email_body);
            /*EMAIL-10: Freelancer : Project Awarded*/

            $result['success'] = true;
            $result['message'] = __("Accepted successfully. Redirecting...");

        }
        else{
            $info = ORM::for_table($config['db']['pre'] . 'project')
                ->select_many('product_name','user_id')
                ->where('id', $project_id)
                ->where('freelancer_id', $_SESSION['user']['id'])
                ->find_one();

            if(!empty($info)){
                $project_title = $info['product_name'];
                $employer_id = $info['user_id'];
                $freelancer_id = $_SESSION['user']['id'];
            }else {
                $result['success'] = false;
                $result['message'] = __("Project does not belong to you");
            }
            //Get Freelancer Data
            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select_many('email','name')
                ->where('id', $freelancer_id)
                ->findOne();

            if(!empty($info)){
                $freelancer_email = $info['email'];
                $freelancer_name = $info['name'];
            }
            //Get Employer Data
            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select_many('email','name')
                ->where('id', $employer_id)
                ->findOne();

            if(!empty($info)){
                $employer_email = $info['email'];
                $employer_name = $info['name'];
            }

            /*if($config['freelancer_commission'] != 0){
                $comission = (($bid_amount/100)*$config['freelancer_commission']);
                minus_balance($freelancer_id,$comission);
            }
            if($config['employer_commission'] != 0){
                $comission = (($bid_amount/100)*$config['employer_commission']);
                minus_balance($employer_id,$comission);
            }

            if($comission > 0){
                //Update Amount in balance table
                $balance = ORM::for_table($config['db']['pre'].'balance')->find_one(1);
                $current_amount=$balance['current_balance'];
                $total_earning=$balance['total_earning'];

                $updated_amount=($comission+$current_amount);
                $total_earning=($comission+$total_earning);

                $balance->current_balance = $updated_amount;
                $balance->total_earning = $total_earning;
                $balance->save();
            }*/

            $product = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'id' => $project_id,
                    'freelancer_id' => $_SESSION['user']['id']
                ))
                ->find_one();
            $product->set('status', 'under_development');
            $product->save();

            /*EMAIL-12: Employer : Project Accepted By Freelancer*/
            $html = $config['email_sub_employer_project_accepted'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{EMPLOYER_NAME}', $employer_name, $html);
            $email_subject = $html;

            $html = $config['emailHTML_employer_project_accepted'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{EMPLOYER_NAME}', $employer_name, $html);
            $email_body = $html;

            email($employer_email,$employer_name,$email_subject,$email_body);
            /*EMAIL-12: Employer : Project Accepted By Freelancer*/

            $result['success'] = true;
            $result['message'] = __("Accepted successfully. Redirecting...");
        }

    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }
    die(json_encode($result));
}

function reject_bid_approval(){
    global $config,$lang,$link;

    if(!checkloggedin()) {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
        die(json_encode($result));
    }

    if(isset($_POST['id']))
    {
        $bid_id = validate_input($_POST['id']);
        $info = ORM::for_table($config['db']['pre'] . 'bids')
            ->where('id', validate_input($bid_id))
            ->find_one();

        if (!empty($info)) {
            $bid_amount = $info['amount'];
            $freelancer_id = $info['user_id'];
            $project_id = $info['project_id'];
        }
        if($_SESSION['user']['user_type'] == 'employer'){

            $info = ORM::for_table($config['db']['pre'] . 'project')
                ->select('product_name')
                ->where('id', $project_id)
                ->where('user_id', $_SESSION['user']['id'])
                ->find_one();

            if(!empty($info)){
                $project_title = $info['product_name'];
            }else {
                $result['success'] = false;
                $result['message'] = __("Project does not belong to you");
            }

            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select('email')
                ->where('id', $freelancer_id)
                ->findOne();

            if(!empty($info)){
                $freelancer_email = $info['provider_email'];
                $freelancer_name = $info['name'];
            }

            $product = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'id' => $project_id,
                    'user_id' => $_SESSION['user']['id']
                ))
                ->find_one();
            $product->set('status', 'open');
            $product->set('freelancer_id', null);
            $product->set('checkstamp', '');
            $product->save();

            /*EMAIL-10: Freelancer : Project Revoked*/
            $html = $config['email_sub_freelancer_project_revoke'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{FREELANCER_NAME}', $freelancer_name, $html);
            $email_subject = $html;

            $html = $config['emailHTML_freelancer_project_revoke'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{FREELANCER_NAME}', $freelancer_name, $html);
            $email_body = $html;

            email($freelancer_email,$freelancer_name,$email_subject,$email_body);
            /*EMAIL-10: Freelancer : Project Awarded*/

            $result['success'] = true;
            $result['message'] = __("Offer rejected. Redirecting...");

        }
        else{
            $info = ORM::for_table($config['db']['pre'] . 'project')
                ->select_many('product_name','user_id')
                ->where('id', $project_id)
                ->where('freelancer_id', $_SESSION['user']['id'])
                ->find_one();

            if(!empty($info)){
                $project_title = $info['product_name'];
                $employer_id = $info['user_id'];
                $freelancer_id = $_SESSION['user']['id'];
            }else {
                $result['success'] = false;
                $result['message'] = __("Project does not belong to you");
            }
            //Get Freelancer Data
            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select_many('email','name')
                ->where('id', $freelancer_id)
                ->findOne();

            if(!empty($info)){
                $freelancer_email = $info['email'];
                $freelancer_name = $info['name'];
            }
            //Get Employer Data
            $info = ORM::for_table($config['db']['pre'] . 'user')
                ->select_many('email','name')
                ->where('id', $employer_id)
                ->findOne();

            if(!empty($info)){
                $employer_email = $info['email'];
                $employer_name = $info['name'];
            }

            /*if($config['freelancer_commission'] != 0){
                $comission = (($bid_amount/100)*$config['freelancer_commission']);
                minus_balance($freelancer_id,$comission);
            }
            if($config['employer_commission'] != 0){
                $comission = (($bid_amount/100)*$config['employer_commission']);
                minus_balance($employer_id,$comission);
            }

            if($comission > 0){
                //Update Amount in balance table
                $balance = ORM::for_table($config['db']['pre'].'balance')->find_one(1);
                $current_amount=$balance['current_balance'];
                $total_earning=$balance['total_earning'];

                $updated_amount=($comission+$current_amount);
                $total_earning=($comission+$total_earning);

                $balance->current_balance = $updated_amount;
                $balance->total_earning = $total_earning;
                $balance->save();
            }*/

            $product = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'id' => $project_id,
                    'freelancer_id' => $_SESSION['user']['id']
                ))
                ->find_one();
            $product->set('status', 'open');
            $product->set('freelancer_id', null);
            $product->set('checkstamp', '');
            $product->save();

            /*EMAIL-12: Employer : Project approval rejected By Freelancer*/
            $html = $config['email_sub_employer_project_approval_reject'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{EMPLOYER_NAME}', $employer_name, $html);
            $email_subject = $html;

            $html = $config['emailHTML_employer_project_approval_reject'];
            $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
            $html = str_replace ('{SITE_URL}', $config['site_url'], $html);
            $html = str_replace ('{PROJECT_TITLE}', $project_title, $html);
            $html = str_replace ('{PROJECT_LINK}', $link['PROJECT']."/".$project_id, $html);
            $html = str_replace ('{EMPLOYER_NAME}', $employer_name, $html);
            $email_body = $html;

            email($employer_email,$employer_name,$email_subject,$email_body);
            /*EMAIL-12: Employer : Project Accepted By Freelancer*/

            $result['success'] = true;
            $result['message'] = __("Offer rejected. Redirecting...");
        }

    }else {
        $result['success'] = false;
        $result['message'] = __("Error: Please try again.");
    }
    die(json_encode($result));
}

function closeMyProject(){
    global $config;

    if(!checkloggedin()) {
        echo 0;
        die();
    }

    if(isset($_POST['id']))
    {
        $product = ORM::for_table($config['db']['pre'].'project')
            ->where(array(
                'id' => validate_input($_POST['id']),
                'user_id' => 1
            ))
            ->find_one();
        $product->set('status', 'closed');
        $product->save();

        echo 1;
        die();
    }else {
        echo 0;
        die();
    }
}

function removeImage(){
    global $config;
    if(isset($_POST['product_id'])){
        $id = validate_input($_POST['product_id']);
        $info = ORM::for_table($config['db']['pre'].'product')->select('screen_shot')->find_one($_POST['product_id']);

        $screnshots = explode(',',$info['screen_shot']);
        if($key = array_search($_POST['imagename'],$screnshots) != -1){
            unset($screnshots[$key]);
            $screens = implode(',',$screnshots);
            $product = ORM::for_table($config['db']['pre'].'product')->find_one($id);
            $product->screen_shot = $screens;
            $product->save();
        }
    }

}

function email_contact_seller(){
    global $config,$lang,$link;
    if (isset($_POST['sendemail'])) {

        $item_id = validate_input($_POST['id']);
        $iteminfo = get_item_by_id($item_id);

        $item_title = $iteminfo['title'];
        $item_author_name = $iteminfo['author_name'];
        $item_author_email = $iteminfo['author_email'];

        $ad_link = $link['POST-DETAIL']."/".$item_id;

        $html = $config['email_sub_contact_seller'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{ADTITLE}', $item_title, $html);
        $html = str_replace ('{ADLINK}', $ad_link, $html);
        $html = str_replace ('{SELLER_NAME}', $item_author_name, $html);
        $html = str_replace ('{SELLER_EMAIL}', $item_author_email, $html);
        $html = str_replace ('{SENDER_NAME}', $_POST['name'], $html);
        $html = str_replace ('{SENDER_EMAIL}', $_POST['email'], $html);
        $html = str_replace ('{SENDER_PHONE}', $_POST['phone'], $html);
        $email_subject = $html;

        $html = $config['email_message_contact_seller'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{ADTITLE}', $item_title, $html);
        $html = str_replace ('{ADLINK}', $ad_link, $html);
        $html = str_replace ('{SELLER_NAME}', $item_author_name, $html);
        $html = str_replace ('{SELLER_EMAIL}', $item_author_email, $html);
        $html = str_replace ('{SENDER_NAME}', $_POST['name'], $html);
        $html = str_replace ('{SENDER_EMAIL}', $_POST['email'], $html);
        $html = str_replace ('{SENDER_PHONE}', $_POST['phone'], $html);
        $html = str_replace ('{MESSAGE}', $_POST['message'], $html);
        $email_body = $html;

        email($item_author_email,$item_author_name,$email_subject,$email_body);

        echo 'success';
        die();
    }else{
        echo 0;
        die();
    }
}

function getStateByCountryID()
{
    global $config;
    $country_code = isset($_POST['id']) ? $_POST['id'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $rows = ORM::for_table($config['db']['pre'].'subadmin1')
        ->select_many('id','code','name')
        ->where(array(
            'country_code' => $country_code,
            'active' => 1
        ))
        ->order_by_desc('name')
        ->find_many();

    if (count($rows) > 0) {

        $list = '<option value="">Select State</option>';
        foreach ($rows as $info) {
            $name = $info['name'];
            $state_id = $info['id'];
            $state_code = $info['code'];
            if($selectid == $state_code){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            $list .= '<option value="'.$state_code.'" '.$selected_text.'>'.$name.'</option>';
        }

        echo $list;
    }
}

function getCityByStateID()
{
    global $config;
    $state_id = isset($_POST['id']) ? $_POST['id'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $rows = ORM::for_table($config['db']['pre'].'cities')
        ->select_many('id','name')
        ->where(array(
            'subadmin1_code' => $state_id,
            'active' => 1
        ))
        ->find_many();

    if (count($rows) > 0) {

        $list = '<option value="">Select City</option>';
        foreach ($rows as $info) {
            $name = $info['name'];
            $id = $info['id'];
            if($selectid == $id){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            $list .= '<option value="'.$id.'" '.$selected_text.'>'.$name.'</option>';
        }
        echo $list;
    }
}

function getCityidByCityName()
{
    global $config;
    $country_code = isset($_POST['country']) ? $_POST['country'] : "";
    $state = isset($_POST['state']) ? $_POST['state'] : "";
    $city_name = isset($_POST['city']) ? $_POST['city'] : "";

    $info = ORM::for_table($config['db']['pre'].'subadmin1')
        ->select('code')
        ->where('active', '1')
        ->where_raw('(`name` = ? OR `asciiname` = ?)', array($state, $state))
        ->find_one();

    $state_code = $info['code'];

    $info2 = ORM::for_table($config['db']['pre'].'cities')
        ->select('id')
        ->where(array(
            'subadmin1_code' => $state_code,
            'country_code' => $country_code,
            'active' => 1
        ))
        ->where_raw('(`name` = ? OR `asciiname` = ?)', array($city_name, $city_name))
        ->find_one();
    if ($info2['id']) {
        echo $id = $info2['id'];
    }


    die();
}

function ModelGetStateByCountryID()
{
    global $config,$lang;
    $country_code = isset($_POST['id']) ? $_POST['id'] : 0;
    $countryName = get_countryName_by_code($country_code);

    $result = ORM::for_table($config['db']['pre'].'subadmin1')
        ->select_many('id','code','asciiname')
        ->where(array(
            'country_code' => $country_code,
            'active' => 1
        ))
        ->order_by_desc('asciiname')
        ->find_many();


    $list = '<ul class="column col-md-12 col-sm-12 cities">';
    $count = 1;
    if (count($result) > 0) {
        foreach ($result as $row) {
            $name = $row['asciiname'];
            $id = $row['code'];

            if($count == 1)
            {
                $list .=  '<li class="selected"><a href="#" class="selectme" data-id="'.$country_code.'" data-name="'.__("All").' '.$countryName.'" data-type="country"><strong>'.__("All").' '.$countryName.'</strong></a></li>';
            }
            $list .= '<li class=""><a href="#" id="region'.$id.'" class="statedata" data-id="'.$id.'" data-name="'.$name.'"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';

            $count++;
        }
        echo $list."</ul>";
    }
}

function ModelGetCityByStateID()
{
    global $config,$lang;
    $state_id = isset($_POST['id']) ? $_POST['id'] : '0';
    $stateName = get_stateName_by_id($state_id);
    //$state_code = substr($state_id,3);
    $country_code = substr($state_id,0,2);

    $result = ORM::for_table($config['db']['pre'].'cities')
        ->select_many('id','asciiname')
        ->where(array(
            'subadmin1_code' => $state_id,
            'country_code' => $country_code,
            'active' => 1
        ))
        ->order_by_asc('asciiname')
        ->find_many();

    //echo ORM::get_last_query();

    if($result){
        $total = count($result);
        $list = '<ul class="column col-md-12 col-sm-12 cities">';
        $count = 1;
        if ($total > 0) {
            foreach ($result as $row) {
                $name = $row['asciiname'];
                $id = $row['id'];
                if($count == 1)
                {
                    $list .=  '<li class="selected"><a href="#" id="changeState"><strong><i class="fa fa-angle-left"></i> '.__("Change Region").'</strong></a></li>';
                    $list .=  '<li class="selected"><a href="#" class="selectme" data-id="'.$state_id.'" data-name="'.$stateName.', '.__("Region").'" data-type="state"><strong>'.__("Whole").' '.$stateName.'</strong></a></li>';
                }

                $list .= '<li class=""><a href="#" id="region'.$id.'" class="selectme" data-id="'.$id.'" data-name="'.$name.', '.__("City").'" data-type="city"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';
                $count++;
            }

            echo $list."</ul>";
        }

    }else{
        echo '<ul class="column col-md-12 col-sm-12 cities">
            <li class="selected"><a href="#" id="changeState"><strong><i class="fa fa-arrow-left"></i>'.__("Change Region").'</strong></a></li>
            <li><a> '.__("No City Available").'</a></li>
            </ul>';
    }

}

function searchCityFromCountry()
{
    global $config;
    $dataString = isset($_GET['q']) ? $_GET['q'] : "";
    $sortname = check_user_country();

    $perPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : "1";
    $start = ($page-1)*$perPage;
    if($start < 0) $start = 0;

    $total = ORM::for_table($config['db']['pre'].'cities')
        ->where(array(
            'country_code' => 'sortname',
            'active' => 1
        ))
        ->where_like('asciiname', ''.$dataString.'%')
        ->count();

    $sql = "SELECT c.id, c.asciiname, c.latitude, c.longitude, c.subadmin1_code, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."subadmin1` AS s ON s.code = c.subadmin1_code and s.active = 1
 WHERE (c.name like '%$dataString%' or c.asciiname like '%$dataString%') and c.country_code = '$sortname' and c.active = 1
 ORDER BY
  CASE
    WHEN c.name = '$dataString' THEN 1
    WHEN c.name LIKE '$dataString%' THEN 2
    ELSE 3
  END ";
    $query =  $sql . " limit " . $start . "," . $perPage;
    $pdo = ORM::get_db();
    $rows = $pdo->query($query);
    if(empty($_GET["rowcount"])) {
        $pdo = ORM::get_db();
        $result = $pdo->query($sql);
        $_GET["rowcount"] = $rowcount = $result->rowCount();
    }

    $pages  = ceil($_GET["rowcount"]/$perPage);

    $items = '';
    $i = 0;
    $MyCity = array();

    foreach ($rows as $row) {
        $cityid = $row['id'];
        $cityname = $row['asciiname'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];
        $statename = $row['statename'];

        $MyCity[$i]["id"]   = $cityid;
        $MyCity[$i]["text"] = $cityname.", ".$statename;
        $MyCity[$i]["latitude"]   = $latitude;
        $MyCity[$i]["longitude"]   = $longitude;
        $i++;
    }

    echo $json = '{"items" : '.json_encode($MyCity, JSON_UNESCAPED_SLASHES).',"totalEntries" : '.$total.'}';
    die();
}

function searchStateCountry()
{
    global $config,$lang;
    $dataString = isset($_POST['dataString']) ? $_POST['dataString'] : "";
    $sortname = check_user_country();
    $query = "SELECT c.id, c.asciiname, c.subadmin1_code, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."subadmin1` AS s ON s.code = c.subadmin1_code and s.active = 1
 WHERE (c.name like '%$dataString%' or c.asciiname like '%$dataString%') and c.country_code = '$sortname' and c.active = 1
 ORDER BY
  CASE
    WHEN c.name = '$dataString' THEN 1
    WHEN c.name LIKE '$dataString%' THEN 2
    WHEN c.name LIKE '%$dataString' THEN 4
    ELSE 3
  END
 LIMIT 20";

    $pdo = ORM::get_db();
    $result = $pdo->query($query);
    $total = $result->rowCount();
    $list = '<ul class="searchResgeo"><li><a href="#" class="title selectme" data-id="" data-name="" data-type="">'.__("Any City").'</span></a></li>';
    if ($total > 0) {
        foreach ($result as $row) {
            $cityid = $row['id'];
            $cityname = $row['asciiname'];
            $stateid = $row['subadmin1_code'];
            $statename = $row['statename'];

            $list .= '<li><a href="#" class="title selectme" data-id="'.$cityid.'" data-name="'.$cityname.'" data-type="city">'.$cityname.', <span class="color-9">'.$statename.'</span></a></li>';
        }
        $list .= '</ul>';
        echo $list;
    }
    else{
        echo '<ul class="searchResgeo"><li><span class="noresult">'.__("No result found.").'</span></li>';
    }
}

function searchCityStateCountry()
{
    global $config,$lang;
    $dataString = isset($_POST['dataString']) ? $_POST['dataString'] : "";
    $sortname = check_user_country();

    $query = "SELECT c.id, c.asciiname, c.subadmin1_code, s.name AS statename
FROM `".$config['db']['pre']."cities` AS c
INNER JOIN `".$config['db']['pre']."subadmin1` AS s ON s.code = c.subadmin1_code and s.active = 1
 WHERE c.name like '%$dataString%' and c.country_code = '$sortname' and c.active = 1
 ORDER BY
  CASE
    WHEN c.name = '$dataString' THEN 1
    WHEN c.name LIKE '$dataString%' THEN 2
    WHEN c.name LIKE '%$dataString' THEN 4
    ELSE 3
  END
 LIMIT 20";
    $pdo = ORM::get_db();
    $result = $pdo->query($query);
    $total = count($result);
    $list = '<ul class="searchResgeo">';
    if ($total > 0) {
        foreach ($result as $row) {
            $cityid = $row['id'];
            $cityname = $row['asciiname'];
            $stateid = $row['subadmin1_code'];
            $countryid = $sortname;
            $statename = $row['statename'];

            $list .= '<li><a href="#" class="title selectme" data-cityid="'.$cityid.'" data-stateid="'.$stateid.'"data-countryid="'.$countryid.'" data-name="'.$cityname.', '.$statename.'">'.$cityname.', <span class="color-9">'.$statename.'</span></a></li>';
        }
        $list .= '</ul>';
        echo $list;
    }
    else{
        echo '<ul class="searchResgeo"><li><span class="noresult">'.__("No result found.").'</span></li>';
    }
}

function hideItem()
{
    global $config;
    $id = $_POST['id'];
    if (trim($id) != '') {
        $info = ORM::for_table($config['db']['pre'].'product')
            ->select('hide')
            ->find_one($id);
        $status = $info['hide'];
        $pdo = ORM::get_db();
        if($status == "0"){
            $query = "UPDATE `".$config['db']['pre']."product` set hide='1' WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['user']['id']."' ";
            $query_result = $pdo->query($query);
            echo 1;
        }else{
            $query = "UPDATE `".$config['db']['pre']."product` set hide='0' WHERE `id` = '".$id."' and `user_id` = '".$_SESSION['user']['id']."' ";
            $query_result = $pdo->query($query);
            echo 2;
        }
        die();
    } else {
        echo 0;
        die();
    }

}

function removeAdImg(){
    global $config;
    $id = $_POST['id'];
    $img = $_POST['img'];

    $info = ORM::for_table($config['db']['pre'].'product')->select('screen_shot')->find_one($id);

    if (!empty($info)) {
        $screen = "";
        $uploaddir =  "storage/products/";
        $screen_sm = explode(',',$info['screen_shot']);
        $count = 0;
        foreach ($screen_sm as $value)
        {
            $value = trim($value);

            if($value == $img){
                //Delete Image From Storage ----
                $filename1 = $uploaddir.$value;
                if(file_exists($filename1)){
                    $filename1 = $uploaddir.$value;
                    $filename2 = $uploaddir."small_".$value;
                    unlink($filename1);
                    unlink($filename2);
                }
            }
            else{
                if($count == 0){
                    $screen .= $value;
                }else{
                    $screen .= ",".$value;
                }
                $count++;
            }
        }
        $product = ORM::for_table($config['db']['pre'].'product')->find_one($id);
        $product->screen_shot = $screen;
        $product->save();

        echo 1;
        die();
    }
    else{
        echo 0;
        die();
    }
}

function setFavUser(){
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'fav_users')
        ->where(array(
            'user_id' => $_POST['userId'],
            'fav_user_id' => $_POST['id']
        ))
        ->count();

    if ($num_rows == 0) {
        $insert_favads = ORM::for_table($config['db']['pre'].'fav_users')->create();
        $insert_favads->user_id = $_POST['userId'];
        $insert_favads->fav_user_id = $_POST['id'];
        $insert_favads->save();

        if ($insert_favads->id())
            echo 1;
        else
            echo 0;
    }
    else{
        $result = ORM::for_table($config['db']['pre'].'fav_users')
            ->where(array(
                'user_id' => $_POST['userId'],
                'fav_user_id' => $_POST['id'],
            ))
            ->delete_many();
        if ($result)
            echo 2;
        else
            echo 0;
    }
    die();
}

function setFavAd()
{
    global $config;
    $num_rows = ORM::for_table($config['db']['pre'].'favads')
        ->where(array(
            'user_id' => $_POST['userId'],
            'product_id' => $_POST['id']
        ))
        ->count();

    if ($num_rows == 0) {
        $insert_favads = ORM::for_table($config['db']['pre'].'favads')->create();
        $insert_favads->user_id = $_POST['userId'];
        $insert_favads->product_id = $_POST['id'];
        $insert_favads->save();

        if ($insert_favads->id())
            echo 1;
        else
            echo 0;
    }
    else{
        $result = ORM::for_table($config['db']['pre'].'favads')
            ->where(array(
                'user_id' => $_POST['userId'],
                'product_id' => $_POST['id'],
            ))
            ->delete_many();
        if ($result)
            echo 2;
        else
            echo 0;
    }
    die();
}

function removeFavAd()
{
    global $config;
    $result = ORM::for_table($config['db']['pre'].'favads')
        ->where(array(
            'user_id' => $_POST['userId'],
            'product_id' => $_POST['id'],
        ))
        ->delete_many();

    if ($result)
        echo 1;
    else
        echo 0;

    die();
}

function deleteMyAd()
{
    global $config;
    if(isset($_POST['id']))
    {

        $info = ORM::for_table($config['db']['pre'].'product')
            ->select('screen_shot')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->find_one();

        if (!empty($info)) {
            $file = dirname(__DIR__) . "/storage/products/" . $info['screen_shot'];
            if (file_exists($file))
                unlink($file);

        }

        ORM::for_table($config['db']['pre'].'product')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();
        
        echo 1;
        die();
    }else {
        echo 0;
        die();
    }

}

function deleteResume()
{
    global $config;
    if(isset($_POST['id']))
    {
        $row = ORM::for_table($config['db']['pre'].'resumes')
            ->select('filename')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->find_one();

        if (!empty($row)) {
            $file = dirname(__DIR__) . "/storage/resumes/" . $row['filename'];
            if (file_exists($file))
                unlink($file);
        }

        ORM::for_table($config['db']['pre'].'resumes')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();


        echo 1;
        die();
    }else {
        echo 0;
        die();
    }
}

function deleteExperience(){
    global $config;
    if(isset($_POST['id']))
    {
        ORM::for_table($config['db']['pre'].'experiences')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();

        echo 1;
        die();
    }else {
        echo 0;
        die();
    }
}

function deleteCompany(){
    global $config;
    if(isset($_POST['id']))
    {
        $row = ORM::for_table($config['db']['pre'].'companies')
            ->select('logo')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->find_one();

        // delete logo
        if (!empty($row)) {
            $file = dirname(__DIR__) . "/storage/products/" . $row['filename'];
            if (file_exists($file))
                unlink($file);
        }

        // delete jobs
        ORM::for_table($config['db']['pre'].'product')
        ->where(array(
            'company_id' => $_POST['id'],
            'user_id' => $_SESSION['user']['id'],
        ))
        ->delete_many();

        ORM::for_table($config['db']['pre'].'product_resubmit')
        ->where(array(
            'company_id' => $_POST['id'],
            'user_id' => $_SESSION['user']['id'],
        ))
        ->delete_many();

        ORM::for_table($config['db']['pre'].'companies')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();


        echo 1;
        die();
    }else {
        echo 0;
        die();
    }
}

function deleteResumitAd()
{
    global $config;
    if(isset($_POST['id']))
    {
        $info = ORM::for_table($config['db']['pre'].'product')
            ->select('screen_shot')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->find_one();

        $info1 = ORM::for_table($config['db']['pre'].'product_resubmit')
            ->select('screen_shot')
            ->where(array(
                'id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->find_one();

        if (!empty($info)) {
            if ($info1['screen_shot'] != $info['screen_shot']) {
                $file = dirname(__DIR__) . "/storage/products/" . $info1['screen_shot'];
                if (file_exists($file))
                    unlink($file);
            }
        }

        ORM::for_table($config['db']['pre'].'product_resubmit')
            ->where(array(
                'product_id' => $_POST['id'],
                'user_id' => $_SESSION['user']['id'],
            ))
            ->delete_many();

        echo 1;
        die();
    }else {
        echo 0;
        die();
    }

}

function getsubcatbyid()
{
    global $config;
    $id = isset($_POST['catid']) ? $_POST['catid'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $rows = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where('main_cat_id',$id)
        ->find_many();

    if (count($rows) > 0) {

        foreach ($rows as $info) {
            $name = $info['sub_cat_name'];
            $sub_id = $info['sub_cat_id'];
            $photo_show = $info['photo_show'];
            $price_show = $info['price_show'];
            if($selectid == $sub_id){
                $selected_text = "selected";
            }
            else{
                $selected_text = "";
            }
            echo '<option value="'.$sub_id.'" data-photo-show="'.$photo_show.'" data-price-show="'.$price_show.'" '.$selected_text.'>'.$name.'</option>';
        }
    }else{
        echo 0;
    }
    die();
}

function getsubcatbyidList()
{
    global $config;
    $id = isset($_POST['catid']) ? $_POST['catid'] : 0;
    $selectid = isset($_POST['selectid']) ? $_POST['selectid'] : "";

    $rows = ORM::for_table($config['db']['pre'].'catagory_sub')
        ->where('main_cat_id',$id)
        ->find_many();

    if (count($rows) > 0) {

        foreach ($rows as $info) {

            $name = $info['sub_cat_name'];
            $sub_id = $info['sub_cat_id'];
            $photo_show = $info['photo_show'];
            $price_show = $info['price_show'];
            if($selectid == $sub_id){
                $selected_text = "link-active";
            }
            else{
                $selected_text = "";
            }

            if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
                $subcat = get_category_translation("sub",$info['sub_cat_id']);
                $name = $subcat['title'];
            }else{
                $name = $info['sub_cat_name'];
            }

            echo '<li data-ajax-subcatid="'.$sub_id.'" data-photo-show="'.$photo_show.'" data-price-show="'.$price_show.'" class="'.$selected_text.'"><a href="#">'.$name.'</a></li>';
        }

    }else{
        echo 0;
    }
    die();
}

function getCustomFieldByCatID()
{
    global $config,$lang;
    $maincatid = isset($_POST['catid']) ? $_POST['catid'] : 0;
    $subcatid = isset($_POST['subcatid']) ? $_POST['subcatid'] : 0;

    if ($maincatid > 0) {
        $custom_fields = get_customFields_by_catid($maincatid,$subcatid);
        $showCustomField = (count($custom_fields) > 0) ? 1 : 0;
    } else {
        die();
    }
    $tpl = '';
    if ($showCustomField) {
        foreach ($custom_fields as $row) {
            $id = $row['id'];
            $name = $row['title'];
            $type = $row['type'];
            $required = $row['required'];

            if($type == "text-field"){
                $tpl .= '<div class="submit-field">
                            <h5>'.$name.' '.($required === "1" ? '<span class="required">*</span>' : "").'</h5>
                            '.$row['textbox'].'
                        </div>';
            }
            elseif($type == "textarea"){
                $tpl .= '<div class="submit-field">
                            <h5>'.$name.' '.($required === "1" ? '<span class="required">*</span>' : "").'</h5>
                            '.$row['textarea'].'
                        </div>';
            }
            elseif($type == "radio-buttons"){
                $tpl .= '<div class="submit-field">
                            <h5>'.$name.' '.($required === "1" ? '<span class="required">*</span>' : "").'</h5>
                            '.$row['radio'].'
                        </div>';
            }
            elseif($type == "checkboxes"){
                $tpl .= '<div class="submit-field">
                            <h5>'.$name.' '.($required === "1" ? '<span class="required">*</span>' : "").'</h5>
                            '.$row['checkbox'].'
                        </div>';
            }
            elseif($type == "drop-down"){
                $tpl .= '<div class="submit-field">
                            <h5>'.$name.' '.($required === "1" ? '<span class="required">*</span>' : "").'</h5>
                            <select class="form-control selectpicker with-border quick-custom-field" name="custom['.$id.']" data-name="'.$id.'" data-req="'.$required.'">
                                        <option value="" selected>'.__("Select").' '.$name.'</option>
                                        '.$row['selectbox'].'
                                    </select>
                                    <div class="quick-error">'.__("This field is required.").'</div>
                        </div>';
            }
        }
        echo $tpl;
        die();
    } else {
        echo 0;
        die();
    }
}

function getlocHomemap()
{
    global $config;
    $appr = 'active';
    $country = check_user_country();

    if(isset($_GET['serachStr'])){
        $serachStr = $_GET['serachStr'];
    }
    else{
        $serachStr = '';
    }

    if(isset($_GET['state'])){
        $state = $_GET['state'];
    }
    else{
        $state = '';
    }
    if(!empty($_GET['city'])){
        $city = $_GET['city'];
    }
    else{
        if(!empty($_GET['locality'])){
            $city = $_GET['locality'];
        }else{
            $city = '';
        }
    }
    if(isset($_GET['searchBox'])){
        $searchBox = $_GET['searchBox'];
    }
    else{
        $searchBox = '';
    }

    if(isset($_GET['catid'])){
        $catid = $_GET['catid'];
    }
    else{
        $catid = '';
    }


    $where = "";



    if ($city != '') {

        if ($serachStr != '') {
            $where .= " product_name LIKE '%".validate_input($serachStr)."%'";
        }

        if ($searchBox != '') {
            $where .= " category = '".validate_input($searchBox)."'";
        }

        if ($catid != '') {
            $where .= " sub_category = '".validate_input($catid)."'";
        }

        if ($country != '') {
            $where .= " country = '".validate_input($country)."'";
        }

        /*$query = "SELECT p.*,c.id AS cityid
        FROM `".$config['db']['pre']."cities` AS c
        INNER JOIN `".$config['db']['pre']."product` AS p ON p.city = c.id Where (c.name like '%$city%' or c.asciiname like '%$city%') AND p.status = 'active' $where";*/

    }
    else{

        if ($serachStr != '') {
            $where .= " product_name LIKE '%".validate_input($serachStr)."%'";
        }

        if ($searchBox != '') {
            $where .= " category = '".validate_input($searchBox)."'";
        }

        if ($catid != '') {
            $where .= " sub_category = '".validate_input($catid)."'";
        }

        if ($country != '') {
            $where .= " country = '".validate_input($country)."'";
        }


    }

    $results = ORM::for_table($config['db']['pre'].'product')
        ->where('status', $appr)
        ->where_raw($where)
        ->find_many();

    $data = array();
    $i = 0;
    if (count($results) > 0) {

        foreach($results as $result){
            $id = $result['id'];
            $featured = $result['featured'];
            $urgent = $result['urgent'];
            $highlight = $result['highlight'];
            $title = $result['product_name'];
            $cat = $result['category'];
            $price = $result['price'];
            $pics = $result['screen_shot'];
            $location = $result['location'];
            $latlong = $result['latlong'];
            $desc = $result['description'];
            $url = $config['site_url'].$id;

            $fetch = ORM::for_table($config['db']['pre'].'catagory_main')
                ->where('cat_id',$cat)
                ->find_one();

            $catIcon = $fetch['icon'];
            $catname = $fetch['cat_name'];

            $map = explode(',', $latlong);
            $lat = $map[0];
            $long = $map[1];

            $p = explode(',', $pics);
            $pic = $p[0];
            $pic = $config['site_url'].'storage/products/'.$pic;

            $data[$i]['id'] = $id;
            $data[$i]['latitude'] = $lat;
            $data[$i]['longitude'] = $long;
            $data[$i]['featured'] = $featured;
            $data[$i]['title'] = $title;
            $data[$i]['location'] = $location;
            $data[$i]['category'] = $catname;
            $data[$i]['cat_icon'] = $catIcon;
            $data[$i]['marker_image'] = $pic;
            $data[$i]['url'] = $url;
            $data[$i]['description'] = strip_tags(htmlentities($desc));

            $i++;
        }
        echo json_encode($data);
    } else {
        echo '0';
    }
    die();
}

function openlocatoionPopup()
{
    global $config,$link;
    $result = ORM::for_table($config['db']['pre'].'product')->find_one($_POST['id']);

    $data = array();
    $i = 0;
    if (!empty($result)) {
        $id = $result['id'];
        $featured = $result['featured'];
        $urgent = $result['urgent'];
        $highlight = $result['highlight'];
        $title = $result['product_name'];
        $cat = $result['category'];
        $price = $result['price'];
        $pics = $result['screen_shot'];
        $location = $result['location'];
        $city_id = $result['city'];
        $cityname = get_cityName_by_id($result['city']);
        $country = get_countryName_by_code($result['country']);

        $location = $cityname.", ".$country;

        $latlong = $result['latlong'];
        $desc = strip_tags(htmlentities($result['description']));
        $url = $link['POST-DETAIL']."/".$id;

        $fetch = ORM::for_table($config['db']['pre'].'catagory_main')
            ->where('cat_id',$cat)
            ->find_one();
        $catIcon = $fetch['icon'];
        $catname = $fetch['cat_name'];

        $map = explode(',', $latlong);
        $lat = $map[0];
        $long = $map[1];


        $picture = explode(',', $pics);
        $pic_count = count($picture);
        if($picture[0] != ""){
            $pic = $picture[0];
            $pic = $config['site_url'].'storage/products/thumb/'.$pic;
            $pic = '<img class="activator" src="' . $pic . '">';
        }else{
            $pic = "";
        }



        echo '<div class="item gmapAdBox" data-id="' . $id . '" style="margin-bottom: 0px;">
                    <a href="' . $url . '" style="display: block;position: relative;">
                     <div class="card small">
                        <div class="card-image waves-effect waves-block waves-light">
                          ' . $pic . '
                        </div>
                        <div class="card-content">
                            <div class="label label-default">' . $catname . '</div>
                          <span class="card-title activator grey-text text-darken-4 mapgmapAdBoxTitle">' . $title . '</span>
                          <p class="mapgmapAdBoxLocation">' . $location . '</p>
                        </div>
                      </div>

                    </a>
                </div>';
    } else {
        echo false;
    }
    die();
}

function quickad_ajax_home_search()
{
    global $config,$lang,$link,$cats;
    $pdo = ORM::get_db();
    $searchmode = "titlematch";
    $qString      = '';
    $qString      = $_POST['tagID'];
    $qString      = strtolower($qString);
    $output       = array();
    $TAGOutput    = array();
    $CATOutput    = array();
    $TagCatOutput = array();
    $TitleOutput  = array();
    $lpsearchMode = "titlematch";
    $catIcon_type = "icon";

    if( isset($searchmode) ){
        if( !empty($searchmode) && $searchmode=="keyword" ){
            $lpsearchMode = "keyword";
        }
    }

    if (empty($qString)) {

        $categories = get_maincategory();
        $catIcon    = '';
        foreach ($categories as $cat) {
            $catIcon = $cat['icon'];
            if (!empty($catIcon)) {
                if($catIcon_type == "image")
                    $catIcon = '<img src="' . $catIcon . '" />';
                else
                    $catIcon = '<i class="' . $catIcon . '" ></i>';
            }
            $cats[$cat['id']] = '<li class="lp-default-cats" data-catid="' . $cat['id'] . '">' . $catIcon . '<span class="qucikad-as-cat">' . $cat['name'] . '</span></li>';
        }
        $output           = array(
            'tag' => '',
            'cats' => $cats,
            'tagsncats' => '',
            'titles' => '',
            'more' => ''
        );
        $query_suggestion = json_encode(array(
            "tagID" => $qString,
            "suggestions" => $output
        ));
        die($query_suggestion);
    }
    else {
        //$catTerms = get_maincategory();


        if( $lpsearchMode == "keyword" ){

            $sql = "SELECT DISTINCT *
FROM `".$config['db']['pre']."catagory_main`
 WHERE cat_name like '%$qString%'
 ORDER BY
  CASE
    WHEN cat_name = '$qString' THEN 1
    WHEN cat_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";
        }else{

            $sql = "SELECT DISTINCT *
FROM `".$config['db']['pre']."catagory_main`
 WHERE cat_name like '$qString%'
 ORDER BY
  CASE
    WHEN cat_name = '$qString' THEN 1
    WHEN cat_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";

        }

        $rows = $pdo->query($sql);
        foreach ($rows as $info) {
            $catTerms[$info['cat_id']]['id'] = $info['cat_id'];
            $catTerms[$info['cat_id']]['icon'] = $info['icon'];

            if ($config['lang_code'] != 'en' && $config['userlangsel'] == '1') {
                $maincat = get_category_translation("main", $info['cat_id']);
                $catTerms[$info['cat_id']]['name'] = $maincat['title'];
                $catTerms[$info['cat_id']]['slug'] = $maincat['slug'];
            } else {
                $catTerms[$info['cat_id']]['name'] = $info['cat_name'];
                $catTerms[$info['cat_id']]['slug'] = $info['slug'];
            }
        }


        if( $lpsearchMode == "keyword" ){

            $sql = "SELECT DISTINCT *
FROM `".$config['db']['pre']."catagory_sub`
 WHERE sub_cat_name like '%$qString%'
 ORDER BY
  CASE
    WHEN sub_cat_name = '$qString' THEN 1
    WHEN sub_cat_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";
        }else{

            $sql = "SELECT DISTINCT *
FROM `".$config['db']['pre']."catagory_sub`
 WHERE sub_cat_name like '$qString%'
 ORDER BY
  CASE
    WHEN sub_cat_name = '$qString' THEN 1
    WHEN sub_cat_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";

        }
        $rows = $pdo->query($sql);
        foreach ($rows as $info) {
            $subcatTerms[$info['sub_cat_id']]['id'] = $info['sub_cat_id'];

            if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
                $subcategory = get_category_translation("sub",$info['sub_cat_id']);

                $subcatTerms[$info['sub_cat_id']]['name'] = $subcategory['title'];
                $subcatTerms[$info['sub_cat_id']]['slug'] = $subcategory['slug'];
            }else{
                $subcatTerms[$info['sub_cat_id']]['name'] = $info['sub_cat_name'];
                $subcatTerms[$info['sub_cat_id']]['slug'] =  $info['slug'];
            }

            $get_main = get_maincat_by_id($info['main_cat_id']);
            $subcatTerms[$info['sub_cat_id']]['main_cat_name'] = $get_main['cat_name'];
            $subcatTerms[$info['sub_cat_id']]['main_cat_icon'] = $get_main['icon'];
            $subcatTerms[$info['sub_cat_id']]['main_cat_id'] = $info['main_cat_id'];
        }
        //$subcatTerms = get_subcategories();

        $catName  = '';
        $catIcon  = '';
        if (!empty($catTerms) && !empty($subcatTerms)) {
            foreach ($catTerms as $cat) {
                $catIcon = $cat['icon'];
                if (!empty($catIcon)) {
                    if($catIcon_type == "image")
                        $catIcon = '<img src="' . $catIcon . '" />';
                    else
                        $catIcon = '<i class="' . $catIcon . '" ></i>';
                }

                $catTermMatch = false;

                $catTernName  = $cat['name'];
                $catTernName  = strtolower($catTernName);
                if( $lpsearchMode == "keyword" ){
                    preg_match("/[$qString]/", "$catTernName", $lpMatches, PREG_OFFSET_CAPTURE);
                    $lpresCnt = count($lpMatches);
                    if( $lpresCnt > 0 ){
                        $catTermMatch = true;
                    }

                }else{
                    $catTermMatch = strpos($catTernName, $qString);
                }

                if ( $catTermMatch !== false ) {
                    $CATOutput[$cat['id']] = '<li class="qucikad-ajaxsearch-li-cats" data-catid="' . $cat['id'] . '">' . $catIcon . '<span class="qucikad-as-cat">' . $cat['name'] . '</span></li>';
                }
            }
            foreach ($subcatTerms as $subcat) {

                $tagTermMatch = false;
                $tagTernName  = strtolower($subcat['name']);

                if( $lpsearchMode == "keyword" ){
                    preg_match("/[$qString]/", "$tagTernName", $lpMatches, PREG_OFFSET_CAPTURE);
                    $lpresCnt = count($lpMatches);
                    if( $lpresCnt > 0 ){
                        $tagTermMatch = true;
                    }
                }else{
                    $tagTermMatch = strpos($tagTernName, $qString);
                }

                if ( $tagTermMatch !== false ) {
                    $TAGOutput[$subcat['id']] = '<li class="qucikad-ajaxsearch-li-tags" data-tagid="' . $subcat['id'] . '"><span class="qucikad-as-tag">' . $subcat['name'] . '</span></li>';
                }
            }

        }
        else {

            if( !empty($catTerms) ){
                foreach ($catTerms as $cat) {

                    $catIcon = $cat['icon'];
                    if (!empty($catIcon)) {
                        if($catIcon_type == "image")
                            $catIcon = '<img src="' . $catIcon . '" />';
                        else
                            $catIcon = '<i class="' . $catIcon . '" ></i>';
                    }

                    $catTermMatch = false;

                    $catTernName  = $cat['name'];
                    $catTernName  = strtolower($catTernName);
                    if( $lpsearchMode == "keyword" ){
                        preg_match("/[$qString]/", "$catTernName", $lpMatches, PREG_OFFSET_CAPTURE);
                        $lpresCnt = count($lpMatches);
                        if( $lpresCnt > 0 ){
                            $catTermMatch = true;
                        }

                    }else{
                        $catTermMatch = strpos($catTernName, $qString);
                    }

                    if ( $catTermMatch !== false ) {
                        $CATOutput[$cat['id']] = '<li class="qucikad-ajaxsearch-li-cats" data-catid="' . $cat['id'] . '">' . $catIcon . '<span class="qucikad-as-cat">' . $cat['name'] . '</span></li>';
                    }
                }
            }

            if( !empty($subcatTerms) ) {

                foreach ($subcatTerms as $subcat) {

                    $catIcon = $subcat['main_cat_icon'];
                    if (!empty($catIcon)) {
                        if($catIcon_type == "image")
                            $catIcon = '<img class="qucikad-as-caticon" src="' . $catIcon . '" />';
                        else
                            $catIcon = '<i class="qucikad-as-caticon ' . $catIcon . '"  ></i>';
                    }
                    $tagTermMatch = false;
                    $tagTernName  = strtolower($subcat['name']);

                    if( $lpsearchMode == "keyword" ){
                        preg_match("/[$qString]/", "$tagTernName", $lpMatches, PREG_OFFSET_CAPTURE);
                        $lpresCnt = count($lpMatches);
                        if( $lpresCnt > 0 ){
                            $tagTermMatch = true;
                        }
                    }else{
                        $tagTermMatch = strpos($tagTernName, $qString);
                    }

                    if ( $tagTermMatch !== false ) {
                        //$TAGOutput[$subcat['id']]    = '<li class="qucikad-ajaxsearch-li-tags" data-tagid="' . $subcat['id'] . '"><span class="qucikad-as-tag">' . $subcat['name'] . '</span></li>';

                        $TagCatOutput[] = '<li class="cats-n-tags" data-tagid="' . $subcat['id'] . '" data-catid="' . $subcat['main_cat_id'] . '">' . $catIcon . '<span class="qucikad-as-tag">' . $subcat['name'] . '</span><span> in </span><span class="qucikad-as-cat">' . $subcat['main_cat_name'] . '</span></li>';
                    }
                }

            }
        }

        $machTitles = false;
        $country_code = check_user_country();

        if( $lpsearchMode == "keyword" ){

            $sql = "SELECT DISTINCT p.*,u.group_id,g.show_in_home_search
FROM `".$config['db']['pre']."product` as p
LEFT JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id
LEFT JOIN `".$config['db']['pre']."usergroups` as g ON g.group_id = u.group_id
 WHERE p.product_name like '%$qString%' and p.status = 'active' and p.hide = '0' and p.country = '".$country_code."' and g.show_in_home_search = 'yes'
 ORDER BY
  CASE
    WHEN p.product_name = '$qString' THEN 1
    WHEN p.product_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";
        }else{

            $sql = "SELECT DISTINCT p.*,u.group_id,g.show_in_home_search
FROM `".$config['db']['pre']."product` as p
INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id
INNER JOIN `".$config['db']['pre']."usergroups` as g ON g.group_id = u.group_id
 WHERE p.product_name like '$qString%' and p.status = 'active' and p.hide = '0' and p.country = '".$country_code."' and g.show_in_home_search = 'yes'
 ORDER BY
  CASE
    WHEN p.product_name = '$qString' THEN 1
    WHEN p.product_name LIKE '$qString%' THEN 2
    ELSE 3
  END ";

        }

        $result = $pdo->query($sql);
        if (count($result) > 0) {
            $machTitles = true;      // output data of each row
            foreach ($result as $info) {
                $listTitle  = $info['product_name'];
                $listTitle  = strtolower($listTitle);
                $pro_url = create_slug($info['product_name']);
                $permalink = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;
                $cityname = get_cityName_by_id($info['city']);

                if(check_user_upgrades($info['user_id']))
                {
                    $sub_info = get_user_membership_detail($info['user_id']);
                    $sub_title = $sub_info['sub_title'];
                    $sub_image = $sub_info['sub_image'];
                    $premium_badge = "<img src='".$sub_image."' alt='".$sub_title."' width='20px'/>";
                }else{
                    $sub_title = '';
                    $sub_image = '';
                    $premium_badge = '';
                }


                $listThumb = '';
                $picture =   explode(',' ,$info['screen_shot']);
                if (!empty($picture[0])) {
                    if(file_exists("../storage/products/thumb/".$picture[0])){
                        $image = $config['site_url']."storage/products/thumb/" . $picture[0];
                    }else{
                        $image = $config['site_url']."storage/products/thumb/default.png";
                    }
                    $listThumb = "<img src='".$image."' width='50' height='50'/>";
                } else {
                    $listThumb = '<img src="'.$config['site_url'].'storage/products/thumb/default.png" alt="" width="50" height="50">';
                }

                $TitleOutput[] = '<li class="qucikad-ajaxsearch-li-title" data-url="' . $permalink . '">' . $listThumb . '<span class="qucikad-as-title"><a href="' . $permalink . '">' . $listTitle . ' '.
                    $premium_badge.' <span class="lp-loc">' . $cityname . '</span></a></span></li>';

            }
        }

        $TAGOutput    = array_unique($TAGOutput);
        $CATOutput    = array_unique($CATOutput);
        $TagCatOutput = array_unique($TagCatOutput);
        $TitleOutput  = array_unique($TitleOutput);
        if ((!empty($TAGOutput) && count($TAGOutput) > 0) || (!empty($CATOutput) && count($CATOutput) > 0) || (!empty($TagCatOutput) && count($TagCatOutput) > 0) || (!empty($TitleOutput) && count($TitleOutput) > 0)) {
            $output = array(
                'tag' => $TAGOutput,
                'cats' => $CATOutput,
                'tagsncats' => $TagCatOutput,
                'titles' => $TitleOutput,
                'more' => '',
                'matches' => $machTitles
            );
        } else {
            $moreResult = array();
            $mResults   = '<strong>' . __("More Results For") . '</strong>';
            $mResults .= $qString;
            $moreResult[] = '<li class="qucikad-ajaxsearch-li-more-results" data-moreval="' . $qString . '">' . $mResults . '</li>';
            $output       = array(
                'tag' => '',
                'cats' => '',
                'tagsncats' => '',
                'titles' => '',
                'more' => $moreResult
            );
        }
        $query_suggestion = json_encode(array(
            "tagID" => $qString,
            "suggestions" => $output
        ));
        die($query_suggestion);
    }
}

function submitBlogComment(){
    global $config,$lang;
    $comment_error = $name = $email = $user_id = $comment = null;
    $result = array();
    $is_admin = '0';
    $is_login = false;
    if (checkloggedin()) {
        $is_login = true;
    }
    $avatar = $config['site_url'].'storage/profile/default_user.png';
    if (!($is_login || isset($_SESSION['admin']['id']))) {
        if (empty($_POST['user_name']) || empty($_POST['user_email'])) {
            $comment_error = __("All fields are required.");
        } else {
            $name = removeEmailAndPhoneFromString($_POST['user_name']);
            $email = $_POST['user_email'];

            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if (!preg_match($regex, $email)) {
                $comment_error = __("This is not a valid email address");
            }
        }
    } else if ($is_login && isset($_SESSION['admin']['id'])) {
        $commenting_as = 'admin';
        if (!empty($_POST['commenting-as'])) {
            if (in_array($_POST['commenting-as'], array('admin', 'user'))) {
                $commenting_as = $_POST['commenting-as'];
            }
        }
        if ($commenting_as == 'admin') {
            $is_admin = '1';
            $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
            $user_id = $_SESSION['admin']['id'];
            $name = $info['name'];
            $email = $info['email'];
            if(!empty($info['image'])){
                $avatar = $config['site_url'].'storage/profile/'.$info['image'];
            }
        } else {
            $user_id = $_SESSION['user']['id'];
            $user_data = get_user_data(null, $user_id);
            $name = $user_data['name'];
            $email = $user_data['email'];
            if(!empty($user_data['image'])){
                $avatar = $config['site_url'].'storage/profile/'.$user_data['image'];
            }
        }
    } else if ($is_login) {
        $user_id = $_SESSION['user']['id'];
        $user_data = get_user_data(null, $user_id);
        $name = $user_data['name'];
        $email = $user_data['email'];
        if(!empty($user_data['image'])){
            $avatar = $config['site_url'].'storage/profile/'.$user_data['image'];
        }
    } else if (isset($_SESSION['admin']['id'])) {
        $is_admin = '1';
        $info = ORM::for_table($config['db']['pre'] . 'admins')->find_one($_SESSION['admin']['id']);
        $user_id = $_SESSION['admin']['id'];
        $name = $info['name'];
        $email = $info['email'];
        if(!empty($info['image'])){
            $avatar = $config['site_url'].'storage/profile/'.$info['image'];
        }
    }else{
        $comment_error = __("Please login to post a comment.");
    }

    if (empty($_POST['comment'])) {
        $comment_error = __("All fields are required.");
    } else {
        $comment = validate_input($_POST['comment']);
    }

    $duplicates = ORM::for_table($config['db']['pre'] . 'blog_comment')
        ->where('blog_id', $_POST['comment_post_ID'])
        ->where('name', $name)
        ->where('email', $email)
        ->where('comment', $comment)
        ->count();

    if ($duplicates > 0) {
        $comment_error = __("Duplicate Comment: This comment is already exists.");
    }

    if (!$comment_error) {
        if($is_admin){
            $approve = '1';
        }else{
            if($config['blog_comment_approval'] == 1){
                $approve = '0';
            }else if($config['blog_comment_approval'] == 2){
                if($is_login){
                    $approve = '1';
                }else{
                    $approve = '0';
                }
            }else{
                $approve = '1';
            }
        }

        $blog_cmnt = ORM::for_table($config['db']['pre'] . 'blog_comment')->create();
        $blog_cmnt->blog_id = validate_input($_POST['comment_post_ID']);
        $blog_cmnt->user_id = validate_input($user_id);
        $blog_cmnt->is_admin = $is_admin;
        $blog_cmnt->name = validate_input($name);
        $blog_cmnt->email = validate_input($email);
        $blog_cmnt->comment = validate_input($comment);
        $blog_cmnt->created_at = date('Y-m-d H:i:s');
        $blog_cmnt->active = $approve;
        $blog_cmnt->parent = validate_input($_POST['comment_parent']);
        $blog_cmnt->save();

        $id = $blog_cmnt->id();
        $date = date('d, M Y');
        $approve_txt = '';
        if($approve == '0'){
            $approve_txt = '<em><small>'.__("Comment is posted, wait for the reviewer to approve.").'</small></em>';
        }

        $html = '<li id="li-comment-'.$id.'"';
        if($_POST['comment_parent'] != 0) {
            $html .= 'class="children-2"';
        }
        $html .= '>
                   <div class="comments-box" id="comment-'.$id.'">
                        <div class="avatar">
                            <img src="'.$avatar.'" alt="'.$name.'">
                        </div>
                        <div class="comment-content"><div class="arrow-comment"></div>
                            <div class="comment-by">'.$name.'
                                <span class="date">'.$date.'</span>
                            </div>
                            '.$approve_txt.'
                            <p>'.nl2br(stripcslashes($comment)).'</p>
                        </div>
                    </div>
                </li>';

        $result['success'] = true;
        $result['html'] = $html;
        $result['id'] = $id;
    }else{
        $result['success'] = false;
        $result['error'] = $comment_error;
    }
    die(json_encode($result));
}