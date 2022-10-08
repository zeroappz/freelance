<?php
if(checkloggedin())
{
	update_lastactive();
	$ses_userdata = get_user_data($_SESSION['user']['username']);
	if($ses_userdata['user_type'] != 'user'){
		headerRedirect($link['DASHBOARD']);
	}

	$notify_cat = explode(',', $ses_userdata['notify_cat']);
	$category = get_maincategory($notify_cat,"checked");

	if(!isset($_POST['submit']))
    {
        //Print Template
        HtmlTemplate::display('job-alert', array(
            'categories' => $category,
            'notify' => $ses_userdata['notify']
        ));
        exit;
    }else{
    	$notify = isset($_POST['notify']) ? '1' : '0';

        if (isset($_POST['choice']) && is_array($_POST['choice'])) {
            $choice = validate_input(implode(',', $_POST['choice']));
        }else{
            $choice = '';
        }
        $now = date("Y-m-d H:i:s");
        $user_update = ORM::for_table($config['db']['pre'].'user')->find_one($_SESSION['user']['id']);
        $user_update->set('notify', $notify);
        $user_update->set('notify_cat', $choice);
        $user_update->set('updated_at', $now);
        $user_update->save();

        ORM::for_table($config['db']['pre'].'notification')
                ->where_equal('user_id', $_SESSION['user']['id'])
                ->delete_many();

        if($notify)
        {
            if(isset($_POST['choice']))
            {
                foreach ($_POST['choice'] as $key=>$value)
                {
                    $notification = ORM::for_table($config['db']['pre'].'notification')->create();
                    $notification->user_id = $_SESSION['user']['id'];
                    $notification->cat_id = $key;
                    $notification->user_email = $ses_userdata['email'];
                    $notification->save();
                }
            }
        }
        transfer($link['JOBALERT'],__("Profile Updated Successfully"),__("Profile Updated Successfully"));
    }

}else{
	headerRedirect($link['LOGIN']);
}
?>
