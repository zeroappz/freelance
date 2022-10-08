<?php
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");
require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

sec_session_start();

// Check if this is an Name availability check from signup page using ajax
if(isset($_POST["name"])) {
    if(empty($_POST["name"])) {
        $name_error = __("Enter your full name.");
        echo "<span class='status-not-available'> ".$name_error."</span>";
        exit;
    }

    $name_length = strlen(utf8_decode($_POST['name']));
    if( ($name_length < 4) OR ($name_length > 21) )
    {
        $name_error = __("Name must be between 4 and 20 characters long.");
        echo "<span class='status-not-available'> ".$name_error.".</span>";
        exit;
    }
    else{
        echo "<span class='status-available'>".__("Success")."</span>";
        exit;
    }

    /*if(preg_match('/[^A-Za-z\s]/',$_POST['name']))
    {
        $name_error = $lang['ONLY_LETTER_SPACE'];
        echo "<span class='status-not-available'> ".$name_error." [A-Z,a-z,0-9]</span>";
        exit;
    }*/
}

// Check if this is an Username availability check from signup page using ajax
if(isset($_POST["username"])) {

    if(empty($_POST["username"])) {
        $username_error = __("Please enter an username");
        echo "<span class='status-not-available'> ".$username_error."</span>";
        exit;
    }

    if(preg_match('/[^A-Za-z0-9]/',$_POST['username']))
    {
        $username_error = __("Username may only contain alphanumeric characters");
        echo "<span class='status-not-available'> ".$username_error." [A-Z,a-z,0-9]</span>";
        exit;
    }
    elseif( (strlen($_POST['username']) < 4) OR (strlen($_POST['username']) > 16) )
    {
        $username_error = __("Username must be between 4 and 15 characters long");
        echo "<span class='status-not-available'> ".$username_error.".</span>";
        exit;
    }
    else
    {
        if(checkloggedin())
        {
            if($_POST["username"] != $_SESSION['user']['username'])
            {
                 $user_count = check_username_exists($_POST["username"]);
                if($user_count>0) {
                    $username_error = __("Username not available");
                    echo "<span class='status-not-available'>".$username_error."</span>";
                }
                else {
                    $username_error = __("Username available");
                    echo "<span class='status-available'>".$username_error."</span>";
                }
                exit;
            }
            else{
                echo "<span class='status-available'>".__("Success")."</span>";
                exit;
            }
        }
        else{
            $user_count = check_username_exists($_POST["username"]);
            if($user_count>0) {
                $username_error = __("Username not available");
                echo "<span class='status-not-available'>".$username_error."</span>";
            }
            else {
                $username_error = __("Username available");
                echo "<span class='status-available'>".$username_error."</span>";
            }
            exit;
        }

    }

}

// Check if this is an Email availability check from signup page using ajax
if(isset($_POST["email"])) {
    $_POST['email'] = strtolower($_POST['email']);

    $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

    if(empty($_POST["email"])) {
        $email_error = __("Please enter an email address");
        echo "<span class='status-not-available'> ".$email_error."</span>";
        exit;
    }
    elseif(!preg_match($regex, $_POST['email']))
    {
        $email_error = __("This is not a valid email address");
        echo "<span class='status-not-available'> ".$email_error.".</span>";
        exit;
    }

    if(checkloggedin())
    {
        $ses_userdata = get_user_data($_SESSION['user']['username']);
        if($_POST["email"] != $ses_userdata['email'])
        {
            $user_count = check_account_exists($_POST["email"]);
            if($user_count>0) {
                $email_error = __("An account already exists with that e-mail address");
                echo "<span class='status-not-available'>".$email_error."</span>";
            }
            else {
                $email_error = __("Email address is Available");
                echo "<span class='status-available'>".$email_error."</span>";
            }
            exit;
        }else{
            echo "<span class='status-available'>".__("Success")."</span>";
            exit;
        }
    }
    else{
        $user_count = check_account_exists($_POST["email"]);
        if($user_count>0) {
            $email_error = __("An account already exists with that e-mail address");
            echo "<span class='status-not-available'>".$email_error."</span>";
        }
        else {
            $email_error = __("Email address is Available");
            echo "<span class='status-available'>".$email_error."</span>";
        }
        exit;
    }
}

// Check if this is an Password availability check from signup page using ajax
if(isset($_POST["password"])) {

    if(empty($_POST["password"])) {
        $password_error = __("Please enter password");
        echo "<span class='status-not-available'> ".$password_error."</span>";
        exit;
    }
    elseif( (strlen($_POST['password']) < 5) OR (strlen($_POST['password']) > 21) )
    {
        $password_error = __("Password must be between 4 and 20 characters long");
        echo "<span class='status-not-available'> ".__("Password must be between 4 and 20 characters long").".</span>";
        exit;
    }
    else{
        echo "<span class='status-available'>".__("Success")."</span>";
        exit;
    }

}

?>