<?php
// if company is disable
if(!$config['company_enable']){
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
}
if(checkloggedin()) {
    update_lastactive();
}

if(!isset($_GET['page']))
	$page = 1;
else
    $page = $_GET['page'];

$limit = 12;
$keyword = null;

$country_code = check_user_country();
$sql = "SELECT * FROM `".$config['db']['pre']."companies`
 WHERE status = '1' AND (country IS NULL or country = '$country_code')";

if(isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $sql .= " AND name LIKE '%$keyword%'";
}

$total = mysqli_num_rows(mysqli_query($mysqli, $sql));
$query = "$sql LIMIT ".($page-1)*$limit.",$limit";

$result = ORM::for_table($config['db']['pre'].'companies')->raw_query($query)->find_many();

$items = array();
if ($result) {
    foreach ($result as $info)
    {
    	$items[$info['id']]['id'] = $info['id'];
    	$items[$info['id']]['name'] = $info['name'];
    	$items[$info['id']]['image'] = !empty($info['logo'])?$info['logo']:'default.png';
    	$items[$info['id']]['jobs'] = count_company_jobs($info['id']);
    	$items[$info['id']]['link'] = $link['COMPANY-DETAIL'].'/'.$info['id'].'/'.create_slug($info['name']);
    }
}

if($keyword){
    $pagging = pagenav($total,$page,$limit,$link['COMPANIES'].'?keyword='.$keyword,1);
}else{
    $pagging = pagenav($total,$page,$limit,$link['COMPANIES']);
}

//Print Template
HtmlTemplate::display('companies', array(
    'companies' => $items,
    'total' => $total,
    'keyword' => $keyword,
    'pages' => $pagging
));
exit;
?>
