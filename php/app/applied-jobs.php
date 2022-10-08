<?php

if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 5;
$page = $_GET['page'];

if(checkloggedin()) {
    $item = array();
    $ses_userdata = get_user_data($_SESSION['user']['username']);

    if($ses_userdata['user_type'] != 'user'){
        headerRedirect($link['DASHBOARD']);
    }

    $author_image = $ses_userdata['image'];

    $pagelimit = "";
    if($page != null && $limit != null){
        $limit = $limit;
        $offset = ($page-1)*$limit;
    }

    $result = ORM::for_table($config['db']['pre'].'user_applied')
        ->select('job_id')
        ->where('user_id', $_SESSION['user']['id'])
        ->limit($limit)->offset($offset)
        ->find_many();
    if (count($result) > 0) {
        foreach ($result as $applied) {
            $sql = "SELECT p.*, c.name company_name, c.logo company_image
FROM `".$config['db']['pre']."product` p 
LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id
 WHERE p.status = 'active' AND p.hide = '0' AND p.id = '".$applied['job_id']."' ";
            $info = ORM::for_table($config['db']['pre'].'product')->raw_query($sql)->find_one();
            if (!empty($info)) {
                $item[$info['id']]['id'] = $info['id'];
                $item[$info['id']]['product_name'] = $info['product_name'];
                $item[$info['id']]['company_name'] = $info['company_name'];
                $item[$info['id']]['company_image'] = !empty($info['company_image'])?$info['company_image']:'default.png';
                $item[$info['id']]['desc'] = strlimiter(strip_tags($info['description']),80);
                $item[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
                $item[$info['id']]['salary_type'] = get_salaryType_title_by_id($info['salary_type']);
                $item[$info['id']]['featured'] = $info['featured'];
                $item[$info['id']]['urgent'] = $info['urgent'];
                $item[$info['id']]['highlight'] = $info['highlight'];
                $item[$info['id']]['location'] = get_cityName_by_id($info['city']);
                $item[$info['id']]['status'] = $info['status'];
                $item[$info['id']]['created_at'] = timeago($info['created_at']);

                $item[$info['id']]['image'] = !empty($info['screen_shot'])?$info['screen_shot']:$item[$info['id']]['company_image'];

                $salary_min = price_format($info['salary_min'],$info['country']);
                $item[$info['id']]['salary_min'] = $salary_min;
                $salary_max = price_format($info['salary_max'],$info['country']);
                $item[$info['id']]['salary_max'] = $salary_max;

                $item[$info['id']]['cat_id'] = $info['category'];
                $item[$info['id']]['sub_cat_id'] = $info['sub_category'];

                $get_main = get_maincat_by_id($info['category']);
                $get_sub = get_subcat_by_id($info['sub_category']);
                $item[$info['id']]['category'] = $get_main['cat_name'];
                $item[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

                $pro_url = create_slug($info['product_name']);
                $item[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;

                $cat_slug = create_slug($get_main['cat_name']);
                $item[$info['id']]['catlink'] = $config['site_url'].'category/'.$info['category'].'/'.$cat_slug;

                $subcat_slug = create_slug($get_sub['sub_cat_name']);
                $item[$info['id']]['subcatlink'] = $config['site_url'].'subcategory/'.$info['sub_category'].'/'.$subcat_slug;
            }


        }
    }

    $total_item = applied_jobs_count($_SESSION['user']['id']);
    $pagging = pagenav($total_item,$page,$limit,$link['APPLIED_JOBS']);

    //Print Template
    HtmlTemplate::display('job-applied', array(
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
?>
