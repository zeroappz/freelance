<?php

if(checkloggedin())
{
    update_lastactive();
    $ses_userdata = get_user_data($_SESSION['user']['username']);
    if($ses_userdata['user_type'] != 'user'){
        headerRedirect($link['DASHBOARD']);
    }
    $id = $title = $company = $description = $city = $start_date = $end_date = $currently_working = $error = '';
    global $match;
    if(isset($match['params']['id'])){
        $_GET['id'] = $match['params']['id'];

        $result = ORM::for_table($config['db']['pre'].'experiences')
            ->where('user_id' , $_SESSION['user']['id'])
            ->where('id' , $_GET['id'])
            ->find_one();

        $title = $result['title'];
        $company = $result['company'];
        $description = $result['description'];
        $city = $result['city'];
        $start_date = $result['start_date'];
        $end_date = $result['end_date'];
        $currently_working = $result['currently_working'];
        $id = $_GET['id'];
    }

    if(isset($_POST['submit'])){
        if(empty($_POST['title']) || empty($_POST['company']) || empty($_POST['description']) || empty($_POST['city']) || empty($_POST['start_date'])){
            $error = __("All fields are required.");
        }
        $start_date = date("Y-m-d", strtotime($_POST['start_date']));
        $end_date = null;
        if(!empty($_POST['end_date'])){
            $end_date = date("Y-m-d", strtotime($_POST['end_date']));
            if($end_date <= $start_date) {
                $error = __("Invalid end date.");
            }
        }else{
            $_POST['currently_working'] = '1';
        }

        if($error == '') {
            if (!empty($_POST['id'])) {
                $experience_create = ORM::for_table($config['db']['pre'].'experiences')
                    ->where('id',$_POST['id'])
                    ->where('user_id',$_SESSION['user']['id'])
                    ->find_one();

                $experience_create->set('title', validate_input($_POST['title']));
                $experience_create->set('company', validate_input($_POST['company']));
                $experience_create->set('description', validate_input($_POST['description']));
                $experience_create->set('city', validate_input($_POST['city']));
                $experience_create->set('start_date', $start_date);
                $experience_create->set('end_date', $end_date);
                $experience_create->set('currently_working', validate_input($_POST['currently_working']));
                $experience_create->save();
            } else {
                $experiences = ORM::for_table($config['db']['pre'].'experiences')->create();
                $experiences->user_id = $_SESSION['user']['id'];
                $experiences->title = validate_input($_POST['title']);
                $experiences->company = validate_input($_POST['company']);
                $experiences->description = validate_input($_POST['description']);
                $experiences->city = validate_input($_POST['city']);
                $experiences->start_date = $start_date;
                $experiences->end_date = $end_date;
                $experiences->currently_working = validate_input($_POST['currently_working']);
                $experiences->save();
            }

            transfer($link['EXPERIENCES'],__("Experience Updated."),__("Experience Updated."));
            exit;
        }
    }

    //Print Template
    HtmlTemplate::display('user-experience', array(
        'title' => $title,
        'company' => $company,
        'description' => $description,
        'city' => $city,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'currently_working' => $currently_working,
        'language_direction' => get_current_lang_direction(),
        'id' => $id,
        'error' => $error
    ));
    exit;
}else{
    headerRedirect($link['LOGIN']);
}