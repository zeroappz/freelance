<?php
if (checkloggedin()) {
    update_lastactive();
}

global $match;
if (!isset($match['params']['id'])) {
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit;
}

$_GET['id'] = $match['params']['id'];

$num_rows = ORM::for_table($config['db']['pre'] . 'product')
    ->where('id', $_GET['id'])
    ->count();
$item_custom = array();
$item_custom_textarea = array();
$item_checkbox = array();

if ($num_rows > 0) {

    $sql = "SELECT p.*, u.username as username,u.name as user_name, u.image as user_image, u.city as user_city, u.city_code as user_city_code, u.country as user_country, u.phone as user_phone, u.email as user_email, u.website as user_website, c.id company_id, c.name company_name, c.reg_no reg_no, c.logo company_image, c.city company_city, c.state company_state, c.phone company_phone, c.email company_email, c.website company_website FROM `" . $config['db']['pre'] . "product` p LEFT JOIN `" . $config['db']['pre'] . "companies` c on p.company_id = c.id INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id WHERE p.id = '" . $_GET['id'] . "' ";

    $info = ORM::for_table($config['db']['pre'] . 'product')->raw_query($sql)->find_one();
    // output data of each row
    update_itemview($_GET['id']);

    $item_id = $info['id'];
    $item_title = $info['product_name'];

    if($config['company_enable'] && !empty($info['company_id'])) {
        $company_id = $info['company_id'];
        $company_name = $info['company_name'];
        $company_reg_no = $info['reg_no'];
        $company_image = !empty($info['company_image']) ? $info['company_image'] : 'default.png';
        $company_image = $config['site_url'].'storage/products/'.$company_image;
        $company_city = get_cityName_by_id($info['company_city']);
        $company_state = get_stateName_by_id($info['company_state']);
        $company_phone = $info['company_phone'];
        $company_email = $info['company_email'];
        $company_website = $info['company_website'];
        $company_link = $link['COMPANY-DETAIL'] . '/' . $info['company_id'] . '/' . create_slug($info['company_name']);
    }else{
        $company_id = $info['user_id'];
        $company_name = $info['user_name'];
        $company_reg_no = '';
        $company_image = !empty($info['user_image']) ? $info['user_image'] : 'default_user.png';
        $company_image = $config['site_url'].'storage/profile/'.$company_image;
        $company_city = $info['user_city'];
        $company_state = $info['user_country'];
        $company_phone = $info['user_phone'];
        $company_email = $info['user_email'];
        $company_website = $info['user_website'];
        $company_link = $link['PROFILE'] . '/' . $info['username'];

        if(!empty($info['user_city_code'])) {
            $city_detail = get_cityDetail_by_id($info['user_city_code']);
            $company_city = $city_detail['asciiname'];
            $company_state = get_stateName_by_id($city_detail['subadmin1_code']);
        }
    }

    $user_name = $info['user_name'];
    $user_link = $link['PROFILE'] . '/' . $info['username'];
    $item_status = $info['status'];
    $item_featured = $info['featured'];
    $item_urgent = $info['urgent'];
    $item_highlight = $info['highlight'];
    $item_description = $info['description'];
    $item_tag = $info['tag'];
    $item_location = $info['location'];
    $item_city = get_cityName_by_id($info['city']);
    $item_state = get_stateName_by_id($info['state']);
    $item_country = get_countryName_by_code($info['country']);

    $item_product_type = get_productType_title_by_id($info['product_type']);
    $item_salary_type = get_salaryType_title_by_id($info['salary_type']);
    $item_salary_min = price_format($info['salary_min'], $info['country']);
    $item_salary_max = price_format($info['salary_max'], $info['country']);
    $item_negotiable = $info['negotiable'];
    if ($item_negotiable == 1)
        $item_negotiable = __("Negotiable");
    else
        $item_negotiable = "";

    $item_image = $info['screen_shot'];

    $item_view = thousandsCurrencyFormat($info['view']);
    $item_application_url = $info['application_url'];
    $item_created_at = timeAgo($info['created_at']);
    $item_updated_at = timeago($info['updated_at']);
    $item_catid = $info['category'];
    $get_main = get_maincat_by_id($info['category']);
    $item_category = $get_main['cat_name'];
    $item_catlink = $config['site_url'] . 'category/' . $get_main['slug'];

    $get_sub = $item_sub_category = $item_subcatlink = null;
    if (!empty($info['sub_category'])) {
        $get_sub = get_subcat_by_id($info['sub_category']);
        $item_sub_category = $get_sub['sub_cat_name'];
        $item_subcatlink = $config['site_url'] . 'category/' . $get_main['slug'] . '/' . $get_sub['slug'];
    }


    $item_phone = $info['phone'];
    $item_hide_phone = $info['hide_phone'];

    $hide_contact = 0;

    if ($config['contact_validation'] == '1') {
        if (checkloggedin()) {
            if ($item_phone != "" && $item_hide_phone == '0') {
                $item_hide_phone = "no";
            } else {
                $item_hide_phone = "yes";
            }
        } else {
            $hide_contact = 1;
            $item_hide_phone = "yes";
        }
    } else {
        if ($item_phone != "" && $item_hide_phone == '0') {
            $item_hide_phone = "no";
        } else {
            $item_hide_phone = "yes";
        }
    }

    $item_contact_phone = $info['contact_phone'];
    $item_contact_email = $info['contact_email'];
    $item_contact_chat = $info['contact_chat'];

    $count = 0;
    $q_result = ORM::for_table($config['db']['pre'] . 'custom_data')
        ->where('product_id', $item_id)
        ->find_many();
    $item_custom_field = count($q_result);
    foreach ($q_result as $customdata) {
        $field_id = $customdata['field_id'];
        $field_type = $customdata['field_type'];
        $field_data = $customdata['field_data'];

        $custom_fields_title = get_customField_title_by_id($field_id);

        if ($field_type == 'checkboxes') {
            $checkbox_value2 = array();

            $checkbox_value = explode(",", $field_data);

            foreach ($checkbox_value as $val) {
                $val = get_customOption_by_id(trim($val));
                $checkbox_value2[] = $val;
            }
            if ($custom_fields_title != "") {
                $item_checkbox[$field_id]['title'] = $custom_fields_title;
                $item_checkbox[$field_id]['value'] = implode('  ', $checkbox_value2);
            }

        }

        if ($field_type == 'textarea') {
            $item_custom_textarea[$field_id]['title'] = $custom_fields_title;
            $item_custom_textarea[$field_id]['value'] = stripslashes($field_data);
        }

        if ($field_type == 'radio-buttons' or $field_type == 'drop-down') {
            $custom_fields_data = get_customOption_by_id($field_data);
            $item_custom[$field_id]['title'] = $custom_fields_title;
            $item_custom[$field_id]['value'] = $custom_fields_data;
        }

        if ($field_type == 'text-field') {
            $custom_fields_data = stripcslashes($field_data);
            $item_custom[$field_id]['title'] = $custom_fields_title;
            $item_custom[$field_id]['value'] = $custom_fields_data;
        }
    }


    if (!empty($info['latlong'])) {
        $latlong = explode(',', $info['latlong']);
        $lat = $latlong[0];
        $long = $latlong[1];
        if (empty($lat) || empty($long)) {
            $data = get_cityDetail_by_id($info['city']);
            $lat = $data['latitude'];
            $long = $data['longitude'];
        }
    } else {
        $data = get_cityDetail_by_id($info['city']);
        $lat = $data['latitude'];
        $long = $data['longitude'];
    }

    $item_author_id = $info['user_id'];
    $info2 = get_user_data(null, $item_author_id);

    $item_author_name = $info2['name'];
    $item_author_username = $info2['username'];
    if (checkloggedin()) {
        $item_author_email = $info2['email'];
    } else {
        $item_author_email = "Login to see";
    }

    $item_author_image = $info2['image'];
    $item_author_country = $info2['country'];
    $item_author_joined = $info2['created_at'];
    $item_author_online = $info2['online'];

    if ($info2['online'] == 1) {
        $item_author_online = "Online";
    } else {
        $item_author_online = "Offline";
    }

    $author_url = create_slug($info2['username']);
    $item_author_link = $link['PROFILE'] . '/' . $author_url;

    $pro_url = create_slug($info['product_name']);
    $item_link = $link['POST-DETAIL'] . '/' . $item_id . '/' . $pro_url;

    if ($info['tag'] != "") {
        $tag = explode(',', $info['tag']);
        $tag2 = array();
        foreach ($tag as $val) {
            //REMOVE SPACE FROM $VALUE ----
            $tagTrim = preg_replace("/[\s_]/", "-", trim($val));
            $tag2[] = '<a href="' . $config['site_url'] . 'listing?keywords=' . $tagTrim . '">' . $val . '</a>';
        }
        $item_tag = implode('  ', $tag2);
        $show_tag = 1;
    } else {
        $item_tag = "";
        $show_tag = 0;
    }
    $meta_image = $config['site_url'] . 'storage/products/' . $company_image;


} else {
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit;
}

