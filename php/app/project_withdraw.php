<?php
if(checkloggedin()) {

    $error = "";
    if(isset($_POST['Submit']) && isset($_POST['payment_id']))
    {
        if(is_numeric($_POST['amount']) || $_POST['amount'] > 0){
            minus_balance($_SESSION['user']['id'],$_POST['amount']);
            $now = date("Y-m-d H:i:s");
            $create_withdraw = ORM::for_table($config['db']['pre'].'withdrawal')->create();
            $create_withdraw->user_id = $_SESSION['user']['id'];
            $create_withdraw->amount = validate_input($_POST['amount']);
            $create_withdraw->payment_method_id = validate_input($_POST['payment_id']);
            $create_withdraw->account_details = validate_input($_POST['account_details']);
            $create_withdraw->created_at = $now;
            $create_withdraw->save();

            message(__("Success"),__("Amount added Successfully to withdrawal."),$link['WITHDRAW']);
            exit();
        }else{
            $error = "Amount not valid";
        }
    }

    $rows = ORM::for_table($config['db']['pre'].'payments')
        ->where('payment_install', '1')
        ->find_many();
    $num_rows = count($rows);
    foreach ($rows as $info)
    {
        $payment_types[$info['payment_id']]['id'] = $info['payment_id'];
        $payment_types[$info['payment_id']]['title'] = $info['payment_title'];
        $payment_types[$info['payment_id']]['cost'] = $info['payment_cost'];
        $payment_types[$info['payment_id']]['folder'] = $info['payment_folder'];
        $payment_types[$info['payment_id']]['desc'] = $info['payment_desc'];
    }

    $withdraw = array();
    $rows2 = ORM::for_table($config['db']['pre'].'withdrawal')
        ->where('user_id',$_SESSION['user']['id'])
        ->find_many();
    $withdraw_count = count($rows2);
    foreach ($rows2 as $info)
    {
        $withdraw[$info['id']]['id'] = $info['id'];
        $withdraw[$info['id']]['user_id'] = $info['user_id'];
        $withdraw[$info['id']]['amount'] = $info['amount'];
        $withdraw[$info['id']]['payment_id'] = $info['payment_method_id'];
        $withdraw[$info['id']]['time'] = date('d M Y h:i A', strtotime($info['created_at']));

        $t_status = $info['status'];
        $status = '';
        if ($t_status == "success") {
            $status = '<span class="dashboard-status-button green">'.__("Success").'</span>';
        } elseif ($t_status == "pending") {
            $status = '<span class="dashboard-status-button blue">'.__("Pending").'</span>';
        } else{
            $status = '<span class="dashboard-status-button red">'.__("Reject").'</span>';
        }

        $withdraw[$info['id']]['status'] = $status;
    }

    //Print Template
    HtmlTemplate::display('project_withdraw', array(
        'payment_types' => $payment_types,
        'withdraw' => $withdraw,
        'withdraw_count' => $withdraw_count
    ));
    exit;
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
