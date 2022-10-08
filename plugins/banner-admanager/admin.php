<?php
if (isset($_GET['debug'])) error_reporting(1);
else error_reporting(1);
include_once(dirname(__FILE__).'/inc/config.php');
include_once(dirname(__FILE__).'/inc/settings.php');
include_once(dirname(__FILE__).'/inc/icdb.php');
include_once(dirname(__FILE__).'/inc/functions.php');
$icdb = new ICDB(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, TABLE_PREFIX);

install();

function headerRedirectScript($url){
    echo "<script>window.location.href='".$url."';</script>";
}

$is_logged = true;


get_options();

$url_base = ((empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == 'off') ? 'http://' : 'https://').$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$filename = basename(__FILE__);
if (($pos = strpos($url_base, $filename)) !== false) $url_base = substr($url_base, 0, $pos);

if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']);
} else $error_message = '';
if (isset($_SESSION['ok'])) {
    $ok_message = $_SESSION['ok'];
    unset($_SESSION['ok']);
} else $ok_message = '';

$pages = array (
    'settings' => array('title' => 'Settings', 'menu' => true),
    'types' => array('title' => 'Banner Types', 'menu' => true),
    'banners' => array('title' => 'Banners', 'menu' => true),
    'transactions' => array('title' => 'Transactions', 'menu' => true),
    'edit' => array('title' => 'Add/Edit Banner', 'menu' => false),
    'edittype' => array('title' => 'Add/Edit Banner Type', 'menu' => false)
);
$deafult_page = 'types';
if ($is_logged) {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {

            case 'update-options':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=settings');
                    exit;
                }
                if (isset($_POST['action']) && $_POST['action'] == 'update-options') {
                    populate_options();

                    $errors = check_options();

                    update_options();
                    if (is_array($errors)) {
                        $_SESSION['error'] = 'The following error(s) exists:<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
                    } else {
                        $_SESSION['ok'] = 'Settings successfully saved!';
                    }
                }
                headerRedirectScript('banner-ad-manage.php?page=settings');
                exit;
                break;

            case 'update-type':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                if (isset($_POST['action']) && $_POST['action'] == 'update-type') {
                    unset($id);
                    if (isset($_POST["id"]) && !empty($_POST["id"])) {
                        echo $_POST["id"];
                        $id = intval($_POST["id"]);
                        $type_details = $icdb->get_row("SELECT t1.*, t2.total_banners, t2.total_amount FROM ".$icdb->prefix."types t1 LEFT JOIN (SELECT type_id, SUM(amount) AS total_amount, COUNT(*) AS total_banners FROM ".$icdb->prefix."banners WHERE status != '".STATUS_DRAFT."' AND deleted = '0' GROUP BY type_id) t2 ON t2.type_id = t1.id WHERE t1.id = '".$id."' AND t1.deleted = '0'");
                        if (!$type_details) unset($id);
                    }

                    $title = trim($_POST['title']);
                    $preview_url = trim($_POST["preview_url"]);
                    $price = floatval(trim($_POST["price"]));
                    $type = intval(trim($_POST["type"]));
                    $slots = intval(trim($_POST["slots"]));
                    if ($type != 0) {
                        foreach($types as $type_tmp) {
                            if ($type_tmp["id"] == $type) {
                                $width = $type_tmp["width"];
                                $height = $type_tmp["height"];
                                break;
                            }
                        }
                    } else {
                        $width = intval(trim($_POST["width"]));
                        $height = intval(trim($_POST["height"]));
                    }
                    $title = stripslashes($title);
                    $preview_url = stripslashes($preview_url);
                    $error = array();

                    if (empty($title)) $errors[] = 'Title is too short';
                    else if (strlen($title) > 128) $errors[] = 'Title is too long';
                    if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $preview_url) && strlen($preview_url) > 0) $errors[] = 'Preview url must be valid URL';
                    if ($price <= 0) $errors[] = 'Price must be higher then zero';
                    if ($slots <= 0) $errors[] = 'Number of slots must be higher then zero';
                    if ($width < 20) $errors[] = 'Width must be 20 or higher';
                    if ($height < 20) $errors[] = 'Height must be 20 or higher';
                    if (!empty($id) && $type_details["total_banners"] > 0 && ($width != $type_details["width"] || $height != $type_details["height"])) $errors[] = 'Banner size must be the same, because you have banners of this type';

                    if (!empty($errors)) {
                        $_SESSION['error'] = 'The following error(s) exists:<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
                        $_SESSION['title'] = $title;
                        $_SESSION['price'] = $price;
                        $_SESSION['preview_url'] = $preview_url;
                        $_SESSION['width'] = $width;
                        $_SESSION['height'] = $height;
                        $_SESSION['slots'] = $slots;
                        headerRedirectScript('banner-ad-manage.php?page=edittype'.(empty($id) ? '' : '&id='.$id));
                        exit;
                    } else {
                        if (!empty($id)) {
                            $icdb->query("UPDATE ".$icdb->prefix."types SET 
								title = '".mysqli_real_escape_string($icdb->link,$title)."', 
								price = '".number_format(floatval($price), 2, ".", "")."', 
								preview_url = '".mysqli_real_escape_string($icdb->link,$preview_url)."', 
								width = '".$width."', 
								height = '".$height."', 
								slots = '".$slots."'
								WHERE id = '".$id."'");
                            $_SESSION['ok'] = 'Banner Type details successfully updated!';
                        } else {
                            $icdb->query("INSERT INTO ".$icdb->prefix."types 
							(title, price, preview_url, width, height, slots, status, details, registered, deleted) VALUES (
							'".mysqli_real_escape_string($icdb->link,$title)."', 
							'".number_format(floatval($price), 2, ".", "")."', 
							'".mysqli_real_escape_string($icdb->link,$preview_url)."', 
							'".$width."', 
							'".$height."', 
							'".$slots."', 
							'".STATUS_ACTIVE."', '', '".time()."', '0')");
                            $_SESSION['ok'] = 'New Banner Type successfully added!';
                        }
                    }
                }
                headerRedirectScript('banner-ad-manage.php?page=types');
                exit;
                break;

            case 'delete-type':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $id = intval($_GET["id"]);
                $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$id."' AND deleted = '0'");
                if (intval($type_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."types SET deleted = '1' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Record successfully deleted!';
                } else {
                    $_SESSION['error'] = 'Record can not be deleted!';
                }
                headerRedirectScript('banner-ad-manage.php?page=types');
                exit;
                break;

            case 'delete-all-types':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."types SET deleted = '1' WHERE deleted != '1'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Records successfully deleted!';
                } else {
                    $_SESSION['error'] = 'Records can not be deleted!';
                }
                headerRedirectScript('banner-ad-manage.php?page=types');
                exit;
                break;

            case 'block-type':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $id = intval($_GET["id"]);
                $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$id."' AND deleted = '0'");
                if (intval($type_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."types SET status = '".STATUS_PENDING."' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Banner Type successfully blocked!';
                } else {
                    $_SESSION['error'] = 'Banner Type can not be blocked!';
                }
                headerRedirectScript('banner-ad-manage.php?page=types');
                exit;
                break;

            case 'unblock-type':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $id = intval($_GET["id"]);
                $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$id."' AND deleted = '0'");
                if (intval($type_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=types');
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."types SET status = '".STATUS_ACTIVE."' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Banner Type successfully unblocked!';
                } else {
                    $_SESSION['error'] = 'Banner Type can not be unblocked!';
                }
                headerRedirectScript('banner-ad-manage.php?page=types');
                exit;
                break;

            case 'update-banner':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=banners');
                    exit;
                }
                if (isset($_POST['action']) && $_POST['action'] == 'update-banner') {
                    if (isset($_POST["cid"])) $cid = intval(trim(stripslashes($_POST["cid"])));
                    else $cid = 0;
                    unset($id);
                    if (isset($_POST["id"]) && !empty($_POST["id"])) {
                        $id = intval($_POST["id"]);
                        $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id = '".$id."' AND deleted = '0'");
                        if (!$banner_details) unset($id);
                    }

                    $type_id = intval($_POST['type_id']);
                    $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$type_id."' AND deleted = '0'");
                    if (!$type_details) {
                        if (isset($id)) {
                            $type_id = $banner_details['type_id'];
                        } else {
                            $_SESSION['error'] = 'Banner Type not found!';
                            headerRedirectScript('banner-ad-manage.php?page=edit'.($cid == 0 ? '' : '&cid='.$cid));
                            exit;
                        }
                    }

                    $title = trim($_POST['title']);
                    $email = trim($_POST['email']);
                    $url = trim($_POST['url']);
                    $days_purchased = trim($_POST['days_purchased']);
                    $catid = ($_POST['category'] != '0')? trim($_POST['category']) : 0;
                    if(isset($_POST['sub_category'])){
                        $subcatid = ($_POST['sub_category'] != '0')? trim($_POST['sub_category']) : 0;
                    }else{
                        $subcatid = 0;
                    }

                    $country = ($_POST['country'] != '0')? trim($_POST['country']) : '';
                    $state = ($_POST['state'] != '0')? trim($_POST['state']) : '';
                    $city = ($_POST['city'] != '0')? trim($_POST['city']) : '';

                    $title = stripslashes($title);
                    $email = stripslashes($email);
                    $url = stripslashes($url);
                    $days_purchased = stripslashes($days_purchased);

                    $error = array();
                    if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email) && strlen($email) > 0) $errors[] = 'E-mail must be valid e-mail address';
                    if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) && strlen($url) > 0) $errors[] = 'URL must be valid URL';
                    if (!is_numeric($days_purchased) || intval($days_purchased) <= 0) $errors[] = 'Invalid rotation period';

                    if (isset($_POST['filename'])) $file = trim($_POST['filename']);
                    else if (!empty($id)) $file = $banner_details["file"];
                    else $file = '';
                    if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
                        $ext = "";
                        if (($pos = strrpos($_FILES["file"]["name"], ".")) !== false) {
                            $ext = strtolower(substr($_FILES["file"]["name"], $pos));
                        }
                        if ($ext != ".jpg" && $ext != ".jpeg" && $ext != ".gif" && $ext != ".png") $errors[] = 'Banner image must be JPEG, GIF or PNG file';
                        else {
                            list($width, $height, $imagetype, $attr) = getimagesize($_FILES["file"]["tmp_name"]);
                            if ($width != $type_details["width"] || $height != $type_details["height"]) $errors[] = 'Banner image size must be '.$type_details["width"].'x'.$type_details["height"];
                            else {
                                $file = "banner_".random_string(16).$ext;
                                if (!move_uploaded_file($_FILES["file"]["tmp_name"], ABSPATH."/files/".$file)) {
                                    $errors[] = 'Can not save uploaded banner image';
                                    $file = "";
                                } else {
                                    if (!empty($banner_details["file"]) && empty($errors)) {
                                        if (file_exists(ABSPATH."/files/".$banner_details["file"]) && is_file(ABSPATH."/files/".$banner_details["file"]))
                                            unlink(ABSPATH."/files/".$banner_details["file"]);
                                    }
                                }
                            }
                        }
                    } else if (!file_exists(ABSPATH."/files/".$file) || !is_file(ABSPATH."/files/".$file)) $errors[] = 'Banner image must be uploaded';

                    if (!empty($errors)) {
                        $_SESSION['error'] = 'The following error(s) exists:<ul><li>'.implode('</li><li>', $errors).'</li></ul>';
                        $_SESSION['title'] = $title;
                        $_SESSION['email'] = $email;
                        $_SESSION['url'] = $url;
                        $_SESSION['days_purchased'] = $days_purchased;
                        $_SESSION['catid'] = $catid;
                        $_SESSION['subcatid'] = $subcatid;
                        $_SESSION['country'] = $country;
                        $_SESSION['state'] = $state;
                        $_SESSION['city'] = $city;
                        $_SESSION['type_id'] = $type_id;
                        $_SESSION['file'] = $file;
                        headerRedirectScript('banner-ad-manage.php?page=edit'.(empty($id) ? '' : '&id='.$id).($cid == 0 ? '' : '&cid='.$cid));
                        exit;
                    } else {
                        if (!empty($id)) {
                            $icdb->query("UPDATE ".$icdb->prefix."banners SET 
								type_id = '".$type_id."', 
								title = '".mysqli_real_escape_string($icdb->link,$title)."', 
								email = '".mysqli_real_escape_string($icdb->link,$email)."', 
								url = '".mysqli_real_escape_string($icdb->link,$url)."', 
								days_purchased = '".intval($days_purchased)."', 
								cat_id = '".mysqli_real_escape_string($icdb->link,$catid)."', 
								sub_cat_id = '".mysqli_real_escape_string($icdb->link,$subcatid)."', 
								country = '".mysqli_real_escape_string($icdb->link,$country)."', 
								state = '".mysqli_real_escape_string($icdb->link,$state)."', 
								city = '".mysqli_real_escape_string($icdb->link,$city)."', 
								file = '".mysqli_real_escape_string($icdb->link,$file)."' 
								WHERE id = '".$id."'");
                            $_SESSION['ok'] = 'Banner details successfully updated!';
                        } else {
                            $icdb->query("INSERT INTO ".$icdb->prefix."banners (
								type_id,
								title,
								email,
								url,
								file,
								days_purchased,
								amount,
								currency,
								cat_id,
								sub_cat_id,
								country,
								state,
								city,
								shows_displayed,
								clicks,
								id_str,
								status,
								details,
								echo,
								registered,
								blocked,
								deleted
								) VALUES (
								'".$type_id."', 
								'".mysqli_real_escape_string($icdb->link,$title)."', 
								'".mysqli_real_escape_string($icdb->link,$email)."', 
								'".mysqli_real_escape_string($icdb->link,$url)."', 
								'".mysqli_real_escape_string($icdb->link,$file)."',
								'".mysqli_real_escape_string($icdb->link,$days_purchased)."',
								'0.00', 'USD',
								'".mysqli_real_escape_string($icdb->link,$catid)."', 
								'".mysqli_real_escape_string($icdb->link,$subcatid)."', 
								'".mysqli_real_escape_string($icdb->link,$country)."', 
								'".mysqli_real_escape_string($icdb->link,$state)."', 
								'".mysqli_real_escape_string($icdb->link,$city)."', 
								 '0', '0', '".random_string(16)."',
								'".STATUS_ACTIVE."', '', '', '".time()."', '0', '0')");
                            $_SESSION['ok'] = 'New banner successfully added!';
                        }
                    }
                }
                headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$type_id));
                exit;
                break;

            case 'delete-banner':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=banners');
                    exit;
                }
                if (isset($_GET["cid"])) $cid = intval($_GET["cid"]);
                else $cid = 0;
                $id = intval($_GET["id"]);
                $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id = '".$id."' AND deleted = '0'");
                if (intval($banner_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."banners SET deleted = '1' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Record successfully deleted!';
                    if ($banner_details["blocked"] == $banner_details["registered"] && !empty($banner_details["email"]) && $options['enable_approval'] == 'on') {
                        $tags = array("{banner_title}");
                        $vals = array($banner_details["title"]);
                        $body = str_replace($tags, $vals, $options['rejected_email_body']);
                        $mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
                        $mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
                        $mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
                        mail($banner_details["email"], $banner_details["title"], $body, $mail_headers);
                        $_SESSION['ok'] = 'Record successfully deleted and advertiser notified about rejection!';
                    }
                } else {
                    $_SESSION['error'] = 'Record can not be deleted!';
                }
                headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                exit;
                break;

            case 'delete-all-banners':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=banners');
                    exit;
                }
                if (isset($_GET["cid"])) $cid = intval($_GET["cid"]);
                else $cid = 0;
                $sql = "UPDATE ".$icdb->prefix."banners SET deleted = '1' WHERE deleted != '1'".($cid == 0 ? '' : " AND type_id = '".$cid."'");
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Records successfully deleted!';
                } else {
                    $_SESSION['error'] = 'Records can not be deleted!';
                }
                headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                exit;
                break;

            case 'block-banner':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=banners');
                    exit;
                }
                if (isset($_GET["cid"])) $cid = intval($_GET["cid"]);
                else $cid = 0;
                $id = intval($_GET["id"]);
                $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id = '".$id."' AND deleted = '0'");
                if (intval($banner_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                    exit;
                }
                if ($banner_details['status'] != STATUS_ACTIVE) {
                    $_SESSION['error'] = 'Banner can not be blocked!';
                    headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."banners SET status = '".STATUS_PENDING."', blocked = '".time()."' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Banner successfully blocked!';
                } else {
                    $_SESSION['error'] = 'Banner can not be blocked!';
                }
                headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                exit;
                break;

            case 'unblock-banner':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=banners');
                    exit;
                }
                if (isset($_GET["cid"])) $cid = intval($_GET["cid"]);
                else $cid = 0;
                $id = intval($_GET["id"]);
                $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id = '".$id."' AND deleted = '0'");
                if (intval($banner_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                    exit;
                }
                if ($banner_details['status'] != STATUS_PENDING) {
                    $_SESSION['error'] = 'Banner is already unblocked!';
                    headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                    exit;
                }
                if (intval($banner_details["blocked"]) >= $banner_details["registered"]) {
                    $registered = time() - $banner_details["blocked"] + $banner_details["registered"];
                } else $registered = $banner_details["registered"];

                $sql = "UPDATE ".$icdb->prefix."banners SET status = '".STATUS_ACTIVE."', registered = '".$registered."' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Banner successfully unblocked!';
                    if ($banner_details["blocked"] == $banner_details["registered"] && !empty($banner_details["email"]) && $options['enable_approval'] == 'on') {
                        $tags = array("{banner_title}");
                        $vals = array($banner_details["title"]);
                        $body = str_replace($tags, $vals, $options['approved_email_body']);
                        $mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
                        $mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
                        $mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
                        mail($banner_details["email"], $banner_details["title"], $body, $mail_headers);
                        $_SESSION['ok'] = 'Banner successfully unblocked and advertiser notified about approval!';
                    }
                } else {
                    $_SESSION['error'] = 'Banner can not be unblocked!';
                }
                headerRedirectScript('banner-ad-manage.php?page=banners'.($cid == 0 ? '' : '&cid='.$cid));
                exit;
                break;

            case 'delete-transaction':
                if (DEMO_MODE) {
                    $_SESSION['error'] = '<strong>Demo mode.</strong> This operation is disabled.';
                    headerRedirectScript('banner-ad-manage.php?page=transactions');
                    exit;
                }
                $id = intval($_GET["id"]);
                $transaction_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."transactions WHERE id = '".$id."' AND deleted = '0'");
                if (intval($transaction_details["id"]) == 0) {
                    $_SESSION['error'] = 'Record not found!';
                    headerRedirectScript('banner-ad-manage.php?page=transactions');
                    exit;
                }
                $sql = "UPDATE ".$icdb->prefix."transactions SET deleted = '1' WHERE id = '".$id."'";
                if ($icdb->query($sql) !== false) {
                    $_SESSION['ok'] = 'Record successfully deleted!';
                } else {
                    $_SESSION['error'] = 'Record can not be deleted!';
                }
                headerRedirectScript('banner-ad-manage.php?page=transactions');
                exit;
                break;

            default:
                break;
        }
        headerRedirectScript('banner-ad-manage.php');
        exit;
    }
    if (isset($_GET['page'])) {
        $page = preg_replace('/[^a-zA-Z0-9]/', '', $_GET['page']);
        if (!array_key_exists($_GET['page'], $pages)) $page = $deafult_page;
    } else $page = $deafult_page;
}
?>
?>
<!-- Page JS Plugins CSS -->
<script src="assets/js/core/jquery.min.js"></script>
<main class="app-layout-content">

    <!-- Page Content -->
    <div class="container-fluid">
        <link href="../plugins/banner-admanager/css/style.css" rel="stylesheet">
        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <div class="container">
                    <ul id="main-menu" class="nav navbar-nav navbar-left">
                        <ul id="main-menu" class="nav navbar-nav navbar-left">
                            <li><a href="advertising.php">Embedding in theme</a></li>
                            <li><a href="banner-ad-manage.php?page=types">Banner Types</a></li>
                            <li><a href="banner-ad-manage.php?page=banners">Banners</a></li>
                            <li><a href="banner-ad-manage.php?page=transactions">Transactions</a></li>
                            <li><a href="banner-ad-manage.php?page=settings">Settings</a></li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Partial Table -->
        <div class="card">
            <div class="card-header">
                <h4><?php echo (array_key_exists($page, $pages) ? $pages[$page]['title'] : '').' - '; ?> Banner Manager</h4>
            </div>
            <div class="card-block">
                <?php
                if ($page == 'settings') {
                    if (empty($error_message)) {
                        $errors = check_options();
                        if (is_array($errors)) $message = '<div class="alert alert-danger">The following error(s) exists:<ul><li>'.implode('</li><li>', $errors).'</li></ul></div>';
                        else if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                        else if (DEMO_MODE) $message = '<div class="alert alert-warning"><strong>Demo mode.</strong> Real e-mails and payment details are hidden.</div>';
                        else $message = '';
                    } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                    echo $message;
                    if (DEMO_MODE) {
                        $hidden_options = array (
                            "owner_email" => "<hidden>",
                            "from_email" => "<hidden>"
                        );
                        $options = array_merge($options, $hidden_options);
                    }
                    ?>


                    <div class="row">

                        <div class="col-md-12">
                            <form enctype="multipart/form-data" method="post" action="banner-ad-manage.php?action=update-options" class="fieldset">
                                <div class="card">
                                    <div class="card-block tab-content">
                                        <div class="tab-pane fade in active" id="setting-tab1">
                                            <h4 class="m-t-sm m-b">General info</h4>
                                            <div class="form-group">
                                                <label for="owner_email">E-mail for notifications</label>
                                                <input type="text" class="form-control" id="owner_email" name="owner_email" value="<?php echo htmlspecialchars($options['owner_email'], ENT_QUOTES); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="from_name">Sender name</label>
                                                <input type="text" class="form-control" id="from_name" name="from_name" value="<?php echo htmlspecialchars($options['from_name'], ENT_QUOTES); ?>">
                                                <small>Please enter sender name. All messages to advertisers are sent using this name as "FROM:" header value.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="from_email">Sender e-mail</label>
                                                <input type="text" class="form-control" id="from_email" name="from_email" value="<?php echo htmlspecialchars($options['from_email'], ENT_QUOTES); ?>">
                                                <small>Please enter sender e-mail. All messages to advertisers are sent using this e-mail as "FROM:" header value. It is recommended to set existing e-mail address.</small>
                                            </div>
                                            <div class="form-group">
                                                <div class="span3"><strong>Thanksgiving e-mail subject:</strong></div>
                                                <input type="text" class="form-control" id="success_email_subject" name="success_email_subject" value="<?php echo htmlspecialchars($options['success_email_subject'], ENT_QUOTES); ?>">
                                                <small>All advertisers receive thanksgiving e-mail message. This is subject field of the message.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="success_email_body">Thanksgiving e-mail body</label>
                                                <textarea class="form-control" rows="5" id="success_email_body" name="success_email_body"><?php echo htmlspecialchars($options['success_email_body'], ENT_QUOTES); ?></textarea>
                                                <small>Thanksgiving e-mail message. You can use the following keywords: {payer_name}, {payer_email}, {amount}, {currency}, {banner_title}.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="failed_email_subject">Failed e-mail subject</label>
                                                <input type="text" class="form-control" id="failed_email_subject" name="failed_email_subject" value="<?php echo htmlspecialchars($options['failed_email_subject'], ENT_QUOTES); ?>">
                                                <small>In case of any problems with payment processing, advertisers receive failed e-mail message. This is subject field of the message.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="failed_email_body">Failed e-mail body</label>
                                                <textarea class="form-control" rows="5" id="failed_email_body" name="failed_email_body"><?php echo htmlspecialchars($options['failed_email_body'], ENT_QUOTES); ?></textarea>
                                                <small>Failed e-mail message. You can use the following keywords: {payer_name}, {payer_email}, {amount}, {currency}, {payment_status}.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="enable_approval">Enable Approval</label>
                                                <div class="checkbox">
                                                    <label for="enable_approval">
                                                        <input type="checkbox" id="enable_approval" name="enable_approval" <?php echo ($options['enable_approval'] == 'on' ? ' checked="checked"' : ''); ?>>  Enable Approval Mechanism
                                                    </label>
                                                </div>
                                                <small>Please tick checkbox if you would like to enable approval mechanism.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="approved_email_body">Approved e-mail body</label>
                                                <textarea class="form-control" rows="5" id="approved_email_body" name="approved_email_body"><?php echo htmlspecialchars($options['approved_email_body'], ENT_QUOTES); ?></textarea>
                                                <small>This message is sent when administrator approve (unblock) advertiser's banner. Approval mechanism must be enabled. You can use the following keywords: {banner_title}.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="rejected_email_body">Rejected e-mail body</label>
                                                <textarea class="form-control" rows="5" id="rejected_email_body" name="rejected_email_body"><?php echo htmlspecialchars($options['rejected_email_body'], ENT_QUOTES); ?></textarea>
                                                <small>This message is sent when administrator reject (delete) advertiser's banner. Approval mechanism must be enabled. You can use the following keywords: {banner_title}.</small>

                                            </div>
                                            <div class="form-group">
                                                <label for="stats_email_subject">Statistics e-mail subject</label>
                                                <input type="text" class="form-control" id="stats_email_subject" name="stats_email_subject" value="<?php echo htmlspecialchars($options['stats_email_subject'], ENT_QUOTES); ?>">
                                                <small>All advertisers receive e-mail message with statistics. This is subject field of the message.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="stats_email_body">Statistics e-mail body</label>
                                                <textarea class="form-control" rows="5" id="stats_email_body" name="stats_email_body"><?php echo htmlspecialchars($options['stats_email_body'], ENT_QUOTES); ?></textarea>
                                                <small>Statistics e-mail body. You can use the following keywords: {banner_title}, {statistics}, {signup_page}.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="minimum_days">Minimum days</label>
                                                <input type="text" class="form-control" id="minimum_days" name="minimum_days" value="<?php echo htmlspecialchars($options['minimum_days'], ENT_QUOTES); ?>">
                                                <small>Enter minimum number of days available to purchase.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="signup_page">Sign up page</label>
                                                <input type="text" class="form-control" id="signup_page" name="signup_page" value="<?php echo htmlspecialchars($options['signup_page'], ENT_QUOTES); ?>">
                                                <small>Please set URL of the page where you put advertiser sign up form shortcode &lt;div class="qbm-form"&gt;&lt;/div&gt;.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="intro">Intro text</label>
                                                <textarea class="form-control" rows="5" id="intro" name="intro"><?php echo htmlspecialchars($options['intro'], ENT_QUOTES); ?></textarea>
                                                <small>This text is shown above sign up form. Describe your service here. You can use the following keywords: {minimum_days}.</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="terms">Terms & Conditions</label>
                                                <textarea class="form-control" rows="5" id="terms" name="terms"><?php echo htmlspecialchars($options['terms'], ENT_QUOTES); ?></textarea>
                                                <small>Your customers must be agree with Terms & Conditions to publish their banners. Leave this field blank if you do not need Terms & Conditions box to be shown.</small>
                                            </div>
                                            <div class="form-group">
                                                <div class="span12">
                                                    <input type="hidden" name="action" value="update-options" />
                                                    <input type="hidden" name="version" value="<?php echo VERSION; ?>" />
                                                    <input type="submit" class="btn btn-primary pull-right" name="submit" value="Update Settings">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- .card-block .tab-content -->
                                </div>
                                <!-- .card -->
                            </form>
                        </div>
                        <!-- .col-md-8 -->
                    </div>

                <?php
                }
                else if ($page == 'types') {
                if (empty($error_message)) {
                    if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                    else $message = '';
                } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                echo $message;

                if (isset($_GET["s"])) $search_query = trim(stripslashes($_GET["s"]));
                else $search_query = "";

                $tmp = $icdb->get_row("SELECT COUNT(*) AS total FROM ".$icdb->prefix."types WHERE deleted = '0'".((strlen($search_query) > 0) ? " AND title LIKE '%".addslashes($search_query)."%'" : ""));
                $total = $tmp["total"];
                $totalpages = ceil($total/RECORDS_PER_PAGE);
                if ($totalpages == 0) $totalpages = 1;
                if (isset($_GET["p"])) $page = intval($_GET["p"]);
                else $page = 1;
                if ($page < 1 || $page > $totalpages) $page = 1;
                $switcher = page_switcher("banner-ad-manage.php?page=types".((strlen($search_query) > 0) ? "&s=".rawurlencode($search_query) : ""), $page, $totalpages);

                $sql = "SELECT t1.*, t2.total_banners, t2.total_amount FROM ".$icdb->prefix."types t1 LEFT JOIN (SELECT type_id, SUM(amount) AS total_amount, COUNT(*) AS total_banners FROM ".$icdb->prefix."banners WHERE status != '".STATUS_DRAFT."' && deleted = '0' GROUP BY type_id) t2 ON t2.type_id = t1.id WHERE t1.deleted = '0'".((strlen($search_query) > 0) ? " AND t1.title LIKE '%".addslashes($search_query)."%'" : "")." ORDER BY registered DESC LIMIT ".(($page-1)*RECORDS_PER_PAGE).", ".RECORDS_PER_PAGE;
                $rows = $icdb->get_rows($sql);
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="span12">
                                <div class="btn-group">
                                    <a class="btn btn-primary" href="banner-ad-manage.php?page=edittype">Add New Banner Type</a>
                                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="banner-ad-manage.php?page=edittype">Add New Banner Type</a></li>
                                        <?php echo (!$rows ? '<li class="disabled"><a>Delete All Banner Types</a></li>' : '<li><a href="banner-ad-manage.php?action=delete-all-types" onclick="return submitOperation();">Delete All Banner Types</a></li>'); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="banner-ad-manage.php" method="get" class="form-inline pull-right" style="margin-bottom: 10px;">
                                <input type="hidden" name="page" value="types" />
                                <input type="text" class="form-control" name="s" placeholder="Search" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                                <input type="submit" class="btn" value="Search" />
                                <?php echo (strlen($search_query) > 0 ? '<input type="button" class="btn" value="Reset search results" onclick="window.location.href=\'banner-ad-manage.php?page=types\';" />' : ''); ?>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <tr>
                            <th>Title</th>
                            <th>Shortcode</th>
                            <th style="width: 100px; text-align: right;">Size</th>
                            <th style="width: 100px; text-align: right;">Price</th>
                            <th style="width: 60px; text-align: right;">Slots</th>
                            <th style="width: 115px;"></th>
                        </tr>

                        <?php
                        if (sizeof($rows) > 0) {
                            foreach ($rows as $row) {
                                print ('
<tr'.($row['status'] == STATUS_PENDING ? ' class="error"' : '').'>
    <td>'.htmlspecialchars($row['title'], ENT_QUOTES).(!empty($row['preview_url']) ? '<br><small>Preview: <a href="'.$row['preview_url'].'" target="_blank">'.htmlspecialchars(cut_string($row['preview_url'], 30), ENT_QUOTES).'</a></small>' : '').'</td>
    <td><code>'.htmlspecialchars('<a class="quick-bm-banner" data-id="'.$row['id'].'"></a>', ENT_QUOTES).'</code></td>
    <td style="text-align: right;">'.$row['width'].' x '.$row['height'].'</td>
    <td style="text-align: right;">'.number_format($row['price'], 2, ".", "").' '.$options['currency'].'</td>
    <td style="text-align: right;">'.$row['slots'].'</td>
    <td style="text-align: center;">
        <div class="btn-group">
            <a href="banner-ad-manage.php?page=edittype&id='.$row['id'].'" title="Edit type details" class="btn btn-xs btn-default"><i class="ion-compose"></i></a>
            <a href="banner-ad-manage.php?page=banners&cid='.$row['id'].'" title="Banners" class="btn btn-xs btn-default"><i class="ion-ios-photos-outline"></i></a>
            '.($row["status"] == STATUS_ACTIVE ? '<a href="banner-ad-manage.php?action=block-type&id='.$row['id'].'" title="Block type" class="btn btn-xs btn-default"><i class="ion-android-close"></i></a>' : '').'
            '.($row["status"] == STATUS_PENDING ? '<a href="banner-ad-manage.php?action=unblock-type&id='.$row['id'].'" title="Approve" class="btn btn-xs btn-success"><i class="ion-android-done"></i></a>' : '').'
            <a href="banner-ad-manage.php?action=delete-type&id='.$row['id'].'" title="Delete record" onclick="return submitOperation();" class="btn btn-xs btn-default"><i class="ion-android-delete"></i></a>
        </div>
    </td>
</tr>');
                            }
                        } else {
                            print ('
				<tr><td colspan="6" style="padding: 20px; text-align: center;">'.((strlen($search_query) > 0) ? 'No results found for "<strong>'.htmlspecialchars($search_query, ENT_QUOTES).'</strong>"' : 'List is empty.').'</td></tr>');
                        }
                        ?>
                    </table>
                    <div class="row">
                        <div class="span6">
                            <div class="pull-left">
                                <?php echo $switcher; ?>
                                &nbsp;
                            </div>
                        </div>
                        <div class="span6">
                            <div class="btn-group pull-right">
                                <a class="btn btn-primary" href="banner-ad-manage.php?page=edittype">Add New Banner Type</a>
                                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a href="banner-ad-manage.php?page=edittype">Add New Banner Type</a></li>
                                    <?php echo (!$rows ? '<li class="disabled"><a>Delete All Banner Types</a></li>' : '<li><a href="banner-ad-manage.php?action=delete-all-types" onclick="return submitOperation();">Delete All Banner Types</a></li>'); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <hr>
                    <script type="text/javascript">
                        function submitOperation() {
                            var answer = confirm("Do you really want to continue?");
                            if (answer) return true;
                            else return false;
                        }
                    </script>
                <?php
                }
                else if ($page == 'edittype') {
                if (empty($error_message)) {
                    if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                    else $message = '';
                } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                echo $message;

                unset($id);
                if (isset($_GET["id"]) && !empty($_GET["id"])) {
                    $id = intval($_GET["id"]);
                    $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$id."' AND deleted = '0'");
                    if (!$type_details) unset($id);
                }
                $values = array();
                foreach (array('title', 'price', 'slots', 'preview_url', 'width', 'height') as $value) {
                    if (isset($_SESSION[$value])) {
                        $values[$value] = $_SESSION[$value];
                        unset($_SESSION[$value]);
                    } else if (!empty($id)) $values[$value] = $type_details[$value];
                    else $values[$value] = '';
                }
                ?>

                    <form enctype="multipart/form-data" method="post" action="banner-ad-manage.php?action=update-type" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-xs-12" for="title">Title</label>
                            <div class="col-xs-12">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?php echo htmlspecialchars($values['title'], ENT_QUOTES); ?>">
                                <small>Please enter banner type title.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12" for="type">Banner size</label>
                            <div class="col-xs-12">
                                <select class="input-mini" name="type" id="type" onchange="changesize();">
                                    <?php
                                    $selected = false;
                                    foreach($types as $type) {
                                        if ($values["width"] > 0 && $values["height"] > 0 && $values["width"] == $type["width"] && $values["height"] == $type["height"]) {
                                            $selected = true;
                                            print('<option value="'.$type["id"].'" selected="selected">'.$type["width"]." x ".$type["height"].'</option>');
                                        }else if ($type["id"] > 0) {
                                         print('<option value="'.$type["id"].'">'.$type["width"]." x ".$type["height"].'</option>');
                                        }else if ($values["width"] > 0 && $values["height"] > 0 && !$selected) {
                                            print ('<option value="0" selected="selected">Custom</option>');
                                        }else {
                                            print ('<option value="0">Custom</option>');
                                        }
                                    }
                                    ?>
                                </select>
                                <input type="text" class="input-mini" id="width" name="width" value="<?php echo (!empty($values['width']) ? htmlspecialchars($values['width'], ENT_QUOTES) : $types[0]["width"]); ?>"> x
                                <input type="text" class="input-mini" id="height" name="height" value="<?php echo (!empty($values['height']) ? htmlspecialchars($values['height'], ENT_QUOTES) : $types[0]["height"]); ?>">
                                <small>Select banner size. You can choose standard banner sizes or specify your custom size.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12" for="price">Price</label>
                            <div class="col-xs-12">
                                <input type="text" class="form-control" id="price" name="price" value="<?php echo number_format(floatval($values['price']), 2, ".", ""); ?>"> <?php echo $config['currency_code']; ?>
                                <small>Enter price for 10 days period for this banner type.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12" for="slots">Slots</label>
                            <div class="col-xs-12">
                                <input type="text" class="form-control" id="slots" name="slots" value="<?php echo (!empty($values['slots']) ? intval($values['slots']) : '1'); ?>">
                                <small>Enter number of available slots for this banner type.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12" for="preview_url">Preview URL</label>
                            <div class="col-xs-12">
                                <input type="text" class="form-control" id="preview_url" name="preview_url" value="<?php echo htmlspecialchars($values['preview_url'], ENT_QUOTES); ?>">
                                <small>Enter URL of any page which contains this banner type. It is used for "Live preview" functionality.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                <input type="hidden" name="action" value="update-type" />
                                <?php echo (empty($id) ? '' : '<input type="hidden" name="id" value="'.$id.'" />'); ?>
                                <input type="submit" class="btn btn-primary pull-right" name="submit" value="Update Details">
                            </div>
                        </div>
                    </form>

                    <script type="text/javascript">
                        function changesize() {
                            var type = jQuery("#type").val();
                            <?php
                            foreach($types as $type) {
                                if ($type["id"] == 0) {
                                    print ('
				if (type == "0") {
					jQuery("#width").removeAttr("disabled");
					jQuery("#height").removeAttr("disabled");
				}');
                                } else {
                                    print ('
				if (type == "'.$type["id"].'") {
					jQuery("#width").attr("disabled", "disabled");
					jQuery("#height").attr("disabled", "disabled");
					jQuery("#width").val("'.$type["width"].'");
					jQuery("#height").val("'.$type["height"].'");
				}');
                                }
                            }
                            ?>
                        }
                        changesize();
                    </script>
                <?php
                }
                else if ($page == 'banners') {
                if (empty($error_message)) {
                    if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                    else if (DEMO_MODE) $message = '<div class="alert alert-warning"><strong>Demo mode.</strong> Real e-mails are hidden.</div>';
                    else $message = '';
                } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                echo $message;

                if (isset($_GET["s"])) $search_query = trim(stripslashes($_GET["s"]));
                else $search_query = "";
                if (isset($_GET["cid"])) {
                    $type_id = intval($_GET["cid"]);
                    $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE deleted = '0' AND id = '".$type_id."'");
                    if (!$type_details) $type_id = 0;
                }
                else $type_id = 0;

                $tmp = $icdb->get_row("SELECT COUNT(*) AS total FROM ".$icdb->prefix."types WHERE deleted = '0'");
                $total_types = $tmp["total"];

                $tmp = $icdb->get_row("SELECT COUNT(*) AS total FROM ".$icdb->prefix."banners WHERE deleted = '0' AND status != '".STATUS_DRAFT."'".($type_id > 0 ? " AND type_id = '".$type_id."'" : "").((strlen($search_query) > 0) ? " AND (title LIKE '%".addslashes($search_query)."%' OR email LIKE '%".addslashes($search_query)."%')" : ""));
                $total = $tmp["total"];
                $totalpages = ceil($total/RECORDS_PER_PAGE);
                if ($totalpages == 0) $totalpages = 1;
                if (isset($_GET["p"])) $page = intval($_GET["p"]);
                else $page = 1;
                if ($page < 1 || $page > $totalpages) $page = 1;
                $switcher = page_switcher("banner-ad-manage.php?page=banners".($type_id > 0 ? "&cid=".$type_id : "").((strlen($search_query) > 0) ? "&s=".rawurlencode($search_query) : ""), $page, $totalpages);

                $sql = "SELECT t1.*, t2.title AS type_title, t2.deleted AS type_deleted, t2.width, t2.height, t2.preview_url FROM ".$icdb->prefix."banners t1 LEFT JOIN ".$icdb->prefix."types t2 ON t1.type_id = t2.id WHERE t1.deleted = '0' AND t1.status != '".STATUS_DRAFT."'".($type_id > 0 ? " AND t1.type_id = '".$type_id."'" : "").((strlen($search_query) > 0) ? " AND (t1.title LIKE '%".addslashes($search_query)."%' OR t1.email LIKE '%".addslashes($search_query)."%')" : "")." ORDER BY t1.registered DESC LIMIT ".(($page-1)*RECORDS_PER_PAGE).", ".RECORDS_PER_PAGE;
                $rows = $icdb->get_rows($sql);
                ?>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="span12">
                                <div class="btn-group">
                                    <?php echo ($total_types == 0 ? '<a class="btn btn-primary disabled" href="#" onclick="return false;">Add New Banner</a>' : '<a class="btn btn-primary" href="banner-ad-manage.php?page=edit'.($type_id > 0 ? '&cid='.$type_id : '').'">Add New Banner</a>'); ?>
                                    <button class="btn btn-primary dropdown-toggle<?php echo ($total_types == 0 ? ' disabled' : ''); ?>" data-toggle="dropdown"><span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <?php echo ($total_types == 0 ? '<li class="disabled"><a>Add New Banner</a></li>' : '<li><a href="banner-ad-manage.php?page=edit'.($type_id > 0 ? '&cid='.$type_id : '').'">Add New Banner</a></li>'); ?>
                                        <?php echo (!$rows ? '<li class="disabled"><a>Delete All Banners</a></li>' : '<li><a href="banner-ad-manage.php?action=delete-all-banners'.($type_id > 0 ? '&cid='.$type_id : '').'" onclick="return submitOperation();">Delete All Banners</a></li>'); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="banner-ad-manage.php" method="get" class="form-inline pull-right" style="margin-bottom: 10px;">
                                <input type="hidden" name="page" value="banners" />
                                <input type="text" class="form-control" name="s" placeholder="Search" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                                <?php echo ($type_id > 0 ? '<input type="hidden" name="cid" value="'.$type_id.'" />' : ''); ?>
                                <input type="submit" class="btn" value="Search" />
                                <?php echo (strlen($search_query) > 0 ? '<input type="button" class="btn" value="Reset search results" onclick="window.location.href=\'banner-ad-manage.php?page=banners'.($type_id > 0 ? "&cid=".$type_id : "").'\';" />' : ''); ?>
                            </form>
                        </div>
                    </div>

                    <table class="table table-striped">
                        <tr>
                            <th>Title</th>
                            <th>Banner Type</th>
                            <th>E-mail</th>
                            <th style="width: 60px; text-align: right;">Shows</th>
                            <th style="width: 60px; text-align: right;">Clicks</th>
                            <th style="width: 115px;"></th>
                        </tr>
                        <?php
                        if (sizeof($rows) > 0) {
                            foreach ($rows as $row) {
                                $email = $row['email'];
                                if (DEMO_MODE) {
                                    if (($pos = strpos($email, "@")) !== false) {
                                        $name = substr($email, 0, strpos($email, "@"));
                                        $email = substr($name, 0, 1).'*****'.substr($email, $pos);
                                    }
                                }
                                if ($row["status"] < STATUS_PENDING && $row["status"] > STATUS_DRAFT) {
                                    if (time() <= $row["registered"] + 24*3600*$row["days_purchased"]) $expired = 'Expires in '.period_to_string($row["registered"] + 24*3600*$row["days_purchased"] - time());
                                    else $expired = '<span class="label label-danger">Expired</span>';
                                } else $expired = "";
                                print ('
		<tr'.($row['status'] == STATUS_PENDING ? ' class="error"' : '').'>
		    <td>
				'.(!empty($row['url']) ? '<a href="'.$row['url'].'" target="_blank">' : '').(empty($row['title']) ? '-' : htmlspecialchars($row['title'], ENT_QUOTES)).(!empty($row['url']) ? '</a>' : '').'
				<br /><small>'.$expired.(!empty($row['preview_url']) ? '<br><a href="'.add_url_parameters($row["preview_url"], array("ubm_show" => $row["id_str"])).'" target="_blank"> Live preview </a>' : '').'</small>
			</td>
			<td>'.htmlspecialchars($row['type_title'], ENT_QUOTES).' ('.$row['width'].'x'.$row['height'].')'.($row['type_deleted'] ? ' <span class="label label-important">DEL</span>' : '').'</td>
			<td>'.htmlspecialchars($email, ENT_QUOTES).'</td>
			<td style="text-align: right;">'.intval($row["shows_displayed"]).'</td>
			<td style="text-align: right;">'.intval($row["clicks"]).'</td>
			<td style="text-align: center;">
                <div class="btn-group">
                    <a href="banner-ad-manage.php?page=edit&id='.$row['id'].($type_id > 0 ? '&cid='.$type_id : '').'" title="Edit banner details" class="btn btn-xs btn-default"> <i class="ion-compose"></i> </a>
             
                    <a href="banner-ad-manage.php?page=transactions&did='.$row['id'].'" title="Payment transactions" class="btn btn-xs btn-default"><i class="ion-arrow-graph-up-right"></i></a>
                    '.($row["status"] == STATUS_ACTIVE ? '<a href="banner-ad-manage.php?action=block-banner&id='.$row['id'].($type_id > 0 ? '&cid='.$type_id : '').'" title="Block banner" class="btn btn-xs btn-default"><i class="ion-android-close"></i></a>' : '').'
                    '.($row["status"] == STATUS_PENDING ? '<a href="banner-ad-manage.php?action=unblock-banner&id='.$row['id'].($type_id > 0 ? '&cid='.$type_id : '').'" title="Unblock banner" class="btn btn-xs btn-default"><i class="ion-android-done"></i></a>' : '').'
                    <a href="banner-ad-manage.php?action=delete-banner&id='.$row['id'].($type_id > 0 ? '&cid='.$type_id : '').'" title="Delete record" class="btn btn-xs btn-default" onclick="return submitOperation();"><i class="ion-android-delete"></i></a>
				</div>
			</td>
		</tr>');
                            }
                        } else {
                            print ('
				<tr><td colspan="6" style="padding: 20px; text-align: center;">'.((strlen($search_query) > 0) ? 'No results found for "<strong>'.htmlspecialchars($search_query, ENT_QUOTES).'</strong>"' : 'List is empty.').'</td></tr>');
                        }
                        ?>
                    </table>
                    <div class="row">
                        <div class="span6">
                            <div class="pull-left">
                                <?php echo $switcher; ?>
                                &nbsp;
                            </div>
                        </div>
                        <div class="span6">
                            <div class="btn-group pull-right">
                                <?php echo ($total_types == 0 ? '<a class="btn btn-primary disabled" href="#" onclick="return false;">Add New Banner</a>' : '<a class="btn btn-primary" href="banner-ad-manage.php?page=edit'.($type_id > 0 ? '&cid='.$type_id : '').'">Add New Banner</a>'); ?>
                                <button class="btn btn-primary dropdown-toggle<?php echo ($total_types == 0 ? ' disabled' : ''); ?>" data-toggle="dropdown"><span class="caret"></span></button>
                                <ul class="dropdown-menu">
                                    <?php echo ($total_types == 0 ? '<li class="disabled"><a>Add New Banner</a></li>' : '<li><a href="banner-ad-manage.php?page=edit'.($type_id > 0 ? '&cid='.$type_id : '').'">Add New Banner</a></li>'); ?>
                                    <?php echo (!$rows ? '<li class="disabled"><a>Delete All Banners</a></li>' : '<li><a href="banner-ad-manage.php?action=delete-all-banners'.($type_id > 0 ? '&cid='.$type_id : '').'" onclick="return submitOperation();">Delete All Banners</a></li>'); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <hr>
                    <script type="text/javascript">
                        function submitOperation() {
                            var answer = confirm("Do you really want to continue?");
                            if (answer) return true;
                            else return false;
                        }
                    </script>
                <?php
                }
                else if ($page == 'edit') {
                if (empty($error_message)) {
                    if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                    else if (DEMO_MODE) $message = '<div class="alert alert-warning"><strong>Demo mode.</strong> Real e-mail is hidden.</div>';
                    else $message = '';
                } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                echo $message;

                $types = $icdb->get_rows("SELECT * FROM ".$icdb->prefix."types WHERE deleted = '0'");

                if (isset($_GET["cid"])) $type_id = intval($_GET["cid"]);
                else $type_id = 0;

                unset($id);
                if (isset($_GET["id"]) && !empty($_GET["id"])) {
                    $id = intval($_GET["id"]);
                    $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id = '".$id."' AND deleted = '0'");
                    if (!$banner_details) unset($id);
                }
                $values = array();
                foreach (array('title', 'email', 'url', 'days_purchased', 'cat_id', 'sub_cat_id', 'country', 'state', 'city', 'type_id', 'file') as $value) {
                    if (isset($_SESSION[$value])) {
                        $values[$value] = $_SESSION[$value];
                        unset($_SESSION[$value]);
                    } else if (!empty($id)) $values[$value] = $banner_details[$value];
                    else $values[$value] = '';
                }
                if ($values['type_id'] == '') $values['type_id'] = $type_id;

                if (DEMO_MODE) {
                    if (($pos = strpos($values['email'], "@")) !== false) {
                        $nickname = substr($values['email'], 0, strpos($values['email'], "@"));
                        $values['email'] = substr($nickname, 0, 1).'*****'.substr($values['email'], $pos);
                    }
                }

                ?>
                    <form enctype="multipart/form-data" method="post" action="banner-ad-manage.php?action=update-banner" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="type_id">Banner type:</label>
                            <div class="col-sm-9">
                                <select id="type_id" name="type_id" class="form-control" onchange="changecurrency();">
                                    <?php
                                    if (sizeof($types) > 0) {
                                        foreach ($types as $type) {
                                            echo '<option value="'.$type['id'].'"'.($type['id'] == $values['type_id'] ? ' selected="selected"' : '').'>'.htmlspecialchars($type['title'], ENT_QUOTES).' ('.$type["width"].'x'.$type["height"].' | '.number_format($type["price"], 2, ".", "").' '.$options['currency'].' per 10 days)</option>';
                                        }
                                    } else {
                                        echo '<option value="0">No types found</option>';
                                    }
                                    ?>
                                </select>
                                <?php echo (sizeof($types) > 0 ? '<small>Select banner type.</small>' : '<span class="label label-important">Please create at least one banner type.</span>'); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="category">Category</label>
                            <div class="col-sm-9">
                                <select name="category" id="category" class="form-control getsubcatToCatid" data-ajax-action="getsubcatbyid" data-catid="<?php echo $values["cat_id"] ?>" data-selectid="<?php echo $values["sub_cat_id"] ?>"  data-placeholder="Select a Category">
                                    <option value="0">Any Category</option>
                                    <?php
                                    $cat =  get_maincategory($values["cat_id"]);
                                    foreach($cat as $option){
                                        echo '<option value="'.$option['id'].'" '.$option['selected'].'>'.$option['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="sub_category">SubCategory</label>
                            <div class="col-sm-9">
                                <select name="sub_category" id="sub_category" class="form-control" data-placeholder="Any Subcategory">
                                    <option value="0">Any Subcategory</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="country">Country</label>
                            <div class="col-sm-9">
                                <select name="country" class="form-control getcountryToState" id="country" data-ajax-action="getStateByCountryID" data-countryid="<?php echo $values["country"] ?>" data-selectid="<?php echo $values["state"] ?>"  data-placeholder="Any country">
                                    <option value="0"> Any Country</option>
                                    <!-- Required for data-placeholder attribute to work with Chosen plugin -->
                                    <?php

                                    $country = get_country_list($values["country"]);
                                    foreach ($country as $value){
                                        echo '<option value="'.$value['code'].'" '.$value['selected'].'>'.$value['asciiname'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="state">State/region</label>
                            <div class="col-sm-9">
                                <select name="state" id="state" class="form-control getstateToCity" data-ajax-action="getCityByStateID" data-stateid="<?php echo $values["state"] ?>" data-selectid="<?php echo $values["city"] ?>" data-placeholder="Any region">
                                    <option value="<?php echo $values["state"] ?>" selected><?php echo get_stateName_by_id($values["state"]) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="city">City</label>
                            <div class="col-sm-9">
                                <select name="city" id="city" class="form-control js-select2" data-placeholder="Any city">
                                    <option value="<?php echo $values["city"] ?>" selected><?php echo get_cityName_by_id($values["city"]) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="title">Title</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($values['title'], ENT_QUOTES); ?>">
                                <small>Please enter banner title.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="url">URL</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="url" name="url" value="<?php echo htmlspecialchars($values['url'], ENT_QUOTES); ?>">
                                <small>Please enter banner URL. The banner will be hyperlinked with this URL.</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="file">Banner image</label>
                            <div class="col-sm-9">
                                <?php echo (!empty($values["file"]) && file_exists(ABSPATH.'/files/'.$values["file"]) ? '<img src="'.$config['site_url'].'plugins/banner-admanager/files/'.rawurlencode($values["file"]).'" style="margin-bottom:10px">
                                <input type="hidden" name="filename" value="'.rawurlencode($values["file"]).'"><br />' : ''); ?>
                                <input type="file" name="file" id="file" class="form-control">
                                <small>Please select banner image. You can use JPEG, GIF and PNG images. The size of image must be exactly the same as specified in banner type.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="days_purchased">Display period in days</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="days_purchased" name="days_purchased" value="<?php echo ($values['days_purchased'] == 0 ? intval($options['minimum_days']) : intval($values['days_purchased'])); ?>">
                                <small>Please enter period of rotation. How many days banner will be display.</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="email">E-Mail</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($values['email'], ENT_QUOTES); ?>">
                                <small>Enter user's e-mail. It is used to send statistics to user.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span12">
                                <input type="hidden" name="action" value="update-banner" />
                                <?php echo (empty($id) ? '' : '<input type="hidden" name="id" value="'.$id.'" />'); ?>
                                <?php echo ($type_id == 0 ? '' : '<input type="hidden" name="cid" value="'.$type_id.'" />'); ?>
                                <input type="submit" class="btn btn-primary pull-right" name="submit" value="Update Details">
                            </div>
                        </div>
                    </form>

                    <script type="text/javascript">

                        $("#country").change(function () {
                            var id = $(this).val();
                            var action = $(this).data('ajax-action');
                            var data = {action: action, id: id};
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: data,
                                success: function (result) {
                                    $("#state").html('<option value=""> Any state</option>'+result);
                                    //$("#state").select2();
                                    $("#city").html('');
                                    //$("#city").select2();
                                }
                            });
                        });

                        $("#state").change(function () {
                            var id = $(this).val();
                            var action = $(this).data('ajax-action');
                            var data = {action: action, id: id};
                            $.ajax({
                                type: "POST",
                                url: ajaxurl,
                                data: data,
                                success: function (result) {
                                    $("#city").html('<option value=""> Any city</option>'+result);
                                    //$("#city").select2();
                                }
                            });
                        });



                        function changecurrency() {
                            <?php
                            $ids = array();
                            $currencies = array();
                            foreach($types as $type) {
                                $ids[] = $type['id'];
                                $currencies[] = $type['currency'];
                            }
                            echo '
			var ids = new Array("'.implode('", "', $ids).'");
			var currencies = new Array("'.implode('", "', $currencies).'");';
                            ?>
                            var type_id = jQuery("#type_id").val();
                            var id = jQuery.inArray(type_id, ids);
                            if (id >= 0) {
                                jQuery("#currency").html(currencies[id]);
                            }
                        }
                        changecurrency();
                    </script>
                <?php
                }
                else if ($page == 'transactions') {
                if (empty($error_message)) {
                    if (!empty($ok_message)) $message = '<div class="alert alert-success">'.$ok_message.'</div>';
                    else if (DEMO_MODE) $message = '<div class="alert alert-warning"><strong>Demo mode.</strong> Real e-mails and transaction details are hidden.</div>';
                    else $message = '';
                } else $message = '<div class="alert alert-error">'.$error_message.'</div>';
                echo $message;

                if (isset($_GET["s"])) $search_query = trim(stripslashes($_GET["s"]));
                else $search_query = "";

                if (isset($_GET["did"])) $banner_id = intval(trim(stripslashes($_GET["did"])));
                else $banner_id = 0;
                $tmp = $icdb->get_row("SELECT COUNT(*) AS total FROM ".$icdb->prefix."transactions WHERE deleted = '0'".($banner_id > 0 ? " AND banner_id = '".$banner_id."'" : "").((strlen($search_query) > 0) ? " AND (payer_name LIKE '%".addslashes($search_query)."%' OR payer_email LIKE '%".addslashes($search_query)."%')" : ""));
                $total = $tmp["total"];
                $totalpages = ceil($total/RECORDS_PER_PAGE);
                if ($totalpages == 0) $totalpages = 1;
                if (isset($_GET["p"])) $page = intval($_GET["p"]);
                else $page = 1;
                if ($page < 1 || $page > $totalpages) $page = 1;
                $switcher = page_switcher("banner-ad-manage.php?page=transactions".($banner_id > 0 ? "&did = '".$banner_id."'" : "").((strlen($search_query) > 0) ? "&s=".rawurlencode($search_query) : ""), $page, $totalpages);

                $rows = $icdb->get_rows("SELECT t1.*, t2.title AS banner_title, t2.url AS banner_url FROM ".$icdb->prefix."transactions t1 LEFT JOIN ".$icdb->prefix."banners t2 ON t1.banner_id = t2.id WHERE t1.deleted = '0'".($banner_id > 0 ? " AND t1.banner_id = '".$banner_id."'" : "").((strlen($search_query) > 0) ? " AND (t1.payer_name LIKE '%".addslashes($search_query)."%' OR t1.payer_email LIKE '%".addslashes($search_query)."%')" : "")." ORDER BY t1.created DESC LIMIT ".(($page-1)*RECORDS_PER_PAGE).", ".RECORDS_PER_PAGE);
                ?>
                    <form action="banner-ad-manage.php" method="get" class="form-inline" style="margin-bottom: 10px;">
                        <input type="hidden" name="page" value="transactions" />
                        <input type="text" name="s" class="form-control" placeholder="Search" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES); ?>">
                        <?php echo ($banner_id > 0 ? '<input type="hidden" name="did" value="'.$banner_id.'" />' : ''); ?>
                        <input type="submit" class="btn" value="Search" />
                        <?php echo (strlen($search_query) > 0 ? '<input type="button" class="btn" value="Reset search results" onclick="window.location.href=\'banner-ad-manage.php?page=transactions'.($banner_id > 0 ? "&did = '".$banner_id."'" : "").'\';" />' : ''); ?>
                    </form>
                    <table class="table table-striped">
                        <tr>
                            <th>Banner</th>
                            <th>Payer</th>
                            <th style="width: 100px; text-align: right;">Amount</th>
                            <th style="width: 160px;">Status</th>
                            <th style="width: 120px;">Created</th>
                            <th style="width: 20px;"></th>
                        </tr>
                        <?php
                        $modals = '';
                        if (sizeof($rows) > 0) {
                            foreach ($rows as $row) {
                                $email = $row['payer_email'];
                                $name = $row['payer_name'];
                                if (DEMO_MODE) {
                                    if (($pos = strpos($email, "@")) !== false) {
                                        $nickname = substr($email, 0, strpos($email, "@"));
                                        $email = substr($nickname, 0, 1).'*****'.substr($email, $pos);
                                    }
                                    if (($pos = strpos($name, "@")) !== false) {
                                        $nickname = substr($name, 0, strpos($name, "@"));
                                        $name = substr($nickname, 0, 1).'*****'.substr($name, $pos);
                                    }
                                }
                                $modals .= '
				<div style="display: none;" class="modal" id="details_'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Transaction Details</h3>
					</div>
					<div class="modal-body">
						<table class="table table-striped">';
                                $details = explode("&", $row["details"]);
                                foreach ($details as $param) {
                                    $param = trim($param);
                                    if (!empty($param)) {
                                        $data = explode("=", $param, 2);
                                        $modals .= '
							<tr>
								<td style="width: 170px; font-weight: bold;">'.htmlspecialchars($data[0], ENT_QUOTES).'</td>
								<td>'.(DEMO_MODE ? '*****' : htmlspecialchars(urldecode($data[1]), ENT_QUOTES)).'</td>
							</tr>';
                                    }
                                }
                                $modals .= '
						</table>						
					</div>
				</div>';
                                echo '
		<tr>
			<td>'.(!empty($row['banner_url']) ? '<a href="'.$row['banner_url'].'" target="_blank">' : '').(empty($row['banner_title']) ? '-' : htmlspecialchars($row['banner_title'], ENT_QUOTES)).(!empty($row['banner_url']) ? '</a>' : '').'</td>
			<td>'.htmlspecialchars($name, ENT_QUOTES).'<br /><em>'.htmlspecialchars($email, ENT_QUOTES).'</em></td>
			<td style="text-align: right;">'.number_format($row['gross'], 2, ".", "").' '.$row['currency'].'</td>
			<td><a href="#details_'.$row['id'].'" data-toggle="modal">'.$row["payment_status"].'</a><br /><em>'.$row["transaction_type"].'</em></td>
			<td>'.date("Y-m-d H:i", $row['created']).'</td>
			<td style="text-align: center;">
				<a href="banner-ad-manage.php?action=delete-transaction&id='.$row['id'].'" title="Delete record" class="btn btn-xs btn-default" onclick="return submitOperation();"><i class="ion-android-delete"></i></a>
			</td>
		</tr>';
                            }
                        } else {
                            print ('
				<tr><td colspan="6" style="padding: 20px; text-align: center;">'.((strlen($search_query) > 0) ? 'No results found for "<strong>'.htmlspecialchars($search_query, ENT_QUOTES).'</strong>"' : 'List is empty.').'</td></tr>');
                        }
                        ?>
                    </table>
                    <div class="row">
                        <div class="span6">
                            <div class="pull-left">
                                <?php echo $switcher; ?>
                                &nbsp;
                            </div>
                        </div>
                    </div>
                <?php echo $modals; ?>
                <hr>
                    <script type="text/javascript">
                        function submitOperation() {
                            var answer = confirm("Do you really want to continue?");
                            if (answer) return true;
                            else return false;
                        }
                    </script>
                <?php
                }
                ?>
            </div>
            <!-- .card-block -->
        </div>
        <!-- .card -->
        <!-- End Partial Table -->
    </div>
    <!-- .container-fluid -->
    <!-- End Page Content -->

</main>