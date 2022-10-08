<?php

if(checkloggedin()) {
    update_lastactive();

    $ses_userdata = get_user_data($_SESSION['user']['username']);
    if($ses_userdata['user_type'] != 'employer'){
        headerRedirect($link['DASHBOARD']);
    }
    if (!isset($_GET['id'])) {
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit;
    }

    $product = ORM::for_table($config['db']['pre'] . 'product')
        ->select('product_name')
        ->where('id', $_GET['id'])
        ->where('user_id', $_SESSION['user']['id'])
        ->whereNotEqual('status','pending')
        ->findOne();

    if (!empty($product)) {
        if (!isset($_GET['page']))
            $_GET['page'] = 1;

        $limit = 10;
        $page = $_GET['page'];
        $offset = ($page - 1) * $limit;
        $item = array();

        $total_item = ORM::for_table($config['db']['pre'] . 'user_applied')
            ->table_alias('ua')
            ->where(array(
                'u.status' => '1',
                'u.user_type' => 'user',
                'ua.job_id' => $_GET['id']
            ))
            ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
            ->count();

        $result = ORM::for_table($config['db']['pre'] . 'user_applied')
            ->table_alias('ua')
            ->select_many('ua.*', 'u.username', 'u.name', 'u.salary_min', 'u.salary_max', 'u.sex', 'u.image','u.category','u.subcategory','u.country_code','u.city_code')
            ->where(array(
                'u.status' => '1',
                'u.user_type' => 'user',
                'ua.job_id' => $_GET['id']
            ))
            ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
            ->limit($limit)->offset($offset)
            ->find_many();

        foreach($result as $info){
            $item[$info['id']]['id'] = $info['id'];
            $item[$info['id']]['username'] = $info['username'];
            $item[$info['id']]['name'] = !empty($info['name'])?$info['name']:$info['username'];
            $item[$info['id']]['description'] = nl2br(stripcslashes($info['message']));
            $item[$info['id']]['sex'] = $info['sex'];
            $item[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default_user.png';

            $item[$info['id']]['category'] = $item[$info['id']]['subcategory'] = null;
            if(!empty($info['category'])){
                $get_cat = get_maincat_by_id($info['category']);
                $item[$info['id']]['category'] = $get_cat['cat_name'];
            }
            if(!empty($info['subcategory'])){
                $get_cat = get_subcat_by_id($info['subcategory']);
                $item[$info['id']]['subcategory'] = $get_cat['sub_cat_name'];
            }

            $country_code = $info['country_code'];
            $item[$info['id']]['salary_min'] = price_format($info['salary_min'], $country_code);
            $item[$info['id']]['salary_max'] = price_format($info['salary_max'], $country_code);

            $item[$info['id']]['city'] = $info['city'];
            if(!empty($info['city_code'])) {
                $city_detail = get_cityDetail_by_id($info['city_code']);
                $item[$info['id']]['city'] = $city_detail['asciiname'];
                $item[$info['id']]['city'] .= ', '.get_stateName_by_id($city_detail['subadmin1_code']);
            }

            $item[$info['id']]['user_id'] = $info['user_id'];
            $item[$info['id']]['favorite'] = check_user_favorite($info['user_id']);

            $resume_link = null;
            if(!empty($info['resume_id'])) {
                $result = ORM::for_table($config['db']['pre'] . 'resumes')
                    ->where('user_id', $info['user_id'])
                    ->where('id', $info['resume_id'])
                    ->where('active', '1')
                    ->find_one();

                if (!empty($result)) {
                    $resume_link = $config['site_url'] . "storage/resumes/" . $result['filename'];
                }
            }
            $item[$info['id']]['resume'] = $resume_link;
        }

        $pagging = pagenav($total_item, $_GET['page'], $limit, $link['APPLIED_USERS'].'/'.$_GET['id']);

        //Print Template
        HtmlTemplate::display('job-applied-users', array(
            'product_name' => $product['product_name'],
            'items' => $item,
            'totalitem' => $total_item,
            'pages' => $pagging
        ));
        exit;
    }
}
error(__("Page Not Found"), __LINE__, __FILE__, 1);
exit();