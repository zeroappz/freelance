<?php
if(!isset($_GET['page']))
    $_GET['page'] = 1;

$limit = 5;

if(checkloggedin()) {
    $ses_userdata = get_user_data($_SESSION['user']['username']);
    $author_image = $ses_userdata['image'];

    $total_item = resubmited_ads_count($_SESSION['user']['id']);

    $items = get_resubmited_items($_SESSION['user']['id'],"",$_GET['page'],$limit);

    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['RESUBMITJOBS']);

    //Print Template
    HtmlTemplate::display('job-resubmission', array(
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
