<?php
global $config,$lang,$link;
if (checkloggedin()) {
    headerRedirect($link['DASHBOARD']);
}
$start = microtime(true);
$limit = 6;

if(isset($match['params']['country'])) {
    if ($match['params']['country'] != ""){
        change_user_country($match['params']['country']);
    }
}

$country_code = check_user_country();

if($latlong = get_lat_long_of_country($country_code)){
    $mapLat     =  $latlong['lat'];
    $mapLong    =  $latlong['lng'];
}else{
    $mapLat     =  get_option("home_map_latitude");
    $mapLong    =  get_option("home_map_longitude");
}

$item = get_items("","active",true,1,$limit,"p.id",true);

$item2 = array();
if($config['show_latest_jobs_home']){
    $item2 = get_items("","active",false,1,$limit,"p.id",true);
}

$result = ORM::for_table($config['db']['pre'].'catagory_main')
        ->order_by_asc('cat_order')
        ->limit(8)
        ->find_many();
foreach ($result as $info) {
    if($config['lang_code'] != 'en' && $config['userlangsel'] == '1'){
        $maincat = get_category_translation("main",$info['cat_id']);
        $info['cat_name'] = $maincat['title'];
        $info['slug'] = $maincat['slug'];
    }
    $category[$info['cat_id']]['slug'] = $info['slug'];
    $category[$info['cat_id']]['icon'] = $info['icon'];
    $category[$info['cat_id']]['name'] = $info['cat_name'];
    $category[$info['cat_id']]['main_id'] = $info['cat_id'];
    $category[$info['cat_id']]['picture'] = $info['picture'];
    $category[$info['cat_id']]['link'] = $config['site_url'].'projects/'.$info['slug'];

    if(trim($config['home_page']) == "home-freelance"){
        $totalAdsMaincat = ORM::for_table($config['db']['pre'].'project')
            ->where(array(
                'category'=> $info['cat_id'],
                'status'=> 'open'
                ))
            ->count();
    }
    else{
        $totalAdsMaincat = get_items_count(false,"active",false,null,$info['cat_id'],true);
    }

    $category[$info['cat_id']]['main_ads_count'] = $totalAdsMaincat;
    $count = 1;

}

$result1 = ORM::for_table($config['db']['pre'] . 'user')
    ->where(array(
        'status' => '1',
        'user_type' => 'user'
    ))
    ->limit(6)
    ->find_many();
