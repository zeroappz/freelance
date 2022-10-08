<?php
if(checkloggedin())
{
	update_lastactive();
	$ses_userdata = get_user_data($_SESSION['user']['username']);
	if($ses_userdata['user_type'] != 'employer'){
		headerRedirect($link['DASHBOARD']);
	}

	if(!isset($_GET['page']))
	    $page = 1;
	else
	    $page = $_GET['page'];

	$limit = isset($_GET['limit']) ? $_GET['limit'] : 6;

	$keywords = isset($_GET['keywords']) ? str_replace("-"," ",$_GET['keywords']) : "";

    $where = '';
    if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
        $where.= "AND (name LIKE '%$keywords%') ";
    }

    $sql = "SELECT * FROM `".$config['db']['pre']."companies`
 WHERE status = '1' AND user_id = '".$_SESSION['user']['id']."' ";

    $total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."companies where status = '1' and user_id = '".$_SESSION['user']['id']."' $where"));
    $query = "$sql $where LIMIT ".($page-1)*$limit.",$limit";

    $result = ORM::for_table($config['db']['pre'].'companies')->raw_query($query)->find_many();

    $items = array();
    if ($result) {
        foreach ($result as $info)
        {
        	$items[$info['id']]['id'] = $info['id'];
        	$items[$info['id']]['name'] = $info['name'];
        	$items[$info['id']]['image'] = !empty($info['logo'])?$info['logo']:'default.png';
        	$items[$info['id']]['description'] = strlimiter($info['description'],80);
        	$items[$info['id']]['jobs'] = count_company_jobs($info['id']);
        	$items[$info['id']]['link'] = $link['COMPANY-DETAIL'].'/'.$info['id'].'/'.create_slug($info['name']);
        }
    }

    $pagging = pagenav($total,$page,$limit,$link['MYCOMPANIES']);
    //Print Template
    HtmlTemplate::display('my-companies', array(
        'items' => $items,
        'totalitem' => $total,
        'keywords' => $keywords,
        'pages' => $pagging
    ));
    exit;
}else{
	headerRedirect($link['LOGIN']);
}
?>
