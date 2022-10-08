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

$show_apply_form = 1;
if (checkloggedin()) {
    if ($_SESSION['user']['user_type'] == 'user') {
        if(!$config['non_active_allow']){
            $user_data = get_user_data(null,$_SESSION['user']['id']);
            if($user_data['status'] == 0){
                $show_apply_form = 0;
            }
        }
    }
}



$num_rows = ORM::for_table($config['db']['pre'] . 'project')
    ->where('id', $_GET['id'])
    ->count();
$item_custom = array();
$item_custom_textarea = array();
$item_checkbox = array();

if ($num_rows > 0) {

    $sql = "SELECT p.*, u.username as username,u.name as user_name, u.image as user_image, u.country as user_country, u.phone as user_phone, u.email as user_email, u.online as user_online, u.created_at as user_joined FROM `" . $config['db']['pre'] . "project` p INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id WHERE p.id = '" . $_GET['id'] . "' ";

    $info = ORM::for_table($config['db']['pre'] . 'project')->raw_query($sql)->find_one();
    // output data of each row
    update_projectview($_GET['id']);
    $item_id = $info['id'];
    $item_title = $info['product_name'];
    $user_name = $info['user_name'];
    $user_link = $link['PROFILE'] . '/' . $info['username'];
    $item_status = $info['status'];
    $item_freelancer_id = $info['freelancer_id'];
    $item_featured = $info['featured'];
    $item_urgent = $info['urgent'];
    $item_highlight = $info['highlight'];
    $item_description = nl2br(stripcslashes($info['description']));
    $showmore = (strlen($item_description) > 500)? 1 : 0;
    $item_tag = $info['tag'];
    $item_country = get_countryName_by_code($info['country']);
    $item_salary_type = ($info['salary_type'] == 0)? __("Fixed Price") : __("Hourly Price");
    $item_salary_min = price_format($info['salary_min']);
    $item_salary_max = price_format($info['salary_max']);
    $item_image = $info['screen_shot'];

    $item_view = thousandsCurrencyFormat($info['view']);
    $item_created_at = timeAgo($info['created_at']);
    $item_updated_at = timeago($info['updated_at']);
    $item_catid = $info['category'];
    $get_main = get_maincat_by_id($info['category']);
    $item_category = $get_main['cat_name'];
    $item_catlink = $link['SEARCH_PROJECTS'] . '/' . $get_main['slug'];

    $get_sub = $item_sub_category = $item_subcatlink = null;
    if (!empty($info['sub_category'])) {
        $skills = explode(',', $info['sub_category']);
        $skills2 = implode('\' OR sub_cat_id=\'', $skills);

        $count = 0;
        $skills3 = array();

        $query = "SELECT sub_cat_id,sub_cat_name,slug FROM `".$config['db']['pre']."catagory_sub` WHERE sub_cat_id='" . $skills2 . "' ORDER BY sub_cat_name LIMIT " . count($skills);
        $query_result = mysqli_query ($mysqli,$query) OR error(mysqli_error($mysqli));
        while ($data = mysqli_fetch_array($query_result))
        {
            $count++;

            $skills3[$count]['id'] = $data['sub_cat_id'];
            $skills3[$count]['name'] = $data['sub_cat_name'];
            $skills3[$count]['slug'] = $data['slug'];
            $skills3[$count]['link'] = $link['SEARCH_PROJECTS'] . '/' . $get_main['slug'] . '/' . $data['slug'];
        }

    }

    $item_author_id = $info['user_id'];
    $item_author_name = $info['user_name'];
    $item_author_username = $info['username'];
    $item_author_email = $info['user_email'];
    $item_author_image = $info['user_image'];
    $item_author_country = $info['user_country'];
    $item_author_online = $info['user_online'];
    $item_author_joined = $info['user_joined'];

    if ($item_author_online == 1) {
        $item_author_online = "Online";
    } else {
        $item_author_online = "Offline";
    }

    $author_url = create_slug($item_author_username);
    $item_author_link = $link['PROFILE'] . '/' . $author_url;

    $pro_url = create_slug($info['product_name']);
    $item_link = $link['PROJECT'] . '/' . $item_id . '/' . $pro_url;

    if ($info['tag'] != "") {
        $tag = explode(',', $info['tag']);
        $tag2 = array();
        foreach ($tag as $val) {
            //REMOVE SPACE FROM $VALUE ----
            $tagTrim = preg_replace("/[\s_]/", "-", trim($val));
            $tag2[] = '<a href="' . $config['site_url'] . 'projects?keywords=' . $tagTrim . '">' . $val . '</a>';
        }
        $item_tag = implode('  ', $tag2);
        $show_tag = 1;
    } else {
        $item_tag = "";
        $show_tag = 0;
    }

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

    /******************************************
     *******Start Insert/Update Freelancer Bid ******
     *****************************************/
    $mailsent = 0;
    $error = '';
    $already_applied = check_bid_exist($item_id);
    $bid_amount = '';
    $bid_days = '';
    $bid_message = '';
    if($already_applied){
        $my_bid = ORM::for_table($config['db']['pre'] . 'bids')
            ->where(array(
                'project_id' => $item_id,
                'user_id' => $_SESSION['user']['id']
            ))
            ->find_one();

        if (!empty($my_bid)) {
            $bid_amount = $my_bid['amount'];
            $bid_days = $my_bid['days'];
            $bid_message = $my_bid['message'];
        }
    }
    if (isset($_POST['submit'])) {
        if (empty($_POST['amount'])) {
            $error = __("Amount is required");
        }elseif (empty($_POST['days'])) {
            $error = __("Delivery with in days is required");
        }elseif  (empty($_POST['message'])) {
            $error = __("Message is required");
        }

        if ($error == '') {
            if($already_applied){
                $my_bid = ORM::for_table($config['db']['pre'] . 'bids')
                    ->where(array(
                        'project_id' => $item_id,
                        'user_id' => $_SESSION['user']['id']
                    ))
                    ->find_one();
                $my_bid->set('amount', validate_input($_POST['amount']));
                $my_bid->set('days', validate_input($_POST['days']));
                $my_bid->set('message', validate_input($_POST['message']));
                $my_bid->save();
            }else{
                $now = date("Y-m-d H:i:s");
                $apply_create = ORM::for_table($config['db']['pre'] . 'bids')->create();
                $apply_create->user_id = $_SESSION['user']['id'];
                $apply_create->project_id = $item_id;
                $apply_create->amount = validate_input($_POST['amount']);
                $apply_create->days = validate_input($_POST['days']);
                $apply_create->message = validate_input($_POST['message']);
                $apply_create->created_at = $now;
                $apply_create->save();
            }

            message(__("Success"), __("Bid placed successfully."),$item_link);
        }
    }
    /******************************************
     *******End Insert/Update Freelancer Bid ******
     *****************************************/
} else {
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit;
}