//Loop for list view
$freelancers = array();
if (!empty($result1)) {
    // output data of each row
    foreach ($result1 as $info) {
        $freelancers[$info['id']]['id'] = $info['id'];
        $freelancers[$info['id']]['username'] = $info['username'];
        $freelancers[$info['id']]['name'] = !empty($info['name'])?$info['name']:$info['username'];
        $freelancers[$info['id']]['description'] = !empty($info['tagline'])?$info['tagline']:strlimiter(strip_tags($info['description']),200);
        $freelancers[$info['id']]['sex'] = $info['sex'];
        $freelancers[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default_user.png';
        $freelancers[$info['id']]['country_code'] = strtolower($info['country_code']);

        $freelancers[$info['id']]['category'] = $freelancers[$info['id']]['subcategory'] = null;
        if(!empty($info['category'])){
            $get_cat = get_maincat_by_id($info['category']);
            $freelancers[$info['id']]['category'] = $get_cat['cat_name'];
        }
        if(!empty($info['subcategory'])){
            $get_cat = get_subcat_by_id($info['subcategory']);
            $freelancers[$info['id']]['subcategory'] = $get_cat['sub_cat_name'];
        }

        $user_id = $info['id'];
        $freelancers[$info['id']]['rating'] = averageRating($user_id,$info['user_type']);

        $hourly_rate = price_format(get_user_option($user_id,'hourly_rate','0'));
        $freelancers[$info['id']]['hourly_rate'] = ($hourly_rate)? $hourly_rate: '-';

        $win_project = $rehired_count = 0;

        $win_project = ORM::for_table($config['db']['pre'].'project')
            ->where('freelancer_id' , $user_id)
            ->count();
        $freelancers[$info['id']]['win_project'] = $win_project;
        $rehired = ORM::for_table($config['db']['pre'].'project')
            ->select_many_expr('user_id, COUNT(user_id) as hired')
            ->where('freelancer_id' , $user_id)
            ->group_by('user_id')
            ->having_raw('COUNT(user_id) > 1')
            ->find_many();

        $i = 0;
        foreach($rehired as $info1){
            $i+=$info1['hired']-1;
        }
        $rehired_count = $i;
        $freelancers[$info['id']]['rehired'] = $rehired_count;
    }
}


    $country_code = check_user_country();
    $countryName = get_countryName_by_code($country_code);

    $popular = array();
    $count = 1;

    $result = ORM::for_table($config['db']['pre'].'cities')
        ->select_many('id','asciiname')
        ->where(array(
                'country_code' => $country_code,
                'active' => '1'
            ))
        ->order_by_desc('population')
        ->limit(18)
        ->find_many();
    foreach ($result as $info) {
        $id = $info['id'];
        $name = $info['asciiname'];
        $popular[$count]['tpl'] =  '<li><a href="#" class="selectme" data-id="'.$id.'" data-name="'.$name.'" data-type="city"><span>'.$name.'</span></a></li>';
        $count++;
    }

    $states = array();
    $count = 1;

    $result = ORM::for_table($config['db']['pre'].'subadmin1')
        ->select_many('id','code','asciiname')
        ->where(array(
            'country_code' => $country_code,
            'active' => '1'
        ))
        ->order_by_asc('asciiname')
        ->find_many();

    foreach ($result as $info) {
        $states[$count]['tpl'] = "";
        $id = $info['id'];
        $code = $info['code'];
        $name = $info['asciiname'];
        if($count == 1){
            $states[$count]['tpl'] =  '<li class="selected"><a href="#" class="selectme" data-id="'.$country_code.'" data-name="'.__("All").' '.$countryName.'" data-type="country"><strong>'.__("All").' '.$countryName.'</strong></a></li>';
        }
        $states[$count]['tpl'] .= '<li class=""><a href="#" id="region'.$code.'" class="statedata" data-id="'.$code.'" data-name="'.$name.'"><span>'.$name.' <i class="fa fa-angle-right"></i></span></a></li>';
        $count++;
    }

/**
 * Memebrship Plans
 * Start
 */
$sub_types = array();
$total_monthly = $total_annual = $total_lifetime = 0;
if($config['show_membershipplan_home']) {

    $sub_info = get_user_membership_detail(isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : null);

// custom settings
    $plan_custom = ORM::for_table($config['db']['pre'] . 'plan_options')
        ->where('active', 1)
        ->order_by_asc('position')
        ->find_many();

    $plan = json_decode(get_option('free_membership_plan'), true);
    if ($plan['status']) {
        if ($plan['id'] == $sub_info['id']) {
            $sub_types[$plan['id']]['Selected'] = 1;
        } else {
            $sub_types[$plan['id']]['Selected'] = 0;
        }

        $sub_types[$plan['id']]['id'] = $plan['id'];
        $sub_types[$plan['id']]['title'] = $plan['name'];
        $sub_types[$plan['id']]['monthly_price'] = price_format(0);
        $sub_types[$plan['id']]['annual_price'] = price_format(0);
        $sub_types[$plan['id']]['lifetime_price'] = price_format(0);

        $settings = $plan['settings'];
        $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
        $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
        $sub_types[$plan['id']]['bids'] = $settings['bids'];
        $sub_types[$plan['id']]['skills'] = $settings['skills'];
        $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999") ? __("Unlimited") : $settings['ad_limit'];
        $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
        $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
        $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
        $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
        $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
        $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
        $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
        $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
        $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
        $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

        $sub_types[$plan['id']]['custom_settings'] = '';
        if (!empty($plan_custom)) {
            foreach ($plan_custom as $custom) {
                if (!empty($custom['title']) && trim($custom['title']) != '') {
                    $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                    if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                        $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                    }
                    $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                }
            }
        }
    }

    $plan = json_decode(get_option('trial_membership_plan'), true);
    if ($plan['status']) {
        if ($plan['id'] == $sub_info['id']) {
            $sub_types[$plan['id']]['Selected'] = 1;
        } else {
            $sub_types[$plan['id']]['Selected'] = 0;
        }

        $sub_types[$plan['id']]['id'] = $plan['id'];
        $sub_types[$plan['id']]['title'] = $plan['name'];
        $sub_types[$plan['id']]['monthly_price'] = price_format(0);
        $sub_types[$plan['id']]['annual_price'] = price_format(0);
        $sub_types[$plan['id']]['lifetime_price'] = price_format(0);

        $settings = $plan['settings'];
        $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
        $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
        $sub_types[$plan['id']]['bids'] = $settings['bids'];
        $sub_types[$plan['id']]['skills'] = $settings['skills'];
        $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999") ? $lang['UNLIMITED'] : $settings['ad_limit'];
        $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
        $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
        $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
        $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
        $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
        $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
        $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
        $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
        $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
        $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

        $sub_types[$plan['id']]['custom_settings'] = '';
        if (!empty($plan_custom)) {
            foreach ($plan_custom as $custom) {
                if (!empty($custom['title']) && trim($custom['title']) != '') {
                    $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                    if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                        $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                    }
                    $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                }
            }
        }
    }

    $total_monthly = $total_annual = $total_lifetime = 0;

    $rows = ORM::for_table($config['db']['pre'] . 'plans')
        ->where('status', '1')
        ->find_many();

    foreach ($rows as $plan) {
        if ($plan['id'] == $sub_info['id']) {
            $sub_types[$plan['id']]['Selected'] = 1;
        } else {
            $sub_types[$plan['id']]['Selected'] = 0;
        }

        $sub_types[$plan['id']]['id'] = $plan['id'];
        $sub_types[$plan['id']]['title'] = $plan['name'];
        $sub_types[$plan['id']]['recommended'] = $plan['recommended'];

        $total_monthly += $plan['monthly_price'];
        $total_annual += $plan['annual_price'];
        $total_lifetime += $plan['lifetime_price'];

        $sub_types[$plan['id']]['monthly_price'] = price_format($plan['monthly_price']);
        $sub_types[$plan['id']]['annual_price'] = price_format($plan['annual_price']);
        $sub_types[$plan['id']]['lifetime_price'] = price_format($plan['lifetime_price']);

        $settings = json_decode($plan['settings'], true);
        $sub_types[$plan['id']]['employer_commission'] = $settings['employer_commission'];
        $sub_types[$plan['id']]['freelancer_commission'] = $settings['freelancer_commission'];
        $sub_types[$plan['id']]['bids'] = $settings['bids'];
        $sub_types[$plan['id']]['skills'] = $settings['skills'];
        $sub_types[$plan['id']]['limit'] = ($settings['ad_limit'] == "999") ? $lang['UNLIMITED'] : $settings['ad_limit'];
        $sub_types[$plan['id']]['duration'] = $settings['ad_duration'];
        $sub_types[$plan['id']]['featured_fee'] = $settings['featured_project_fee'];
        $sub_types[$plan['id']]['urgent_fee'] = $settings['urgent_project_fee'];
        $sub_types[$plan['id']]['highlight_fee'] = $settings['highlight_project_fee'];
        $sub_types[$plan['id']]['featured_duration'] = $settings['featured_duration'];
        $sub_types[$plan['id']]['urgent_duration'] = $settings['urgent_duration'];
        $sub_types[$plan['id']]['highlight_duration'] = $settings['highlight_duration'];
        $sub_types[$plan['id']]['top_search_result'] = $settings['top_search_result'];
        $sub_types[$plan['id']]['show_on_home'] = $settings['show_on_home'];
        $sub_types[$plan['id']]['show_in_home_search'] = $settings['show_in_home_search'];

        $sub_types[$plan['id']]['custom_settings'] = '';
        if (!empty($plan_custom)) {
            foreach ($plan_custom as $custom) {
                if (!empty($custom['title']) && trim($custom['title']) != '') {
                    $tpl = '<li><span class="icon-text no"><i class="icon-feather-x-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';

                    if (isset($settings['custom'][$custom['id']]) && $settings['custom'][$custom['id']] == '1') {
                        $tpl = '<li><span class="icon-text yes"><i class="icon-feather-check-circle margin-right-2"></i></span> ' . $custom['title'] . '</li>';
                    }
                    $sub_types[$plan['id']]['custom_settings'] .= $tpl;
                }
            }
        }
    }
}

