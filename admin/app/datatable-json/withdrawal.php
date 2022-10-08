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
    1 =>'user_id',
    2 =>'amount',
    3 =>'payment_id',
    4 =>'status',
    5 =>'time',
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if( !empty($params['search']['value']) ) {
    $where .=" WHERE ";
    $where .=" ( w.status LIKE '%".$params['search']['value']."%' ";
    $where .=" OR u.username LIKE '%".$params['search']['value']."%' ";
    $where .=" OR p.payment_title LIKE '%".$params['search']['value']."%'  ) ";
}

// getting total number records without any search
$sql = "SELECT w.*, u.username as username, p.payment_title as payment_title
FROM `".$config['db']['pre']."withdrawal` as w
INNER JOIN `".$config['db']['pre']."user` as u ON u.id = w.user_id 
INNER JOIN `".$config['db']['pre']."payments` as p ON p.payment_id = w.payment_method_id ";
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

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    //$data[] = $row;
    $id = $row['id'];
    $username = $row['username'];
    $amount = $row['amount'];
    $payment_title = $row['payment_title'];
    $account_details = $row['account_details'];
    $created_at  = date('d M Y h:i A', strtotime($row['created_at']));

    $t_status = $row['status'];
    $status = '';
    if ($t_status == "success") {
        $status = '<span class="label label-success">'.__("Paid").'</span>';
    } elseif ($t_status == "pending") {
        $status = '<span class="label label-warning">'.__("Pending").'</span>';
    } else{
        $status = '<span class="label label-danger">'.__("Reject").'</span>';
    }

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="'.$id.'" id="row_'.$id.'" name="row_'.$id.'"><span></span>
                </label>
            </td>';
    $row1 = '<td>'.$username.'</td>';
    $row2 = '<td>'.$config['currency_sign'].$amount.'</td>';
    $row3 = '<td>'.$payment_title.'</td>';
    $row4 = '<td>'.$account_details.'</td>';
    $row5 = '<td>'.$status.'</td>';
    $row6 = '<td>'.$created_at.'</td>';
    $row7 = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/withdraw_edit.php?id='.$id.'" data-toggle="slidePanel" class="btn btn-xs btn-default"> <i class="ion-edit"></i> Edit</a>
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
