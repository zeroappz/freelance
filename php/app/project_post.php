<?php
if(isset($match['params']['country'])) {
    if ($match['params']['country'] != ""){
        change_user_country($match['params']['country']);
    }
}

if(get_option("post_without_login") == '0'){
    if (!checkloggedin()) {
        headerRedirect($link['LOGIN']."?ref=post-job");
        exit();
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == "post_job") {
        ajax_post_advertise();
    }
}

if (checkloggedin()) {
    if($_SESSION['user']['user_type'] == 'user'){
        headerRedirect($link['DASHBOARD']);
    }

    if(!$config['non_active_allow']){
        $user_data = get_user_data(null,$_SESSION['user']['id']);
        if($user_data['status'] == 0){
            message(__("Notify"),__("Your email address is not verified. Please verify your email address first."));
            exit();
        }
    }
}



function ajax_post_advertise(){

    global $config, $lang, $link;
    if(isset($_POST['submit'])) {
        $errors = array();

        if (empty($_POST['catid'])) {
            $errors[]['message'] = __("Project category is required");
        }
        if (empty($_POST['subcatid'])) {
            $errors[]['message'] = __("Project skills is required");
        }
        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Project Title is required.");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Job Description is required.");
        }

        if (empty($_POST['salary_min']) or empty($_POST['salary_max'])) {
            $errors[]['message'] = __("Project budget required");
        }

        if (!is_numeric($_POST['salary_min']) or !is_numeric($_POST['salary_max'])) {
            $errors[]['message'] = __("Project budget must be a number.");
        }

        /*IF : USER NOT LOGIN THEN CHECK SELLER INFORMATION*/
        if (!checkloggedin()) {
            if(isset($_POST['user_name'])){
                $seller_name = $_POST['user_name'];
                if (empty($seller_name)) {
                    $errors[]['message'] = __("User Name Required");
                }
            }else{
                $errors[]['message'] = __("User Name Required");
            }

            if(isset($_POST['user_email'])){
                $seller_email = $_POST['user_email'];

                if (empty($seller_email)) {
                    $errors[]['message'] = __("User Email Required");
                } else {
                    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
                    if (!preg_match($regex, $seller_email)) {
                        $errors[]['message'] = __("User Email") . " : " . __("This is not a valid email address");
                    }
                }
            }else{
                $errors[]['message'] = __("User Email Required");
            }
        }
        /*IF : USER NOT LOGIN THEN CHECK SELLER INFORMATION*/

        /*IF : USER GO TO PEMIUM POST*/
        $urgent = isset($_POST['urgent']) ? 1 : 0;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $highlight = isset($_POST['highlight']) ? 1 : 0;

        if (!count($errors) > 0) {

            if (!checkloggedin()) {
                $seller_name = $_POST['user_name'];
                $seller_email = $_POST['user_email'];

                $user_count = check_account_exists($seller_email);
                if ($user_count > 0) {
                    $info = ORM::for_table($config['db']['pre'].'user')
                        ->where('email', $seller_email)
                        ->find_one();
                    $json = '{"status" : "email-exist","errors" : "' . __("An account already exists with that e-mail address") . '","email" : "' . $seller_email . '","username" : "' . $info['username'] . '","user_type" : "' . $info['user_type'] . '"}';
                    echo $json;
                    die();
                } else {
                    /*Create user account with givern email id*/
                    $created_username = parse_name_from_email($seller_email);
                    //mysql query to select field username if it's equal to the username that we check '
                    $check_username = ORM::for_table($config['db']['pre'].'user')
                        ->select('username')
                        ->where('username', $created_username)
                        ->count();

                    //if number of rows fields is bigger them 0 that means it's NOT available '
                    if ($check_username > 0) {
                        $username = createusernameslug($created_username);
                    } else {
                        $username = $created_username;
                    }
                    $location = getLocationInfoByIp();
                    $confirm_id = get_random_id();
                    $password = get_random_id();
                    $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
                    $now = date("Y-m-d H:i:s");

                    $insert_user = ORM::for_table($config['db']['pre'].'user')->create();
                    $insert_user->status = '0';
                    $insert_user->name = validate_input($seller_name);
                    $insert_user->username = validate_input($username);
                    $insert_user->password_hash = $pass_hash;
                    $insert_user->email = validate_input($seller_email);
                    $insert_user->confirm = $confirm_id;
                    $insert_user->user_type = 'employer';
                    $insert_user->created_at = $now;
                    $insert_user->updated_at = $now;
                    $insert_user->country = $location['country'];
                    $insert_user->city = $location['city'];
                    $insert_user->save();

                    $user_id = $insert_user->id();

                    /*CREATE ACCOUNT CONFIRMATION EMAIL*/
                    email_template("signup_confirm",$user_id);

                    /*SEND ACCOUNT DETAILS EMAIL*/
                    email_template("signup_details",$user_id,$password);

                    $loggedin = userlogin($username, $password);
                    create_user_session($loggedin['id'], $loggedin['username'], $loggedin['password'],$loggedin['user_type']);

                }
            }

            if (checkloggedin()) {

                $salary_type = $_POST['salary_type'];
                $salary_min = !empty($_POST['salary_min']) ? $_POST['salary_min'] : '0';
                $salary_max = !empty($_POST['salary_max']) ? $_POST['salary_max'] : '0';
                $phone = !empty($_POST['phone']) ? $_POST['phone'] : '0';

                $negotiable = isset($_POST['negotiable']) ? '1' : '0';
                $hide_phone = isset($_POST['hide_phone']) ? '1' : '0';

                if($config['post_desc_editor'] == 1)
                    $description = validate_input($_POST['content'],true);
                else
                    $description = validate_input($_POST['content']);



                $post_title = removeEmailAndPhoneFromString($_POST['title']);
                $slug = create_post_slug($post_title);

                if(isset($_POST['tags'])){
                    $tags = $_POST['tags'];
                }else{
                    $tags = '';
                }

                // Get membership details
                $group_get_info = get_user_membership_settings();
                $urgent_project_fee = $group_get_info['urgent_project_fee'];
                $featured_project_fee = $group_get_info['featured_project_fee'];
                $highlight_project_fee = $group_get_info['highlight_project_fee'];

                $ad_duration = $group_get_info['ad_duration'];
                $timenow = date('Y-m-d H:i:s');
                $expire_time = date('Y-m-d H:i:s', strtotime($timenow . ' +'.$ad_duration.' day'));
                $expire_timestamp = strtotime($expire_time);


                $item_insrt = ORM::for_table($config['db']['pre'].'project')->create();
                $item_insrt->user_id = $_SESSION['user']['id'];
                $item_insrt->product_name = validate_input($post_title);
                $item_insrt->slug = $slug;
                $item_insrt->status = 'open';
                $item_insrt->category = validate_input($_POST['catid']);
                $item_insrt->sub_category = validate_input(implode(",", $_POST['subcatid']));
                $item_insrt->description = $description;
                $item_insrt->salary_min = validate_input($salary_min);
                $item_insrt->salary_max = validate_input($salary_max);
                $item_insrt->salary_type = validate_input($salary_type);
                $item_insrt->tag = validate_input($tags);
                $item_insrt->created_at = $timenow;
                $item_insrt->updated_at = $timenow;
                $item_insrt->expire_date = $expire_timestamp;
                $item_insrt->save();

                $project_id = $item_insrt->id();
                //add_post_customField_data($_POST['catid'], $_POST['subcatid'],$project_id);

                $amount = 0;
                $trans_desc = __("Package");

                $premium_tpl = "";

                if ($featured == 1) {
                    $amount = $featured_project_fee;
                    $trans_desc = $trans_desc ." ". __("Featured");
                    $premium_tpl .= ' <div class="ModalPayment-paymentDetails">
                                            <div class="ModalPayment-label">'.__("Featured").'</div>
                                            <div class="ModalPayment-price">
                                                <span class="ModalPayment-totalCost-price">'.$config['currency_sign'].$featured_project_fee.'</span>
                                            </div>
                                        </div>';
                }
                if ($urgent == 1) {
                    $amount = $amount + $urgent_project_fee;
                    $trans_desc = $trans_desc ." ". __("Urgent");
                    $premium_tpl .= ' <div class="ModalPayment-paymentDetails">
                                            <div class="ModalPayment-label">'.__("Urgent").'</div>
                                            <div class="ModalPayment-price">
                                                <span class="ModalPayment-totalCost-price">'.$config['currency_sign'].$urgent_project_fee.'</span>
                                            </div>
                                        </div>';
                }
                if ($highlight == 1) {
                    $amount = $amount + $highlight_project_fee;
                    $trans_desc = $trans_desc ." ". __("Highlight");
                    $premium_tpl .= ' <div class="ModalPayment-paymentDetails">
                                            <div class="ModalPayment-label">'.__("Highlight").'</div>
                                            <div class="ModalPayment-price">
                                                <span class="ModalPayment-totalCost-price">'.$config['currency_sign'].$highlight_project_fee.'</span>
                                            </div>
                                        </div>';
                }

                if ($amount > 0) {
                    $premium_tpl .= '<div class="ModalPayment-totalCost">
                                            <span class="ModalPayment-totalCost-label">'.__("Total").': </span>
                                            <span class="ModalPayment-totalCost-price">'.$config['currency_sign'].$amount." ".$config['currency_code'].'</span>
                                        </div>';

                    /*These details save in session and get on payment sucecess*/
                    $title = $post_title;
                    $payment_type = "premium";
                    $access_token = uniqid();

                    $_SESSION['quickad'][$access_token]['name'] = $title;
                    $_SESSION['quickad'][$access_token]['amount'] = $amount;
                    $_SESSION['quickad'][$access_token]['payment_type'] = $payment_type;
                    $_SESSION['quickad'][$access_token]['trans_desc'] = $trans_desc;
                    $_SESSION['quickad'][$access_token]['product_id'] = $project_id;
                    $_SESSION['quickad'][$access_token]['featured'] = $featured;
                    $_SESSION['quickad'][$access_token]['urgent'] = $urgent;
                    $_SESSION['quickad'][$access_token]['highlight'] = $highlight;
                    /*End These details save in session and get on payment sucecess*/

                    $url = $link['PAYMENT']."/" . $access_token;
                    $response = array();
                    $response['status'] = "success";
                    $response['ad_type'] = "package";
                    $response['redirect'] = $url;
                    $response['tpl'] = $premium_tpl;
                    unset($_POST);
                    echo json_encode($response, JSON_UNESCAPED_SLASHES);
                    die();
                } else {
                    unset($_POST);
                    $ad_link = $link['PROJECT'] . "/" . $project_id;

                    $json = '{"status" : "success","ad_type" : "free","redirect" : "' . $ad_link . '"}';
                    echo $json;
                    die();
                }
            }
            else {
                $status = "error";
                $errors[]['message'] = __("Your job could not be saved! Try it again later.");
            }


        } else {
            $status = "error";
        }

        $json = '{"status" : "' . $status . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
        echo $json;
        die();
    }
}


