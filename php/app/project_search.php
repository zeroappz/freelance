<?php
if(!isset($_GET['page']))
    $page_number = 1;
else{
    $page_number = $_GET['page'];
}

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
    $sort = "id";
elseif($_GET['sort'] == "title")
    $sort = "product_name";
elseif($_GET['sort'] == "price")
    $sort = "price";
elseif($_GET['sort'] == "date")
    $sort = "created_at";
else
    $sort = "id";

$limit = isset($_GET['limit']) ? $_GET['limit'] : 9;
$filter = isset($_GET['filter']) ? $_GET['filter'] : "";
$sorting = isset($_GET['sort']) ? $_GET['sort'] : "Newest";
$budget = isset($_GET['budget']) ? $_GET['budget'] : "";
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
}
if(isset($_GET['cat']) && !empty($_GET['cat'])){
    if(is_numeric($_GET['cat'])){
        if(check_category_exists($_GET['cat'])){
            $category = $_GET['cat'];
        }
    }else{
        $category = get_category_id_by_slug($_GET['cat']);
    }
}

if($subcat != ''){
    $custom_fields = get_customFields_by_catid('',$subcat,false);
}else if($category != ''){
    $custom_fields = get_customFields_by_catid($category,'',false);
}else{
    $custom_fields = get_customFields_by_catid('','',false);
}

$custom = array();
if(isset($_GET['custom']) && !empty($_GET['custom'])){
    $custom = $_GET['custom'];
}

if(isset($_GET['city']) && !empty($_GET['city'])){
    $city = $_GET['city'];
}else{
    $city = "";
}

$total = 0;

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
    $where.= "AND ( find_in_set($subcat,p.sub_category) <> 0 ) ";
}


if (isset($_GET['range1']) && $_GET['range1'] != '') {
    $range1 = str_replace('.', '', $_GET['range1']);
    $range2 = str_replace('.', '', $_GET['range2']);
    $where.= ' AND (p.salary_min BETWEEN '.$range1.' AND '.$range2.') OR (p.salary_max BETWEEN '.$range1.' AND '.$range2.')';
} else {
    $range1 = "";
    $range2 = "";
}

/*if(isset($_GET['city']) && !empty($_GET['city']))
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
else{
    $country_code = check_user_country();
    $where.= "AND (p.country = '$country_code') ";
}*/

if(isset($_GET['custom'])) {
    $whr_count = 1;
    $custom_where = "";
    $custom_join = "";
    foreach ($_GET['custom'] as $key => $value) {
        if (empty($value)) {
            unset($_GET['custom'][$key]);
        }
        if (!empty($_GET['custom'])) {
            // custom value is not empty.

            if ($key != "" && $value != "") {
                $c_as = "c".$whr_count;
                $custom_join .= " JOIN `".$config['db']['pre']."custom_data` AS $c_as ON $c_as.product_id = p.id AND `$c_as`.`field_id` = '$key' ";

                if (is_array($value)) {
                    $custom_where = " AND ( ";
                    $cond_count = 0;
                    foreach ($value as $val) {
                        if ($cond_count == 0) {
                            $custom_where .= " find_in_set('$val',$c_as.field_data) <> 0 ";
                        } else {
                            $custom_where .= " AND find_in_set('$val',$c_as.field_data) <> 0 ";
                        }
                        $cond_count++;
                    }
                    $custom_where .= " )";
                }else{
                    $custom_where .= " AND `$c_as`.`field_data` = '$value' ";
                }

                $whr_count++;
            }
        }
    }
    if($custom_where != "")
        $where .= $custom_where;

    if (!empty($_GET['custom'])) {
        $sql = "SELECT DISTINCT p.*
FROM `".$config['db']['pre']."project` AS p
$custom_join
 WHERE (status = 'open' or status = 'pending_for_approval') ";
    }else{
        $sql = "SELECT DISTINCT p.*
FROM `".$config['db']['pre']."project` AS p
 WHERE (status = 'open' or status = 'pending_for_approval') ";
    }
    $q = "$sql $where";
    $totalWithoutFilter = mysqli_num_rows(mysqli_query($mysqli, $q));
}
else{
    $totalWithoutFilter = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."project as p where (status = 'open' or status = 'pending_for_approval') $where"));
}

