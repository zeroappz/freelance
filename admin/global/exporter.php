<?php
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

$mysqli = db_connect();
admin_session_start();
checkloggedadmin();


if(isset($_POST['export'])){

// set headers to force download on csv format
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=product_table.csv');

// we initialize the output with the headers
    $output = "id,user_id,product_name,status,category,sub_category,description,price,phone,city,state,country,image,created_at,updated_at,expire_date\n";
// select all members
    $rows = ORM::for_table($config['db']['pre'].'product')->limit(20)->find_many();
    foreach ($rows as $info) {
        // add new row
        $title = str_replace(array(',', ' '), '-', $info['product_name']);
        $desc = str_replace(array(',', ' '), '-', $info['description']);
        $price = str_replace(array(',', ' '), '-', $info['price']);
        $phone = str_replace(array(',', ' '), '-', $info['phone']);
        $main_Screen = $info['screen_shot'];
        $screen_shot = str_replace(array(',', ' '), '-', $main_Screen);
        $output .= $info['id'].",".$info['user_id'].",".$title.",".$info['status'].",".$info['category'].",".$info['sub_category'].",".$desc.",".$price.",".$phone.",".$info['city'].",".$info['state'].",".$info['country'].",".$main_Screen.",".$info['created_at'].",".$info['expire_date']."\n";
    }
// export the output
    echo $output;
    exit;
}
?>