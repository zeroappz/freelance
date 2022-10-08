<?php
if(!isset($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];


if(!isset($_GET['order']))
    $order = "DESC";
else{
    if($_GET['order'] == ""){
        $order = "DESC";
    }else{
        $order = $_GET['order'];
    }
}

if(!isset($_GET['sort']))
    $sort = "p.id";
elseif($_GET['sort'] == "title")
    $sort = "product_name";
elseif($_GET['sort'] == "price")
    $sort = "price";
elseif($_GET['sort'] == "date")
    $sort = "created_at";
else
    $sort = "p.id";

$limit = isset($_GET['limit']) ? $_GET['limit'] : 6;
$sorting = isset($_GET['sort']) ? $_GET['sort'] : "Newest";


if(checkloggedin()) {
    $ses_userdata = get_user_data(null,$_SESSION['user']['id']);
    $author_image = $ses_userdata['image'];
    if($ses_userdata['user_type'] != 'employer'){
        headerRedirect($link['DASHBOARD']);
    }
    $keywords = isset($_GET['keywords']) ? str_replace("-"," ",$_GET['keywords']) : "";

    $category = "";
    $subcat = "";

    if(isset($_GET['subcat']) && !empty($_GET['subcat'])){

        if(is_numeric($_GET['subcat'])){
            if(check_sub_category_exists($_GET['subcat'])){
                $subcat = $_GET['subcat'];
            }
        }else{
            $subcat = get_subcategory_id_by_slug($_GET['subcat']);
        }
    }elseif(isset($_GET['cat']) && !empty($_GET['cat'])){
        if(is_numeric($_GET['cat'])){
            if(check_category_exists($_GET['cat'])){
                $category = $_GET['cat'];
            }
        }else{
            $category = get_category_id_by_slug($_GET['cat']);
        }
    }

    $where = '';
    $order_by_keyword = '';
    if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
        $where.= "AND (p.product_name LIKE '%$keywords%' or p.tag LIKE '%$keywords%') ";
        $order_by_keyword = "(CASE
    WHEN p.product_name = '$keywords' THEN 1
    WHEN p.product_name LIKE '$keywords%' THEN 2
    WHEN p.product_name LIKE '%$keywords%' THEN 3
    WHEN p.tag = '$keywords' THEN 4
    WHEN p.tag LIKE '$keywords%' THEN 5
    WHEN p.tag LIKE '%$keywords%' THEN 6
    ELSE 7
  END),";
    }

    if(isset($category) && !empty($category)){
        $where.= "AND (p.category = '$category') ";
    }

    if(isset($_GET['subcat']) && !empty($_GET['subcat'])){
        $where.= "AND (p.sub_category = '$subcat') ";
    }

    if(isset($_GET['city']) && !empty($_GET['city']))
    {
        $where.= "AND (p.city = '".$_GET['city']."') ";
    }
    elseif(isset($_GET['location']) && !empty($_GET['location']))
    {
        $placetype = $_GET['placetype'];
        $placeid = $_GET['placeid'];

        if($placetype == "country"){
            $where.= "AND (p.country = '$placeid') ";
        }elseif($placetype == "state"){
            $where.= "AND (p.state = '$placeid') ";
        }else{
            $where.= "AND (p.city = '$placeid') ";
        }
    }

    $sql = "SELECT p.*, c.name company_name, pt.id product_type