if(isset($_GET['filter'])){
    if($_GET['filter'] == 'free')
    {
        $where.= "AND (p.urgent='0' AND p.featured='0' AND p.highlight='0') ";
    }
    elseif($_GET['filter'] == 'featured')
    {
        $where.= "AND (p.featured='1') ";
    }
    elseif($_GET['filter'] == 'urgent')
    {
        $where.= "AND (p.urgent='1') ";
    }
    elseif($_GET['filter'] == 'highlight')
    {
        $where.= "AND (p.highlight='1') ";
    }
}

$job_type = $salary_type = '';
if(isset($_GET['job-type'])){
    $job_type = $_GET['job-type'];
    $where.= "AND (p.product_type=$job_type) ";
}

if(isset($_GET['salary-type'])){
    $salary_type = $_GET['salary-type'];
    $where.= "AND (p.salary_type='$salary_type') ";
}

$order_by = "
      (CASE
        WHEN p.featured = '1' and p.urgent = '1' and p.highlight = '1' THEN 1
        WHEN p.urgent = '1' and p.featured = '1' THEN 2
        WHEN p.urgent = '1' and p.highlight = '1' THEN 3
        WHEN p.featured = '1' and p.highlight = '1' THEN 4
        WHEN p.urgent = '1' THEN 5
        WHEN p.featured = '1' THEN 6
        WHEN p.highlight = '1' THEN 7
        ELSE 8
      END),".$order_by_keyword." $sort $order";

if(isset($_GET['custom']))
{

    if (!empty($_GET['custom'])) {
        $sql = "SELECT DISTINCT p.*
FROM `".$config['db']['pre']."project` AS p
$custom_join
 WHERE p.status = 'open' ";
    }else{
        $sql = "SELECT DISTINCT p.*
FROM `".$config['db']['pre']."project` AS p
 WHERE p.status = 'open' ";
    }

    $query =  $sql . " $where ORDER BY $sort $order LIMIT ".($page_number-1)*$limit.",$limit";

    $total = mysqli_num_rows(mysqli_query($mysqli, "$sql $where"));
    $featuredAds = mysqli_num_rows(mysqli_query($mysqli, "$sql and (p.featured='1') $where"));
    $urgentAds = mysqli_num_rows(mysqli_query($mysqli, "$sql and (p.urgent='1') $where"));

}
else{
    $total = mysqli_num_rows(mysqli_query($mysqli,
        "SELECT 1 FROM ".$config['db']['pre']."project as p where (status = 'open' or status = 'pending_for_approval') $where"));
    $featuredAds = mysqli_num_rows(mysqli_query($mysqli,
        "SELECT 1 FROM ".$config['db']['pre']."project as p where (status = 'open' or status = 'pending_for_approval') and featured='1' $where"));
    $urgentAds = mysqli_num_rows(mysqli_query($mysqli,
        "SELECT 1 FROM ".$config['db']['pre']."project as p where (status = 'open' or status = 'pending_for_approval') and urgent='1' $where") );


    $query = "SELECT p.* FROM `".$config['db']['pre']."project` as p
    INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id
     where (p.status = 'open' or p.status = 'pending_for_approval') $where ORDER BY $order_by LIMIT ".($page_number-1)*$limit.",$limit";

}

