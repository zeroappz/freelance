<?php
/*
Copyright (c) 2020 Bylancer.com
*/
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

global $config, $lang, $link;
admin_session_start();
checkloggedadmin();

if (!isset($_SESSION['admin']['id'])) {
    exit('Access Denied.');
}

//SidePanel Ajax Function
if(isset($_GET['action'])){
    if(!check_allow()){
        $status = "Sorry:";
        $message = __("permission denied for demo.");
        echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    }

    if ($_GET['action'] == "addAdmin") { addAdmin(); }
    if ($_GET['action'] == "editAdmin") { editAdmin(); }
    if ($_GET['action'] == "addUser") { addUser(); }
    if ($_GET['action'] == "editUser") { editUser(); }

    if ($_GET['action'] == "addCountry") { addCountry(); }
    if ($_GET['action'] == "editCountry") { editCountry(); }
    if ($_GET['action'] == "addState") { addState(); }
    if ($_GET['action'] == "editState") { editState(); }
    if ($_GET['action'] == "addDistrict") { addDistrict(); }
    if ($_GET['action'] == "editDistrict") { editDistrict(); }
    if ($_GET['action'] == "addCity") { addCity(); }
    if ($_GET['action'] == "editCity") { editCity(); }

    if ($_GET['action'] == "addCurrency") { addCurrency(); }
    if ($_GET['action'] == "editCurrency") { editCurrency(); }
    if ($_GET['action'] == "addTimezone") { addTimezone(); }
    if ($_GET['action'] == "editTimezone") { editTimezone(); }
    if ($_GET['action'] == "addLanguage") { addLanguage(); }
    if ($_GET['action'] == "editLanguage") { editLanguage(); }

    if ($_GET['action'] == "addMembershipPlan") { addMembershipPlan(); }
    if ($_GET['action'] == "editMembershipPlan") { editMembershipPlan(); }
    if ($_GET['action'] == "addMembershipPackage") { addMembershipPackage(); }
    if ($_GET['action'] == "editMembershipPackage") { editMembershipPackage(); }
    if ($_GET['action'] == "addTax") { addTax(); }
    if ($_GET['action'] == "editTax") { editTax(); }

    if ($_GET['action'] == "addStaticPage") { addStaticPage(); }
    if ($_GET['action'] == "editStaticPage") { editStaticPage(); }
    if ($_GET['action'] == "addFAQentry") { addFAQentry(); }
    if ($_GET['action'] == "editFAQentry") { editFAQentry(); }

    if ($_GET['action'] == "expirePostRenew") { expirePostRenew(); }
    if ($_GET['action'] == "postEdit") { postEdit(); }
    if ($_GET['action'] == "transactionEdit") { transactionEdit(); }
    if ($_GET['action'] == "withdrawEdit") { withdrawEdit(); }

    if ($_GET['action'] == "editAdvertise") { editAdvertise(); }
    if ($_GET['action'] == "paymentEdit") { paymentEdit(); }

    if ($_GET['action'] == "SaveSettings") { SaveSettings(); }
    if ($_GET['action'] == "saveEmailTemplate") { saveEmailTemplate(); }
    if ($_GET['action'] == "testEmailTemplate") { testEmailTemplate(); }

    if ($_GET['action'] == "companyEdit") { companyEdit(); }

    if ($_GET['action'] == "addTestimonial") { addTestimonial(); }
    if ($_GET['action'] == "editTestimonial") { editTestimonial(); }

}

