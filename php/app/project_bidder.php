<?php
if(checkloggedin()) {
    update_lastactive();

    $ses_userdata = get_user_data($_SESSION['user']['username']);
    if($ses_userdata['user_type'] != 'employer'){
        headerRedirect($link['DASHBOARD']);
    }
    if (!isset($_GET['id'])) {
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit;
    }

    $product = ORM::for_table($config['db']['pre'] . 'project')
        ->select_many('id','user_id','status','product_name')
        ->where('id', $_GET['id'])
        ->where('user_id', $_SESSION['user']['id'])
        ->findOne();
    if (!empty($product)) {
        if (!isset($_GET['page']))
            $_GET['page'] = 1;

        $freelancer_id_orm = ORM::for_table($config['db']['pre'].'project')
            ->select('freelancer_id')
            ->where('id' , $_GET['id'])
            ->find_one();

        if(isset($freelancer_id_orm['freelancer_id'])){
            $freelancer_id = $freelancer_id_orm['freelancer_id'];
        }else{
            $freelancer_id = 0;
        }

        /*Freelancers Bidding*/
        $total_item = ORM::for_table($config['db']['pre'] . 'bids')
            ->table_alias('ua')
            ->where(array(
                'u.status' => '1',
                'u.user_type' => 'user',
                'ua.project_id' => $_GET['id']
            ))
            ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
            ->count();

        $result = ORM::for_table($config['db']['pre'] . 'bids')
            ->table_alias('ua')
            ->select_many('ua.*', 'u.username', 'u.name', 'u.image','u.country_code','u.online')
            ->where(array(
                'u.status' => '1',
                'u.user_type' => 'user',
                'ua.project_id' => $_GET['id']
            ))
            ->join($config['db']['pre'] . 'user', array('ua.user_id', '=', 'u.id'), 'u')
            ->order_by_expr("(CASE
                WHEN u.id = '$freelancer_id' THEN 1
                ELSE 2
              END), ua.id DESC")
            ->find_many();

        $bid = array();
        foreach($result as $info){
            $bid[$info['id']]['id'] = $info['id'];
            $bid[$info['id']]['user_id'] = $info['user_id'];
            $bid[$info['id']]['freelancer_id'] = $freelancer_id;
            $bid[$info['id']]['username'] = $info['username'];
            $bid[$info['id']]['name'] = !empty($info['name'])?$info['name']:$info['username'];
            $bid_description = nl2br(stripcslashes($info['message']));
            $bid[$info['id']]['description'] = $bid_description;
            $bid[$info['id']]['showmore'] = (strlen($bid_description) > 500)? 1 : 0;
            $bid[$info['id']]['amount'] = price_format($info['amount']);
            $bid[$info['id']]['days'] = $info['days'];
            $bid[$info['id']]['image'] = !empty($info['image'])?$info['image']:'default_user.png';
            $bid[$info['id']]['online'] = $info['online'];
            if ($info['online'] == 1) {
                $bid[$info['id']]['online']  = "online";
            } else {
                $bid[$info['id']]['online']  = "offline";
            }
            $bid[$info['id']]['rating'] = averageRating($info['user_id'],'user');

            $country_code = $info['country_code'];
            $bid[$info['id']]['country_code'] = strtolower($country_code);
            $bid[$info['id']]['country'] = get_countryName_by_code($country_code);

            $postid = base64_url_encode($product['id']);
            $qcuserid = base64_url_encode($info['user_id']);
            $quickchat_url = $link['MESSAGE']."/?postid=$postid&userid=$qcuserid";

            $bid[$info['id']]['quickchat_url'] = $quickchat_url;
        }
        /*Freelancers Bidding*/


        $pro_url = create_slug($product['product_name']);
        $project_link = $link['PROJECT'] . '/' . $product['id'] . '/' . $pro_url;
        //Print Template
        HtmlTemplate::display('project_bidder', array(
            'bids' => $bid,
            'project_id' => $product['id'],
            'project_status' => $product['status'],
            'project_name' => $product['product_name'],
            'project_link' => $project_link,
            'totalitem' => $total_item
        ));
        exit;
    }
}
error(__("Page Not Found"), __LINE__, __FILE__, 1);
exit();