$count = 0;
$noresult_id = "";
//Loop for list view
$item = array();
$result = $mysqli->query($query);
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($info = mysqli_fetch_assoc($result)) {
        $item[$info['id']]['id'] = $info['id'];
        $item[$info['id']]['featured'] = $info['featured'];
        $item[$info['id']]['urgent'] = $info['urgent'];
        $item[$info['id']]['highlight'] = $info['highlight'];
        $item[$info['id']]['product_name'] = $info['product_name'];
        //$item[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
        $item[$info['id']]['salary_type'] = ($info['salary_type'] == 0)? __("Fixed Price") : __("Hourly Price");
        $item[$info['id']]['description'] = strlimiter(strip_tags($info['description']),80);
        $item[$info['id']]['category'] = $info['category'];

        $full_amount = 0;
        $data = ORM::for_table($config['db']['pre'] . 'bids')
            ->select('amount')
            ->table_alias('ua')
            ->where(array(
                'u.status' => '1',
                'u.user_type' => 'user',
                'ua.project_id' => $info['id']
            ))
            ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
            ->find_many();
        $bids_count = count($data);
        foreach($data as $d){
            $full_amount = $full_amount + $d['amount'];
        }

        $avg_bid = ($bids_count == 0)? 0 : $full_amount / $bids_count;
        $item[$info['id']]['bids_count'] = $bids_count;
        $item[$info['id']]['avg_bid'] = $avg_bid;

        $item[$info['id']]['salary_min'] = $info['salary_min'];
        $item[$info['id']]['salary_max'] = $info['salary_max'];

        $item[$info['id']]['tag'] = $info['tag'];
        $item[$info['id']]['status'] = $info['status'];
        $item[$info['id']]['view'] = $info['view'];
        $item[$info['id']]['created_at'] = timeAgo($info['created_at']);
        //$item[$info['id']]['updated_at'] = date('d M Y', $info['updated_at']);

        $item[$info['id']]['cat_id'] = $info['category'];
        $item[$info['id']]['sub_cat_id'] = $info['sub_category'];
        $get_main = get_maincat_by_id($info['category']);
        $item[$info['id']]['category'] = $get_main['cat_name'];

        $get_sub = $item_sub_category = $item_subcatlink = null;
        if (!empty($info['sub_category'])) {
            $skills = explode(',', $info['sub_category']);
            $skills2 = implode('\' OR sub_cat_id=\'', $skills);

            $skills3 = array();

            $query = "SELECT sub_cat_id,sub_cat_name,slug FROM `".$config['db']['pre']."catagory_sub` WHERE sub_cat_id='" . $skills2 . "' ORDER BY sub_cat_name LIMIT " . count($skills);

            $result2 = ORM::for_table($config['db']['pre'].'catagory_sub')->raw_query($query)->find_many();
            foreach ($result2 as $info2)
            {
                $skills_link = $link['SEARCH_PROJECTS'] . '/' . $get_main['slug'] . '/' . $info2['slug'];
                $skills3[] = '<span>'.$info2['sub_cat_name'].'</span>';
            }

            $item[$info['id']]['skills'] = implode('  ', $skills3);
        }else{
            $item[$info['id']]['skills'] = '';
        }

        if($info['tag'] != ''){
            $item[$info['id']]['showtag'] = "1";
            $tag = explode(',', $info['tag']);
            $tag2 = array();
            foreach ($tag as $val)
            {
                //REMOVE SPACE FROM $VALUE ----
                $val = preg_replace("/[\s_]/","-", trim($val));
                $tag2[] = '<li><a href="'.$link['SEARCH_PROJECTS'].'?keywords='.$val.'">'.$val.'</a> </li>';
            }
            $item[$info['id']]['tag'] = implode('  ', $tag2);
        }else{
            $item[$info['id']]['tag'] = "";
            $item[$info['id']]['showtag'] = "0";
        }



        $user = "SELECT username FROM ".$config['db']['pre']."user where id='".$info['user_id']."'";
        $userresult = mysqli_query($mysqli, $user);
        $userinfo = mysqli_fetch_assoc($userresult);

        $item[$info['id']]['username'] = $userinfo['username'];


        if(check_user_upgrades($info['user_id']))
        {
            $sub_info = get_user_membership_detail($info['user_id']);
            $item[$info['id']]['sub_title'] = $sub_info['sub_title'];
            $item[$info['id']]['sub_image'] = $sub_info['sub_image'];
        }else{
            $item[$info['id']]['sub_title'] = '';
            $item[$info['id']]['sub_image'] = '';
        }

        $item[$info['id']]['highlight_bg'] = ($info['highlight'] == 1)? "highlight-premium-ad" : "";

        $author_url = create_slug($userinfo['username']);

        $item[$info['id']]['author_link'] = $link['PROFILE'].'/'.$author_url;

        $pro_url = create_slug($info['product_name']);

        $item[$info['id']]['link'] = $link['PROJECT'].'/' . $info['id'] . '/'.$pro_url;

        $item[$info['id']]['catlink'] = $link['SEARCH_PROJECTS'].'/'.$get_main['slug'];

    }
}
else
{

    //echo "0 results";
}

