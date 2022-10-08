<?php
if(checkloggedin()) {
    update_lastactive();

    if (!isset($_GET['id'])) {
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit;
    }
    if($_SESSION['user']['user_type'] == 'employer'){
        $count = ORM::for_table($config['db']['pre'] . 'project')
            ->select('product_name')
            ->where('id', $_GET['id'])
            ->where('user_id', $_SESSION['user']['id'])
            ->count();
    }else{
        $count = ORM::for_table($config['db']['pre'] . 'project')
            ->select('product_name')
            ->where('id', $_GET['id'])
            ->where('freelancer_id', $_SESSION['user']['id'])
            ->count();
    }

    if($count == 0){
        error(__("Project does not belong to you"), __LINE__, __FILE__, 1);
        exit();
    }

    $project = ORM::for_table($config['db']['pre'] . 'project')
        ->select_many('product_name','status','freelancer_id')
        ->where('id', $_GET['id'])
        ->findOne();
    $project_id = $_GET['id'];
    $project_title = $project['product_name'];
    $project_status = $project['status'];
    $freelancer_id = $project['freelancer_id'];

    $bid = ORM::for_table($config['db']['pre'] . 'bids')
        ->select('amount')
        ->where(array(
            'project_id'=> $_GET['id'],
            'user_id'=> $freelancer_id
        ))
        ->findOne();

    $project_amount = $bid['amount'];

    $ses_userdata = ORM::for_table($config['db']['pre'].'user')
        ->select('balance')
        ->find_one($_SESSION['user']['id']);

    if($_SESSION['user']['user_type'] == 'employer'){
        $result = ORM::for_table($config['db']['pre'] . 'milestone')
            ->where('project_id', $_GET['id'])
            ->where('employer_id', $_SESSION['user']['id'])
            ->find_many();
    }else{
        $result = ORM::for_table($config['db']['pre'] . 'milestone')
            ->where('project_id', $_GET['id'])
            ->where('freelancer_id', $_SESSION['user']['id'])
            ->find_many();
    }

    $milestone = array();
    $total_item = 0;
    $epaid = 0;
    $epending = 0;
    $total_escrow = 0;
    if (!empty($result)) {
        $total_item = count($result);
        foreach ($result as $info)
        {
            $milestone[$info['id']]['id'] = $info['id'];
            $milestone[$info['id']]['title'] = $info['title'];
            $milestone[$info['id']]['amount'] = $info['amount'];
            $milestone[$info['id']]['created_by'] = $info['created_by'];
            $milestone[$info['id']]['project_id'] = $info['project_id'];
            $milestone[$info['id']]['request_id'] = $info['request'];
            $milestone[$info['id']]['start_date'] = date('d-M-Y', strtotime($info['start_date']));

            if($info['status'] == 'paid'){
                $epaid = $epaid + $info['amount'];
            }else{
                $epending = $epending + $info['amount'];
            }
            $total_escrow = $epaid + $epending;
        }

        $remaining_amount = $project_amount - $total_escrow;
    }

    // Get membership details
    $group_info = get_user_membership_settings();
    $freelancer_commission = $group_info['freelancer_commission'];
    $employer_commission = $group_info['employer_commission'];
    //Print Template
    HtmlTemplate::display('project_milestone', array(
        'milestones' => $milestone,
        'freelancer_commission' => $freelancer_commission,
        'employer_commission' => $employer_commission,
        'balance' => $ses_userdata['balance'],
        'project_id' => $project_id,
        'project_name' => $project_title,
        'project_status' => $project_status,
        'amount' => $project_amount,
        'remainning_amount' => $remaining_amount,
        'epaid' => $epaid,
        'epanding' => $epending,
        'total_ea' => $total_escrow,
        'totalitem' => $total_item
    ));
    exit;
}
error(__("Page Not Found"), __LINE__, __FILE__, 1);
exit();