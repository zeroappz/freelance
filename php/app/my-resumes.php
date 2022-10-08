<?php
// if resume is disable
if(!$config['resume_enable']){
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
}

if(checkloggedin())
{
	update_lastactive();
	$ses_userdata = get_user_data($_SESSION['user']['username']);
	if($ses_userdata['user_type'] != 'user'){
		headerRedirect($link['DASHBOARD']);
	}

	$keywords = '';

	$orm = ORM::for_table($config['db']['pre'].'resumes')
        ->where('user_id' , $_SESSION['user']['id'])
        ->where('active' , '1');

    if(!empty($_GET['keywords'])){
    	$keywords = $_GET['keywords'];
    	$orm->where_like('name','%'.$keywords.'%');
    }

    $result = $orm->find_many();
    $items = array();
    if ($result) {
        foreach ($result as $info)
        {
        	$items[$info['id']]['id'] = $info['id'];
        	$items[$info['id']]['name'] = $info['name'];
        	$items[$info['id']]['filename'] = $info['filename'];
        }
    }

    //Print Template
    HtmlTemplate::display('my-resumes', array(
        'items' => $items,
        'resumes' => resumes_count($_SESSION['user']['id']),
        'keywords' => $keywords
    ));
    exit;
}else{
	headerRedirect($link['LOGIN']);
}
?>