$selected = "";
if(isset($_GET['cat']) && !empty($_GET['cat'])){
    $selected = $_GET['cat'];
}
// Check Settings For quotes
$GetCategory = get_maincategory($selected);
$cat_dropdown = get_categories_dropdown($lang);
if(isset($_GET['cat']) && !empty($_GET['cat'])){
    $maincatname = get_maincat_by_id($category);
    $maincatname = $maincatname['cat_name'];
    $mainCategory = $maincatname;
}else{
    $maincatname = "";
    $mainCategory = "";
}
if(isset($_GET['subcat']) && !empty($_GET['subcat'])){
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
    $Pagetitle = __("Projects");
}

if(!empty($_GET['location'])){
    $locTitle        =   explode(',' ,$_GET['location']);
    $locTitle     =   $locTitle[0];
    $Pagetitle .= " ".$locTitle;
}
else{
    $sortname = check_user_country();
    $countryName = get_countryName_by_code($sortname);
    $Pagetitle .= " ".$countryName;
}

if(isset($_GET['city']) && !empty($_GET['city']))
{
    $cityName = get_cityName_by_id($_GET['city']);
    $Pagetitle = __("Projects")." ".__("in")." ".$cityName;
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

if(isset($category) && !empty($category)) {
    $SubCatList = get_subcat_of_maincat( $category);
}else{
    $SubCatList = get_maincategory();
}

$Pagelink = "";
if(count($_GET) >= 1){
    $get = http_build_query($_GET);
    $Pagelink .= "?".$get;
    $pagging = pagenav($total,$page_number,$limit,$link['LISTING'].$Pagelink,1);
}else{
    $pagging = pagenav($total,$page_number,$limit,$link['LISTING']);
}

HtmlTemplate::display('project_search', array(
    'pages' => $pagging,
    'pagetitle' => $Pagetitle,
    'popularcity' => $popular,
    'statelist' => $states,
    'subcatlist' => $SubCatList,
    'user_country' => strtolower($country_code),
    'default_country' => $countryName,
    'default_country_id' => $country_code,
    'items' => $item,
    'category' => $GetCategory,
    'cat_dropdown' => $cat_dropdown,
    'serkey' => $keywords,
    'maincat' => $category,
    'subcat' => $subcat,
    'maincategory' => $mainCategory,
    'subcategory' => $subCategory,
    'budget' => $budget,
    'keywords' => $keywords,
    'range1' => $range1,
    'range2' => $range2,
    'project_type' => $job_type,
    'salary_type' => $salary_type,
    'adsfound' => $total,
    'totaladsfound' => $totalWithoutFilter,
    'featuredfound' => $featuredAds,
    'urgentfound' => $urgentAds,
    'limit' => $limit,
    'filter' => $filter,
    'sort' => $sorting,
    'order' => $order,
    'no_result_id' => $noresult_id,
    'customfields' => $custom_fields,
    'posttypes' => $post_types,
    'showcustomfield' => (count($custom_fields) > 0) ? 1 : 0
    ));
exit;
