<?php
// if job seekers is disable
if(!$config['job_seeker_enable']){
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
}

if(!isset($_GET['page']))
    $page_number = 1;
else{
    $page_number = $_GET['page'];
}

$limit = 10;
$keywords = isset($_GET['keywords']) ? str_replace("-"," ",$_GET['keywords']) : "";

$category = $subcat = $gender = $range1 = $range2 = $age_range1 = $age_range2 = "";

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


if(isset($_GET['city']) && !empty($_GET['city'])){
    $city = $_GET['city'];
}else{
    $city = "";
}

$total = 0;

$where = '';
$order_by = 'u.id DESC';
if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
    $where.= "AND (u.username LIKE '%$keywords%' or u.name LIKE '%$keywords%' or u.tagline LIKE '%$keywords%' or u.description LIKE '%$keywords%') ";
    $order_by = "(CASE
    WHEN u.username = '$keywords' THEN 1
    WHEN u.name = '$keywords' THEN 2
    WHEN u.name LIKE '$keywords%' THEN 3
    WHEN u.name LIKE '%$keywords%' THEN 4
    WHEN u.tagline = '$keywords' THEN 5
    WHEN u.tagline LIKE '$keywords%' THEN 6
    WHEN u.tagline LIKE '%$keywords%' THEN 7
    WHEN u.description LIKE '$keywords%' THEN 8
    WHEN u.description LIKE '%$keywords%' THEN 9
    ELSE 10
  END)";
}

if(isset($category) && !empty($category)){
    $where.= "AND (u.category = '$category') ";
}

if(isset($_GET['subcat']) && !empty($_GET['subcat'])){
    $where.= "AND (u.subcategory = '$subcat') ";
}


if (!empty($_GET['range1'])) {
    $range1 = str_replace('.', '', $_GET['range1']);
    $range2 = str_replace('.', '', $_GET['range2']);
    $where.= ' AND (u.salary_min BETWEEN '.$range1.' AND '.$range2.') OR (u.salary_max BETWEEN '.$range1.' AND '.$range2.')';
}

if (!empty($_GET['age_range1'])) {
    $age_range1 = $_GET['age_range1'];
    $age_range2 = $_GET['age_range2'];
    $where.= ' AND (DATEDIFF(CURRENT_DATE, u.dob) BETWEEN ('.$age_range1.' * 365.25) AND ('.$age_range2.' * 365.25))';
}


$total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM `".$config['db']['pre']."user` u where u.status = '1' AND u.user_type = 'user' $where"));

$query = "SELECT u.* FROM `".$config['db']['pre']."user` u
     where u.status = '1' AND u.user_type = 'user' $where ORDER BY $order_by LIMIT ".($page_number-1)*$limit.",$limit";

$count = 0;
$noresult_id = "";
//Loop for list view
$items = array();
$result = $mysqli->query($query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($info = mysqli_fetch_assoc($result)) {
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
        $items[$info['id']]['rating'] = averageRating($info['id'],$info['user_type']);
    }
}

$selected = "";
if(isset($_GET['cat']) && !empty($_GET['cat'])){
    $selected = $_GET['cat'];
}
// Check Settings For quotes
$GetCategory = get_maincategory($selected);
$cat_dropdown = get_categories_dropdown($lang);

if(isset($_GET['cat']) && !empty($category)){
    $maincatname = get_maincat_by_id($category);
    $maincatname = $maincatname['cat_name'];
    $mainCategory = $maincatname;
}else{
    $maincatname = "";
    $mainCategory = "";
}
if(isset($_GET['subcat']) && !empty($subcat)){
    $subcatname = get_subcat_by_id($subcat);
    $subcatname = $subcatname['sub_cat_name'];
    $subCategory = $subcatname;
}else{
    $subcatname = "";
    $subCategory = "";
}

if(isset($category) && !empty($category)){
    $Pagetitle = $mainCategory;
}
elseif(isset($subcat) && !empty($subcat)){
    $Pagetitle = $subCategory;
}
elseif(!empty($keywords)){
    $Pagetitle = ucfirst($keywords);
}
else{
    $Pagetitle = __("Freelancers");
}

$cat_dropdown = get_categories_dropdown($lang);
$Pagelink = "";
if(count($_GET) >= 1){
    $get = http_build_query($_GET);
    $Pagelink .= "?".$get;
    $pagging = pagenav($total,$page_number,$limit,$link['FREELANCERS'].$Pagelink,1);
}else{
    $pagging = pagenav($total,$page_number,$limit,$link['FREELANCERS']);
}
$country_code = check_user_country();
//Print Template
HtmlTemplate::display('freelancers', array(
    'pagetitle' => $Pagetitle,
    'items' => $items,
    'usersfound' => $total,
    'cat_dropdown' => $cat_dropdown,
    'user_country' => strtolower($country_code),
    'default_country_id' => $country_code,
    'category' => $GetCategory,
    'maincat' => $category,
    'subcat' => $subcat,
    'maincategory' => $mainCategory,
    'subcategory' => $subCategory,
    'keywords' => $keywords,
    'gender' => $gender,
    'range1' => $range1,
    'range2' => $range2,
    'age_range1' => $age_range1,
    'age_range2' => $age_range2,
    'pages' => $pagging
));
exit;
