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
    0 => 'id',
    1 => 'name'
);

$where = $sqlTot = $sqlRec = "";

// check search value exist
if (!empty($params['search']['value'])) {
    if (isset($_GET['status'])) {
        $where .= " WHERE ";
        $where .= " name LIKE '%" . $params['search']['value'] . "%' ";
    } elseif (isset($_GET['hide'])) {
        $where .= " WHERE ";
        $where .= " name LIKE '%" . $params['search']['value'] . "%' ";
    } else {
        $where .= " WHERE ";
        $where .= " name LIKE '%" . $params['search']['value'] . "%' ";
    }
}


// getting total number records without any search
$sql = "SELECT * FROM `" . $config['db']['pre'] . "testimonials`";
$sqlTot .= $sql;
$sqlRec .= $sql;
//concatenate search sql if value exist
if (isset($where) && $where != '') {
    $sqlTot .= $where;
    $sqlRec .= $where;
}

$sqlRec .= " ORDER BY " . $columns[$params['order'][0]['column']] . " " . $params['order'][0]['dir'] . " LIMIT " . $params['start'] . " ," . $params['length'] . " ";

$queryTot = $pdo->query($sqlTot);
$totalRecords = $queryTot->rowCount();
$queryRecords = $pdo->query($sqlRec);

//iterate on results row and create new index array of data
foreach ($queryRecords as $row) {
    $id = $row['id'];
    $name = htmlspecialchars($row['name']);
    $designation = htmlspecialchars($row['designation']);
    $content = stripslashes($row['content']);

    if($row['image'] != ""){
        $image = $row['image'];
    }else{
        $image = "default_user.png";
    }

    $row0 = '<td>
                <label class="css-input css-checkbox css-checkbox-default">
                    <input type="checkbox" class="service-checker" value="' . $id . '" id="row_' . $id . '" name="row_' . $id . '"><span></span>
                </label>
            </td>';
    $row1 = '<td class="w-30">
                <div class="pull-left m-r"><img class="img-avatar img-avatar-round" src="'.$config['site_url'].'storage/testimonials/'.$image.'"></div>
                <p class="font-500 m-b-0">'.$name.'</p>
                <p class="text-muted m-b-0">'.$designation.'</p>
            </td>';
    $row2 = '<td class="hidden-xs hidden-sm">' . $content . '</td>';
    $row3 = '<td class="text-center">
                <div class="btn-group">
                    <a href="#" data-url="panel/testimonial_edit.php?id='.$id.'" data-toggle="slidePanel"  title="Edit" class="btn btn-xs btn-default"> <i class="ion-edit"></i> </a>
                    <a href="#" title="Delete" class="btn btn-xs btn-default item-js-delete" data-ajax-action="deleteTestimonial"><i class="ion-close"></i></a>
                </div>
            </td>';
    $value = array(
        "DT_RowId" => $id,
        0 => $row0,
        1 => $row1,
        2 => $row2,
        3 => $row3
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
