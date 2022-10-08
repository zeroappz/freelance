<?php
if(isset($_GET['page']) && !empty($_GET['page']) && is_numeric($_GET['page'])){
    $page = $_GET['page'];
}else{
    $page = 1;
}

if(isset($_GET['limit']) && !empty($_GET['limit']) && is_numeric($_GET['limit'])){
    $limit = $_GET['limit'];
}else{
    $limit = 6;
}

if(isset($_GET['status']) && !empty($_GET['status'])){
    $status = $_GET['status'];
}else{
    $status = '';
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
    $sort = "p.id";
elseif($_GET['sort'] == "title")
    $sort = "product_name";
elseif($_GET['sort'] == "price")
    $sort = "price";
elseif($_GET['sort'] == "date")
    $sort = "created_at";
else
    $sort = "p.id";

$sorting = isset($_GET['sort']) ? $_GET['sort'] : "Newest";

if(checkloggedin()) {

    $keywords = isset($_GET['keywords']) ? str_replace("-"," ",$_GET['keywords']) : "";


    $where = '';
    if(isset($_GET['keywords']) && !empty($_GET['keywords'])){
        $where.= "AND (p.product_name LIKE '%$keywords%' or p.tag LIKE '%$keywords%') ";
    }

    if($status){
        if($status == 'open' or $status == 'closed'){
            $where.= "AND (p.status = '".$status."') ";
        }else{
            $where.= "AND (p.status = '".$status."') AND (p.freelancer_id = '".$_SESSION['user']['id']."') ";
        }
    }else{
        if($_SESSION['user']['user_type'] == 'user'){
            $where.= " AND ((p.freelancer_id = '".$_SESSION['user']['id']."') OR (p.freelancer_id IS NULL ))";
        }
    }

    if($_SESSION['user']['user_type'] == 'employer'){

        $sql = "SELECT p.* FROM `".$config['db']['pre']."project` p WHERE p.user_id = '".$_SESSION['user']['id']."' ";

        $total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."project as p 
        WHERE user_id = '".$_SESSION['user']['id']."' $where"));

        $query = "$sql $where ORDER BY $sort DESC LIMIT ".($page-1)*$limit.",$limit";

        $result = ORM::for_table($config['db']['pre'].'product')->raw_query($query)->find_many();

    }else{

        $sql = "SELECT b.id as bid_id,p.* FROM `".$config['db']['pre']."bids` b 
        LEFT JOIN `".$config['db']['pre']."project` p on p.id = b.project_id
        WHERE b.user_id = '".$_SESSION['user']['id']."' ";

        $total = mysqli_num_rows(mysqli_query($mysqli, "SELECT 1 FROM ".$config['db']['pre']."bids as b 
        LEFT JOIN `".$config['db']['pre']."project` p on p.id = b.project_id
        WHERE b.user_id = '".$_SESSION['user']['id']."' $where "));

        $query = "$sql $where ORDER BY $sort DESC LIMIT ".($page-1)*$limit.",$limit";

        $result = ORM::for_table($config['db']['pre'].'bids')->raw_query($query)->find_many();
    }

    $items = array();
    if ($result) {
        foreach ($result as $info)
        {
            $items[$info['id']]['id'] = $info['id'];
            $items[$info['id']]['status'] = $info['status'];
            $items[$info['id']]['product_name'] = $info['product_name'];
            $items[$info['id']]['freelancer_id'] = $info['freelancer_id'];
            $items[$info['id']]['featured'] = $info['featured'];
            $items[$info['id']]['urgent'] = $info['urgent'];
            $items[$info['id']]['highlight'] = $info['highlight'];
            $items[$info['id']]['highlight_bgClr'] = ($info['highlight'] == 1)? "highlight-premium-ad" : "";
            $items[$info['id']]['created_at'] = timeAgo($info['created_at']);

            $items[$info['id']]['salary_min'] = $info['salary_min'];
            $items[$info['id']]['salary_max'] = $info['salary_max'];
            $items[$info['id']]['salary_type'] = ($info['salary_type'] == 0)? __("Fixed Price") : __("Hourly Price");

            if($info['status'] == "completed"){
                if(rating_exist($info['id'])){
                    $items[$info['id']]['rated'] = 0;
                }else{
                    $items[$info['id']]['rated'] = 1;
                }
            }else{
                $items[$info['id']]['rated'] = 0;
            }


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
            foreach ($data as $d) {
                $full_amount = $full_amount + $d['amount'];
            }

            $avg_bid = ($bids_count == 0)? 0 : $full_amount / $bids_count;
            $items[$info['id']]['bids_count'] = $bids_count;
            $items[$info['id']]['avg_bid'] = $avg_bid;

            $pro_url = create_slug($info['product_name']);
            $items[$info['id']]['link'] = $link['PROJECT'].'/' . $info['id'] . '/'.$pro_url;
        }
    }

    $total_item = get_items_count($_SESSION['user']['id']);

    $pagging = pagenav($total_item,$page,$limit,$link['MYPROJECTS']);

    $Pagelink = "";
    if(count($_GET) >= 1){
        $get = http_build_query($_GET);
        $Pagelink .= "?".$get;

        $pagging = pagenav($total,$page,$limit,$link['MYPROJECTS'].$Pagelink,1);
    }else{
        $pagging = pagenav($total,$page,$limit,$link['MYPROJECTS']);
    }

    //Print Template
    HtmlTemplate::display('project_manage', array(
        'items' => $items,
        'pages' => $pagging,
        'limit' => $limit,
        'sort' => $sorting,
        'order' => $order,
        'keywords' => $keywords,
        'adsfound' => $total,
        'totalitem' => $total_item
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
