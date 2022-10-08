<?php
if(!isset($_GET['page']))
    $_GET['page'] = 1;

$page = $_GET['page'];
$limit = 10;

if(checkloggedin()) {

    $pagelimit = "";
    if($page != null && $limit != null){
        $pagelimit = ($page-1)*$limit.",".$limit;
    }

    $pdo = ORM::get_db();
    $query = "UPDATE `".$config['db']['pre']."push_notification` SET `recd` = '1' WHERE `owner_id` = '" . $_SESSION['user']['id'] . "' ";
    $pdo->query($query);

    $total_item = ORM::for_table($config['db']['pre'].'push_notification')
        ->where('owner_id',$_SESSION['user']['id'])
        ->orderByDesc('id')
        ->count();

    $notification = array();
    $rows = ORM::for_table($config['db']['pre'].'push_notification')
        ->where('owner_id',$_SESSION['user']['id'])
        ->orderByDesc('id')
        ->limit($pagelimit)
        ->find_many();

    foreach ($rows as $info)
    {
        $note['sender_id'] = $info['sender_id'];
        $note['sender_name'] = $info['sender_name'];
        $note['owner_id'] = $info['owner_id'];
        $note['owner_name'] = $info['owner_name'];
        $note['product_id'] = $info['product_id'];
        $note['product_title'] = $info['product_title'];
        $note['type'] = $info['type'];
        $note['message'] = $info['message'];

        $notification[] = $note;
    }

    $pagging = pagenav($total_item,$_GET['page'],$limit,$link['NOTIFICATIONS']);

    //Print Template
    HtmlTemplate::display('project_notifications', array(
        'notification' => $notification,
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