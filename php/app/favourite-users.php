<?php
global $config,$link;
if(checkloggedin()) {
    update_lastactive();
    $items = array();
    $ses_userdata = get_user_data($_SESSION['user']['username']);

    if($ses_userdata['user_type'] != 'employer'){
        headerRedirect($link['DASHBOARD']);
    }

    if(!isset($_GET['page']))
        $_GET['page'] = 1;

    $limit = 10;
    $page = $_GET['page'];
    $offset = ($page-1)*$limit;

    $result = ORM::for_table($config['db']['pre'].'fav_users')
        ->select('fav_user_id')
        ->where('user_id', $_SESSION['user']['id'])
        ->limit($limit)->offset($offset)
        ->find_many();

    if (count($result) > 0) {
        foreach ($result as $fav) {
            $sql = "SELECT *
FROM `".$config['db']['pre']."user`
 WHERE status = '1' AND user_type = 'user' AND id = '".$fav['fav_user_id']."' ";
            $info = ORM::for_table($config['db']['pre'].'user')->raw_query($sql)->find_one();
            if (!empty($info)) {
                $items[$info['id']]['id'] = $info['id'];
                $items[$info['id']]['username'] = $info['username'];
                $items[$info['id']]['name'] = !empty($info['name'])?$info['name']:$info['username'];
                $items[$info['id']]['description'] = !empty($info['tagline'])?$info['tagline']:strlimiter(strip_tags($info['description']),200);
                $items[$info['id']]['sex'] = $info['sex'];
                $items[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default_user.png';

                $items[$info['id']]['category'] = $items[$info['id']]['subcategory'] = null;
                if(!empty($info['category'])){
                    $get_cat = get_maincat_by_id($info['category']);
                    $items[$info['id']]['category'] = $get_cat['cat_name'];
                }
                if(!empty($info['subcategory'])){
                    $get_cat = get_subcat_by_id($info['subcategory']);
                    $items[$info['id']]['subcategory'] = $get_cat['sub_cat_name'];
                }

                $country_code = $info['country_code'];
                $items[$info['id']]['salary_min'] = price_format($info['salary_min'], $country_code);
                $items[$info['id']]['salary_max'] = price_format($info['salary_max'], $country_code);

                $items[$info['id']]['city'] = $info['city'];
                if(!empty($info['city_code'])) {
                    $city_detail = get_cityDetail_by_id($info['city_code']);
                    $items[$info['id']]['city'] = $city_detail['asciiname'];
                    $items[$info['id']]['city'] .= ', '.get_stateName_by_id($city_detail['subadmin1_code']);
                }

                $items[$info['id']]['favorite'] = check_user_favorite($info['id']);
            }
        }
    }

    $total_item = favorite_users_count($_SESSION['user']['id']);
    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['FAVUSERS']);

    //Print Template
    HtmlTemplate::display('favourite-users', array(
        'items' => $items,
        'totalitem' => $total_item,
        'pages' => $pagging
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}