<?php

if (checkloggedin()) {
    update_lastactive();
    $ses_userdata = get_user_data($_SESSION['user']['username']);
    $author_name = $ses_userdata['name'];
    $author_image = $ses_userdata['image'];
    $username_error = $email_error = $password_error = $type_error = $avatar_error = '';
    $avatarName = null;
    $errors = 0;
    if (isset($_POST['submit_type'])) {
        $errors = 0;
        if (empty($_POST["user-type"])) {
            $errors++;
            $type_error = "<span class='status-not-available'> " . __("Please select a user type.") . "</span>";
        } else {
            if (!in_array($_POST["user-type"], array(1, 2))) {
                $errors++;
                $type_error = "<span class='status-not-available'> " . __("Invalid user type.") . "</span>";
            }
        }

        if ($errors == 0) {
            $now = date("Y-m-d H:i:s");
            $user_update = ORM::for_table($config['db']['pre'] . 'user')->find_one($_SESSION['user']['id']);
            if($_POST["user-type"] == 1){
                $user_update->user_type = 'user';
            }else{
                $user_update->user_type = 'employer';
            }
            $user_update->set('updated_at', $now);
            $user_update->save();

            $loggedin = get_user_data("", $_SESSION['user']['id']);
            create_user_session($loggedin['id'], $loggedin['username'], $loggedin['password'], $loggedin['user_type']);

            transfer($link['DASHBOARD'], __("Profile Updated Successfully"), __("Profile Updated Successfully"));
            exit;
        }
    }

    $author_lastactive = date('d M Y H:i', strtotime($ses_userdata['lastactive']));

    $country_code = !empty($ses_userdata['country_code']) ? $ses_userdata['country_code'] : check_user_country();
    $currency_info = set_user_currency($country_code);
    $currency_sign = $currency_info['html_entity'];

    $win_project = 0;
    $posted_project = 0;
    $posted_jobs = 0;
    $completed_projects = 0;
    if($_SESSION['user']['user_type'] == 'user'){
        $win_project = ORM::for_table($config['db']['pre'].'project')
            ->where('freelancer_id' , $_SESSION['user']['id'])
            ->count();
        $completed_projects = ORM::for_table($config['db']['pre'].'project')
            ->where(array(
                'freelancer_id' => $_SESSION['user']['id'],
                'status'=> 'completed'
            ))
            ->count();
        $review_count = ORM::for_table($config['db']['pre'].'reviews')
            ->where(array(
                'freelancer_id' => $_SESSION['user']['id'],
                'rated_by'=> 'employer'
            ))
            ->count();
    }else{
        $posted_project = ORM::for_table($config['db']['pre'].'project')
            ->where('user_id' , $_SESSION['user']['id'])
            ->count();
        $posted_jobs = ORM::for_table($config['db']['pre'].'product')
            ->where('user_id' , $_SESSION['user']['id'])
            ->count();
        $review_count = ORM::for_table($config['db']['pre'].'reviews')
            ->where(array(
                'employer_id' => $_SESSION['user']['id'],
                'rated_by'=> 'user'
            ))
            ->count();
    }

    $page = 1;
    $limit = 10;
    $total_item = ORM::for_table($config['db']['pre'].'push_notification')
        ->where('owner_id',$_SESSION['user']['id'])
        ->orderByDesc('id')
        ->count();

    $notification = array();
    $rows = ORM::for_table($config['db']['pre'].'push_notification')
        ->where('owner_id',$_SESSION['user']['id'])
        ->orderByDesc('id')
        ->limit($limit)
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
    if (check_user_upgrades($_SESSION['user']['id'])) {
        $sub_info = get_user_membership_detail($_SESSION['user']['id']);
        $sub_title = $sub_info['sub_title'];
        $sub_image = $sub_info['sub_image'];
    } else {
        $sub_title = '';
        $sub_image = '';

    }
    //Print Template 'Home/index Page'
    HtmlTemplate::display('dashboard', array(
        'sub_title' => $sub_title,
        'sub_image' => $sub_image,
        'type_error' => $type_error,
        'notification' => $notification,
        'lastactive' => $author_lastactive,
        'win_project' => $win_project,
        'completed_projects' => $completed_projects,
        'review_count' => $review_count,
        'posted_project' => $posted_project,
        'posted_jobs' => $posted_jobs,
        'balance' => $ses_userdata['balance'],
        'notify' => $ses_userdata['notify'],
        'currency_sign' => $currency_sign,
        'authorname' => $author_name,
        'avatar' => !empty($ses_userdata['image']) ? 'small_' . $ses_userdata['image'] : 'small_default_user.png'
    ));
} else {
    headerRedirect($link['LOGIN']);
}
?>