$country_code = check_user_country();

$freelancer_id_orm = ORM::for_table($config['db']['pre'].'project')
    ->select('freelancer_id')
    ->where('id' , $_GET['id'])
    ->find_one();

if(isset($freelancer_id_orm['freelancer_id'])){
    $freelancer_id = $freelancer_id_orm['freelancer_id'];
}else{
    $freelancer_id = 0;
}
/*Freelancers Bidding*/
$total_bid = ORM::for_table($config['db']['pre'] . 'bids')
    ->table_alias('ua')
    ->where(array(
        'u.status' => '1',
        'u.user_type' => 'user',
        'ua.project_id' => $_GET['id']
    ))
    ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
    ->count();

$result = ORM::for_table($config['db']['pre'] . 'bids')
    ->table_alias('ua')
    ->select_many('ua.*', 'u.username', 'u.name', 'u.salary_min', 'u.salary_max', 'u.image','u.country_code','u.online')
    ->where(array(
        'u.status' => '1',
        'u.user_type' => 'user',
        'ua.project_id' => $_GET['id']
    ))
    ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
    ->order_by_expr("(CASE
        WHEN u.id = '$freelancer_id' THEN 1
        ELSE 2
      END), ua.id DESC")
    ->find_many();
$bid = array();
foreach($result as $info){
    $bid[$info['id']]['id'] = $info['id'];
    $bid[$info['id']]['user_id'] = $info['user_id'];
    $bid[$info['id']]['username'] = $info['username'];
    $bid[$info['id']]['name'] = !empty($info['name'])?$info['name']:$info['username'];
    $bid_description = nl2br(stripcslashes($info['message']));
    $bid[$info['id']]['description'] = $bid_description;
    $bid[$info['id']]['showmore'] = (strlen($bid_description) > 1000)? 1 : 0;
    $bid[$info['id']]['amount'] = price_format($info['amount']);
    $bid[$info['id']]['days'] = $info['days'];
    $bid[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default_user.png';
    $bid[$info['id']]['online'] = $info['online'];
    if ($info['online'] == 1) {
        $bid[$info['id']]['online']  = "online";
    } else {
        $bid[$info['id']]['online']  = "offline";
    }

    $bid[$info['id']]['rating'] = averageRating($info['user_id'],$info['user_type']);

    $country_code = $info['country_code'];
    $bid[$info['id']]['country_code'] = strtolower($country_code);
    $bid[$info['id']]['country'] = get_countryName_by_code($country_code);
    $bid[$info['id']]['salary_min'] = price_format($info['salary_min']);
    $bid[$info['id']]['salary_max'] = price_format($info['salary_max']);

    $bid[$info['id']]['favorite'] = check_user_favorite($info['user_id']);

    $postid = base64_url_encode($item_id);
    $qcuserid = base64_url_encode($info['user_id']);
    $quickchat_url = $link['MESSAGE']."/?postid=$postid&userid=$qcuserid";

    $bid[$info['id']]['quickchat_url'] = $quickchat_url;
}
/*Freelancers Bidding*/

// Get membership details
$group_info = get_user_membership_settings();
$freelancer_commission = $group_info['freelancer_commission'];

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

HtmlTemplate::display('project_detail', array(
    'meta_desc' => $meta_desc,
    'sub_title' => $sub_title,
    'sub_image' => $sub_image,
    'bids' => $bid,
    'skills' => $skills3,
    'freelancer_commission' => $freelancer_commission,
    'total_bid' => $total_bid,
    'item_customfield' => $item_custom_field,
    'item_custom' => $item_custom,
    'item_custom_textarea' => $item_custom_textarea,
    'item_custom_checkbox' => $item_checkbox,
    'show_apply_form' => $show_apply_form,
    'error' => $error,
    'item_favorite' => check_product_favorite($item_id),
    'already_applied' => $already_applied,
    'bid_amount' => $bid_amount,
    'bid_days' => $bid_days,
    'bid_message' => $bid_message,
    'user_name' => $user_name,
    'user_link' => $user_link,
    'item_id' => $item_id,
    'item_title' => $item_title,
    'item_link' => $item_link,
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
    'item_country' => $item_country,
    'item_created' => $item_created_at,
    'item_updated' => $item_updated_at,
    'item_desc' => $item_description,
    'item_showmore' => $showmore,
    'item_salary_type' => $item_salary_type,
    'item_salary_min' => $item_salary_min,
    'item_salary_max' => $item_salary_max,
    'item_status' => $item_status,
    'item_freelancer_id' => $item_freelancer_id,
    'item_image' => $item_image,
    'item_tag' => $item_tag,
    'show_tag' => $show_tag,
    'item_view' => $item_view,
    'mailsent' => $mailsent,
    'total_review' => count_user_review($item_author_id,'employer'),
    'average_rating' => averageRating($item_author_id,'employer')
));
exit;
?>
