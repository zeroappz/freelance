<?php
if(isset($_GET['confirm']))
{
    $check_confirm = 0;

    $check_confirm = ORM::for_table($config['db']['pre'].'user')
        ->where(array(
            'id' => $_GET['user'],
            'confirm' => $_GET['confirm']
        ))
        ->count();

    if($check_confirm)
    {
        $pdo = ORM::get_db();
        $query = "UPDATE `".$config['db']['pre']."user` SET `status` = '1', `confirm` = '' WHERE id='".mysqli_real_escape_string($mysqli,$_GET['user'])."' AND confirm='".mysqli_real_escape_string($mysqli,$_GET['confirm'])."' LIMIT 1 ";

        $pdo->query($query);


        $user_info = get_user_data(null,$_GET['user']);
        $user_email = $user_info['email'];


        message(__("Success"),__("Thanks for signing up"), 'login');
    }
    else
    {
        message(__("Error"),$lang['CONFUSED'],'',false);
    }

    exit;
}

if(checkloggedin())
{
    header("Location: ".$config['site_url']."dashboard");
    exit;
}
// Check if this is an Name availability check from signup page using ajax

$name_field = '';
$username_field = '';
$email_field = '';
$type_error = '';
$name_error = '';
$username_error = '';
$email_error = '';
$password_error = '';
$recaptcha_error = '';

if(isset($_POST["submit"])) {
    $errors = 0;
    $name_field = $_POST['name'];
    $username_field = $_POST['username'];
    $email_field = $_POST['email'];
    $name_length = strlen(utf8_decode($_POST['name']));

    if(empty($_POST["user-type"])) {
        $errors++;
        $type_error = "<span class='status-not-available'> ".__("Please select a user type.")."</span>";
    }else{
        if(!in_array($_POST["user-type"], array(1,2))){
            $errors++;
            $type_error = "<span class='status-not-available'> ".__("Invalid user type.")."</span>";
        }
    }

    if(empty($_POST["name"])) {
        $errors++;
        $name_error = __("Enter your full name.");
        $name_error = "<span class='status-not-available'> ".$name_error."</span>";
    }
    elseif( ($name_length < 4) OR ($name_length > 21) )
    {
        $errors++;
        $name_error = __("Name must be between 4 and 20 characters long.");
        $name_error = "<span class='status-not-available'> ".$name_error.".</span>";
    }
    /*elseif(preg_match('/[^A-Za-z\s]/',$_POST['name']))
    {
        $errors++;
        $name_error = $lang['ONLY_LETTER_SPACE'];
        $name_error = "<span class='status-not-available'> ".$name_error." [A-Z,a-z,0-9]</span>";
    }*/


    // Check if this is an Username availability check from signup page using ajax
    if(empty($_POST["username"]))
    {
        $errors++;
        $username_error = __("Please enter an username");
        $username_error = "<span class='status-not-available'> ".$username_error."</span>";
    }
    elseif(preg_match('/[^A-Za-z0-9]/',$_POST['username']))
    {
        $errors++;
        $username_error = __("Username may only contain alphanumeric characters");
        $username_error = "<span class='status-not-available'> ".$username_error." [A-Z,a-z,0-9]</span>";
    }
    elseif( (strlen($_POST['username']) < 4) OR (strlen($_POST['username']) > 16) )
    {
        $errors++;
        $username_error = __("Username must be between 4 and 15 characters long");
        $username_error = "<span class='status-not-available'> ".$username_error.".</span>";
    }
    else{
        $user_count = check_username_exists($_POST["username"]);
        if($user_count>0) {
            $errors++;
            $username_error = __("Username not available");
            $username_error = "<span class='status-not-available'>".$username_error."</span>";
        }
        else {
            $username_error = __("Username available");
            $username_error = "<span class='status-available'>".$username_error."</span>";
        }
    }


    // Check if this is an Email availability check from signup page using ajax
    $_POST["email"] = strtolower($_POST["email"]);
    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(empty($_POST["email"])) {
        $errors++;
        $email_error = __("Please enter an email address");
        $email_error = "<span class='status-not-available'> ".$email_error."</span>";
    }
    elseif(!preg_match($regex, $_POST['email'])) {
        $errors++;
        $email_error = __("This is not a valid email address");
        $email_error = "<span class='status-not-available'> ".$email_error.".</span>";
    }
    else{
        $user_count = check_account_exists($_POST["email"]);
        if($user_count>0) {
            $errors++;
            $email_error = __("An account already exists with that e-mail address");
            $email_error = "<span class='status-not-available'>".$email_error."</span>";
        }
    }


    // Check if this is an Password availability check from signup page using ajax
    if(empty($_POST["password"])) {
        $errors++;
        $password_error = __("Please enter password");
        $password_error = "<span class='status-not-available'> ".$password_error."</span>";
    }
    elseif( (strlen($_POST['password']) < 4) OR (strlen($_POST['password']) > 21) )
    {
        $errors++;
        $password_error = __("Password must be between 4 and 20 characters long");
        $password_error = "<span class='status-not-available'> ".$password_error.".</span>";
    }
    if($config['recaptcha_mode'] == 1) {
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            //your site secret key
            $secret = $config['recaptcha_private_key'];
            //get verify response data
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);
            $responseData = json_decode($verifyResponse);
            if (!$responseData->success) {
                $errors++;
                $recaptcha_error = __("reCAPTCHA verification failed, please try again.");
                $recaptcha_error = "<span class='status-not-available'> " . $recaptcha_error . ".</span>";
            }
        } else {
            $errors++;
            $recaptcha_error = __("Please click on the reCAPTCHA box.");
            $recaptcha_error = "<span class='status-not-available'> " . $recaptcha_error . ".</span>";
        }
    }

    if($errors == 0) {

        $confirm_id = get_random_id();
        $location = getLocationInfoByIp();
        $password = $_POST["password"];
        $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
        $now = date("Y-m-d H:i:s");

        $insert_user = ORM::for_table($config['db']['pre'].'user')->create();
        $insert_user->status = '0';
        $insert_user->name = $_POST["name"];
        if($_POST["user-type"] == 1){
            $insert_user->user_type = 'user';
        }else{
            $insert_user->user_type = 'employer';
        }
        $insert_user->username = $_POST["username"];
        $insert_user->password_hash = $pass_hash;
        $insert_user->email = $_POST['email'];
        $insert_user->confirm = $confirm_id;
        $insert_user->created_at = $now;
        $insert_user->updated_at = $now;
        $insert_user->country = $location['country'];
        $insert_user->country_code = $location['countryCode'];
        $insert_user->city = $location['city'];
        $insert_user->save();

        $user_id = $insert_user->id();

        /*SEND CONFIRMATION EMAIL*/
        email_template("signup_confirm",$user_id);

        /*SEND ACCOUNT DETAILS EMAIL*/
        email_template("signup_details",$user_id,$password);

        $loggedin = userlogin($_POST['username'], $_POST['password']);

        create_user_session($loggedin['id'],$loggedin['username'],$loggedin['password'], $loggedin['user_type']);

        message(__("Welcome"),__("Welcome to our site. Get experience to post free job jobs. Thanks"),'dashboard',false);
        exit;
    }
}

//Print Template
HtmlTemplate::display('global/signup', array(
    'name_field' => $name_field,
    'username_field' => $username_field,
    'email_field' => $email_field,
    'type_error' => $type_error,
    'name_error' => $name_error,
    'username_error' => $username_error,
    'email_error' => $email_error,
    'password_error' => $password_error,
    'recaptcha_error' => $recaptcha_error
));
exit;
