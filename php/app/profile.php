<?php
if(!isset($_GET['page']))
    $page = 1;
else
    $page = $_GET['page'];

$limit = 10;

if(isset($_GET['username'])){
    $get_userdata = get_user_data($_GET['username']);
    if(is_array($get_userdata)){
        $user_id = $get_userdata['id'];
        $user_status = $get_userdata['status'];
        update_profileview($user_id);
        $user_type = $get_userdata['user_type'];
        $user_view = thousandsCurrencyFormat($get_userdata['view']);
        $username = $get_userdata['username'];
        $user_name = $get_userdata['name'];
        $user_tagline = $get_userdata['tagline'];
        $user_about = nl2br(stripcslashes($get_userdata['description']));
        $user_sex = $get_userdata['sex'];
        $user_country = $get_userdata['country'];
        $user_city = $get_userdata['city'];
        $user_address = nl2br(stripcslashes($get_userdata['address']));
        $user_website = $get_userdata['website'];
        $user_image = !empty($get_userdata['image'])?$get_userdata['image']:'default_user.png';
        $created = date('d M Y', strtotime($get_userdata['created_at']));
        $lastactive = date('d M Y', strtotime($get_userdata['lastactive']));
        $user_rating = averageRating($user_id,$get_userdata['user_type']);
        $user_category = $user_subcategory = null;
        if(!empty($get_userdata['category'])){
            $get_cat = get_maincat_by_id($get_userdata['category']);
            $user_category = $get_cat['cat_name'];
        }
        if(!empty($get_userdata['subcategory'])){
            $get_cat = get_subcat_by_id($get_userdata['subcategory']);
            $user_subcategory = $get_cat['sub_cat_name'];
        }

        $user_country_code = $get_userdata['country_code'];
        $user_salary_min = price_format($get_userdata['salary_min'], $user_country_code);
        $user_salary_max = price_format($get_userdata['salary_max'], $user_country_code);

        $user_age = null;
        if(!empty($get_userdata['dob'])){
            $user_age = date_diff(date_create($get_userdata['dob']), date_create('today'))->y;
        }

        $item_title = $user_name;
        $item_link = $link['PROFILE'] . '/' . $username;

        if($config['contact_validation'] == '1'){
            $user_email = (checkloggedin()) ? $get_userdata['email'] : "Login to see";
            $user_phone = (checkloggedin()) ? $get_userdata['phone'] : "Login to see";
        }else{
            $user_email = $get_userdata['email'];
            $user_phone = $get_userdata['phone'];
        }

        $hide_contact = 0;
        if($config['contact_validation'] == '1'){
            if(!checkloggedin()){
                $hide_contact = 1;
            }
        }
        $state_name = '';
        if(!empty($get_userdata['city_code'])) {
            $city_detail = get_cityDetail_by_id($get_userdata['city_code']);
            $user_city = $city_detail['asciiname'];
            $state_name = ', '.get_stateName_by_id($city_detail['subadmin1_code']);
        }
        $win_project = $rehired_count = $on_budget_percentage = $on_time_percentage = $recommendation_percentage = 0;
        $project_completed = 0;
        $total_projects = $open_projects = $completed_projects = $posted_jobs = 0;

        if($get_userdata['user_type'] == 'user'){
            $completed_projects = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'freelancer_id' => $user_id,
                    'status'=> 'completed'
                ))
                ->count();
            $project_completed = ($completed_projects)? 100 : 0; // In percentage

            $win_project = ORM::for_table($config['db']['pre'].'project')
                ->where('freelancer_id' , $user_id)
                ->count();

            $rehired = ORM::for_table($config['db']['pre'].'project')
                ->select_many_expr('user_id, COUNT(user_id) as hired')
                ->where('freelancer_id' , $user_id)
                ->group_by('user_id')
                ->having_raw('COUNT(user_id) > 1')
                ->find_many();

            $i = 0;
            foreach($rehired as $info){
                $i+=$info['hired']-1;
            }
            $rehired_count = $i;
            $review_count = ORM::for_table($config['db']['pre'].'reviews')
                ->where(array(
                    'freelancer_id' => $user_id,
                    'rated_by'=> 'employer'
                ))
                ->count();
            if($review_count){
                $on_budget_count = ORM::for_table($config['db']['pre'].'project')
                    ->where(array(
                        'freelancer_id' => $user_id,
                        'on_budget'=> 'yes'
                    ))
                    ->count();
                $on_budget_percentage = ($on_budget_count/$review_count)*100;

                $on_time_count = ORM::for_table($config['db']['pre'].'project')
                    ->where(array(
                        'freelancer_id' => $user_id,
                        'on_time'=> 'yes'
                    ))
                    ->count();
                $on_time_percentage = ($on_time_count/$review_count)*100;

                $recommendation_count = ORM::for_table($config['db']['pre'].'project')
                    ->where(array(
                        'freelancer_id' => $user_id,
                        'recommendation'=> 'yes'
                    ))
                    ->count();
                $recommendation_percentage = ($recommendation_count/$review_count)*100;
            }
        }else{
            $total_projects = ORM::for_table($config['db']['pre'].'project')
                ->where('user_id' , $user_id)
                ->count();
            $open_projects = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'user_id' => $user_id,
                    'status'=> 'open'
                ))
                ->count();
            $completed_projects = ORM::for_table($config['db']['pre'].'project')
                ->where(array(
                    'user_id' => $user_id,
                    'status'=> 'completed'
                ))
                ->count();

            $posted_jobs = ORM::for_table($config['db']['pre'].'product')
                ->where('user_id' , $user_id)
                ->count();
        }
        $items = array();
        $total = 0;
        $pagging = array();
        if($get_userdata['user_type'] == 'employer'){
            $results = ORM::for_table($config['db']['pre'].'product')
                ->where('user_id',$user_id)
                ->where('status','active')
                ->where('hide','0')
                ->limit($limit)
                ->offset(($page-1)*$limit)
                ->find_many();
            $items = array();
            foreach($results as $info){
                $items[$info['id']]['id'] = $info['id'];
                $items[$info['id']]['name'] = $info['product_name'];
                $items[$info['id']]['product_type'] = get_productType_title_by_id($info['product_type']);
                $items[$info['id']]['salary_type'] = get_salaryType_title_by_id($info['salary_type']);
                $items[$info['id']]['featured'] = $info['featured'];
                $items[$info['id']]['urgent'] = $info['urgent'];
                $items[$info['id']]['highlight'] = $info['highlight'];

                $salary_min = price_format($info['salary_min'],$info['country']);
                $items[$info['id']]['salary_min'] = $salary_min;
                $salary_max = price_format($info['salary_max'],$info['country']);
                $items[$info['id']]['salary_max'] = $salary_max;

                $cityname = get_cityName_by_id($info['city']);
                $items[$info['id']]['city'] = $cityname;
                $items[$info['id']]['created_at'] = timeAgo($info['created_at']);
                $pro_url = create_slug($info['product_name']);
                $items[$info['id']]['link'] = $link['POST-DETAIL'].'/' . $info['id'] . '/'.$pro_url;
            }
            $total = ORM::for_table($config['db']['pre'].'product')
                ->where('user_id',$user_id)
                ->where('status','active')
                ->where('hide','0')
                ->count();
            $pagging = pagenav($total,$page,$limit,$link['PROFILE'].'/'.$_GET['username']);
        }

        $experiences = array();
        if($get_userdata['user_type'] == 'user'){
            $result = ORM::for_table($config['db']['pre'].'experiences')
                ->where('user_id' , $user_id)->find_many();
            foreach ($result as $info)
            {
                $experiences[$info['id']]['id'] = $info['id'];
                $experiences[$info['id']]['title'] = $info['title'];
                $experiences[$info['id']]['company'] = $info['company'];
                $experiences[$info['id']]['description'] = $info['description'];
                $experiences[$info['id']]['start_date'] = date('d M, Y', strtotime($info['start_date']));
                $experiences[$info['id']]['end_date'] = $info['currently_working']?__("Currently Working"):date('d M, Y', strtotime($info['end_date']));
                $experiences[$info['id']]['city'] = $info['city'];
            }
        }
        $hourly_rate = price_format(get_user_option($user_id,'hourly_rate','0'));
        $hourly_rate = ($hourly_rate)? $hourly_rate : '-';

        $skills_data = get_user_option($user_id,'skills');
        if ($skills_data != "") {
            $skills_ex= explode(',', $skills_data);
            $skills = array();
            foreach ($skills_ex as $skill_id) {
                //REMOVE SPACE FROM $VALUE ----
                $info_sub = get_subcat_by_id($skill_id);
                $info_main = get_maincat_by_id($info_sub['main_cat_id']);
                $skill_link = $config['site_url'].'projects/'.$info_main['slug'].'/'.$info_sub['slug'];
                $skillTrim = preg_replace("/[\s_]/", "-", trim($skill_id));
                $skills[] = '<a href="' . $skill_link. '"><span>' . $info_sub['sub_cat_name'] . '</span></a>';
            }
            $skills = implode('  ', $skills);
            $show_skills = 1;
        } else {
            $skills = "";
            $show_skills = 0;
        }
        $pagetitle = $user_name." ".__("Profile");
        //Print Template
        HtmlTemplate::display('profile', array(
            'pagetitle' => $pagetitle,
            'profilevisit' => $user_view,
            'item_title' => $item_title,
            'item_link' => $item_link,
            'fullname' => $user_name,
            'userid' => $user_id,
            'profileusername' => $username,
            'userstatus' => $user_status,
            'profile_usertype' => $user_type,
            'email' => $user_email,
            'category' => $user_category,
            'subcategory' => $user_subcategory,
            'salary_min' => $user_salary_min,
            'salary_max' => $user_salary_max,
            'age' => $user_age,
            'user_country_code' => $user_country_code,
            'user_country' => $user_country,
            'cityname' => $user_city,
            'statename' => $state_name,
            'tagline' => $user_tagline,
            'about' => $user_about,
            'userimage' => $user_image,
            'average_rating' => $user_rating,
            'user_type' => $get_userdata['user_type'],
            'phone' => $user_phone,
            'gender' => $user_sex,
            'address' => $user_address,
            'website' => $user_website,
            'facebook' => $get_userdata['facebook'],
            'twitter' => $get_userdata['twitter'],
            'instagram' => $get_userdata['instagram'],
            'linkedin' => $get_userdata['linkedin'],
            'youtube' => $get_userdata['youtube'],
            'created' => $created,
            'hide_contact' => $hide_contact,
            'lastactive' => $lastactive,
            'items' => $items,
            'experiences' => $experiences,
            'pages' => $pagging,
            'show_paging' => (int)($total > $limit),
            'totalitem' => $total,
            'total_experiences' => count($experiences),
            'user_favorite' => check_user_favorite($user_id),
            'win_project' => $win_project,
            'rehired' => $rehired_count,
            'project_completed' => $project_completed,
            'on_budget_percentage' => $on_budget_percentage,
            'on_time_percentage' => $on_time_percentage,
            'recommendation_percentage' => $recommendation_percentage,
            'total_projects' => $total_projects,
            'open_projects' => $open_projects,
            'completed_projects' => $completed_projects,
            'posted_jobs' => $posted_jobs,
            'hourly_rate' => $hourly_rate,
            'skills' => $skills
        ));
        exit;
    }
    else{
        error(__("Page Not Found"), __LINE__, __FILE__, 1);
        exit();
    }
}
else{
    error(__("Page Not Found"), __LINE__, __FILE__, 1);
    exit();
}
?>
