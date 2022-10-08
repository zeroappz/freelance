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
    0 => 'created_at',
    1 => 'name',
    2 => 'comment',
    3 => 'blog_id',
    4 => 'created_at',
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if (!empty($params['search']['value'])) {
    if (isset($_GET['status'])) {
        $where .= " WHERE ";
        $where .= " comment LIKE '%" . $params['search']['value'] . "%' ";
        $where .= " OR name LIKE '" . $params['search']['value'] . "%' ";
        $where .= " OR email LIKE '" . $params['search']['value'] . "%' ";
    } elseif (isset($_GET['hide'])) {
        $where .= " WHERE ";
        $where .= " comment LIKE '%" . $params['search']['value'] . "%' ";
        $where .= " OR name LIKE '" . $params['search']['value'] . "%' ";
        $where .= " OR email LIKE '" . $params['search']['value'] . "%' ";
    } else {
        $where .= " WHERE ";
        $where .= " comment LIKE '%" . $params['search']['value'] . "%' ";
        $where .= " OR name LIKE '" . $params['search']['value'] . "%' ";
        $where .= " OR email LIKE '" . $params['search']['value'] . "%' ";
    }
}


// getting total number records without any search
$sql = "SELECT * FROM `" . $config['db']['pre'] . "blog_comment`";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if (isset($where) && $where != '') {
    $sqlTot .= $where;
    $sqlRec .= $where;
} else {
    if (isset($_GET['status'])) {
        $where .= " Where ( b.status = '" . $_GET['status'] . "' )";
        $sqlTot .= $where;
        $sqlRec .= $where;
    }
}

$sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'] . " ";


$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    $id = $row['id'];
    $name = $row['name'];
    $email = $row['email'];
    $cmnt = $row['comment'];
    $status = '';
    if ($row['active'] == "0") {
        $status = '<span class="label label-warning">Unapproved</span>';
    } elseif ($row['active'] == "1") {
        $status = '<span class="label label-success">Approved</span>';
    }
    $info = ORM::for_table($config['db']['pre'] . 'blog')->find_one($row['blog_id']);

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="' . $id . '" id="row_' . $id . '" name="row_' . $id . '"><span></span>
                </label>
            </td>';
    $row1 = '<td class="text-center">
                <p class="font-500">' . $name . '</p>
                <small>' . $email . '</small>
            </td>';
    $row2 = '<td class="hidden-xs">' . $cmnt . '</td>';
    $row3 = '<td><a href="' . $config['site_url'] . 'blog/' . $info['id'] . '" target="_blank">' . $info['title'] . '</a></td>';
    $row4 = '<td class="hidden-xs">' . $status . '</td>';
    $row5 = '<td class="hidden-xs">' . date('d, M Y H:i:s', strtotime($row['created_at'])) . '</td>';
    $row6 = '<td class="text-center">
                <div class="btn-group">'.
        ($row['active'] == "0"?'<a href="#" title="Approve" class="btn btn-xs btn-default item-approve" data-ajax-action="approveComment"> <i class="ion-edit"></i> </a>':'').'
                    <a href="#" title="Delete" class="btn btn-xs btn-default item-js-delete" data-ajax-action="deleteComment"><i class="ion-close"></i></a>
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
        6 => $row6
    );
    $data[] = $value;



}

$json_data = array(
    "draw" => intval($params['draw']),
    "recordsTotal" => intval($totalRecords),
    "recordsFiltered" => intval($totalRecords),
    "data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
