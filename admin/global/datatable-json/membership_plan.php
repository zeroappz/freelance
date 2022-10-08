<?php
define("ROOTPATH", dirname(dirname(dirname(__DIR__))));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';
admin_session_start();
$pdo = ORM::get_db();

// initilize all variable
$params = $columns = $totalRecords = $data = array();
$params = $_REQUEST;

//define index of column
$columns = array(
    0 =>'id',
    1 =>'name',
    2 =>'monthly_price',
    3 =>'annual_price',
    4 =>'lifetime_price'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( name LIKE '".$params['search']['value']."%' )";
}

// getting total number records without any search
$sql = "SELECT * FROM `".$config['db']['pre']."plans` ";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if(isset($where) && $where != '') {

    $sqlTot .= $where;
    $sqlRec .= $where;
}


$sqlRec .=  " ORDER BY ". $columns[$params['order'][0]['column']]."   ".$params['order'][0]['dir']."  LIMIT ".$params['start']." ,".$params['length']." ";

$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

$free_plan = json_decode(get_option('free_membership_plan'), true);

$value = array(
    "DT_RowId" => $free_plan['id'],
    0 => '<td>'.$free_plan['name'].'</td>',
    1 => '<td>-</td>',
    2 => '<td>-</td>',
    3 => '<td>-</td>',
    4 => '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/membership_plan_edit.php?id=free" data-toggle="slidePanel" class="btn btn-xs btn-default"> <i class="ion-edit"></i> Edit</a>
                </div>
            </td>',
);
$data[] = $value;

$trial_plan = json_decode(get_option('trial_membership_plan'), true);

$value = array(
    "DT_RowId" => $trial_plan['id'],
    0 => '<td>'.$trial_plan['name'].'</td>',
    1 => '<td>-</td>',
    2 => '<td>-</td>',
    3 => '<td>-</td>',
    4 => '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/membership_plan_edit.php?id=trial" data-toggle="slidePanel" class="btn btn-xs btn-default"> <i class="ion-edit"></i> Edit</a>
                </div>
            </td>',
);
$data[] = $value;

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    //$data[] = $row;
    $id = $row['id'];
    $plan_name = $row['name'];
    $monthly_price = $row['monthly_price'];
    $annual_price = $row['annual_price'];
    $lifetime_price = $row['lifetime_price'];

    $row0 = '<td>'.$plan_name.'</td>';
    $row1 = '<td>'.price_format($monthly_price).'</td>';
    $row2 = '<td>'.price_format($annual_price).'</td>';
    $row3 = '<td>'.price_format($lifetime_price).'</td>';
    $row4 = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/membership_plan_edit.php?id='.$id.'" data-toggle="slidePanel" class="btn btn-xs btn-default"> <i class="ion-edit"></i> Edit</a>
                    <a href="javascript:void(0)" class="btn btn-xs btn-danger item-js-delete" data-ajax-action="deleteMembershipPlan"> <i class="ion-close"></i> Delete</a>
                </div>
            </td>';

    $value = array(
        "DT_RowId" => $id,
        0 => $row0,
        1 => $row1,
        2 => $row2,
        3 => $row3,
        4 => $row4,
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