if(isset($_GET['country'])) {
    if ($_GET['country'] != ""){
        change_user_country($_GET['country']);
    }
}

$country_code = check_user_country();
$currency_info = set_user_currency($country_code);
$currency_sign = $currency_info['html_entity'];

if($latlong = get_lat_long_of_country($country_code)){
    $mapLat     =  $latlong['lat'];
    $mapLong    =  $latlong['lng'];
}else{
    $mapLat     =  get_option("home_map_latitude");
    $mapLong    =  get_option("home_map_longitude");
}

$custom_fields = get_customFields_by_catid();

// get SKILLS
$rows = ORM::for_table($config['db']['pre'].'catagory_sub')
    ->order_by_asc('cat_order')
    ->find_many();
$skills = array();
foreach($rows as $row){
    $skills[$row['sub_cat_id']]['id'] = $row['sub_cat_id'];
    $skills[$row['sub_cat_id']]['title'] = $row['sub_cat_name'];
}


// get salary types
$rows = ORM::for_table($config['db']['pre'].'salary_type')
    ->where('active', '1')
    ->order_by_asc('position')
    ->find_many();
$salary_types = array();
foreach($rows as $row){
    $salary_types[$row['id']]['id'] = $row['id'];
    $salary_types[$row['id']]['title'] = get_salaryType_title_by_id($row['id']);
}

