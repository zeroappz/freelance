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

function check_user_post_limit(){
    global $config,$lang;

    // Get membership details
    $group_info = get_user_membership_settings();
    $ad_limit = $group_info['ad_limit'];

    if($ad_limit != "999"){
        $total_user_post = ORM::for_table($config['db']['pre'].'product')
            ->where('user_id', $_SESSION['user']['id'])
            ->count();

        if($total_user_post >= $ad_limit){
            message(__("Notify"),__("Sorry, Your job post limit exceed you have to upgrade your membership plan for post more jobs."));
            exit();
        }
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

    check_user_post_limit();
}



function ajax_post_advertise(){

    global $config, $lang, $link;
    if(isset($_POST['submit'])) {
        $errors = array();

        if($config['company_enable']) {
            if (empty($_POST['company'])) {
                if (empty($_POST['company_name'])) {
                    $errors[]['message'] = __("Company Name Required.");
                }

                if (empty($_POST['company_desc'])) {
                    $errors[]['message'] = __("Company Description Required.");
                }
            }

            if($config['reg_no_enable']){
                $reg_no = $_POST['reg_no'];
                if(isset($reg_no)){
                    $regno_count = ORM::for_table($config['db']['pre'].'companies')
                        ->where('id', $_POST['id'])
                        ->where('reg_no', $reg_no)
                        ->count();
                    if ($regno_count) {
                        $errors[]['message'] = __("Registration no. already exist.");
                    }
                }else{
                    $errors[]['message'] = __("Registration no. required.");
                }
            }else{
                $reg_no = 0;
            }
        }

        if (empty($_POST['catid'])) {
            $errors[]['message'] = __("The category and sub-category are required.");
        }elseif(!isset($_POST['subcatid'])){
            $errors[]['message'] = __("The category and sub-category are required.");
        }elseif(isset($_POST['subcatid']) && $_POST['subcatid'] == ""){
            $errors[]['message'] = __("The category and sub-category are required.");
        }

        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Job Title is required.");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Job Description is required.");
        }
        if (empty($_POST['job_type'])) {
            $errors[]['message'] = __("Job Type Required.");
        }
        if (empty($_POST['city'])) {
            $errors[]['message'] = __("The city is required.");
        }
        if (!empty($_POST['salary_min']) or !empty($_POST['salary_max'])) {
            if (!is_numeric($_POST['salary_min']) or !is_numeric($_POST['salary_max'])) {
                $errors[]['message'] = __("Salary must be a number.");
            }
        }
        if (!empty($_POST['application_url'])) {
            if (filter_var($_POST['application_url'], FILTER_VALIDATE_URL) === FALSE) {
                $errors[]['message'] = __("Application Url is invalid.");
            }
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

        
        $company_logo = null;
        if($config['company_enable']) {
            if (!count($errors) > 0) {
                if (isset($_FILES['company_logo'])) {
                    $file = $_FILES['company_logo'];
                    $valid_formats = array("jpg", "jpeg", "png"); // Valid image formats
                    $filename = $file['name'];
                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    if (!empty($filename)) {
                        //File extension check
                        if (in_array($ext, $valid_formats)) {
                            //Valid File extension check
                            $main_path = ROOTPATH . "/storage/products/";
                            $filename = uniqid(time()) . '.' . $ext;
                            move_uploaded_file($file['tmp_name'], $main_path . $filename);
                            // resize image
                            resizeImage(200, $main_path . $filename, $main_path . $filename);
                            $company_logo = $filename;
                        } else {
                            $errors[]['message'] = __("Sorry, only JPG, JPEG and PNG files are allowed.");
                        }
                    }

                }
            }
        }

        $job_image = null;
        if($config['job_image_field']) {
            if (!count($errors) > 0) {
                if (isset($_FILES['job_image'])) {
                    $file = $_FILES['job_image'];
                    $valid_formats = array("jpg", "jpeg", "png"); // Valid image formats
                    $filename = $file['name'];
                    $ext = getExtension($filename);
                    $ext = strtolower($ext);
                    if (!empty($filename)) {
                        //File extension check
                        if (in_array($ext, $valid_formats)) {
                            //Valid File extension check
                            $main_path = ROOTPATH . "/storage/products/";
                            $filename = uniqid(time()) . '.' . $ext;
                            move_uploaded_file($file['tmp_name'], $main_path . $filename);
                            // resize image
                            resizeImage(200, $main_path . $filename, $main_path . $filename);
                            $job_image = $filename;
                        } else {
                            $errors[]['message'] = __("Sorry, only JPG, JPEG and PNG files are allowed.");
                        }
                    }

                }
            }
        }


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
                $cityid = $_POST['city'];

                if($config['post_desc_editor'] == 1)
                    $description = validate_input($_POST['content'],true);
                else
                    $description = validate_input($_POST['content']);

                $citydata = get_cityDetail_by_id($cityid);
                $country = $citydata['country_code'];
                $state = $citydata['subadmin1_code'];

                $latlong = '';
                if(isset($_POST['location'])){
                    $location = $_POST['location'];
                    $mapLat = $_POST['latitude'];
                    $mapLong = $_POST['longitude'];
                    $latlong = $mapLat . "," . $mapLong;
                }else{
                    $location = '';
                }

                $post_title = removeEmailAndPhoneFromString($_POST['title']);
                $slug = create_post_slug($post_title);

                if(isset($_POST['tags'])){
                    $tags = $_POST['tags'];
                }else{
                    $tags = '';
                }

                if($config['post_auto_approve'] == 1){
                    $status = "active";
                }else{
                    $status = "pending";
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

                if($config['company_enable']) {
                    if (empty($_POST['company'])) {
                        // save company details
                        $company = ORM::for_table($config['db']['pre'] . 'companies')->create();
                        $company->user_id = $_SESSION['user']['id'];
                        $company->name = removeEmailAndPhoneFromString($_POST['company_name']);
                        $company->description = validate_input($_POST['company_desc']);
                        $company->reg_no = validate_input($reg_no);
                        $company->logo = $company_logo;
                        $company->created_at = $timenow;
                        $company->updated_at = $timenow;
                        $company->save();
                        $company_id = $company->id();
                    } else {
                        $company_id = $_POST['company'];
                    }
                }else{
                    $company_id = 0;
                }

                $item_insrt = ORM::for_table($config['db']['pre'].'product')->create();
                $item_insrt->user_id = $_SESSION['user']['id'];
                $item_insrt->company_id = $company_id;
                $item_insrt->product_name = validate_input($post_title);
                $item_insrt->slug = $slug;
                $item_insrt->status = $status;
                $item_insrt->category = validate_input($_POST['catid']);
                $item_insrt->sub_category = validate_input($_POST['subcatid']);
                $item_insrt->description = $description;
                $item_insrt->product_type = validate_input($_POST['job_type']);
                $item_insrt->salary_min = validate_input($salary_min);
                $item_insrt->salary_max = validate_input($salary_max);
                $item_insrt->salary_type = validate_input($salary_type);
                $item_insrt->negotiable = validate_input($negotiable);
                $item_insrt->phone = validate_input($phone);
                $item_insrt->hide_phone = validate_input($hide_phone);
                $item_insrt->application_url = $_POST['application_url'];
                $item_insrt->location = validate_input($location);
                $item_insrt->city = validate_input($_POST['city']);
                $item_insrt->state = validate_input($state);
                $item_insrt->country = validate_input($country);
                $item_insrt->latlong = validate_input($latlong);
                $item_insrt->screen_shot = $job_image;
                $item_insrt->tag = validate_input($tags);
                $item_insrt->created_at = $timenow;
                $item_insrt->updated_at = $timenow;
                $item_insrt->expire_date = $expire_timestamp;
                $item_insrt->save();

                $product_id = $item_insrt->id();
                add_post_customField_data($_POST['catid'], $_POST['subcatid'],$product_id);

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
                    $_SESSION['quickad'][$access_token]['product_id'] = $product_id;
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
                    $ad_link = $link['POST-DETAIL'] . "/" . $product_id;

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

// get job types
$rows = ORM::for_table($config['db']['pre'].'product_type')
        ->where('active', '1')
        ->order_by_asc('position')
        ->find_many();
$post_types = array();
foreach($rows as $row){
    $post_types[$row['id']]['id'] = $row['id'];
    $post_types[$row['id']]['title'] = get_productType_title_by_id($row['id']);
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

//Print Template
HtmlTemplate::display('job-post', array(
    'countrylist' => get_country_list(),
    'category' => get_maincategory(),
    'posttypes' => $post_types,
    'salarytypes' => $salary_types,
    'companies' => $companies,
    'customfields' => $custom_fields,
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
