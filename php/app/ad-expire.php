<?php
if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 5;

if(checkloggedin()) {
    update_lastactive();
    $items = get_items($_SESSION['user']['id'],"expire",false,$_GET['page'],$limit);
    $total_item = get_items_count($_SESSION['user']['id'],"expire");
    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['EXPIREJOBS']);

    //Print Template
    HtmlTemplate::display('job-expire', array(
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