FROM `".$config['db']['pre']."product` p LEFT JOIN `".$config['db']['pre']."companies` c on p.company_id = c.id LEFT JOIN `".$config['db']['pre']."product_type` pt on p.product_type = pt.id
 WHERE p.status = 'active' AND p.hide = '0' AND p.user_id = '".$_SESSION['user']['id']."' ";

    $total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."product as p where status = 'active' and hide = '0' and user_id = '".$_SESSION['user']['id']."' $where"));
    $query = "$sql $where ORDER BY $sort DESC LIMIT ".($page-1)*$limit.",$limit";

    $result = ORM::for_table($config['db']['pre'].'product')->raw_query($query)->find_many();

    $items = array();
    if ($result) {
        foreach ($result as $info)
        {
            $items[$info['id']]['id'] = $info['id'];
            $items[$info['id']]['status'] = $info['status'];
            $items[$info['id']]['product_name'] = $info['product_name'];
            $items[$info['id']]['company_name'] = $info['company_name'];
            $items[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
            $items[$info['id']]['salary_type'] = get_salaryType_title_by_id($info['salary_type']);
            $items[$info['id']]['cat_id'] = $info['category'];
            $items[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $items[$info['id']]['salary_min'] = $info['salary_min'];
            $items[$info['id']]['salary_max'] = $info['salary_max'];
            $items[$info['id']]['featured'] = $info['featured'];
            $items[$info['id']]['urgent'] = $info['urgent'];
            $items[$info['id']]['highlight'] = $info['highlight'];
            $items[$info['id']]['highlight_bgClr'] = ($info['highlight'] == 1)? "highlight-premium-ad" : "";

            $cityname = get_cityName_by_id($info['city']);
            $items[$info['id']]['location'] = $cityname;
            $items[$info['id']]['city'] = $cityname;

            $items[$info['id']]['hide'] = $info['hide'];

            $items[$info['id']]['created_at'] = timeAgo($info['created_at']);
            $expire_date_timestamp = $info['expire_date'];
            $expire_date = date('d M, Y', $expire_date_timestamp);
            $items[$info['id']]['expire_date'] = $expire_date;

            $items[$info['id']]['cat_id'] = $info['category'];
            $items[$info['id']]['sub_cat_id'] = $info['sub_category'];
            $get_main = get_maincat_by_id($info['category']);
            $get_sub = get_subcat_by_id($info['sub_category']);
            $items[$info['id']]['category'] = $get_main['cat_name'];
            $items[$info['id']]['sub_category'] = $get_sub['sub_cat_name'];

            $items[$info['id']]['favorite'] = check_product_favorite($info['id']);

            $salary_min = price_format($info['salary_min'],$info['country']);
            $item[$info['id']]['salary_min'] = $salary_min;
            $salary_max = price_format($info['salary_max'],$info['country']);
            $item[$info['id']]['salary_max'] = $salary_max;

            $userinfo = get_user_data(null,$info['user_id']);

            $items[$info['id']]['username'] = $userinfo['username'];
            $author_url = create_slug($userinfo['username']);

            $items[$info['id']]['author_link'] = $link['PROFILE'].'/'.$author_url;

            if(check_user_upgrades($info['user_id']))
            {
                $sub_info = get_user_membership_detail($info['user_id']);
                $items[$info['id']]['sub_title'] = $sub_info['sub_title'];
                $items[$info['id']]['sub_image'] = $sub_info['sub_image'];
            }else{
                $items[$info['id']]['sub_title'] = '';
                $items[$info['id']]['sub_image'] = '';
            }
            $pro_url = create_slug($info['product_name']);
            $items[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;
            $items[$info['id']]['catlink'] = $config['site_url'].'category/'.$get_main['slug'];
            $items[$info['id']]['subcatlink'] = $config['site_url'].'category/'.$get_main['slug'].'/'.$get_sub['slug'];

            $city = create_slug($items[$info['id']]['city']);
            $items[$info['id']]['citylink'] = $config['site_url'].'city/'.$info['city'].'/'.$city;

        }
    }

    $total_item = get_items_count($_SESSION['user']['id']);

    $pagging = pagenav($total_item,$page,$limit,$link['MYJOBS']);

    $Pagelink = "";
    if(count($_GET) >= 1){
        $get = http_build_query($_GET);
        $Pagelink .= "?".$get;

        $pagging = pagenav($total,$page,$limit,$link['MYJOBS'].$Pagelink,1);
    }else{
        $pagging = pagenav($total,$page,$limit,$link['MYJOBS']);
    }

    //Print Template
    HtmlTemplate::display('my-jobs', array(
        'items' => $items,
        'totalitem' => $total_item,
        'keywords' => $keywords,
        'pages' => $pagging
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