// get companies
$companies = array();
if(isset($_SESSION['user']['id'])) {
    $rows = ORM::for_table($config['db']['pre'] . 'companies')
        ->where('user_id', $_SESSION['user']['id'])
        ->where('status', '1')
        ->find_many();
    foreach ($rows as $row) {
        $companies[$row['id']]['id'] = $row['id'];
        $companies[$row['id']]['title'] = $row['name'];
    }
}

// Get membership details
$group_get_info = get_user_membership_settings();
$urgent_project_fee = $group_get_info['urgent_project_fee'];
$featured_project_fee = $group_get_info['featured_project_fee'];
$highlight_project_fee = $group_get_info['highlight_project_fee'];
$urgent_duration = $group_get_info['urgent_duration'];
$featured_duration = $group_get_info['featured_duration'];
$highlight_duration = $group_get_info['highlight_duration'];

HtmlTemplate::display('project_post', array(
    'countrylist' =>get_country_list(),
    'category' =>get_maincategory(),
    'skills' =>$skills,
    'salarytypes' =>$salary_types,
    'companies' =>$companies,
    'customfields' =>$custom_fields,
    'showcustomfield' => (count($custom_fields) > 0) ? 1 : 0,
    'latitude' => $mapLat,
    'longitude' => $mapLong,
    'user_country' => strtolower($country_code),
    'user_currency_sign' => $currency_sign,
    'featured_fee' => $featured_project_fee,
    'urgent_fee' => $urgent_project_fee,
    'highlight_fee' => $highlight_project_fee,
    'featured_duration' => $featured_duration,
    'urgent_duration' => $urgent_duration,
    'highlight_duration' => $highlight_duration,
    'language_direction' => get_current_lang_direction()
));
exit;
?>
