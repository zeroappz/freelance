<?php
if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 5;

if(checkloggedin()) {
    $items = get_items($_SESSION['user']['id'],"hide",false,$_GET['page'],$limit);
    $total_item = get_items_count($_SESSION['user']['id'],"hide");
    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['HIDDENJOBS']);

    //Print Template
    HtmlTemplate::display('job-hidden', array(
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