$country_code = check_user_country();
$result1 = ORM::for_table($config['db']['pre'] . 'product')
    ->table_alias('p')
    ->select('p.*')
    ->select('c.name', 'company_name')
    ->select('c.logo', 'company_logo')
    ->where(array(
        'p.status' => 'active',
        'p.hide' => '0',
        'p.category' => $item_catid,
        'p.country' => $country_code
    ))
    ->join($config['db']['pre'] . 'companies', array('p.company_id', '=', 'c.id'), 'c')
    ->where_not_equal('p.id', $item_id)
    ->order_by_desc('p.id')
    ->limit(4)
    ->find_many();

//Loop for list view
$item = array();
if (count($result1) > 0) {
    // output data of each row
    foreach ($result1 as $info1) {
        $item[$info1['id']]['id'] = $info1['id'];
        $item[$info1['id']]['featured'] = $info1['featured'];
        $item[$info1['id']]['urgent'] = $info1['urgent'];
        $item[$info1['id']]['highlight'] = $info1['highlight'];
        $item[$info1['id']]['product_name'] = $info1['product_name'];
        $item[$info1['id']]['product_id'] = $info1['company_id'];
        $item[$info1['id']]['company_name'] = $info1['company_name'];
        $item[$info1['id']]['company_image'] = !empty($info1['company_logo']) ? $info1['company_logo'] : 'default.png';
        $item[$info1['id']]['location'] = $info1['location'];
        $item[$info1['id']]['city'] = $info1['city'];
        $item[$info1['id']]['cityname'] = get_cityName_by_id($info1['city']);
        $item[$info1['id']]['state'] = get_stateName_by_id($info1['state']);
        $item[$info1['id']]['country'] = get_countryName_by_code($info1['country']);
        $item[$info1['id']]['created_at'] = timeago($info1['created_at']);
        $item[$info1['id']]['author_id'] = $info['user_id'];
        $get_main = get_maincat_by_id($info1['category']);
        $item[$info1['id']]['category'] = $get_main['cat_name'];

        $item[$info1['id']]['image'] = !empty($info1['screen_shot']) ? $info1['screen_shot'] : $item[$info1['id']]['company_image'];

        $item[$info1['id']]['product_type'] = get_productType_title_by_id($info1['product_type']);
        $item[$info1['id']]['salary_type'] = get_salaryType_title_by_id($info1['salary_type']);
        $item[$info1['id']]['salary_min'] = price_format($info1['salary_min'], $info1['country']);
        $item[$info1['id']]['salary_max'] = price_format($info1['salary_max'], $info1['country']);

        $userinfo = get_user_data(null, $info1['user_id']);

        $item[$info1['id']]['username'] = $userinfo['username'];

        $pro_url = create_slug($info1['product_name']);
        $item[$info1['id']]['link'] = $link['POST-DETAIL'] . '/' . $info1['id'] . '/' . $pro_url;

        $cat_url = create_slug($get_main['cat_name']);
        $item[$info1['id']]['catlink'] = $config['site_url'] . 'category/' . $info1['category'] . '/' . $cat_url;

        $city = create_slug($item[$info1['id']]['cityname']);
        $item[$info1['id']]['citylink'] = $config['site_url'] . 'city/' . $info1['city'] . '/' . $city;

    }
}