function companyEdit(){
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Company Name Required.");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Company Description Required.");
        }

        if (!count($errors) > 0) {

            if($config['post_desc_editor'] == 1)
                $description = addslashes($_POST['content']);
            else
                $description = validate_input($_POST['content']);

            $now = date("Y-m-d H:i:s");

            $item_edit = ORM::for_table($config['db']['pre'].'companies')->find_one($_POST['id']);
            $item_edit->set('name', $_POST['title']);
            $item_edit->set('city', $_POST['city']);
            $item_edit->set('state', $_POST['state']);
            $item_edit->set('country', $_POST['country']);
            $item_edit->set('description', $description);
            $item_edit->set('phone', $_POST['phone']);
            $item_edit->set('fax', $_POST['fax']);
            $item_edit->set('email', $_POST['email']);
            $item_edit->set('website', $_POST['website']);
            $item_edit->set('facebook', $_POST['facebook']);
            $item_edit->set('twitter', $_POST['twitter']);
            $item_edit->set('linkedin', $_POST['linkedin']);
            $item_edit->set('pinterest', $_POST['pinterest']);
            $item_edit->set('youtube', $_POST['youtube']);
            $item_edit->set('instagram', $_POST['instagram']);
            $item_edit->set('updated_at', $now);
            $item_edit->save();

            $status = "success";
            $message = __("Saved Successfully");

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function addTestimonial(){
    global $lang,$config;

    $title = validate_input($_POST['name']);
    $designation = validate_input($_POST['designation']);
    $image = null;
    $description = validate_input($_POST['content']);
    $error = array();

    if(empty($title)){
        $error[] = "Name is required.";
    }
    if(empty($designation)){
        $error[] = "Designation is required.";
    }
    if(empty($description)){
        $error[] = "Content is required.";
    }

    if(empty($error)){
        if(!empty($_FILES['image'])){
            $file = $_FILES['image'];
            // Valid formats
            $valid_formats = array("jpeg", "jpg", "png");
            $filename = $file['name'];
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (!empty($filename)) {
                //File extension check
                if (in_array($ext, $valid_formats)) {
                    $main_path = "../storage/testimonials/";
                    $filename = uniqid(time()).'.'.$ext;
                    if(move_uploaded_file($file['tmp_name'], $main_path.$filename)){
                        $image = $filename;
                        resizeImage(100,$main_path.$filename,$main_path.$filename);
                    }else{
                        $error[] = __("Error: Please try again.");
                    }
                } else {
                    $error[] = __("Only allowed jpg, jpeg png");
                }
            }
        }
    }

    if (empty($error)) {
        $test = ORM::for_table($config['db']['pre'].'testimonials')->create();
        $test->name = $title;
        $test->designation = $designation;
        $test->image = $image;
        $test->content = $description;
        $test->save();

        $status = "success";
        $message = __("Saved Successfully");

        echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($error, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function editTestimonial(){
    global $lang,$config;

    $title = validate_input($_POST['name']);
    $designation = validate_input($_POST['designation']);
    $image = null;
    $description = validate_input($_POST['content']);
    $error = array();

    if(empty($title)){
        $error[] = "Name is required.";
    }
    if(empty($designation)){
        $error[] = "Designation is required.";
    }
    if(empty($description)){
        $error[] = "Content is required.";
    }

    if(empty($error)){
        if(!empty($_FILES['image'])){
            $file = $_FILES['image'];
            // Valid formats
            $valid_formats = array("jpeg", "jpg", "png");
            $filename = $file['name'];
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            if (!empty($filename)) {
                //File extension check
                if (in_array($ext, $valid_formats)) {
                    $main_path = "../storage/testimonials/";
                    $filename = uniqid(time()).'.'.$ext;
                    if(move_uploaded_file($file['tmp_name'], $main_path.$filename)){
                        $image = $filename;
                        resizeImage(100,$main_path.$filename,$main_path.$filename);

                        // remove old image
                        $info = ORM::for_table($config['db']['pre'].'testimonials')
                            ->select('image')
                            ->find_one($_POST['id']);

                        if($info['image'] != "default.png"){
                            if(file_exists($main_path.$info['image'])){
                                unlink($main_path.$info['image']);
                            }
                        }
                    }else{
                        $error[] = __("Error: Please try again.");
                    }
                } else {
                    $error[] = __("Only allowed jpg, jpeg png");
                }
            }
        }
    }

    if (empty($error)) {
        $test = ORM::for_table($config['db']['pre'].'testimonials')->find_one($_POST['id']);
        $test->name = $title;
        $test->designation = $designation;
        if($image){
            $test->image = $image;
        }
        $test->content = $description;
        $test->save();

        $status = "success";
        $message = __("Saved Successfully");

        echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
        die();
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }
    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($error, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function change_config_file_settings($filePath, $newSettings,$lang)
{
    // Update $fileSettings with any new values
    $fileSettings = array_merge($lang, $newSettings);
    // Build the new file as a string
    $newFileStr = "<?php\n";
    foreach ($fileSettings as $name => $val) {
        // Using var_export() allows you to set complex values such as arrays and also
        // ensures types will be correct
        $newFileStr .= "\$lang['$name'] = " . var_export($val, true) . ";\n";
    }
    // Closing tag intentionally omitted, you can add one if you want

    // Write it back to the file
    file_put_contents($filePath, $newFileStr);

}

function addAdmin(){
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $valid_formats = array("jpg","jpeg","png"); // Valid image formats

        if ($_FILES['file']['name'] != "") {

            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = '../storage/profile/';
                $original_filename = $_FILES['file']['name'];
                $random1 = rand(9999, 100000);
                $random2 = rand(9999, 200000);
                $random3 = $random1 . $random2;
                $extensions = explode(".", $original_filename);
                $extension = $extensions[count($extensions) - 1];
                $uniqueName = $random3 . "." . $extension;
                $uploadfile = $uploaddir . $uniqueName;

                $file_type = "file";

                if ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") {
                    $file_type = "image";

                    $size = filesize($_FILES['file']['tmp_name']);

                    $image = $_FILES["file"]["name"];
                    $uploadedfile = $_FILES['file']['tmp_name'];

                    if ($image) {
                        if ($extension == "jpg" || $extension == "jpeg") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefromjpeg($uploadedfile);
                        } else if ($extension == "png") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefrompng($uploadedfile);
                        } else {
                            $src = imagecreatefromgif($uploadedfile);
                        }

                        list($width, $height) = getimagesize($uploadedfile);

                        $newwidth = 225;
                        $newheight = 225;
                        $tmp = imagecreatetruecolor($newwidth, $newheight);

                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                        $filename = $uploaddir . "small" . $uniqueName;

                        imagejpeg($tmp, $filename, 100);

                        imagedestroy($src);
                        imagedestroy($tmp);
                    }


                }
                //else if it's not bigger then 0, then it's available '
                //and we send 1 to the ajax request
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                    $password = $_POST["password"];
                    $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                    $admins = ORM::for_table($config['db']['pre'].'admins')->create();
                    $admins->username = validate_input($_POST['username']);
                    $admins->password_hash = $pass_hash;
                    $admins->name = validate_input($_POST['name']);
                    $admins->email = validate_input($_POST['email']);
                    $admins->image = $uniqueName;
                    $admins->save();

                    if ($admins->id()) {
                        $status = "success";
                        $message = __("Saved Successfully");
                    } else{
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                }
            }
            else {
                $error = __("Only allowed jpg, jpeg png");
                $status = "error";
                $message = $error;
            }

        } else {
            $error = __("Profile Picture Required");
            $status = "error";
            $message = $error;
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editAdmin(){
    global $config,$lang;

    if (isset($_POST['id'])) {
        $password = $_POST["newPassword"];

        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "")
        {
            $valid_formats = array("jpg","jpeg","png"); // Valid image formats
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = '../storage/profile/';
                $original_filename = $_FILES['file']['name'];
                $random1 = rand(9999,100000);
                $random2 = rand(9999,200000);
                $random3 = $random1.$random2;
                $extensions = explode(".", $original_filename);
                $extension = $extensions[count($extensions) - 1];
                $uniqueName =  $random3 . "." . $extension;
                $uploadfile = $uploaddir . $uniqueName;

                $file_type = "file";

                if ($extension == "jpg" || $extension == "jpeg" || $extension == "gif" || $extension == "png") {
                    $file_type = "image";

                    $size = filesize($_FILES['file']['tmp_name']);

                    $image = $_FILES["file"]["name"];
                    $uploadedfile = $_FILES['file']['tmp_name'];

                    if ($image) {
                        if ($extension == "jpg" || $extension == "jpeg") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefromjpeg($uploadedfile);
                        } else if ($extension == "png") {
                            $uploadedfile = $_FILES['file']['tmp_name'];
                            $src = imagecreatefrompng($uploadedfile);
                        } else {
                            $src = imagecreatefromgif($uploadedfile);
                        }

                        list($width, $height) = getimagesize($uploadedfile);

                        $newwidth = 225;
                        $newheight = 225;
                        $tmp = imagecreatetruecolor($newwidth, $newheight);

                        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                        $filename = $uploaddir . "small" . $uniqueName;

                        imagejpeg($tmp, $filename, 100);

                        imagedestroy($src);
                        imagedestroy($tmp);
                    }


                }
                //else if it's not bigger then 0, then it's available '
                //and we send 1 to the ajax request
                if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

                    $info = ORM::for_table($config['db']['pre'].'admins')
                        ->select('image')
                        ->find_one(validate_input($_POST['id']));

                    if($info['image'] != "default_user.png"){
                        if(file_exists($uploaddir.$info['image'])){
                            unlink($uploaddir.$info['image']);
                            unlink($uploaddir."small".$info['image']);
                        }
                    }
                    if(!empty($password)){
                        $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                        $admins = ORM::for_table($config['db']['pre'].'admins')->find_one($_POST['id']);
                        $admins->name = validate_input($_POST['name']);
                        $admins->password_hash = $pass_hash;
                        $admins->image = $uniqueName;
                        $admins->save();
                    }else{
                        $admins = ORM::for_table($config['db']['pre'].'admins')->find_one($_POST['id']);
                        $admins->name = validate_input($_POST['name']);
                        $admins->image = $uniqueName;
                        $admins->save();
                    }

                    if (!$admins) {
                        $status = "error";
                        $message = __("Error: Please try again.");
                    } else{
                        $status = "success";
                        $message = __("Saved Successfully");
                    }
                }
            }
            else {
                $error = __("Only allowed jpg, jpeg png");
                $status = "error";
                $message = $error;
            }

        }
        else{
            if(!empty($password)){
                $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                $admins = ORM::for_table($config['db']['pre'].'admins')->find_one($_POST['id']);
                $admins->name = validate_input($_POST['name']);
                $admins->password_hash = $pass_hash;
                $admins->username = validate_input($_POST["username"]);
                $admins->save();

            }else{

                $admins = ORM::for_table($config['db']['pre'].'admins')->find_one($_POST['id']);
                $admins->name = validate_input($_POST['name']);
                $admins->username = validate_input($_POST["username"]);
                $admins->save();
            }


            if (!$admins) {
                $status = "error";
                $message = __("Error: Please try again.");
            } else{
                $status = "success";
                $message = __("Saved Successfully");
            }
        }


    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addUser(){
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $valid_formats = array("jpg","jpeg","png"); // Valid image formats

        if(isset($_FILES['file']['name']))
        {
            $valid_formats = array("jpg","jpeg","png"); // Valid image formats
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = '../storage/profile/';
                $original_filename = $_FILES['file']['name'];
                $random1 = rand(9999,100000);
                $random2 = rand(9999,200000);
                $random3 = $random1.$random2;
                $username = $_POST['username'];
                $image_name = $username.'_'.$random1.$random2.'.'.$ext;
                $image_name1 = 'small_'.$username.'_'.$random1.$random2.'.'.$ext;

                $filename = $uploaddir . $image_name;
                $filename1 = $uploaddir . $image_name1;

                $uploadedfile = $_FILES['file']['tmp_name'];

                //else if it's not bigger then 0, then it's available '
                //and we send 1 to the ajax request
                if (resizeImage(500, $filename, $uploadedfile)) {
                    resize_crop_image(200, 200, $filename1, $uploadedfile);
                    $password = $_POST["password"];
                    $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
                    $now = date("Y-m-d H:i:s");

                    $insert_user = ORM::for_table($config['db']['pre'].'user')->create();
                    $insert_user->status = '0';
                    $insert_user->name = validate_input($_POST['name']);
                    $insert_user->username = validate_input($_POST['username']);
                    $insert_user->user_type = validate_input($_POST['user_type']);
                    $insert_user->password_hash = $pass_hash;
                    $insert_user->email = validate_input($_POST['email']);
                    $insert_user->sex = validate_input($_POST['sex']);
                    $insert_user->description = validate_input($_POST['sex']);
                    $insert_user->country = validate_input($_POST['country']);
                    $insert_user->image = $image_name;
                    $insert_user->created_at = $now;
                    $insert_user->updated_at = $now;
                    $insert_user->save();

                    if ($insert_user->id()) {
                        $status = "success";
                        $message = __("Saved Successfully");
                    } else{
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                }
            }
            else {
                $error = __("Only allowed jpg, jpeg png");
                $status = "error";
                $message = $error;
            }

        } else {
            $error = __("Profile Picture Required");
            $status = "error";
            $message = $error;
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editUser(){
    global $config,$lang;

    if (isset($_POST['id'])) {
        $password = $_POST["password"];

        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != "")
        {
            $valid_formats = array("jpg","jpeg","png"); // Valid image formats
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = '../storage/profile/';
                $original_filename = $_FILES['file']['name'];
                $random1 = rand(9999,100000);
                $random2 = rand(9999,200000);

                $image_name = $random1.$random2.'.'.$ext;
                $image_name1 = 'small_'.$random1.$random2.'.'.$ext;

                $filename = $uploaddir . $image_name;
                $filename1 = $uploaddir . $image_name1;

                $uploadedfile = $_FILES['file']['tmp_name'];

                //else if it's not bigger then 0, then it's available '
                //and we send 1 to the ajax request
                if (resizeImage(500, $filename, $uploadedfile)) {
                    resize_crop_image(200, 200, $filename1, $uploadedfile);

                    $info = ORM::for_table($config['db']['pre'].'user')
                        ->select('image')
                        ->find_one($_POST['id']);

                    if($info['image'] != "default_user.png"){
                        if(file_exists($uploaddir.$info['image'])){
                            unlink($uploaddir.$info['image']);
                            unlink($uploaddir."small_".$info['image']);
                        }
                    }

                    if(!empty($password)){
                        $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);

                        $now = date("Y-m-d H:i:s");
                        $user_update = ORM::for_table($config['db']['pre'].'user')->find_one($_POST['id']);
                        $user_update->set('name', validate_input($_POST['name']));
                        $user_update->set('username', validate_input($_POST['username']));
                        $user_update->set('email', validate_input($_POST['email']));
                        $user_update->set('status', validate_input($_POST['status']));
                        $user_update->set('description', validate_input($_POST['about']));
                        $user_update->set('sex', validate_input($_POST['sex']));
                        $user_update->set('country', validate_input($_POST['country']));
                        $user_update->set('password_hash', $pass_hash);
                        $user_update->set('image', $image_name);
                        $user_update->set('updated_at', $now);
                        $user_update->save();

                    }else{
                        $now = date("Y-m-d H:i:s");
                        $user_update = ORM::for_table($config['db']['pre'].'user')->find_one($_POST['id']);
                        $user_update->set('name', validate_input($_POST['name']));
                        $user_update->set('username', validate_input($_POST['username']));
                        $user_update->set('email', validate_input($_POST['email']));
                        $user_update->set('status', validate_input($_POST['status']));
                        $user_update->set('description', validate_input($_POST['about']));
                        $user_update->set('sex', validate_input($_POST['sex']));
                        $user_update->set('country', validate_input($_POST['country']));
                        $user_update->set('image', $image_name);
                        $user_update->set('updated_at', $now);
                        $user_update->save();
                    }

                    if ($user_update) {
                        $status = "success";
                        $message = __("Saved Successfully");
                    } else{
                        $status = "error";
                        $message = __("Error: Please try again.");
                    }
                }
            }
            else {
                $error = __("Only allowed jpg, jpeg png");
                $status = "error";
                $message = $error;
            }

        }
        else{
            if(!empty($password)){
                $pass_hash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 13]);
                $now = date("Y-m-d H:i:s");

                $user_update = ORM::for_table($config['db']['pre'].'user')->find_one($_POST['id']);
                $user_update->set('name', validate_input($_POST['name']));
                $user_update->set('username', validate_input($_POST['username']));
                $user_update->set('email', validate_input($_POST['email']));
                $user_update->set('status', validate_input($_POST['status']));
                $user_update->set('description', validate_input($_POST['about']));
                $user_update->set('sex', validate_input($_POST['sex']));
                $user_update->set('country', validate_input($_POST['country']));
                $user_update->set('password_hash', $pass_hash);
                $user_update->set('updated_at', $now);
                $user_update->save();

            }else{
                $now = date("Y-m-d H:i:s");

                $user_update = ORM::for_table($config['db']['pre'].'user')->find_one($_POST['id']);
                $user_update->set('name', validate_input($_POST['name']));
                $user_update->set('username', validate_input($_POST['username']));
                $user_update->set('email', validate_input($_POST['email']));
                $user_update->set('status', validate_input($_POST['status']));
                $user_update->set('description', validate_input($_POST['about']));
                $user_update->set('sex', validate_input($_POST['sex']));
                $user_update->set('country', validate_input($_POST['country']));
                $user_update->set('updated_at', $now);
                $user_update->save();
            }


            if ($user_update) {
                $status = "success";
                $message = __("Saved Successfully");
            } else{
                $status = "error";
                $message = __("Error: Please try again.");
            }
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addCountry(){
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $insert_country = ORM::for_table($config['db']['pre'].'countries')->create();
        $insert_country->code = validate_input($_POST['code']);
        $insert_country->name = validate_input($_POST['name']);
        $insert_country->asciiname = validate_input($_POST['asciiname']);
        $insert_country->currency_code = validate_input($_POST['currency_code']);
        $insert_country->phone = validate_input($_POST['phone']);
        $insert_country->languages = validate_input($_POST['languages']);
        $insert_country->save();

        if ($insert_country->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editCountry(){
    global $config,$lang;

    if (isset($_POST['code'])) {

        $info = ORM::for_table($config['db']['pre'].'countries')
            ->select('id')
            ->where('code', $_POST['code'])
            ->find_one();

        $update_country = ORM::for_table($config['db']['pre'].'countries')->find_one($info['id']);
        $update_country->set('name', validate_input($_POST['name']));
        $update_country->set('code', validate_input($_POST['code']));
        $update_country->set('asciiname', validate_input($_POST['asciiname']));
        $update_country->set('currency_code', validate_input($_POST['currency_code']));
        $update_country->set('phone', validate_input($_POST['phone']));
        $update_country->set('languages', validate_input($_POST['languages']));
        $update_country->save();

        if ($update_country) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addState(){
    global $config,$lang;

    if (isset($_POST['code'])) {
        $info = ORM::for_table($config['db']['pre'].'subadmin1')
            ->select('code')
            ->where('country_code', validate_input($_POST['code']))
            ->order_by_desc('code')
            ->find_one();

        $count = count($info);
        if($count > 0 && $info){
            $check = substr($info['code'],3);
            $code = $_POST['code'].".".((int)$check+1);
        }else{
            $code = $_POST['code'].".1";
        }

        $active = isset($_POST['active']) ? '1' : '0';

        $insert_subadmin1 = ORM::for_table($config['db']['pre'].'subadmin1')->create();
        $insert_subadmin1->code = validate_input($code);
        $insert_subadmin1->country_code = validate_input($_POST['code']);
        $insert_subadmin1->name = validate_input($_POST['name']);
        $insert_subadmin1->asciiname = validate_input($_POST['asciiname']);
        $insert_subadmin1->active = $active;
        $insert_subadmin1->save();

        if ($insert_subadmin1->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editState(){
    global $config,$lang;

    if (isset($_POST['code'])) {
        $active = isset($_POST['active']) ? '1' : '0';

        $info = ORM::for_table($config['db']['pre'].'subadmin1')
            ->select('id')
            ->where('code', validate_input($_POST['code']))
            ->find_one();

        $update_subadmin1 = ORM::for_table($config['db']['pre'].'subadmin1')->find_one($info['id']);
        $update_subadmin1->set('name', validate_input($_POST['name']));
        $update_subadmin1->set('asciiname', validate_input($_POST['asciiname']));
        $update_subadmin1->set('active', validate_input($active));
        $update_subadmin1->save();

        if ($update_subadmin1) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addDistrict(){
    global $config,$lang;

    if (isset($_POST['code'])) {
        $info = ORM::for_table($config['db']['pre'].'subadmin2')
            ->select_many('code','country_code','subadmin1_code')
            ->where('subadmin1_code', validate_input($_POST['code']))
            ->order_by_desc('code')
            ->find_one();

        $count = count($info);
        if($count > 0 && $info){
            $country = $info['country_code'];
            $subadmin1 = $info['subadmin1_code'];

            $code = $info['code'];
            $pieces = explode(".", $code);
            $code_count = count($pieces);
            if($code_count == 3){
                $subadmin2 = $pieces[2]+1;
            }
            $code = $_POST['code'].".".$subadmin2;


        }else{
            $code = $_POST['code'].".1";

            $subadmin1 = $_POST['code'];
            $pieces = explode(".", $subadmin1);
            $country = $pieces[0];
        }

        $active = isset($_POST['active']) ? '1' : '0';

        $insert_subadmin2 = ORM::for_table($config['db']['pre'].'subadmin2')->create();
        $insert_subadmin2->name = validate_input($_POST['name']);
        $insert_subadmin2->asciiname = validate_input($_POST['asciiname']);
        $insert_subadmin2->code = validate_input($code);
        $insert_subadmin2->country_code = validate_input($country);
        $insert_subadmin2->subadmin1_code = validate_input($subadmin1);
        $insert_subadmin2->active = validate_input($active);
        $insert_subadmin2->save();

        if ($insert_subadmin2->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editDistrict(){
    global $config,$lang;

    if (isset($_POST['code'])) {
        $active = isset($_POST['active']) ? '1' : '0';

        $info = ORM::for_table($config['db']['pre'].'subadmin2')
            ->select('id')
            ->where('code', validate_input($_POST['code']))
            ->find_one();

        $update_subadmin2 = ORM::for_table($config['db']['pre'].'subadmin2')->find_one($info['id']);
        $update_subadmin2->set('name', validate_input($_POST['name']));
        $update_subadmin2->set('asciiname', validate_input($_POST['asciiname']));
        $update_subadmin2->set('active', validate_input($active));
        $update_subadmin2->save();

        if ($update_subadmin2) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addCity(){
    global $config,$lang;

    if (isset($_POST['submit'])) {
        $active = isset($_POST['active']) ? '1' : '0';

        $insert_city = ORM::for_table($config['db']['pre'].'cities')->create();
        $insert_city->name = validate_input($_POST['name']);
        $insert_city->asciiname = validate_input($_POST['asciiname']);
        $insert_city->country_code = validate_input($_POST['country_code']);
        $insert_city->subadmin1_code = validate_input($_POST['subadmin1_code']);
        $insert_city->subadmin2_code = validate_input($_POST['subadmin2_code']);
        $insert_city->longitude = validate_input($_POST['longitude']);
        $insert_city->latitude = validate_input($_POST['latitude']);
        $insert_city->population = validate_input($_POST['population']);
        $insert_city->time_zone = validate_input($_POST['time_zone']);
        $insert_city->active = validate_input($active);
        $insert_city->save();

        if ($insert_city->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
function editCity(){
    global $config,$lang;

    if (isset($_POST['id'])) {
        $active = isset($_POST['active']) ? '1' : '0';

        $update_city = ORM::for_table($config['db']['pre'].'cities')->find_one($_POST['id']);
        $update_city->set('name', validate_input($_POST['name']));
        $update_city->set('asciiname', validate_input($_POST['asciiname']));
        $update_city->set('country_code', validate_input($_POST['country_code']));
        $update_city->set('subadmin1_code', validate_input($_POST['subadmin1_code']));
        $update_city->set('subadmin2_code', validate_input($_POST['subadmin2_code']));
        $update_city->set('longitude', validate_input($_POST['longitude']));
        $update_city->set('latitude', validate_input($_POST['latitude']));
        $update_city->set('population', validate_input($_POST['population']));
        $update_city->set('time_zone', validate_input($_POST['time_zone']));
        $update_city->set('active', validate_input($active));
        $update_city->save();

        if ($update_city) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addCurrency()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $in_left = isset($_POST['in_left']) ? '1' : '0';

        $insert_currency = ORM::for_table($config['db']['pre'].'currencies')->create();
        $insert_currency->name = validate_input($_POST['name']);
        $insert_currency->code = validate_input($_POST['code']);
        $insert_currency->html_entity = validate_input($_POST['html_entity']);
        $insert_currency->font_arial = validate_input($_POST['font_arial']);
        $insert_currency->font_code2000 = validate_input($_POST['font_code2000']);
        $insert_currency->unicode_decimal = validate_input($_POST['unicode_decimal']);
        $insert_currency->unicode_hex = validate_input($_POST['unicode_hex']);
        $insert_currency->decimal_places = validate_input($_POST['decimal_places']);
        $insert_currency->decimal_separator = validate_input($_POST['decimal_separator']);
        $insert_currency->thousand_separator = validate_input($_POST['thousand_separator']);
        $insert_currency->in_left = $in_left;
        $insert_currency->save();

        if ($insert_currency->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editCurrency()
{
    global $config,$lang;

    if (isset($_POST['id'])) {
        $in_left = isset($_POST['in_left']) ? '1' : '0';

        $update_currency = ORM::for_table($config['db']['pre'].'currencies')->find_one($_POST['id']);
        $update_currency->set('name', validate_input($_POST['name']));
        $update_currency->set('code', validate_input($_POST['code']));
        $update_currency->set('html_entity', validate_input($_POST['html_entity']));
        $update_currency->set('font_arial', validate_input($_POST['font_arial']));
        $update_currency->set('font_code2000', validate_input($_POST['font_code2000']));
        $update_currency->set('unicode_decimal', validate_input($_POST['unicode_decimal']));
        $update_currency->set('unicode_hex', validate_input($_POST['unicode_hex']));
        $update_currency->set('decimal_places', validate_input($_POST['decimal_places']));
        $update_currency->set('decimal_separator', validate_input($_POST['decimal_separator']));
        $update_currency->set('thousand_separator', validate_input($_POST['thousand_separator']));
        $update_currency->set('in_left', $in_left);
        $update_currency->save();

        if ($update_currency) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addTimezone()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $insert_timezone = ORM::for_table($config['db']['pre'].'time_zones')->create();
        $insert_timezone->country_code = validate_input($_POST['country_code']);
        $insert_timezone->time_zone_id = validate_input($_POST['time_zone_id']);
        $insert_timezone->gmt = validate_input($_POST['gmt']);
        $insert_timezone->dst = validate_input($_POST['dst']);
        $insert_timezone->raw = validate_input($_POST['raw']);
        $insert_timezone->save();

        if ($insert_timezone->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editTimezone()
{
    global $config,$lang;

    if (isset($_POST['id'])) {

        $update_timezone = ORM::for_table($config['db']['pre'].'time_zones')->find_one(validate_input($_POST['id']));
        $update_timezone->set('country_code', validate_input($_POST['country_code']));
        $update_timezone->set('time_zone_id', validate_input($_POST['time_zone_id']));
        $update_timezone->set('gmt', validate_input($_POST['gmt']));
        $update_timezone->set('dst', validate_input($_POST['dst']));
        $update_timezone->set('raw', validate_input($_POST['raw']));
        $update_timezone->save();

        if ($update_timezone) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addMembershipPlan()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $recommended = isset($_POST['recommended']) ? "yes" : "no";
        $active = isset($_POST['active']) ? 1 : 0;

        $employer_commission = isset($_POST['employer_commission']) ? $_POST['employer_commission'] : 0;
        $freelancer_commission = isset($_POST['freelancer_commission']) ? $_POST['freelancer_commission'] : 0;
        $bids = isset($_POST['bids']) ? $_POST['bids'] : 0;
        $skills = isset($_POST['skills']) ? $_POST['skills'] : 0;

        $featured = isset($_POST['featured_project_fee']) ? $_POST['featured_project_fee'] : 0;
        $urgent = isset($_POST['urgent_project_fee']) ? $_POST['urgent_project_fee'] : 0;
        $highlight = isset($_POST['highlight_project_fee']) ? $_POST['highlight_project_fee'] : 0;

        $featured_duration = isset($_POST['featured_duration']) ? $_POST['featured_duration'] : 0;
        $urgent_duration = isset($_POST['urgent_duration']) ? $_POST['urgent_duration'] : 0;
        $highlight_duration = isset($_POST['highlight_duration']) ? $_POST['highlight_duration'] : 0;

        $top_search_result = isset($_POST['top_search_result']) ? "yes" : "no";
        $show_on_home = isset($_POST['show_on_home']) ? "yes" : "no";
        $show_in_home_search = isset($_POST['show_in_home_search']) ? "yes" : "no";

        $settings = array(
            'employer_commission' => (int) validate_input($employer_commission),
            'freelancer_commission' => (int) validate_input($freelancer_commission),
            'bids' => (int) validate_input($bids),
            'skills' => (int) validate_input($skills),
            'ad_limit' => (int) $_POST['ad_limit'],
            'ad_duration' => (int) $_POST['ad_duration'],
            'featured_project_fee' => (int) $featured,
            'featured_duration' => (int) $featured_duration,
            'urgent_project_fee' => (int) $urgent,
            'urgent_duration' => (int) $urgent_duration,
            'highlight_project_fee' => (int) $highlight,
            'highlight_duration' => (int) $highlight_duration,
            'top_search_result' => $top_search_result,
            'show_on_home' => $show_on_home,
            'show_in_home_search' => $show_in_home_search,
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom){
            if(!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_'.$custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        $insert_subscription = ORM::for_table($config['db']['pre'].'plans')->create();
        $insert_subscription->name = validate_input($_POST['name']);
        $insert_subscription->badge = $_POST['badge'];
        $insert_subscription->monthly_price = validate_input($_POST['monthly_price']);
        $insert_subscription->annual_price = validate_input($_POST['annual_price']);
        $insert_subscription->lifetime_price = validate_input($_POST['lifetime_price']);
        $insert_subscription->settings = json_encode($settings);
        $insert_subscription->taxes_ids = isset($_POST['taxes'])? validate_input(implode(',',$_POST['taxes'])) : null;
        $insert_subscription->status = $active;
        $insert_subscription->recommended = $recommended;
        $insert_subscription->date = date('Y-m-d H:i:s');
        $insert_subscription->save();

        if ($insert_subscription->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editMembershipPlan()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {
        $active = isset($_POST['active']) ? 1 : 0;

        $employer_commission = isset($_POST['employer_commission']) ? $_POST['employer_commission'] : 0;
        $freelancer_commission = isset($_POST['freelancer_commission']) ? $_POST['freelancer_commission'] : 0;
        $bids = isset($_POST['bids']) ? $_POST['bids'] : 0;
        $skills = isset($_POST['skills']) ? $_POST['skills'] : 0;

        $featured = isset($_POST['featured_project_fee']) ? $_POST['featured_project_fee'] : 0;
        $urgent = isset($_POST['urgent_project_fee']) ? $_POST['urgent_project_fee'] : 0;
        $highlight = isset($_POST['highlight_project_fee']) ? $_POST['highlight_project_fee'] : 0;

        $featured_duration = isset($_POST['featured_duration']) ? $_POST['featured_duration'] : 0;
        $urgent_duration = isset($_POST['urgent_duration']) ? $_POST['urgent_duration'] : 0;
        $highlight_duration = isset($_POST['highlight_duration']) ? $_POST['highlight_duration'] : 0;

        $top_search_result = isset($_POST['top_search_result']) ? "yes" : "no";
        $show_on_home = isset($_POST['show_on_home']) ? "yes" : "no";
        $show_in_home_search = isset($_POST['show_in_home_search']) ? "yes" : "no";

        $settings = array(
            'employer_commission' => (int) validate_input($employer_commission),
            'freelancer_commission' => (int) validate_input($freelancer_commission),
            'bids' => (int) validate_input($bids),
            'skills' => (int) validate_input($skills),
            'ad_limit' => (int) validate_input($_POST['ad_limit']),
            'ad_duration' => (int) validate_input($_POST['ad_duration']),
            'featured_project_fee' => (int) $featured,
            'featured_duration' => (int) $featured_duration,
            'urgent_project_fee' => (int) $urgent,
            'urgent_duration' => (int) $urgent_duration,
            'highlight_project_fee' => (int) $highlight,
            'highlight_duration' => (int) $highlight_duration,
            'top_search_result' => $top_search_result,
            'show_on_home' => $show_on_home,
            'show_in_home_search' => $show_in_home_search,
            'custom' => array()
        );

        $plan_custom = ORM::for_table($config['db']['pre'].'plan_options')
            ->where('active', 1)
            ->order_by_asc('position')
            ->find_many();
        foreach ($plan_custom as $custom){
            if(!empty($custom['title']) && trim($custom['title']) != '' && !empty($_POST['custom_'.$custom['id']])) {
                $settings['custom'][$custom['id']] = 1;
            }
        }

        switch ($_POST['id']){
            case 'free':
                $plan = json_encode(array(
                    'id' => 'free',
                    'name' => validate_input($_POST['name']),
                    'badge' => $_POST['badge'],
                    'settings' => $settings,
                    'status' => $active
                ));
                update_option('free_membership_plan', $plan);
                break;
            case 'trial':
                $plan = json_encode(array(
                    'id' => 'trial',
                    'name' => validate_input($_POST['name']),
                    'badge' => $_POST['badge'],
                    'days' => (int) validate_input($_POST['days']),
                    'settings' => $settings,
                    'status' => $active
                ));
                update_option('trial_membership_plan', $plan);
                break;
            default:
                $recommended = isset($_POST['recommended']) ? "yes" : "no";

                $insert_subscription = ORM::for_table($config['db']['pre'].'plans')->find_one($_POST['id']);
                $insert_subscription->name = validate_input($_POST['name']);
                $insert_subscription->badge = $_POST['badge'];
                $insert_subscription->monthly_price = validate_input($_POST['monthly_price']);
                $insert_subscription->annual_price = validate_input($_POST['annual_price']);
                $insert_subscription->lifetime_price = validate_input($_POST['lifetime_price']);
                $insert_subscription->settings = json_encode($settings);
                $insert_subscription->taxes_ids = isset($_POST['taxes'])? validate_input(implode(',',$_POST['taxes'])) : null;
                $insert_subscription->status = $active;
                $insert_subscription->recommended = $recommended;
                $insert_subscription->date = date('Y-m-d H:i:s');
                $insert_subscription->save();
                break;
        }

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addMembershipPackage()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $removable = isset($_POST['group_removable']) ? $_POST['group_removable'] : 0;

        $featured = isset($_POST['featured_project_fee']) ? $_POST['featured_project_fee'] : 0;
        $urgent = isset($_POST['urgent_project_fee']) ? $_POST['urgent_project_fee'] : 0;
        $highlight = isset($_POST['highlight_project_fee']) ? $_POST['highlight_project_fee'] : 0;

        $featured_duration = isset($_POST['featured_duration']) ? $_POST['featured_duration'] : 0;
        $urgent_duration = isset($_POST['urgent_duration']) ? $_POST['urgent_duration'] : 0;
        $highlight_duration = isset($_POST['highlight_duration']) ? $_POST['highlight_duration'] : 0;

        $top_search_result = isset($_POST['top_search_result']) ? "yes" : "no";
        $show_on_home = isset($_POST['show_on_home']) ? "yes" : "no";
        $show_in_home_search = isset($_POST['show_in_home_search']) ? "yes" : "no";

        $insert_usergroup = ORM::for_table($config['db']['pre'].'usergroups')->create();
        $insert_usergroup->group_name = validate_input($_POST['group_name']);
        $insert_usergroup->group_removable = $removable;
        $insert_usergroup->ad_limit = validate_input($_POST['ad_limit']);
        $insert_usergroup->ad_duration = validate_input($_POST['ad_duration']);
        $insert_usergroup->featured_project_fee = $featured;
        $insert_usergroup->urgent_project_fee = $urgent;
        $insert_usergroup->highlight_project_fee = $highlight;
        $insert_usergroup->featured_duration = $featured_duration;
        $insert_usergroup->urgent_duration = $urgent_duration;
        $insert_usergroup->highlight_duration = $highlight_duration;
        $insert_usergroup->top_search_result = $top_search_result;
        $insert_usergroup->show_on_home = $show_on_home;
        $insert_usergroup->show_in_home_search = $show_in_home_search;
        $insert_usergroup->save();

        if ($insert_usergroup->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editMembershipPackage()
{
    global $config,$lang;

    if (isset($_POST['id'])) {
        $removable = isset($_POST['group_removable']) ? $_POST['group_removable'] : 0;
        $featured = isset($_POST['featured_project_fee']) ? $_POST['featured_project_fee'] : 0;
        $urgent = isset($_POST['urgent_project_fee']) ? $_POST['urgent_project_fee'] : 0;
        $highlight = isset($_POST['highlight_project_fee']) ? $_POST['highlight_project_fee'] : 0;

        $featured_duration = isset($_POST['featured_duration']) ? $_POST['featured_duration'] : 0;
        $urgent_duration = isset($_POST['urgent_duration']) ? $_POST['urgent_duration'] : 0;
        $highlight_duration = isset($_POST['highlight_duration']) ? $_POST['highlight_duration'] : 0;

        $top_search_result = isset($_POST['top_search_result']) ? "yes" : "no";
        $show_on_home = isset($_POST['show_on_home']) ? "yes" : "no";
        $show_in_home_search = isset($_POST['show_in_home_search']) ? "yes" : "no";

        $pdo = ORM::get_db();
        $query = "UPDATE `".$config['db']['pre']."usergroups` SET
        `group_name` = '" . validate_input($_POST['group_name']) . "',
        `group_removable` = '" . validate_input($removable) . "',
        `ad_limit` = '" . validate_input($_POST['ad_limit']) . "',
        `ad_duration` = '" . validate_input($_POST['ad_duration']) . "',
        `featured_project_fee` = '" . validate_input($featured) . "',
        `urgent_project_fee` = '" . validate_input($urgent) . "',
        `highlight_project_fee` = '" . validate_input($highlight) . "',
        `featured_duration` = '" . validate_input($featured_duration) . "',
        `urgent_duration` = '" . validate_input($urgent_duration) . "',
        `highlight_duration` = '" . validate_input($highlight_duration) . "',
        `top_search_result` = '" . validate_input($top_search_result) . "',
        `show_on_home` = '" . validate_input($show_on_home) . "',
        `show_in_home_search` = '" . validate_input($show_in_home_search) . "'
        WHERE `group_id` = '".$_POST['id']."' LIMIT 1 ";

        $query_result = $pdo->query($query);

        if ($query_result) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addTax()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $insert_tax = ORM::for_table($config['db']['pre'].'taxes')->create();
        $insert_tax->internal_name = validate_input($_POST['internal_name']);
        $insert_tax->name = validate_input($_POST['name']);
        $insert_tax->description = validate_input($_POST['description']);
        $insert_tax->value = validate_input($_POST['value']);
        $insert_tax->value_type = validate_input($_POST['value_type']);
        $insert_tax->type = validate_input($_POST['type']);
        $insert_tax->billing_type = validate_input($_POST['billing_type']);
        $insert_tax->countries = isset($_POST['countries'])? validate_input(implode(',',$_POST['countries'])) : null;
        $insert_tax->datetime = date('Y-m-d H:i:s');
        $insert_tax->save();

        if ($insert_tax->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editTax()
{
    global $config,$lang;

    if (isset($_POST['submit'])) {

        $insert_tax = ORM::for_table($config['db']['pre'].'taxes')->find_one($_POST['id']);
        $insert_tax->internal_name = validate_input($_POST['internal_name']);
        $insert_tax->name = validate_input($_POST['name']);
        $insert_tax->description = validate_input($_POST['description']);
        $insert_tax->value = validate_input($_POST['value']);
        $insert_tax->value_type = validate_input($_POST['value_type']);
        $insert_tax->type = validate_input($_POST['type']);
        $insert_tax->billing_type = validate_input($_POST['billing_type']);
        $insert_tax->countries = isset($_POST['countries'])? validate_input(implode(',',$_POST['countries'])) : null;
        $insert_tax->save();

        if ($insert_tax->id()) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addLanguage()
{
    global $config,$lang;
    if (isset($_POST['submit'])) {
        if(isset($_POST['name']) && $_POST['name'] != ""){

            $post_langname = str_replace(' ', '', strtolower(validate_input($_POST['name'])));

            $filePath = '../includes/lang/lang_'.$post_langname.'.php';
            if (!file_exists($filePath)) {
                $source = 'en';
                $target = $_POST['code'];
                $auto_translate = isset($_POST['auto_tran']) ? '1' : '0';
                $active = isset($_POST['active']) ? '1' : '0';

                $trans = new GoogleTranslate();
                $newLangArray = array();
                foreach ($lang as $key => $value)
                {
                    if($auto_translate == 1){
                        $result = $trans->translate($source, $target, $value);
                    }else{
                        $result = $value;
                    }

                    $newLangArray[$key] = $result;
                }
                fopen($filePath, "w");
                change_config_file_settings($filePath, $newLangArray,$lang);

                $lang_filename = $post_langname;

                $insert_language = ORM::for_table($config['db']['pre'].'languages')->create();
                $insert_language->code = validate_input($_POST['code']);
                $insert_language->name = $post_langname;
                $insert_language->direction = validate_input($_POST['direction']);
                $insert_language->file_name = $lang_filename;
                $insert_language->active = $active;
                $insert_language->save();

                if ($insert_language->id()) {
                    $status = "success";
                    $message = __("Saved Successfully");
                } else{
                    $status = "error";
                    $message = __("Error: Please try again.");
                }


            } else {
                $message = __("Same language file is exist. Change language name.");
                echo $json = '{"status" : "error","message" : "' . $message . '"}';
                die();
            }
        }else{
            $status = "error";
            $message = __("Error: Please try again.");
        }

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function editLanguage()
{
    global $config,$lang;

    if (isset($_POST['id'])) {

        $active = isset($_POST['active']) ? '1' : '0';
        $lang_filename = strtolower(validate_input($_POST['name']));

        $update_language = ORM::for_table($config['db']['pre'].'languages')->find_one($_POST['id']);
        $update_language->set('code', validate_input($_POST['code']));
        $update_language->set('name', validate_input($_POST['name']));
        $update_language->set('direction', validate_input($_POST['direction']));
        $update_language->set('file_name', $lang_filename);
        $update_language->set('active', $active);
        $update_language->save();

        if ($update_language) {
            $status = "success";
            $message = __("Saved Successfully");
        } else{
            $status = "error";
            $message = __("Error: Please try again.");
        }


    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function addStaticPage()
{
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['submit'])) {

        if (empty($_POST['name'])) {
            $errors[]['message'] = __("Page name required");
        }
        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Page title required");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Page content required");
        }
        if (!count($errors) > 0) {
            if (empty($_POST['slug']))
                $slug = create_slug(validate_input($_POST['name']));
            else
                $slug = create_slug(validate_input($_POST['slug']));
                $active = isset($_POST['active']) ? '1' : '0';

            $insert_page = ORM::for_table($config['db']['pre'].'pages')->create();
            $insert_page->translation_lang = 'en';
            $insert_page->name = validate_input($_POST['name']);
            $insert_page->title = validate_input($_POST['title']);
            $insert_page->content = validate_input($_POST['content'],true);
            $insert_page->slug = $slug;
            $insert_page->type = validate_input($_POST['type']);
            $insert_page->active = $active;
            $insert_page->save();

            $id = $insert_page->id();

            $update_page = ORM::for_table($config['db']['pre'].'pages')->find_one($id);
            $update_page->set('translation_of', $id);
            $update_page->set('parent_id', $id);
            $update_page->save();

            $rows = ORM::for_table($config['db']['pre'].'languages')
                ->select_many('code','name')
                ->where('active', '1')
                ->where_not_equal('code', 'en')
                ->find_many();

            foreach ($rows as $fetch){
                $insert_page = ORM::for_table($config['db']['pre'].'pages')->create();
                $insert_page->translation_lang = $fetch['code'];
                $insert_page->translation_of = $id;
                $insert_page->parent_id = $id;
                $insert_page->name = validate_input($_POST['name']);
                $insert_page->title = validate_input($_POST['title']);
                $insert_page->content = validate_input($_POST['content'],true);
                $insert_page->slug = validate_input($slug);
                $insert_page->type = validate_input($_POST['type']);
                $insert_page->active = validate_input($active);
                $insert_page->save();

            }

            $status = "success";
            $message = __("Page added successfully.");

            echo $json = '{"id" : "' . $id . '","status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error");
        }
    } else {
        $status = "error";
        $message = __("Unknown Error");
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function editStaticPage()
{
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['name'])) {
            $errors[]['message'] = __("Page name required");
        }
        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Page title required");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Page content required");
        }
        if (!count($errors) > 0) {
            if (empty($_POST['slug']))
                $slug = create_slug(validate_input($_POST['name']));
            else
                $slug = create_slug(validate_input($_POST['slug']));
            $active = isset($_POST['active']) ? '1' : '0';

            $update_page = ORM::for_table($config['db']['pre'].'pages')->find_one(validate_input($_POST['id']));
            $update_page->set('name', validate_input($_POST['name']));
            $update_page->set('title', validate_input($_POST['title']));
            $update_page->set('content', validate_input($_POST['content']));
            $update_page->set('slug', $slug);
            $update_page->set('type', validate_input($_POST['type']));
            $update_page->set('active', $active);
            $update_page->save();

            $status = "success";
            $message = __("Page added successfully.");

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error");
        }
    } else {
        $status = "error";
        $message = __("Unknown Error");
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function addFAQentry()
{
    global $config,$lang;
    $errors = array();

    if (isset($_POST['submit'])) {

        if (empty($_POST['title'])) {
            $errors[]['message'] = __("FAQ entry title required");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("FAQ entry content required");
        }
        if (!count($errors) > 0) {
            $active = isset($_POST['active']) ? '1' : '0';

            $insert_faq = ORM::for_table($config['db']['pre'].'faq_entries')->create();
            $insert_faq->translation_lang = 'en';
            $insert_faq->faq_title = validate_input($_POST['title']);
            $insert_faq->faq_content = validate_input($_POST['content']);
            $insert_faq->active = $active;
            $insert_faq->save();

            $id = $insert_faq->id();

            $pdo = ORM::get_db();
            $query = "UPDATE `".$config['db']['pre']."faq_entries` SET
                `translation_of` = '".validate_input($id)."',
                `parent_id` = '".validate_input($id)."'
                 WHERE `faq_id` = '".validate_input($id)."' LIMIT 1 ";
            $query_result = $pdo->query($query);

            $rows = ORM::for_table($config['db']['pre'].'languages')
                ->select_many('code','name')
                ->where('active', '1')
                ->where_not_equal('code', 'en')
                ->find_many();

            foreach ($rows as $fetch){
                $insert_faq = ORM::for_table($config['db']['pre'].'faq_entries')->create();
                $insert_faq->translation_lang = $fetch['code'];
                $insert_faq->translation_of = $id;
                $insert_faq->parent_id = $id;
                $insert_faq->faq_title = validate_input($_POST['title']);
                $insert_faq->faq_content = validate_input($_POST['content']);
                $insert_faq->active = $active;
                $insert_faq->save();
            }

            $status = "success";
            $message = __("Saved Successfully");

            echo $json = '{"id" : "' . $id . '","status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function editFAQentry()
{
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['title'])) {
            $errors[]['message'] = __("FAQ entry title required");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("FAQ entry content required");
        }
        if (!count($errors) > 0) {
            $active = isset($_POST['active']) ? '1' : '0';

            $pdo = ORM::get_db();
            $query = "UPDATE `".$config['db']['pre']."faq_entries` SET
                `faq_title` = '" . validate_input($_POST['title']) . "',
                `faq_content` = '" . addslashes($_POST['content']) . "',
                 `active` = '" . validate_input($active) . "'
                 WHERE `faq_id` = '".validate_input($_POST['id'])."' LIMIT 1 ";
            $query_result = $pdo->query($query);

            $status = "success";
            $message = __('Content Page Edited');

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error");
        }
    } else {
        $status = "error";
        $message = __('Unknown Error');
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function expirePostRenew(){
    global $config,$lang;
    $pdo = ORM::get_db();
    $timenow = date('Y-m-d H:i:s');

    $ad_duration = isset($_REQUEST['duration']) ? $_REQUEST['duration'] : '7';

    $expire_time = date('Y-m-d H:i:s', strtotime($timenow . ' +'.$ad_duration.' day'));
    $expire_timestamp = strtotime($expire_time);

    $query = "UPDATE `".$config['db']['pre']."product` SET
    `status` = 'active', `expire_date` = '" . $expire_timestamp . "'
    WHERE  status='expire'";
    $pdo->query($query);

    $status = "success";
    $message = __("Saved Successfully");

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function approve_all_pending_post()
{
    global $config,$lang,$link;
    if(check_allow()){
        $items = ORM::for_table($config['db']['pre'].'product')
            ->select_many('id','product_name','user_id')
            ->where('status','pending')
            ->find_many();

        if (count($items) > 0) {
            foreach($items as $info){
                //Ad approve Email to seller
                $product_id = $info['id'];
                $item_title = $info['product_name'];
                $item_author_id = $info['user_id'];

                $product = ORM::for_table($config['db']['pre'].'product')->find_one($product_id);
                $product->set('status', 'active');
                $product->save();

                /*SEND RESUBMISSION AD APPROVE EMAIL*/
                email_template("ad_approve",$item_author_id,null,$product_id,$item_title);
            }
        }
    }
    $status = "success";
    $message = __("Saved Successfully");
    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function postEdit()
{
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (empty($_POST['category'])) {
            $errors[]['message'] = __("The category and sub-category are required.");
        }
        if (empty($_POST['title'])) {
            $errors[]['message'] = __("Title is required.");
        }
        if (empty($_POST['content'])) {
            $errors[]['message'] = __("Description is required.");
        }
        if (empty($_POST['city'])) {
            $errors[]['message'] = __("The city is required.");
        }
        if (!empty($_POST['price'])) {
            if (!is_numeric($_POST['price'])) {
                $errors[]['message'] = __("Price must be a number.");
            }
        }

        if (!count($errors) > 0) {

            $urgent = isset($_POST['urgent']) ? '1' : '0';
            $featured = isset($_POST['featured']) ? '1' : '0';
            $highlight = isset($_POST['highlight']) ? '1' : '0';

            if($config['post_desc_editor'] == 1)
                $description = validate_input($_POST['content'],true);
            else
                $description = validate_input($_POST['content']);

            $start_date = validate_input($_POST['start_date']);
            $expire_date = validate_input($_POST['expire_date']);

            $pro_created_date = ORM::for_table($config['db']['pre'].'product')
                ->select('created_at')
                ->where('id',validate_input($_POST['id']))
                ->find_one();

            $old_st = date('Y-m-d', strtotime($pro_created_date['created_at']));
            $new_st = date('Y-m-d', strtotime($_POST['start_date']));

            if($old_st == $new_st){
                $start_time = $pro_created_date['created_at'];
            }else{
                $start_time = date('Y-m-d H:i:s', strtotime($_POST['start_date']));
            }
            $expire_time = date('Y-m-d H:i:s', strtotime($_POST['expire_date']));
            $expire_timestamp = strtotime($expire_date);
            $now = date("Y-m-d H:i:s");

            $item_edit = ORM::for_table($config['db']['pre'].'product')->find_one($_POST['id']);
            $item_edit->set('product_name', validate_input($_POST['title']));
            $item_edit->set('status', validate_input($_POST['status']));
            $item_edit->set('category', validate_input($_POST['category']));
            $item_edit->set('sub_category', !empty($_POST['sub_category'])?$_POST['sub_category']:0);
            $item_edit->set('featured', $featured);
            $item_edit->set('urgent', $urgent);
            $item_edit->set('highlight', $highlight);
            $item_edit->set('city', validate_input($_POST['city']));
            $item_edit->set('state', validate_input($_POST['state']));
            $item_edit->set('country', validate_input($_POST['country']));
            $item_edit->set('description', $description);
            $item_edit->set('created_at', $start_time);
            $item_edit->set('expire_date', $expire_timestamp);
            $item_edit->set('updated_at', $now);
            $item_edit->save();

            $status = "success";
            $message = __("Saved Successfully");

            echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
            die();
        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    $json = '{"status" : "' . $status . '","message" : "' . $message . '","errors" : ' . json_encode($errors, JSON_UNESCAPED_SLASHES) . '}';
    echo $json;
    die();
}

function transactionEdit()
{
    global $config,$lang;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (isset($_POST['status'])) {

            if($_POST['status'] == "success"){
                $transaction_id = validate_input($_POST['id']);
                transaction_success($transaction_id);
            }else{
                $transaction = ORM::for_table($config['db']['pre'].'transaction')->find_one(validate_input($_POST['id']));
                $transaction->status = validate_input($_POST['status']);
                $transaction->save();
            }
            $status = "success";
            $message = __("Saved Successfully");


        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}


function withdrawEdit()
{
    global $config,$lang,$link;
    $errors = array();
    $response = array();

    if (isset($_POST['id'])) {

        if (isset($_POST['status'])) {

            if($_POST['status'] == "reject"){
                $info = ORM::for_table($config['db']['pre'].'withdrawal')->find_one(validate_input($_POST['id']));
                $user_id = $info['user_id'];
                $amount = $info['amount'];
                add_balance($user_id,$amount);

                /*SEND EMAIL*/
                email_template("withdraw_rejected",$user_id);
            }

            if($_POST['status'] == "success"){
                $info = ORM::for_table($config['db']['pre'].'withdrawal')->select('user_id')->find_one(validate_input($_POST['id']));
                $user_id = $info['user_id'];

                /*SEND EMAIL*/
                email_template("withdraw_accepted",$user_id);
            }

            $withdraw = ORM::for_table($config['db']['pre'].'withdrawal')->find_one(validate_input($_POST['id']));
            $withdraw->status = validate_input($_POST['status']);
            $withdraw->save();

            $status = "success";
            $message = __("Saved Successfully");


        }else {
            $status = "error";
            $message = __("Error: Please try again.");
        }
    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}


function editAdvertise()
{
    global $config,$lang;

    if (isset($_POST['id'])) {

        $status = isset($_POST['status']) ? '1' : '0';

        $update_adsense = ORM::for_table($config['db']['pre'].'adsense')->find_one(validate_input($_POST['id']));
        $update_adsense->set('provider_name', validate_input($_POST['provider_name']));
        $update_adsense->set('status', $status);
        $update_adsense->set('large_track_code', validate_input($_POST['large_track_code']));
        $update_adsense->set('tablet_track_code', validate_input($_POST['tablet_track_code']));
        $update_adsense->set('phone_track_code', validate_input($_POST['phone_track_code']));
        $update_adsense->save();

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function paymentEdit()
{
    global $config,$lang;

    if (isset($_POST['id'])) {

        $pdo = ORM::get_db();
        $query = "UPDATE `".$config['db']['pre']."payments` SET
            `payment_title` = '" . validate_input($_POST['title']) . "',
            `payment_install` = '" . validate_input($_POST['install']) . "'
            WHERE `payment_id` = '".validate_input($_POST['id'])."' LIMIT 1 ";
        $query_result = $pdo->query($query);

        if(isset($_POST['paypal_sandbox_mode'])){
            update_option("paypal_sandbox_mode",isset($_POST['paypal_sandbox_mode'])? $_POST['paypal_sandbox_mode'] : "");
            update_option("paypal_payment_mode",isset($_POST['paypal_payment_mode'])? $_POST['paypal_payment_mode'] : "");
            update_option("paypal_api_client_id",isset($_POST['paypal_api_client_id'])? $_POST['paypal_api_client_id'] : "");
            update_option("paypal_api_secret",isset($_POST['paypal_api_secret'])? $_POST['paypal_api_secret'] : "");
        }

        if(isset($_POST['stripe_secret_key'])){
            update_option("stripe_payment_mode",validate_input($_POST['stripe_payment_mode']));
            update_option("stripe_publishable_key",validate_input($_POST['stripe_publishable_key']));
            update_option("stripe_secret_key",validate_input($_POST['stripe_secret_key']));

            if(empty($_POST['stripe_webhook_secret'])){
                // create webhook dynamically
                try{
                    if(file_exists('../includes/payments/stripe/stripe-php/init.php')) {
                        include '../includes/payments/stripe/stripe-php/init.php';
                        $stripe = new \Stripe\StripeClient(validate_input($_POST['stripe_secret_key']));
                        $data = $stripe->webhookEndpoints->create([
                            'url' => $config['site_url'] . 'webhook/stripe',
                            'enabled_events' => [
                                'checkout.session.completed',
                                'invoice.paid',
                                'invoice.upcoming',
                                'invoice.payment_succeeded'
                            ],
                            'api_version' => '2019-12-03'
                        ]);

                        update_option("stripe_webhook_secret", $data['secret']);
                    }
                } catch (Exception $e){}
            } else {
                update_option("stripe_webhook_secret", $_POST['stripe_webhook_secret']);
            }
        }

        if(isset($_POST['paystack_public_key'])){
            update_option("paystack_public_key",validate_input($_POST['paystack_public_key']));
            update_option("paystack_secret_key",validate_input($_POST['paystack_secret_key']));
        }

        if(isset($_POST['payumoney_merchant_key'])){
            update_option("payumoney_sandbox_mode",validate_input($_POST['payumoney_sandbox_mode']));
            update_option("payumoney_merchant_key",validate_input($_POST['payumoney_merchant_key']));
            update_option("payumoney_merchant_salt",validate_input($_POST['payumoney_merchant_salt']));
            update_option("payumoney_merchant_id",validate_input($_POST['payumoney_merchant_id']));
        }

        if(isset($_POST['checkout_account_number'])){
            update_option("2checkout_sandbox_mode",validate_input($_POST['2checkout_sandbox_mode']));
            update_option("checkout_account_number",validate_input($_POST['checkout_account_number']));
            update_option("checkout_public_key",validate_input($_POST['checkout_public_key']));
            update_option("checkout_private_key",validate_input($_POST['checkout_private_key']));
        }

        if(isset($_POST['company_bank_info'])){
            update_option("company_bank_info",validate_input($_POST['company_bank_info'],true));
        }

        if(isset($_POST['company_cheque_info'])){
            update_option("company_cheque_info",validate_input($_POST['company_cheque_info']));
            update_option("cheque_payable_to",validate_input($_POST['cheque_payable_to']));
        }

        if(isset($_POST['skrill_merchant_id'])){
            update_option("skrill_merchant_id",validate_input($_POST['skrill_merchant_id']));
        }

        if(isset($_POST['nochex_merchant_id'])){
            update_option("nochex_merchant_id",validate_input($_POST['nochex_merchant_id']));
        }

        if(isset($_POST['CCAVENUE_MERCHANT_KEY'])){
            update_option("CCAVENUE_MERCHANT_KEY",validate_input($_POST['CCAVENUE_MERCHANT_KEY']));
            update_option("CCAVENUE_ACCESS_CODE",validate_input($_POST['CCAVENUE_ACCESS_CODE']));
            update_option("CCAVENUE_WORKING_KEY",validate_input($_POST['CCAVENUE_WORKING_KEY']));
        }

        if(isset($_POST['PAYTM_ENVIRONMENT'])){
            update_option("PAYTM_ENVIRONMENT",validate_input($_POST['PAYTM_ENVIRONMENT']));
            update_option("PAYTM_MERCHANT_KEY",validate_input($_POST['PAYTM_MERCHANT_KEY']));
            update_option("PAYTM_MERCHANT_MID",validate_input($_POST['PAYTM_MERCHANT_MID']));
            update_option("PAYTM_MERCHANT_WEBSITE",validate_input($_POST['PAYTM_MERCHANT_WEBSITE']));
        }

        if(isset($_POST['mollie_api_key'])){
            update_option("mollie_api_key",validate_input($_POST['mollie_api_key']));
        }

        if(isset($_POST['iyzico_api_key'])){
            update_option("iyzico_sandbox_mode",validate_input($_POST['iyzico_sandbox_mode']));
            update_option("iyzico_api_key",validate_input($_POST['iyzico_api_key']));
            update_option("iyzico_secret_key",validate_input($_POST['iyzico_secret_key']));
        }

        if(isset($_POST['midtrans_client_key'])){
            update_option("midtrans_sandbox_mode",validate_input($_POST['midtrans_sandbox_mode']));
            update_option("midtrans_client_key",validate_input($_POST['midtrans_client_key']));
            update_option("midtrans_server_key",validate_input($_POST['midtrans_server_key']));
        }

        if(isset($_POST['paytabs_profile_id'])){
            update_option("paytabs_profile_id",validate_input($_POST['paytabs_profile_id']));
            update_option("paytabs_secret_key",validate_input($_POST['paytabs_secret_key']));
        }

        if(isset($_POST['telr_store_id'])){
            update_option("telr_sandbox_mode",validate_input($_POST['telr_sandbox_mode']));
            update_option("telr_store_id",validate_input($_POST['telr_store_id']));
            update_option("telr_authkey",validate_input($_POST['telr_authkey']));
        }

        if(isset($_POST['razorpay_api_key'])){
            update_option("razorpay_api_key",validate_input($_POST['razorpay_api_key']));
            update_option("razorpay_secret_key",validate_input($_POST['razorpay_secret_key']));
        }

        $status = "success";
        $message = __("Saved Successfully");

    } else {
        $status = "error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function SaveSettings(){

    global $config,$lang,$link;
    $status = "";
    if (isset($_POST['logo_watermark'])) {
        $valid_formats = array("jpg","jpeg","png"); // Valid image formats
        if (isset($_FILES['banner']) && $_FILES['banner']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['banner']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/banner/"; //Image upload directory
                $bannername = stripslashes($_FILES['banner']['name']);
                $size = filesize($_FILES['banner']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($bannername);
                $ext = strtolower($ext);
                $banner_name = "bg" . '.' . $ext;
                $newBgname = $uploaddir . $banner_name;
                //Moving file to uploads folder
                if(file_exists($newBgname)){
                    unlink($newBgname);
                }
                if (move_uploaded_file($_FILES['banner']['tmp_name'], $newBgname)) {

                    update_option("home_banner",$banner_name);
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['favicon']) && $_FILES['favicon']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['favicon']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['favicon']['name']);
                $size = filesize($_FILES['favicon']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = "favicon" . '.' . $ext;
                $newLogo = $uploaddir . $image_name;
                if(file_exists($newLogo)){
                    unlink($newLogo);
                }
                //Moving file to uploads folder
                if (move_uploaded_file($_FILES['favicon']['tmp_name'], $newLogo)) {

                    update_option("site_favicon",$image_name);
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['file']) && $_FILES['file']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['file']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['file']['name']);
                $size = filesize($_FILES['file']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name']."_logo" . '.' . $ext;
                $newLogo = $uploaddir . $image_name;
                if(file_exists($newLogo)){
                    unlink($newLogo);
                }
                //Moving file to uploads folder
                if (move_uploaded_file($_FILES['file']['tmp_name'], $newLogo)) {

                    update_option("site_logo",$image_name);
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['footer_logo']) && $_FILES['footer_logo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['footer_logo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['footer_logo']['name']);
                $size = filesize($_FILES['footer_logo']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $image_name = $config['tpl_name']."_footer_logo" . '.' . $ext;
                $newLogo = $uploaddir . $image_name;
                if(file_exists($newLogo)){
                    unlink($newLogo);
                }
                //Moving file to uploads folder
                if (move_uploaded_file($_FILES['footer_logo']['tmp_name'], $newLogo)) {

                    update_option("site_logo_footer",$image_name);
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['watermark']) && $_FILES['watermark']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['watermark']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['watermark']['name']);
                $size = filesize($_FILES['watermark']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $mark_name = "watermark" . '.' . $ext;
                $watermark = $uploaddir . $mark_name;
                if(file_exists($watermark)){
                    unlink($watermark);
                }
                //Moving file to uploads folder
                if (move_uploaded_file($_FILES['watermark']['tmp_name'], $watermark)) {
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if (isset($_FILES['adminlogo']) && $_FILES['adminlogo']['tmp_name'] != "") {
            $filename = stripslashes($_FILES['adminlogo']['name']);
            $ext = getExtension($filename);
            $ext = strtolower($ext);
            //File extension check
            if (in_array($ext, $valid_formats)) {
                $uploaddir = "../storage/logo/"; //Image upload directory
                $filename = stripslashes($_FILES['adminlogo']['name']);
                $size = filesize($_FILES['adminlogo']['tmp_name']);
                //Convert extension into a lower case format

                $ext = getExtension($filename);
                $ext = strtolower($ext);
                $adminlogo_name = "adminlogo" . '.' . $ext;
                $adminlogo = $uploaddir . $adminlogo_name;
                if(file_exists($adminlogo)){
                    unlink($adminlogo);
                }
                //Moving file to uploads folder
                if (move_uploaded_file($_FILES['adminlogo']['tmp_name'], $adminlogo)) {
                    update_option("site_admin_logo",$adminlogo_name);
                    $status = "success";
                    $message = __("Saved Successfully");

                } else {
                    $status = "error";
                    $message = __("Error: Please try again.");
                }
            }
            else {
                $status = "error";
                $message = __("Only allowed jpg, jpeg png");
            }

        }

        if($status == ""){
            $status = "success";
            $message = __("Saved Successfully");
        }
    }

    if (isset($_POST['general_setting'])) {
        update_option("site_url",validate_input($_POST['site_url']));
        update_option("site_title",validate_input($_POST['site_title']));
        update_option("meta_keywords",validate_input($_POST['meta_keywords']));
        update_option("meta_description",validate_input($_POST['meta_description']));
        update_option("non_active_msg",validate_input($_POST['non_active_msg']));
        update_option("non_active_allow",validate_input($_POST['non_active_allow']));
        update_option("job_seeker_enable",validate_input($_POST['job_seeker_enable']));
        update_option("resume_enable",validate_input($_POST['resume_enable']));
        update_option("resume_files",validate_input($_POST['resume_files']));
        update_option("company_enable",validate_input($_POST['company_enable']));
        update_option("reg_no_enable",validate_input($_POST['reg_no_enable']));
        update_option("cron_exec_time",validate_input($_POST['cron_exec_time']));
        update_option("delete_expired",validate_input($_POST['delete_expired']));
        update_option("userlangsel",validate_input($_POST['userlangsel']));
        update_option("userthemesel",validate_input($_POST['userthemesel']));
        update_option("color_switcher",validate_input($_POST['color_switcher']));
        update_option("termcondition_link",validate_input($_POST['termcondition_link']));
        update_option("privacy_link",validate_input($_POST['privacy_link']));
        update_option("cookie_link",validate_input($_POST['cookie_link']));
        update_option("cookie_consent",validate_input($_POST['cookie_consent']));
        update_option("transfer_filter",validate_input($_POST['transfer_filter']));
        update_option("temp_php",validate_input($_POST['temp_php']));
        update_option("quickad_debug",validate_input($_POST['quickad_debug']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['home_page_setting'])) {
        update_option("home_page",validate_input($_POST['home_page']));
        update_option("header_sticky",validate_input($_POST['header_sticky']));
        update_option("transparent_header",validate_input($_POST['transparent_header']));
        update_option("banner_overlay",validate_input($_POST['banner_overlay']));
        update_option("show_search_home",validate_input($_POST['show_search_home']));
        update_option("show_categories_home",validate_input($_POST['show_categories_home']));
        update_option("show_featured_jobs_home",validate_input($_POST['show_featured_jobs_home']));
        update_option("show_latest_jobs_home",validate_input($_POST['show_latest_jobs_home']));
        update_option("show_membershipplan_home",validate_input($_POST['show_membershipplan_home']));
        update_option("show_partner_logo_home",validate_input($_POST['show_partner_logo_home']));

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['theme_setting'])) {
        update_option("theme_color",validate_input($_POST['theme_color']));
        update_option("contact_validation",validate_input($_POST['contact_validation']));
        update_option("contact_address",validate_input($_POST['contact_address']));
        update_option("contact_phone",validate_input($_POST['contact_phone']));
        update_option("contact_email",validate_input($_POST['contact_email']));
        update_option("footer_text",validate_input($_POST['footer_text']));
        update_option("copyright_text",validate_input($_POST['copyright_text']));
        update_option("facebook_link",validate_input($_POST['facebook_link']));
        update_option("twitter_link",validate_input($_POST['twitter_link']));
        update_option("instagram_link",validate_input($_POST['instagram_link']));
        update_option("linkedin_link",validate_input($_POST['linkedin_link']));
        update_option("pinterest_link",validate_input($_POST['pinterest_link']));
        update_option("youtube_link",validate_input($_POST['youtube_link']));
        update_option("external_code",$_POST['external_code']);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['quick_map'])) {
        update_option("post_address_mode",validate_input($_POST['post_address_mode']));
        update_option("map_type",validate_input($_POST['map_type']));
        update_option("openstreet_access_token",validate_input($_POST['openstreet_access_token']));
        update_option("gmap_api_key",validate_input($_POST['gmap_api_key']));
        update_option("map_color",validate_input($_POST['map_color']));
        update_option("home_map_latitude",validate_input($_POST['home_map_latitude']));
        update_option("home_map_longitude",validate_input($_POST['home_map_longitude']));
        update_option("contact_latitude",validate_input($_POST['contact_latitude']));
        update_option("contact_longitude",validate_input($_POST['contact_longitude']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['live_location_track'])) {
        update_option("location_track_icon",validate_input($_POST['location_track_icon']));
        update_option("auto_detect_location",validate_input($_POST['auto_detect_location']));
        update_option("live_location_api",validate_input($_POST['live_location_api']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['billing_details'])) {
        update_option("invoice_nr_prefix", validate_input($_POST['invoice_nr_prefix']));
        update_option("invoice_admin_name", validate_input($_POST['invoice_admin_name']));
        update_option("invoice_admin_email", validate_input($_POST['invoice_admin_email']));
        update_option("invoice_admin_phone", validate_input($_POST['invoice_admin_phone']));
        update_option("invoice_admin_address", validate_input($_POST['invoice_admin_address']));
        update_option("invoice_admin_city", validate_input($_POST['invoice_admin_city']));
        update_option("invoice_admin_state", validate_input($_POST['invoice_admin_state']));
        update_option("invoice_admin_zipcode", validate_input($_POST['invoice_admin_zipcode']));
        update_option("invoice_admin_country", validate_input($_POST['invoice_admin_country']));
        update_option("invoice_admin_tax_type", validate_input($_POST['invoice_admin_tax_type']));
        update_option("invoice_admin_tax_id", validate_input($_POST['invoice_admin_tax_id']));
        update_option("invoice_admin_custom_name_1", validate_input($_POST['invoice_admin_custom_name_1']));
        update_option("invoice_admin_custom_value_1", validate_input($_POST['invoice_admin_custom_value_1']));
        update_option("invoice_admin_custom_name_2", validate_input($_POST['invoice_admin_custom_name_2']));
        update_option("invoice_admin_custom_value_2", validate_input($_POST['invoice_admin_custom_value_2']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['international'])) {

        if(isset($_POST['currency']))
        {
            $info = ORM::for_table($config['db']['pre'].'currencies')->find_one($_POST['currency']);

            $currency_sign = $info['html_entity'];
            $currency_code = $info['code'];
            $currency_pos = $info['in_left'];
        }
        update_option("country_type",validate_input($_POST['country_type']));
        update_option("specific_country",validate_input($_POST['specific_country']));
        update_option("lang",validate_input($_POST['lang']));
        update_option("timezone",validate_input($_POST['timezone']));
        update_option("currency_sign",$currency_sign);
        update_option("currency_code",$currency_code);
        update_option("currency_pos",$currency_pos);
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['email_setting'])) {

        update_option("admin_email",validate_input($_POST['admin_email']));
        update_option("email_template",validate_input($_POST['email_template']));
        update_option("email_type",validate_input($_POST['email_type']));
        update_option("smtp_host",validate_input($_POST['smtp_host']));
        update_option("smtp_port",validate_input($_POST['smtp_port']));
        update_option("smtp_username",validate_input($_POST['smtp_username']));
        update_option("smtp_password",validate_input($_POST['smtp_password']));
        update_option("smtp_secure",validate_input($_POST['smtp_secure']));
        update_option("smtp_auth",validate_input($_POST['smtp_auth']));
        update_option("aws_host",validate_input($_POST['aws_host']));
        update_option("aws_access_key",validate_input($_POST['aws_access_key']));
        update_option("aws_secret_key",validate_input($_POST['aws_secret_key']));
        update_option("mandrill_user",validate_input($_POST['mandrill_user']));
        update_option("mandrill_key",validate_input($_POST['mandrill_key']));
        update_option("sendgrid_user",validate_input($_POST['sendgrid_user']));
        update_option("sendgrid_pass",validate_input($_POST['sendgrid_pass']));

        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['frontend_submission'])) {
        update_option("post_without_login",validate_input($_POST['post_without_login']));
        update_option("post_auto_approve",validate_input($_POST['post_auto_approve']));
        update_option("post_desc_editor",validate_input($_POST['post_desc_editor']));
        update_option("job_image_field",validate_input($_POST['job_image_field']));
        update_option("post_tags_mode",validate_input($_POST['post_tags_mode']));
        update_option("post_premium_listing",validate_input($_POST['post_premium_listing']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['project_setting'])) {
        update_option("payment_minimum_withdraw",validate_input($_POST['payment_minimum_withdraw']));
        update_option("payment_minimum_deposit",validate_input($_POST['payment_minimum_deposit']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['social_login_setting'])) {
        update_option("facebook_app_id",validate_input($_POST['facebook_app_id']));
        update_option("facebook_app_secret",validate_input($_POST['facebook_app_secret']));
        update_option("google_app_id",validate_input($_POST['google_app_id']));
        update_option("google_app_secret",validate_input($_POST['google_app_secret']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['recaptcha_setting'])) {

        update_option("recaptcha_mode",validate_input($_POST['recaptcha_mode']));
        update_option("recaptcha_public_key",validate_input($_POST['recaptcha_public_key']));
        update_option("recaptcha_private_key",validate_input($_POST['recaptcha_private_key']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['blog_setting'])) {

        update_option("blog_enable",validate_input($_POST['blog_enable']));
        update_option("blog_banner",validate_input($_POST['blog_banner']));
        update_option("show_blog_home",validate_input($_POST['show_blog_home']));
        update_option("blog_comment_enable",validate_input($_POST['blog_comment_enable']));
        update_option("blog_comment_approval",validate_input($_POST['blog_comment_approval']));
        update_option("blog_comment_user",validate_input($_POST['blog_comment_user']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['testimonials_setting'])) {

        update_option("testimonials_enable",validate_input($_POST['testimonials_enable']));
        update_option("show_testimonials_blog",validate_input($_POST['show_testimonials_blog']));
        update_option("show_testimonials_home",validate_input($_POST['show_testimonials_home']));
        $status = "success";
        $message = __("Saved Successfully");
    }

    if (isset($_POST['valid_purchase_setting'])) {

        // Set API Key
        $code = validate_input($_POST['purchase_key']);
        $buyer_email = (isset($_POST['buyer_email']))? validate_input($_POST['buyer_email']) : "";
        $installing_version = 'pro';

        $url = "https://bylancer.com/api/api.php?verify-purchase=" . $code . "&version=" . $installing_version . "&site_url=". $config['site_url']."&email=" . $buyer_email;
        // Open cURL channel
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //Set the user agent
        $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        // Decode returned JSON
        $output = json_decode(curl_exec($ch), true);
        // Close Channel
        curl_close($ch);

        if ($output['success']) {
            if(isset($config['quickad_secret_file']) && $config['quickad_secret_file'] != ""){
                $fileName = $config['quickad_secret_file'];
            }else{
                $fileName = get_random_string();
            }
            file_put_contents( $fileName . '.php', $output['data']);
            $success = true;
            update_option("quickad_secret_file",$fileName);
            update_option("purchase_key",validate_input($_POST['purchase_key']));
            $status = "success";
            $message = __("Purchase code verified successfully");
        } else {
            $status = "error";
            $message = $output['error'];
        }

    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function saveEmailTemplate(){

    if (isset($_POST['email_setting'])) {

        /*Start: Email Subject*/
        update_option("email_sub_signup_details",$_POST['email_sub_signup_details']);
        update_option("email_sub_signup_confirm",$_POST['email_sub_signup_confirm']);
        update_option("email_sub_forgot_pass",$_POST['email_sub_forgot_pass']);
        update_option("email_sub_contact",$_POST['email_sub_contact']);
        update_option("email_sub_feedback",$_POST['email_sub_feedback']);
        update_option("email_sub_report",$_POST['email_sub_report']);
        update_option("email_sub_ad_approve",$_POST['email_sub_ad_approve']);
        update_option("email_sub_re_ad_approve",$_POST['email_sub_re_ad_approve']);
        update_option("email_sub_contact_seller",$_POST['email_sub_contact_seller']);
        update_option("email_sub_post_notification",$_POST['email_sub_post_notification']);
        /***Freelancing***/
        update_option("email_sub_freelancer_project_awarded",$_POST['email_sub_freelancer_project_awarded']);
        update_option("email_sub_freelancer_project_revoke",$_POST['email_sub_freelancer_project_revoke']);
        update_option("email_sub_employer_project_accepted",$_POST['email_sub_employer_project_accepted']);
        update_option("email_sub_employer_project_approval_reject",$_POST['email_sub_employer_project_approval_reject']);
        update_option("email_sub_milestone_created",$_POST['email_sub_milestone_created']);
        update_option("email_sub_milestone_released",$_POST['email_sub_milestone_released']);
        update_option("email_sub_milestone_request_to_release",$_POST['email_sub_milestone_request_to_release']);
        update_option("email_sub_got_rating",$_POST['email_sub_got_rating']);
        update_option("email_sub_withdraw_accepted",$_POST['email_sub_withdraw_accepted']);
        update_option("email_sub_withdraw_rejected",$_POST['email_sub_withdraw_rejected']);
        update_option("email_sub_withdraw_request",$_POST['email_sub_withdraw_request']);
        update_option("email_sub_amount_deposit",$_POST['email_sub_amount_deposit']);

        /*Start: Email Message*/
        update_option("email_message_signup_details",$_POST['email_message_signup_details']);
        update_option("email_message_signup_confirm",$_POST['email_message_signup_confirm']);
        update_option("email_message_forgot_pass",$_POST['email_message_forgot_pass']);
        update_option("email_message_contact",$_POST['email_message_contact']);
        update_option("email_message_feedback",$_POST['email_message_feedback']);
        update_option("email_message_report",$_POST['email_message_report']);
        update_option("email_message_ad_approve",$_POST['email_message_ad_approve']);
        update_option("email_message_re_ad_approve",$_POST['email_message_re_ad_approve']);
        update_option("email_message_contact_seller",$_POST['email_message_contact_seller']);
        update_option("email_message_post_notification",$_POST['email_message_post_notification']);
        /***Freelancing***/
        update_option("emailHTML_freelancer_project_awarded",$_POST['emailHTML_freelancer_project_awarded']);
        update_option("emailHTML_freelancer_project_revoke",$_POST['emailHTML_freelancer_project_revoke']);
        update_option("emailHTML_employer_project_accepted",$_POST['emailHTML_employer_project_accepted']);
        update_option("emailHTML_employer_project_approval_reject",$_POST['emailHTML_employer_project_approval_reject']);
        update_option("emailHTML_milestone_created",$_POST['emailHTML_milestone_created']);
        update_option("emailHTML_milestone_released",$_POST['emailHTML_milestone_released']);
        update_option("emailHTML_milestone_request_to_release",$_POST['emailHTML_milestone_request_to_release']);
        update_option("emailHTML_got_rating",$_POST['emailHTML_got_rating']);
        update_option("emailHTML_withdraw_accepted",$_POST['emailHTML_withdraw_accepted']);
        update_option("emailHTML_withdraw_rejected",$_POST['emailHTML_withdraw_rejected']);
        update_option("emailHTML_withdraw_request",$_POST['emailHTML_withdraw_request']);
        update_option("emailHTML_amount_deposit",$_POST['emailHTML_amount_deposit']);


        $status = "success";
        $message = __("Saved Successfully");
    }else{
        $status = "Error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}

function testEmailTemplate(){

    global $config,$lang,$link;

    if (isset($_POST['test-email-notification'])) {
        $test_to_email =  validate_input($_POST['test_to_email']);
        $test_to_name = validate_input($_POST['test_to_name']);

        if (isset($_POST['signup-details'])) {
            
            $html = $config['email_sub_signup_details'];
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_subject = $html;
            
            $html = $config['email_message_signup_details'];
            $html = str_replace ('{USERNAME}', "demo", $html);
            $html = str_replace ('{PASSWORD}', "demo", $html);
            $html = str_replace ('{USER_ID}', "1", $html);
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['create-account'])) {

            
            $html = $config['email_sub_signup_confirm'];
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_subject = $html;

            $confirmation_link = $link['SIGNUP']."?confirm=123456&user=1";
            
            $html = $config['email_message_signup_confirm'];
            $html = str_replace ('{CONFIRMATION_LINK}', $confirmation_link, $html);
            $html = str_replace ('{USERNAME}', "demo", $html);
            $html = str_replace ('{USER_ID}', "1", $html);
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['forgot-pass'])) {
            
            $html = $config['email_sub_forgot_pass'];
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_subject = $html;

            $forget_password_link = $config['site_url']."login?forgot=sd1213f1x1&r=21d1d2d12&e=12&t=1213231";
            
            $html = $config['email_message_forgot_pass'];
            $html = str_replace ('FORGET_PASSWORD_LINK', $forget_password_link, $html);
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{USER_FULLNAME}', $test_to_name, $html);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['contact_us'])) {
            
            $html = $config['email_sub_contact'];
            $html = str_replace ('{CONTACT_SUBJECT}', "Contact Email", $html);
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{NAME}', $test_to_name, $html);
            $email_subject = $html;

            
            $html = $config['email_message_contact'];
            $html = str_replace ('{EMAIL}', $test_to_email, $html);
            $html = str_replace ('{NAME}', $test_to_name, $html);
            $html = str_replace ('{CONTACT_SUBJECT}', "Contact Email", $html);
            $html = str_replace ('{MESSAGE}', "Test Message", $html);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['feedback'])) {
            
            $html = $config['email_sub_feedback'];
            $html = str_replace ('{FEEDBACK_SUBJECT}', "Feedback Email");
            $html = str_replace ('{EMAIL}', $test_to_email);
            $html = str_replace ('{NAME}', $test_to_name);
            $email_subject = $html;

            
            $html = $config['email_message_feedback'];
            $html = str_replace ('{EMAIL}', $test_to_email);
            $html = str_replace ('{NAME}', $test_to_name);
            $html = str_replace ('{PHONE}', "1234567890");
            $html = str_replace ('{FEEDBACK_SUBJECT}', "Feedback Email");
            $html = str_replace ('{MESSAGE}', "Test Message");
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['report'])) {
            
            $html = $config['email_sub_report'];
            $html = str_replace ('{EMAIL}', $test_to_email);
            $html = str_replace ('{NAME}', $test_to_name);
            $html = str_replace ('{USERNAME}', $test_to_name);
            $html = str_replace ('{VIOLATION}', __("Advertising another website"));
            $email_subject = $html;

            
            $html = $config['email_message_report'];
            $html = str_replace ('{EMAIL}', $test_to_email);
            $html = str_replace ('{NAME}', $test_to_name);
            $html = str_replace ('{USERNAME}', $test_to_name);
            $html = str_replace ('{USERNAME2}', "Violator Username");
            $html = str_replace ('{VIOLATION}', __("Advertising another website"));
            $html = str_replace ('{URL}', $link['POST-DETAIL']."/1");
            $html = str_replace ('{DETAILS}', "Violator Message details here");
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        $item_title = "Project Title";
        $ad_link = $link['POST-DETAIL']."/1";
        if (isset($_POST['ad_approve'])) {
            
            $html = $config['email_sub_ad_approve'];
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $email_subject = $html;

            
            $html = $config['email_message_ad_approve'];;
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['re_ad_approve'])) {
            
            $html = $config['email_sub_re_ad_approve'];
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $email_subject = $html;

            
            $html = $config['email_message_re_ad_approve'];;
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $email_body = $html;
            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['contact_to_seller'])) {
            
            $html = $config['email_sub_contact_seller'];
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $html = str_replace ('{SENDER_NAME}', "Sender Name");
            $html = str_replace ('{SENDER_EMAIL}', "sender@gmail.com");
            $html = str_replace ('{SENDER_PHONE}', "1234567890");
            $email_subject = $html;

            
            $html = $config['email_message_contact_seller'];;
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{SELLER_NAME}', $test_to_name);
            $html = str_replace ('{SELLER_EMAIL}', $test_to_email);
            $html = str_replace ('{SENDER_NAME}', "Sender Name");
            $html = str_replace ('{SENDER_EMAIL}', "sender@gmail.com");
            $html = str_replace ('{SENDER_PHONE}', "1234567890");
            $html = str_replace ('{MESSAGE}', "Test Message : I want to inquiry about your classified.");
            $email_body = $html;
            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }

        if (isset($_POST['job_newsletter_email'])) {
            $ad_id = 1;
            
            $html = $config['email_sub_post_notification'];
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{ADID}', $ad_id);
            $email_subject = $html;

            
            $html = $config['email_message_post_notification'];;
            $html = str_replace ('{ADTITLE}', $item_title);
            $html = str_replace ('{ADLINK}', $ad_link);
            $html = str_replace ('{ADID}', $ad_id);
            $email_body = $html;

            email($test_to_email,$test_to_name,$email_subject,$email_body);
        }



        $status = "success";
        $message = __("Email Sent Successfully");
    }else{
        $status = "Error";
        $message = __("Error: Please try again.");
    }

    echo $json = '{"status" : "' . $status . '","message" : "' . $message . '"}';
    die();
}
?>
