<?php
if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 5;

if(checkloggedin()) {

    $items = get_items($_SESSION['user']['id'],"pending",false,$_GET['page'],$limit);
    $total_item = get_items_count($_SESSION['user']['id'],"pending");
    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['PENDINGJOBS']);

    //Print Template
    HtmlTemplate::display('job-pending-approval', array(
        'items' => $items,
        'totalitem' => $total_item,
        'pages' => $pagging
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
