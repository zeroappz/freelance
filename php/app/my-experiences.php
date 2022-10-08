<?php
if(checkloggedin())
{
    update_lastactive();
    $ses_userdata = get_user_data($_SESSION['user']['username']);
    if($ses_userdata['user_type'] != 'user'){
        headerRedirect($link['DASHBOARD']);
    }


    $result = ORM::for_table($config['db']['pre'].'experiences')
        ->where('user_id' , $_SESSION['user']['id'])->find_many();

    $items = array();
    if ($result) {
        foreach ($result as $info)
        {
            $items[$info['id']]['id'] = $info['id'];
            $items[$info['id']]['title'] = $info['title'];
            $items[$info['id']]['company'] = $info['company'];
            $items[$info['id']]['description'] = $info['description'];
            $items[$info['id']]['start_date'] = date('d M, Y', strtotime($info['start_date']));
            $items[$info['id']]['end_date'] = $info['currently_working']?__("Currently Working"):date('d M, Y', strtotime($info['end_date']));
            $items[$info['id']]['city'] = $info['city'];
        }
    }

    //Print Template
    HtmlTemplate::display('my-experiences', array(
        'items' => $items,
        'totalitem' => count($result)
    ));
    exit;
}else{
    headerRedirect($link['LOGIN']);
}