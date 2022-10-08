<?php

if(isset($match['params']['country'])) {
    if ($match['params']['country'] != ""){
        change_user_country($match['params']['country']);
    }
}

if (isset($_GET['action'])) {
    if ($_GET['action'] == "edit_ad") {
        ajax_edit_advertise();
    }
}

function ajax_edit_advertise(){

    global $config, $lang, $link;
    $item_screen = "";
    if (!checkloggedin()) {
        return false;
    }

    if(!check_valid_author($_POST['product_id'])){
        return false;
    }

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
            $errors[]['message'] = $lang['ADTITLE_REQ'];
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Job Description is required.");
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
        if (isset($_POST['comments']) && empty($_POST['comments'])) {
            $errors[]['message'] = __("You must give a brief description of any changes you have made.");
        }

        /*IF : USER GO TO PEMIUM POST*/
        $urgent = isset($_POST['urgent']) ? 1 : 0;
        $featured = isset($_POST['featured']) ? 1 : 0;
        $highlight = isset($_POST['highlight']) ? 1 : 0;

        /*$payment_req = "";
        if (isset($_POST['urgent'])) {
            if (!isset($_POST['payment_id'])) {
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }
        if (isset($_POST['featured'])) {
            if (!isset($_POST['payment_id'])) {
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }
        if (isset($_POST['highlight'])) {
            if (!isset($_POST['payment_id'])) {
                $payment_req = $lang['PAYMENT_METHOD_REQ'];
            }
        }
        if (!empty($payment_req))
            $errors[]['message'] = $payment_req;*/

        /*IF : USER GO TO PREMIUM POST*/

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
            if (checkloggedin()) {
                $salary_min = $_POST['salary_min'];
                $salary_max = $_POST['salary_max'];
                $salary_type = $_POST['salary_type'];
                $phone = $_POST['phone'];
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

                $timenow = date('Y-m-d H:i:s');
                $citydata = get_cityDetail_by_id($cityid);
                $country = $citydata['country_code'];
                $state = $citydata['subadmin1_code'];

                if(isset($_POST['tags'])){
                    $tags = $_POST['tags'];
                }else{
                    $tags = '';
                }

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

                $info = ORM::for_table($config['db']['pre'].'product')
                    ->select('status')
                    ->find_one($_POST['product_id']);

                $item_status = $info['status'];

                if($config['company_enable']) {
                    if (empty($_POST['company'])) {
                        // save company details
                        $company = ORM::for_table($config['db']['pre'] . 'companies')->create();
                        $company->user_id = $_SESSION['user']['id'];
                        $company->name = removeEmailAndPhoneFromString($_POST['company_name']);
                        $company->description = validate_input($_POST['company_desc'],true);
                        $company->reg_no = validate_input($reg_no);
                        $company->logo = $company_logo;
                        $company->save();
                        $company_id = $company->id();
                    } else {
                        $company_id = $_POST['company'];
                    }
                }else{
                    $company_id = 0;
                }

                if($item_status == "pending" or $config['post_auto_approve'] == 1)
                {
                    $item_edit = ORM::for_table($config['db']['pre'].'product')->find_one($_POST['product_id']);
                    $item_edit->set('company_id', $company_id);
                    $item_edit->set('product_name', validate_input($post_title));
                    $item_edit->set('slug', $slug);
                    $item_edit->set('category', validate_input($_POST['catid']));
                    $item_edit->set('sub_category', validate_input($_POST['subcatid']));
                    $item_edit->set('description', $description);
                    $item_edit->set('product_type', validate_input($_POST['job_type']));
                    $item_edit->set('negotiable', validate_input($negotiable));
                    $item_edit->set('salary_min', validate_input($salary_min));
                    $item_edit->set('salary_max', validate_input($salary_max));
                    $item_edit->set('salary_type', validate_input($salary_type));
                    $item_edit->set('phone', validate_input($phone));
                    $item_edit->set('hide_phone', validate_input($hide_phone));
                    $item_edit->set('application_url', $_POST['application_url']);
                    $item_edit->set('location', validate_input($location));
                    $item_edit->set('city', validate_input($cityid));
                    $item_edit->set('state', validate_input($state));
                    $item_edit->set('country', validate_input($country));
                    $item_edit->set('latlong', $latlong);
                    $item_edit->set('tag', validate_input($tags));
                    $item_edit->set('screen_shot', $job_image);
                    $item_edit->set('updated_at', $timenow);
                    $item_edit->save();
                }
                elseif($item_status == "active" or $item_status == "softreject" or $item_status == "expire")
                {
                    $item_insrt = ORM::for_table($config['db']['pre'].'product_resubmit')->create();
                    $item_insrt->product_id = validate_input($_POST['product_id']);
                    $item_insrt->user_id = $_SESSION['user']['id'];
                    $item_insrt->company_id = $company_id;
                    $item_insrt->product_name = validate_input($post_title);
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
                    $item_insrt->latlong = $latlong;
                    $item_insrt->tag = validate_input($tags);
                    $item_insrt->screen_shot = $job_image;
                    $item_insrt->created_at = $timenow;
                    $item_insrt->comments = validate_input($_POST['comments']);
                    $item_insrt->save();
                }

                $product_id = validate_input($_POST['product_id']);

                add_post_customField_data($_POST['catid'], $_POST['subcatid'],$product_id);

                $amount = 0;
                $trans_desc = __("Package");

                // Get membership settings
                $group_get_info = get_user_membership_settings();
                $urgent_project_fee = $group_get_info['urgent_project_fee'];
                $featured_project_fee = $group_get_info['featured_project_fee'];
                $highlight_project_fee = $group_get_info['highlight_project_fee'];
                $urgent_duration = $group_get_info['urgent_duration'];
                $featured_duration = $group_get_info['featured_duration'];
                $highlight_duration = $group_get_info['highlight_duration'];
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
                    $premium_tpl .= ' <div class="ModalPayment-totalCost">
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
            } else {
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

if(checkloggedin()) {

    $status = check_item_status($_GET['id']);

    $header_text = "";
    $header_note = "";
    $resubmit = "";
    if($status == "pending"){
        $header_text = __("Edit Job");
        $resubmit = 0;
    }
    elseif($status == "active" or $status == "softreject" or $status == "hide" or $status == "expire")
    {
        if(check_valid_resubmission($_GET['id'])){
            $header_text = __("Re-submission Job");
            $header_note = __("Re-submission job required review. Our team will check the content and after approve it.");
            $resubmit = 1;
        }else{
            message(__("Already Exist"),__("Your job already resubmitted and pending for approve. If you want to make change, you can delete it from re-submitted jobs and resubmit again."),'',false);
            exit;
        }

    }else {
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit;
    }


    if(check_valid_author($_GET['id'])){

        global $errors, $custom_fields, $catid,$catName, $subcatid,$subcatName, $title, $description, $negotiable, $phone, $hide_phone, $tags, $cityid, $mapLat, $mapLong, $seller_name, $seller_email;

        if(isset($_GET['country'])) {
            if ($_GET['country'] != ""){
                change_user_country($_GET['country']);
            }
        }

        $country_code = check_user_country();

        $currency_info = set_user_currency($country_code);
        $currency_sign = $currency_info['html_entity'];

        $info = ORM::for_table($config['db']['pre'].'product')->find_one($_GET['id']);
        if (!empty($info)) {
            // output data of each row
            $item_id = $info['id'];
            $company_id = $info['company_id'];
            $catid          = $info['category'];
            $subcatid       = $info['sub_category'];
            $title          = $info['product_name'];
            $description    = stripcslashes(nl2br($info['description']));
            $item_product_type = $info['product_type'];
            $item_salary_type = $info['salary_type'];
            $item_salary_min = !empty($info['salary_min'])?$info['salary_min']:'';
            $item_salary_max = !empty($info['salary_max'])?$info['salary_max']:'';
            $phone          = !empty($info['phone'])?$info['phone']:'';
            $negotiable     = $info['negotiable'];
            $hide_phone     = $info['hide_phone'];
            $item_application_url = $info['application_url'];
            $tags           = $info['tag'];
            $cityid         = $info['city'];
            $location = $info['location'];
            $latlong = $info['latlong'];
            if(!empty($latlong)){
                $map = explode(',', $latlong);
                $mapLat = $map[0];
                $mapLong = $map[1];
            }else{
                $mapLat = '';
                $mapLong = '';
            }

            $item_featured = $info['featured'];
            $item_urgent = $info['urgent'];
            $item_highlight = $info['highlight'];

            $maincat = get_maincat_by_id($catid);
            $catName = $maincat['cat_name'];
            $subcat = get_subcat_by_id($subcatid);
            $subcatName = $subcat['sub_cat_name'];

            $custom_fields = array();
            $custom_data = array();

            $customdata = ORM::for_table($config['db']['pre'].'custom_data')
                ->select_many('field_id','field_data')
                ->where('product_id',$item_id)
                ->find_many();

            foreach ($customdata as $array){
                $custom_fields[] = $array['field_id'];
                $custom_data[] = $array['field_data'];
            }

            $custom_fields = get_customFields_by_catid($catid, $subcatid,false, $custom_fields, $custom_data);

            foreach ($custom_fields as $key => $value) {
                if ($value['userent']) {
                    $custom_db_fields[$value['id']] = $value['title'];
                    $custom_db_data[$value['id']] = str_replace(',', '&#44;', $value['default']);
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
            $rows = ORM::for_table($config['db']['pre'].'companies')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('status', '1')
                    ->find_many();
            $companies = array();
            foreach($rows as $row){
                $companies[$row['id']]['id'] = $row['id'];
                $companies[$row['id']]['title'] = $row['name'];
            }

            //Print
            HtmlTemplate::display('job-edit', array(
                'item_id' => $item_id,
                'htmlpage' => get_html_pages(),
                'countrylist' => get_country_list(),
                'categories' => get_maincategory($catid),
                'subcategories' => get_subcat_of_maincat($catid,false,$subcatid),
                'posttypes' => $post_types,
                'salarytypes' => $salary_types,
                'companies' => $companies,
                'customfields' => $custom_fields,
                'showcustomfield' => (count($custom_fields) > 0) ? 1 : 0,
                'catid' => $catid,
                'subcatid' => (int)$subcatid,
                'category' => $catName,
                'subcategory' => $subcatName,
                'company_id' => $company_id,
                'title' => $title,
                'description' => $description,
                'product_type' => $item_product_type,
                'salary_type' => $item_salary_type,
                'salary_min' => $item_salary_min,
                'salary_max' => $item_salary_max,
                'phone' => $phone,
                'negotiable' => $negotiable,
                'hidephone' => $hide_phone,
                'application_url' => $item_application_url,
                'tags' => $tags,
                'city' => $cityid,
                'cityname' => get_cityName_by_id($cityid),
                'location' => $location,
                'latitude' => $mapLat,
                'longitude' => $mapLong,
                'user_country' => strtolower($country_code),
                'seller_name' => $seller_name,
                'seller_email' => $seller_email,
                'user_currency_sign' => $currency_sign,
                'header_text' => $header_text,
                'header_note' => $header_note,
                'resubmit' => $resubmit,
                'featured' => $item_featured,
                'urgent' => $item_urgent,
                'highlight' => $item_highlight,
                'featured_fee' => $featured_project_fee,
                'urgent_fee' => $urgent_project_fee,
                'highlight_fee' => $highlight_project_fee,
                'featured_duration' => $featured_duration,
                'urgent_duration' => $urgent_duration,
                'highlight_duration' => $highlight_duration,
                'language_direction' => get_current_lang_direction(),
            ));
            exit;
        }
        else {
            error(__("Page Not Found"), __LINE__, __FILE__, 1);
            exit;
        }


    }
    else{
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit;
    }
}
else{
    header("Location: ".$config['site_url']."login?ref=dashboard");
    exit();
}
?>
