<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

// initilize all variable
$params = $columns = $order = $totalRecords = $data = array();
$params = $_REQUEST;
if($params['draw'] == 1)
    $params['order'][0]['dir'] = "desc";
//define index of column
$columns = array(
    0 =>'p.id',
    1 =>'p.product_name',
    2 =>'u.username',
    5 =>'p.created_at',
    6 =>'p.status'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ){
    if(isset($_GET['status'])) {
        $where .=" WHERE ";
        $where .=" ( p.product_name LIKE '%".$params['search']['value']."%' ";
        $where .=" OR u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR cat.cat_name LIKE '".$params['search']['value']."%' ) ";

        $where .=" AND ( p.status = '".$_GET['status']."' )";
    }
    else{
        $where .=" WHERE ";
        $where .=" ( p.product_name LIKE '%".$params['search']['value']."%' ";
        $where .=" OR u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR cat.cat_name LIKE '".$params['search']['value']."%' ) ";
    }
}


// getting total number records without any search
$sql = "SELECT p.id,p.product_name,p.created_at,p.status,p.featured,p.urgent,p.highlight, cat.cat_name as catname, u.username as username
FROM `".$config['db']['pre']."project` as p
INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id
INNER JOIN `".$config['db']['pre']."catagory_main` as cat ON cat.cat_id = p.category ";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if(isset($where) && $where != '') {
    $sqlTot .= $where;
    $sqlRec .= $where;
}else{
    if(isset($_GET['status'])){
        $where .=" Where ( p.status = '".$_GET['status']."' )";
        $sqlTot .= $where;
        $sqlRec .= $where;
    }
}

$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]." ".$params['order'][0]['dir']." LIMIT ".$params['start']." ,".$params['length']." ";

$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    $id = $row['id'];
    $username = $row['username'];
    $title = htmlspecialchars($row['product_name']);
    $ad_created_at  = timeAgo($row['created_at']);
    $ad_category = htmlspecialchars($row['catname']);
    $ad_status    = $row['status'];
    $featured = $row['featured'];
    $urgent = $row['urgent'];
    $highlight = $row['highlight'];

    $bids_count = ORM::for_table($config['db']['pre'].'bids')
        ->where('project_id',$id)
        ->count();

    $milestone_count = ORM::for_table($config['db']['pre'].'milestone')
        ->where('project_id',$id)
        ->count();

    $premium = '';
    if ($featured == "1"){
        $premium = $premium.'<span class="badge fs-12">featured</span>';
    }

    if($urgent == "1")
    {
        $premium = $premium.'<span class="badge btn-danger fs-12">Urgent</span>';
    }

    if($highlight == "1")
    {
        $premium = $premium.'<span class="badge btn-primary fs-12">Highlight</span>';
    }

    $status = '';
    if ($ad_status == "open"){
        $status = '<span class="label label-success">Open</span>';
    }
    elseif($ad_status == "pending_for_approval")
    {
        $status = '<span class="label label-warning">Pending For Approval</span>';
    }
    elseif($ad_status == "under_development")
    {
        $status = '<span class="label label-info">Ongoing</span>';
    }
    elseif($ad_status == "completed")
    {
        $status = '<span class="label label-primary">Completed</span>';
    }
    elseif($ad_status == "incomplete")
    {
        $status = '<span class="label label-danger">Incomplete</span>';
    }
    else{
        $status = '<span class="label label-danger">Closed</span>';
    }

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="'.$id.'" id="row_'.$id.'" name="row_'.$id.'"><span></span>
                </label>
            </td>';
    $row1 = '<td class="text-center">
                <p class="font-500 m-b-0"><a href="'.$config['site_url'].'project/'.$id.'" target="_blank">'.$title.'</a></p>
                <p class="m-b-0">'.$premium.'</p>
                <p class="text-muted m-b-0">'.$ad_category.'</p>
            </td>';
    $row2 = '<td class="hidden-xs">'.$username.'</td>';
    $row3 = '<td class="hidden-xs">'.$bids_count.' Bid</td>';
    $row4 = '<td class="hidden-xs"><a class="label label-warning" href="milestone.php?id='.$id.'" target="_blank">'.$milestone_count.' Milestone</a></td>';
    $row5 = '<td class="hidden-xs hidden-sm">'.$ad_created_at.'</td>';
    $row6 = '<td class="hidden-xs hidden-sm">'.$status.'</td>';

    $row7 = '<td class="text-center">
                <div class="btn-group">
                    <a href="'.$config['site_url'].'project/'.$id.'" target="_blank" title="View" class="btn btn-xs btn-default"><i class="ion-eye"></i></a>
                    
                    <a href="#" title="Delete" class="btn btn-xs btn-default item-js-delete" data-ajax-action="deleteProject"><i class="ion-close"></i></a>
                </div>
            </td>';
    $value = array(
        "DT_RowId" => $id,
        0 => $row0,
        1 => $row1,
        2 => $row2,
        3 => $row3,
        4 => $row4,
        5 => $row5,
        6 => $row6,
        7 => $row7
    );
    $data[] = $value;

}

$json_data = array(
    "draw"            => intval( $params['draw'] ),
    "recordsTotal"    => intval( $totalRecords ),
    "recordsFiltered" => intval($totalRecords),
    "data"            => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
