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
    3 =>'c.name',
    4 =>'ct.name',
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
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR cat.cat_name LIKE '".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ) ";

        $where .=" AND ( p.status = '".$_GET['status']."' )";
    }
    elseif(isset($_GET['hide'])) {
        $where .=" WHERE ";
        $where .=" ( p.product_name LIKE '%".$params['search']['value']."%' ";
        $where .=" OR u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR cat.cat_name LIKE '".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ) ";

        $where .=" AND ( p.hide = '".$_GET['hide']."' )";
    }
    else{
        $where .=" WHERE ";
        $where .=" ( p.product_name LIKE '%".$params['search']['value']."%' ";
        $where .=" OR u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR cat.cat_name LIKE '".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ) ";
    }
}


// getting total number records without any search
$sql = "SELECT p.id,p.product_name,p.created_at,p.status,p.screen_shot,p.featured,p.urgent,p.highlight, ct.name as cityname, cat.cat_name as catname, u.username as username, c.name as company, c.logo as company_image
FROM `".$config['db']['pre']."product` as p
INNER JOIN `".$config['db']['pre']."user` as u ON u.id = p.user_id
LEFT JOIN `".$config['db']['pre']."companies` as c ON c.id = p.company_id
INNER JOIN `".$config['db']['pre']."cities` as ct ON ct.id = p.city
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
    }elseif(isset($_GET['hide'])) {
        $where .=" Where ( p.hide = '".$_GET['hide']."' )";
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
    $company = !empty($row['company'])?htmlspecialchars($row['company']):'&#8211;';
    $ad_created_at  = timeAgo($row['created_at']);
    $ad_category = htmlspecialchars($row['catname']);
    $ad_status    = $row['status'];
    $picture     =   explode(',' ,$row['screen_shot']);
    $featured = $row['featured'];
    $urgent = $row['urgent'];
    $highlight = $row['highlight'];
    $company_image = !empty($row['company_image'])?$row['company_image']:'default.png';
    $image = !empty($row['screen_shot'])?$row['screen_shot']:$company_image;

    $image_tag = '';
    if($config['job_image_field']){
        $image_tag = '<div class="pull-left m-r"><img class="img-avatar img-avatar-squre" src="'.$config['site_url'].'storage/products/'.$image.'"></div>';
    }

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
    if ($ad_status == "active"){
        $status = '<span class="label label-success">Approved</span>';
    }
    elseif($ad_status == "pending")
    {
        $status = '<span class="label label-warning">Pending</span>';
    }
    elseif($ad_status == "expire")
    {
        $status = '<span class="label label-danger">Expire</span>';
    }
    else{
        $status = '<span class="label label-danger">Rejected</span>';
    }

    if($ad_status == "pending"){

        $approved_button = '<a href="#"  class="btn btn-xs btn-success item-approve" data-ajax-action="approveitem"><i class="ion-android-done"></i></a>';
    }
    else{
        $approved_button = "";
    }

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="'.$id.'" id="row_'.$id.'" name="row_'.$id.'"><span></span>
                </label>
            </td>';
    $row1 = '<td class="text-center">'.$image_tag.'
                <p class="font-500 m-b-0"><a href="post_detail.php?id='.$id.'" target="_blank">'.$title.'</a></p>
                <p class="m-b-0">'.$premium.'</p>
                <p class="text-muted m-b-0">'.$ad_category.'</p>
            </td>';
    $row2 = '<td class="hidden-xs">'.$username.'</td>';
    $row3 = '<td class="hidden-xs">'.$company.'</td>';
    $row4 = '<td class="hidden-xs">'.$row['cityname'].'</td>';
    $row5 = '<td class="hidden-xs hidden-sm">'.$ad_created_at.'</td>';
    $row6 = '<td class="hidden-xs hidden-sm">'.$status.'</td>';

    $row7 = '<td class="text-center">
                <div class="btn-group">
                '.$approved_button.'
                    <a href="post_detail.php?id='.$id.'" title="View Ad" class="btn btn-xs btn-default"><i class="ion-eye"></i></a>
                    <a href="#" data-url="panel/post_edit.php?id='.$id.'" data-toggle="slidePanel"  title="Edit" class="btn btn-xs btn-default"> <i class="ion-edit"></i> </a>
                    <a href="#" title="Delete" class="btn btn-xs btn-default item-js-delete" data-ajax-action="deleteads"><i class="ion-close"></i></a>
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