/**
 * Memebrship Plans
 * End
 */

/**
 * RECENT blogs
 * Start
 */
$recent_blog = array();
if($config['show_blog_home']){
    $sql = "SELECT
b.*, u.name, u.username, u.image author_pic, GROUP_CONCAT(c.title) categories, GROUP_CONCAT(c.slug) cat_slugs
FROM `".$config['db']['pre']."blog` b
LEFT JOIN `".$config['db']['pre']."admins` u ON u.id = b.author
LEFT JOIN `" . $config['db']['pre'] . "blog_cat_relation` bc ON bc.blog_id = b.id
LEFT JOIN `" . $config['db']['pre'] . "blog_categories` c ON bc.category_id = c.id
WHERE b.status = 'publish' GROUP BY b.id ORDER BY b.created_at DESC LIMIT 3";
    $rows = ORM::for_table($config['db']['pre'].'blog')->raw_query($sql)->find_many();
    foreach ($rows as $info) {
        $recent_blog[$info['id']]['id'] = $info['id'];
        $recent_blog[$info['id']]['title'] = $info['title'];
        $recent_blog[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default.png';
        $recent_blog[$info['id']]['description'] = strlimiter(strip_tags(stripslashes($info['description'])),100);
        $recent_blog[$info['id']]['author'] = $info['name'];
        $recent_blog[$info['id']]['author_link'] = $link['BLOG-AUTHOR'].'/'.$info['username'];
        $recent_blog[$info['id']]['author_pic'] = !empty($info['author_pic'])?$info['author_pic']:'default_user.png';
        $recent_blog[$info['id']]['created_at'] = timeAgo($info['created_at']);
        $recent_blog[$info['id']]['link'] = $link['BLOG-SINGLE'].'/'.$info['id'].'/'.create_slug($info['title']);

        $categories = explode(',',$info['categories']);
        $cat_slugs = explode(',',$info['cat_slugs']);
        $arr = array();
        for($i = 0; $i < count($categories); $i++){
            $arr[] = '<a href="'.$link['BLOG-CAT'].'/'.$cat_slugs[$i].'">'.$categories[$i].'</a>';
        }
        $recent_blog[$info['id']]['categories'] = implode(', ',$arr);
    }
}

/**
 * RECENT blogs
 * END
 */

/**
 * TESTIMONIALS
 * Start
 */
$testimonials = array();
if($config['show_testimonials_home']){
    $rows = ORM::for_table($config['db']['pre'] . 'testimonials')
        ->order_by_desc('id')
        ->limit(5)
        ->find_many();

    foreach ($rows as $row) {
        $testimonials[$row['id']]['id'] = $row['id'];
        $testimonials[$row['id']]['name'] = $row['name'];
        $testimonials[$row['id']]['designation'] = $row['designation'];
        $testimonials[$row['id']]['content'] = $row['content'];
        $testimonials[$row['id']]['image'] = !empty($row['image']) ? $row['image'] : 'default_user.png';
    }
}
/**
 * TESTIMONIALS
 * End
 */

$total_freelancer = $count = ORM::for_table($config['db']['pre'].'user')->where('user_type','user')->count();
$total_jobs = $count = ORM::for_table($config['db']['pre'].'product')->count();
$total_projects = $count = ORM::for_table($config['db']['pre'].'project')->count();
$community_earn = ORM::for_table($config['db']['pre'].'user')
    ->select_expr('SUM(balance)', 'balance')
    ->where('user_type','user')
    ->find_one();
$community_earning = $community_earn['balance'];


//Print Template 'Home/index Page'
HtmlTemplate::display('index2', array(
    'popular' => $popular,
    'states' => $states,
    'items' => $item,
    'item2' => $item2,
    'category' => $category,
    'freelancers' => $freelancers,
    'total_freelancer' => number_format($total_freelancer),
    'total_jobs' => number_format($total_jobs),
    'total_projects' => number_format($total_projects),
    'community_earning' => number_format($community_earning),
    'recent_blog' => $recent_blog,
    'testimonials' => $testimonials,
    'sub_types' => $sub_types,
    'total_monthly' => $total_monthly,
    'total_annual' => $total_annual,
    'total_lifetime' => $total_lifetime
));