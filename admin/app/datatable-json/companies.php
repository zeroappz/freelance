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
if ($params['order'][0]['column'] == 0) {
    $params['order'][0]['dir'] = "desc";
}
//define index of column
$columns = array(
    0 =>'c.id',
    1 =>'c.name',
    2 =>'c.description',
    3 =>'u.username',
    4 =>'c.city'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ){
    if(isset($_GET['status'])) {
        $where .=" WHERE ";
        $where .=" u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
    }
    elseif(isset($_GET['hide'])) {
        $where .=" WHERE ";
        $where .=" u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
    }
    else{
        $where .=" WHERE ";
        $where .=" u.username LIKE '%".$params['search']['value']."%' ";
        $where .=" OR c.name LIKE '".$params['search']['value']."%' ";
        $where .=" OR ct.name LIKE '".$params['search']['value']."%' ";
    }
}


// getting total number records without any search
$sql = "SELECT c.*, u.username, ct.name cityname FROM `".$config['db']['pre']."companies` c
LEFT JOIN `".$config['db']['pre']."user` u ON u.id = c.user_id
LEFT JOIN `".$config['db']['pre']."cities` ct ON ct.id = c.city";
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
    $title = htmlspecialchars($row['name']);
    $desc = strlimiter(stripcslashes(($row['description'])), 100);
    $jobs = count_company_jobs($row['id']);

    if($row['logo'] != ""){
        $image = $row['logo'];
    }else{
        $image = "default.png";
    }

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="'.$id.'" id="row_'.$id.'" name="row_'.$id.'"><span></span>
                </label>
            </td>';
    $row1 = '<td class="text-center">
                <div class="pull-left m-r"><img class="img-avatar img-avatar-squre" src="'.$config['site_url'].'storage/products/'.$image.'"></div>
                <p class="font-500 m-b-0"><a href="company_details.php?id='.$id.'" target="_blank">'.$title.'</a></p>
                <p class="text-muted m-b-0">'.$jobs.' jobs'.'</p>
            </td>';
    $row2 = '<td class="hidden-xs">'.$desc.'</td>';
    $row3 = '<td class="hidden-xs">'.$username.'</td>';
    $row4 = '<td class="hidden-xs hidden-sm">'.$row['cityname'].'</td>';

    $row5 = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/company_edit.php?id='.$id.'" data-toggle="slidePanel"  title="Edit" class="btn btn-xs btn-default"> <i class="ion-edit"></i> </a>
                    <a href="#" title="Delete" class="btn btn-xs btn-default item-js-delete" data-ajax-action="deleteCompany"><i class="ion-close"></i></a>
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