// get resumes of the user
$resumes = array();
$show_apply_form = 1;
if (checkloggedin()) {
    if ($_SESSION['user']['user_type'] == 'user') {
        $result = ORM::for_table($config['db']['pre'] . 'resumes')
            ->where('user_id', $_SESSION['user']['id'])
            ->where('active', '1')->find_many();

        if ($result) {
            foreach ($result as $info) {
                $resumes[$info['id']]['id'] = $info['id'];
                $resumes[$info['id']]['name'] = $info['name'];
                $resumes[$info['id']]['filename'] = $info['filename'];
            }
        }

        if(!$config['non_active_allow']){
            $user_data = get_user_data(null,$_SESSION['user']['id']);
            if($user_data['status'] == 0){
                $show_apply_form = 0;
            }
        }
    }
}


$mailsent = 0;
$error = '';
if (isset($_POST['submit'])) {
    $resume_link = $resume_id = null;

    if (empty($_POST['message'])) {
        $error = __("Message is required");
    }

    if($config['resume_enable']) {
        if (isset($_POST['resume'])) {
            if ($_POST['resume'] == 0 && empty($_FILES['resume_file'])) {
                $error = __("Resume is required");
            }
        } else {
            $error = __("Resume is required");
        }

        if ($error == '') {
            if ($_POST['resume'] == 0) {
                // save resume
                $resume_file = '';
                $file = $_FILES['resume_file'];
                // Valid formats
                $resume_files = trim(get_option("resume_files"));
                $valid_formats = explode(',', $resume_files);
                $filename = $file['name'];
                $ext = getExtension($filename);
                $ext = strtolower($ext);
                if (!empty($filename)) {
                    //File extension check
                    if (in_array($ext, $valid_formats)) {
                        $main_path = ROOTPATH . "/storage/resumes/";
                        $filename = uniqid(time()) . '.' . $ext;
                        if (move_uploaded_file($file['tmp_name'], $main_path . $filename)) {
                            $resume_file = $filename;
                        } else {
                            $error = __("Error: Please try again.");
                        }
                    } else {
                        $error = __("Only pdf, doc, docx, rtf, rtx, ppt, pptx, jpeg, jpg, bmp, png file types allowed.");
                    }
                }

                if ($error == '') {
                    $now = date("Y-m-d H:i:s");
                    $resume_create = ORM::for_table($config['db']['pre'] . 'resumes')->create();
                    $resume_create->name = date('Y-m-d-h-i');
                    $resume_create->filename = $resume_file;
                    $resume_create->user_id = $_SESSION['user']['id'];
                    $resume_create->created_at = $now;
                    $resume_create->updated_at = $now;
                    $resume_create->save();

                    $resume_link = $config['site_url'] . "storage/resumes/" . $resume_file;
                    $resume_id = $resume_create->id();
                }
            } else {
                $result = ORM::for_table($config['db']['pre'] . 'resumes')
                    ->where('user_id', $_SESSION['user']['id'])
                    ->where('id', $_POST['resume'])
                    ->where('active', '1')
                    ->find_one();

                if (!empty($result)) {
                    $resume_link = $config['site_url'] . "storage/resumes/" . $result['filename'];
                    $resume_id = $_POST['resume'];
                } else {
                    $error = __("Resume is required");
                }
            }
        }
    }

    if ($error == '') {
        $now = date("Y-m-d H:i:s");
        $apply_create = ORM::for_table($config['db']['pre'] . 'user_applied')->create();
        $apply_create->user_id = $_SESSION['user']['id'];
        $apply_create->job_id = $item_id;
        $apply_create->resume_id = $resume_id;
        $apply_create->message = validate_input($_POST['message']);
        $apply_create->created_at = $now;
        $apply_create->save();

        /*SEND EMAIL TO SELLER*/
        $ad_link = $link['POST-DETAIL'] . "/" . $item_id;
        $ses_userdata = get_user_data(null, $_SESSION['user']['id']);

        $html = $config['email_sub_contact_seller'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{ADTITLE}', $item_title, $html);
        $html = str_replace ('{ADLINK}', $ad_link, $html);
        $html = str_replace ('{SELLER_NAME}', $item_author_name, $html);
        $html = str_replace ('{SELLER_EMAIL}', $item_author_email, $html);
        $html = str_replace ('{SENDER_NAME}', $ses_userdata['name'], $html);
        $html = str_replace ('{SENDER_EMAIL}', $ses_userdata['email'], $html);
        $html = str_replace ('{SENDER_PROFILE}', $link['PROFILE'].'/'.$ses_userdata['username'], $html);
        $html = str_replace ('{RESUME_LINK}', $resume_link, $html);
        $email_subject = $html;

        $html = $config['email_message_contact_seller'];
        $html = str_replace ('{SITE_TITLE}', $config['site_title'], $html);
        $html = str_replace ('{ADTITLE}', $item_title, $html);
        $html = str_replace ('{ADLINK}', $ad_link, $html);
        $html = str_replace ('{SELLER_NAME}', $item_author_name, $html);
        $html = str_replace ('{SELLER_EMAIL}', $item_author_email, $html);
        $html = str_replace ('{SENDER_NAME}', $ses_userdata['name'], $html);
        $html = str_replace ('{SENDER_EMAIL}', $ses_userdata['email'], $html);
        $html = str_replace ('{SENDER_PHONE}', $ses_userdata['phone'], $html);
        $html = str_replace ('{SENDER_PROFILE}', $link['PROFILE'].'/'.$ses_userdata['username'], $html);
        $html = str_replace ('{RESUME_LINK}', $resume_link, $html);
        $html = str_replace ('{MESSAGE}', $_POST['message'], $html);
        $email_body = $html;

        email($item_author_email,$item_author_name,$email_subject,$email_body);

        message(__("Success"), __("An email sent to the company."),$item_link);
    }
}

$postid = base64_url_encode($item_id);
$qcuserid = base64_url_encode($item_author_id);
$quickchat_url = $link['MESSAGE']."/?postid=$postid&userid=$qcuserid";

$GetCategory = get_maincategory();
$cat_dropdown = get_categories_dropdown($lang);


$meta_desc = substr(strip_tags($item_description), 0, 150);
$meta_desc = trim(preg_replace('/\s\s+/', ' ', $meta_desc));

if (check_user_upgrades($item_author_id)) {
    $sub_info = get_user_membership_detail($item_author_id);
    $sub_title = $sub_info['sub_title'];
    $sub_image = $sub_info['sub_image'];
} else {
    $sub_title = '';
    $sub_image = '';
}

//Print Template
HtmlTemplate::display('job-detail', array(
    'sub_title' => $sub_title,
    'sub_image' => $sub_image,
    'total_items' => count($item),
    'items' => $item,
    'cat_dropdown' => $cat_dropdown,
    'category' => $GetCategory,
    'item_customfield' => $item_custom_field,
    'item_custom' => $item_custom,
    'item_custom_textarea' => $item_custom_textarea,
    'item_custom_checkbox' => $item_checkbox,
    'resumes' => $resumes,
    'resumes_count' => count($resumes),
    'show_apply_form' => $show_apply_form,
    'error' => $error,
    'item_favorite' => check_product_favorite($item_id),
    'already_applied' => check_user_applied($item_id),
    'company_id' => $company_id,
    'company_name' => $company_name,
    'company_reg_no' => $company_reg_no,
    'company_image' => $company_image,
    'company_city' => $company_city,
    'company_state' => $company_state,
    'company_phone' => $company_phone,
    'company_email' => $company_email,
    'company_website' => $company_website,
    'company_link' => $company_link,
    'user_name' => $user_name,
    'user_link' => $user_link,
    'item_link' => $item_link,
    'item_id' => $item_id,
    'item_title' => $item_title,
    'item_featured' => $item_featured,
    'item_urgent' => $item_urgent,
    'item_highlight' => $item_highlight,
    'item_authorid' => $item_author_id,
    'item_authorlink' => $item_author_link,
    'item_authoruemail' => $item_author_email,
    'item_authorname' => $item_author_name,
    'item_authoruname' => $item_author_username,
    'item_authorimg' => $item_author_image,
    'item_authoronline' => $item_author_online,
    'item_authorcountry' => $item_author_country,
    'item_authorjoined' => $item_author_joined,
    'item_category' => $item_category,
    'item_sub_category' => $item_sub_category,
    'item_catlink' => $item_catlink,
    'item_subcatlink' => $item_subcatlink,
    'item_location' => $item_location,
    'item_city' => $item_city,
    'item_state' => $item_state,
    'item_country' => $item_country,
    'item_lat' => $lat,
    'item_long' => $long,
    'item_created' => $item_created_at,
    'item_updated' => $item_updated_at,
    'item_desc' => $item_description,
    'item_negotiate' => $item_negotiable,
    'item_product_type' => $item_product_type,
    'item_salary_type' => $item_salary_type,
    'item_salary_min' => $item_salary_min,
    'item_salary_max' => $item_salary_max,
    'item_phone' => $item_phone,
    'item_hide_phone' => $item_hide_phone,
    'meta_desc' => $meta_desc,
    'meta_image' => $meta_image,
    'item_status' => $item_status,
    'item_application_url' => $item_application_url,
    'item_image' => $item_image,
    'item_tag' => $item_tag,
    'show_tag' => $show_tag,
    'item_contact_phone' => $item_contact_phone,
    'item_contact_email' => $item_contact_email,
    'item_contact_chat' => $item_contact_chat,
    'item_view' => $item_view,
    'hide_contact' => $hide_contact,
    'mailsent' => $mailsent,
    'quickchat' => get_option("quickchat_on_off"),
    'quickchat_url' => $quickchat_url
));
exit;
?>
