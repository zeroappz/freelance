<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

ini_set('log_errors', TRUE); // Error/Exception file logging engine.
ini_set('error_log', 'errors.log'); // Logging file pathini_set('log_errors', TRUE);

require_once('../../includes/autoload.php');
require_once('../../includes/lang/lang_'.$config['lang'].'.php');
include_once(dirname(__FILE__).'/inc/config.php');
include_once(dirname(__FILE__).'/inc/settings.php');
include_once(dirname(__FILE__).'/inc/icdb.php');
include_once(dirname(__FILE__).'/inc/functions.php');
$icdb = new ICDB(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, TABLE_PREFIX);

install();
get_options();
sec_session_start();
if (isset($_REQUEST['callback'])) {
    header("Content-type: application/json");
    $jsonp_enabled = true;
    $jsonp_callback = $_REQUEST['callback'];
} else $jsonp_enabled = false;

$url_base = ((empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == 'off') ? 'http://' : 'https://').$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$filename = basename(__FILE__);
if (($pos = strpos($url_base, $filename)) !== false) $url_base = substr($url_base, 0, $pos);

if (isset($_REQUEST['action'])) {
    switch ($_REQUEST['action']) {
        case 'payment_success':
            $tags = $_REQUEST['tags'];
            $vals = $_REQUEST['vals'];
            $payer_email = $_REQUEST['payer_email'];
            send_thanksgiving_email($tags, $vals, $payer_email);
            $url = $link['PAYMENT'].'?action=success';
            headerRedirect($url);
            exit;
            break;
        case 'payment_failed':
            $tags = $_REQUEST['tags'];
            $vals = $_REQUEST['vals'];
            $payer_email = $_REQUEST['payer_email'];
            send_failed_email($tags, $vals, $payer_email);
            $url = $link['PAYMENT'].'?action=success';
            headerRedirect($url);
            exit;
            break;
        case 'ubm_getbanner':
            $html = '';
            $sql = "SELECT * FROM ".$icdb->prefix."banners WHERE registered+24*3600*days_purchased < '".time()."' AND status = '".STATUS_ACTIVE."' AND deleted = '0' ORDER BY RAND() ";
            $rows = $icdb->get_rows($sql);
            foreach ($rows as $row) {
                $sql = "UPDATE ".$icdb->prefix."banners SET status = '".STATUS_EXPIRED."' WHERE id = '".$row["id"]."'";
                $icdb->query($sql);
                if (!empty($row["email"])) {
                    $stats = 'Title: '.htmlspecialchars($row["title"], ENT_QUOTES).'
URL: '.$row["url"].'
Rotation period: '.$row["days_purchased"].' '.'days
Shows: '.$row["shows_displayed"].'
Clicks: '.$row["clicks"].'
CTR: '.number_format($row["clicks"]*100/$row["shows_displayed"], 2, ".", "").'%';
                    $tags = array("{banner_title}", "{statistics}", "{signup_page}");
                    $vals = array(htmlspecialchars($row["title"], ENT_QUOTES),  $stats, $options['signup_page']);
                    $body = str_replace($tags, $vals, $options['stats_email_body']);
                    $mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
                    $mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
                    $mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
                    mail($row["email"], $options['stats_email_subject'], $body, $mail_headers);
                }
            }

            $banners = array();
            $session_id = time().rand(10000,99999);
            if (check_options() === true) {
                $catid = trim($_REQUEST['catid']);
                $subcatid = trim($_REQUEST['subcatid']);
                $placetype = isset($_REQUEST['placetype'])? trim($_REQUEST['placetype']): '';
                $placeid = isset($_REQUEST['placeid'])? trim($_REQUEST['placeid']): '';
                $data = stripslashes(trim($_REQUEST['ubm_banners']));
                $pairs = explode(",", $data);

                if($placetype == "city"){
                    $sql = "SELECT `country_code`, `subadmin1_code` FROM `".$config['db']['pre']."cities` WHERE `id` = '$placeid' LIMIT 1";
                    $info = $icdb->get_row($sql);
                    $country_code = $info['country_code'];
                    $state_code = $info['subadmin1_code'];
                }
                if($placetype == "state"){
                    $country_code = substr($placeid,0,2);;
                    $state_code = $placeid;
                }



                foreach ($pairs as $pair) {
                    $data = explode(":", $pair);
                    if (sizeof($data) == 2 && is_numeric($data[0]) && is_numeric($data[1])) {
                        $type_id = intval($data[1]);
                        $type_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."types WHERE id = '".$type_id."' AND deleted = '0'");
                        if (!$type_details) continue;
                        unset($banner_details);
                        if (($pos = strpos($_SERVER["HTTP_REFERER"], "ubm_show")) !== false) {
                            $id_str = substr($_SERVER["HTTP_REFERER"], $pos + strlen("ubm_show="));
                            if (($pos = strpos($id_str, "&")) !== false) {
                                $id_str = substr($id_str, 0, $pos);
                            }
                            $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE type_id = '".$type_id."' AND id_str='".$id_str."' AND deleted = '0' AND id NOT IN (SELECT banner_id FROM ".$icdb->prefix."log WHERE session_id = '".$session_id."' AND type_id = '".$type_id."')");
                        }

                        if (empty($banner_details))
                        {
                            $sql = "SELECT * FROM ".$icdb->prefix."banners WHERE type_id = '".$type_id."' AND registered+24*3600*days_purchased >= '".time()."' AND status = '".STATUS_ACTIVE."' AND deleted = '0' AND id NOT IN (SELECT banner_id FROM ".$icdb->prefix."log WHERE session_id = '".$session_id."' AND type_id = '".$type_id."') ORDER BY RAND()";
                            $banner_details = $icdb->get_row($sql);

                            if($placetype == "country"){
                                if ($banner_details['country'] == null or $banner_details['country'] == strtoupper($placeid)){
                                    $res = $banner_details;
                                }else{
                                    continue;
                                }
                            }

                            if($placetype == "state"){
                                if ($banner_details['country'] == null){
                                    $res = $banner_details;
                                }else if ($banner_details['country'] == $country_code){
                                    if ($banner_details['state'] == null or $banner_details['state'] == $state_code){
                                        $res = $banner_details;
                                    }else{
                                        continue;
                                    }
                                }else{
                                    continue;
                                }
                            }

                            if($placetype == "city"){
                                if ($banner_details['country'] == null){
                                    $res = $banner_details;
                                }else if ($banner_details['country'] == $country_code){
                                    if ($banner_details['state'] == null){
                                        $res = $banner_details;
                                    }else if ($banner_details['state'] == $state_code){
                                        if ($banner_details['city'] == null or $banner_details['city'] == $placeid){
                                            $res = $banner_details;
                                        }else{
                                            continue;
                                        }
                                    }else{
                                        continue;
                                    }
                                }else{
                                    continue;
                                }
                            }

                            if($banner_details['sub_cat_id'] != 0){
                                if($banner_details['sub_cat_id'] == $subcatid){
                                    $res = $banner_details;
                                }else{
                                    if($banner_details['cat_id'] != 0){
                                        if($banner_details['cat_id'] == $catid){
                                            if($subcatid == 0){
                                                $res = $banner_details;
                                            }else{
                                                if($banner_details['sub_cat_id'] == $subcatid){
                                                    $res = $banner_details;
                                                }
                                            }
                                        }else{
                                            continue;
                                        }
                                    }else{
                                        $res = $banner_details;
                                    }
                                }
                            }else{
                                if($banner_details['cat_id'] != 0){
                                    if($banner_details['cat_id'] == $catid){
                                        if($subcatid == 0){
                                            $res = $banner_details;
                                        }else{
                                            if($banner_details['sub_cat_id'] == $subcatid){
                                                $res = $banner_details;
                                            }
                                        }
                                    }else{
                                        continue;
                                    }
                                }else{
                                    $res = $banner_details;
                                }
                            }

                            $banner_details = $res;
                        }
                        if (empty($banner_details)) {

                            if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $options['signup_page']) || strlen($options['signup_page']) == 0) {
                                $html = '';
                            }
                            else {
                                $html = '<a class="ubm_banner" href="'.$options['signup_page'].'" style="width: '.$type_details["width"].'px !important; height: '.$type_details["height"].'px !important; line-height: '.$type_details["height"].'px; border: 1px solid #BBB !important;" title="Advertise Here">Advertise Here</a>';
                            }
                        } else {
                            $sql = "UPDATE ".$icdb->prefix."banners SET shows_displayed = shows_displayed + 1 WHERE id = '".$banner_details["id"]."'";
                            $icdb->query($sql);
                            $sql = "INSERT INTO ".$icdb->prefix."log (banner_id, type_id, session_id, created) VALUES ('".$banner_details["id"]."', '".$type_id."', '".$session_id."', '".time()."')";
                            $icdb->query($sql);
                            $html = '<a target="_blank" class="ubm_banner" href="'.(!empty($banner_details["url"]) ? $url_base."go.php?id=".$banner_details["id_str"] : '#').'" style="'.(empty($banner_details["url"]) ? 'cursor: default; ' : '').'width: '.$type_details["width"].'px !important; height: '.$type_details["height"].'px !important; line-height: '.$type_details["height"].'px; background: transparent url('.$url_base.'files/'.$banner_details["file"].') 0 0 no-repeat;  border: 1px solid transparent !important;"'.(empty($banner_details["url"]) ? ' onclick="return false;"' : '').' title="'.htmlspecialchars($banner_details["title"], ENT_QUOTES).'" 
							data-country="'.$banner_details["country"].'"
							data-state="'.$banner_details["state"].'"
							data-city="'.$banner_details["city"].'"
							data-cat_id="'.$catid.'-'.$banner_details["cat_id"].'"
							data-subcat_id="'.$subcatid.'-'.$banner_details["sub_cat_id"].'"
							>&nbsp;</a>';
                        }
                        $banners['ubm_'.intval($data[0])] = $html;
                    }
                }
                $sql = "DELETE FROM ".$icdb->prefix."log WHERE session_id = '".$session_id."'";
                $icdb->query($sql);
            }
            $html = json_encode($banners);
            if ($jsonp_enabled) {
                $html_object = new stdClass();
                $html_object->html = $html;
                echo $jsonp_callback.'('.json_encode($html_object).')';
            } else echo $html;
            exit;
            break;

        case 'ubm_getbox':
            if (isset($_REQUEST['ubm_url'])) $return_url = trim($_REQUEST['ubm_url']);
            else $return_url = '';

            $return_url = stripslashes($return_url);
            $form = '';
            if (check_options() === true) {

                $sql = "SELECT t1.*, t2.total FROM ".$icdb->prefix."types t1 LEFT JOIN (SELECT type_id, COUNT(*) AS total FROM ".$icdb->prefix."banners WHERE registered+24*3600*days_purchased >= '".time()."' AND status = '".STATUS_ACTIVE."' AND deleted = '0' GROUP BY type_id) t2 ON t2.type_id = t1.id WHERE t1.deleted = '0' AND t1.status = '".STATUS_ACTIVE."' AND (t2.total < t1.slots || t2.total IS NULL)";
                $rows = $icdb->get_rows($sql);
                if (sizeof($rows) == 0) {
                    $form = '<div class="ubm_container"><div name="ubm" class="ubm_box"><div class="ubm_confirmation_info" style="text-align: center;">There are no slots available for new banner. Please try again later.</div></div></div>';
                } else {
                    if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $return_url) || strlen($return_url) == 0) $return_url = $_SERVER["HTTP_REFERER"];
                    $tac = '';
                    $terms = htmlspecialchars($options['terms'], ENT_QUOTES);
                    $terms = str_replace("\n", "<br />", $terms);
                    $terms = str_replace("\r", "", $terms);
                    if (strlen($terms) > 0) {
                        $terms_id = "t".random_string(8);
                        $tac = '
						<div id="'.$terms_id.'" style="display: none;">
							<div class="ubm_terms">'.$terms.'</div>
						</div>
						<div style="margin-top: 5px;">'.'By clicking the button below, I agree with the <a href="#" onclick="jQuery(\'#'.$terms_id.'\').slideToggle(300); return false;">Terms & Conditions</a>.</div>';
                    }
                    $intro = $options['intro'];
                    $intro = str_replace("\n", "<br />", $intro);
                    $intro = str_replace("\r", "", $intro);
                    $tags = array("{minimum_days}");
                    $vals = array($options['minimum_days']);
                    $intro = str_replace($tags, $vals, $intro);
                    if (strlen($intro) > 0) $intro = '<div style="margin-bottom: 10px;">'.$intro.'</div>';


                    $form = '
						<div id="contact" class="ubm_container">
							<div name="ubm" class="ubm_box" id="ubm">
								<div class="ubm_signup_form" id="ubm_signup_form">
									'.$intro.'
									<form action="'.$url_base.'ajax.php" target="ubm_iframe" enctype="multipart/form-data" onsubmit="ubm_presubmit();" method="post" style="margin: 0px; padding: 0px;">
									<div style="overflow: hidden; height: 100%; margin-bottom: 10px;">
										<div style="width: 100%; float: left;">
											<div style="padding-right: 0px;">
												<select class="ubm_email" name="type" id="ubm_type" onchange="ubm_calc();">';
                    foreach ($rows as $row) {
                        $form .= '
													<option value="'.$row["id"].'">'.htmlspecialchars($row["title"], ENT_QUOTES).' ('.$row["width"].'x'.$row["height"].' | '.number_format($row["price"], 2, ".", "").' '.$config['currency_code'].' per 10 days)</option>';
                    }
                    $form .= '
												</select>
											</div>
										</div>
										<em class="ubm_comment">Select available banner type.</em>
									</div>
									<div style="overflow: hidden; height: 100%; margin-bottom: 10px;">
										<div style="width: 50%; float: left;">
											<div style="padding-right: 25px;">
												<input class="ubm_email" type="text" name="title" placeholder="Banner title (required)" value="Banner title (required)" onfocus="if (this.value == \'Banner title (required)\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Banner title (required)\';}" title="Please enter banner title." />
											</div>
										</div>
										<div style="width: 50%; float: left;">
											<div style="padding-right: 14px;">
												<input required="required" class="ubm_email" type="text" name="url" placeholder="Link (optional)" value="Link (optional)" onfocus="if (this.value == \'Link (optional)\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Link (optional)\';}" title="Please enter banner URL. Your banner will be hyperlinked with this URL." />
											</div>
										</div>
										<em class="ubm_comment">Enter banner title and its URL. The banner will be hyperlinked with this URL.</em>
									</div>
									<div style="overflow: hidden; height: 100%; margin-bottom: 10px;">
										<div style="width: 100%; float: left;">
											<div style="padding-right: 14px;">
												<input class="ubm_file" type="file" name="file" title="Please enter banner URL. Your banner will be hyperlinked with this URL." />
											</div>
										</div>
										<em class="ubm_comment">Upload your banner image. You can use JPEG, GIF and PNG images. Image size must be exactly the same as per banner type.</em>
									</div>
									<div style="overflow: hidden; height: 100%; margin-bottom: 10px;">
										<div style="width: 100%; float: left;">
											<div style="padding-right: 14px;">
												<input required="required" class="ubm_email" type="text" name="email" placeholder="Enter your e-mail (required)" value="Enter your e-mail (required)" onfocus="if (this.value == \'Enter your e-mail (required)\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Enter your e-mail (required)\';}" title="Please enter your e-mail." />
											</div>
										</div>
										<em class="ubm_comment">Enter your e-mail. We will send statistics to this e-mail.</em>
									</div>
									<div style="overflow: hidden; height: 100%;">
										<div style="width: 100%; float: left;">
											Period:
											<input required="required" class="ubm_qty" name="period" id="ubm_period" type="text" value="'.$options['minimum_days'].'" title="How many days your banner has to be shown on our website." onkeyup="ubm_calc();" onchange="ubm_calc();" />
											days. Total price is
											<input class="ubm_qty" type="text" id="ubm_total" disabled="disabled" value="'.number_format($rows[0]['price']*$options['minimum_days']/10, 2, '.', '').'" title="Total price." />
											'.$config['currency_code'].'.
										</div>
										<em class="ubm_comment">How many days your banner has to be shown on our website.</em>
									</div>';

                    $form .= $tac.'
									<input type="hidden" name="action" value="ubm_submit" />
									<input type="hidden" name="id_str" id="ubm_id_str" value="'.random_string(16).'" />
									<input type="hidden" name="return" value="'.$return_url.'" />
									<input type="submit" class="ubm_submit" id="ubm_submit" value="Continue"  style="margin-top: "/>
									<img id="ubm_loading" class="ubm_loading" src="'.$url_base.'img/loading.gif" alt="">
									</form>';
                    foreach ($rows as $row) {
                        $form .= '<input type="hidden" id="ubm_type_'.$row['id'].'" value="'.number_format($row["price"], 2, '.', '').'" />';
                    }
                    $form .= '
									<iframe id="ubm_iframe" name="ubm_iframe" style="border: 0px; height: 0px; width: 0px; margin: 0px; padding: 0px; display: none;" onload="ubm_load();"></iframe>
								</div>
								<div class="ubm_confirmation_container" id="ubm_confirmation_container"></div>
								<div id="ubm_message" class="ubm_message"></div>
							</div>
						</div>';
                }
            } else $form = '<div class="ubm_container"><div name="ubm" class="ubm_box"><div class="ubm_confirmation_info" style="text-align: center;"><strong>Quickad Banner Manager.</strong> Please check settings!</div></div></div>';
            $html = $form;

            if ($jsonp_enabled) {
                $html_object = new stdClass();
                $html_object->html = $html;
                echo $jsonp_callback.'('.json_encode($html_object).')';
            } else echo $html;
            exit;
            break;

        case 'ubm_submit':
            $html = '';
            $id_str = trim($_REQUEST['id_str']);
            $id_str = preg_replace('/[^a-zA-Z0-9]/', '', $id_str);
            if (strlen($id_str) < 16) die();
            $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id_str = '".$id_str."' AND deleted = '0'");
            if ($banner_details && $banner_details['status'] != STATUS_DRAFT) die();
            if (!$banner_details) {
                $icdb->query("INSERT INTO ".$icdb->prefix."banners (type_id, title, email, url, file, days_purchased, amount, currency, shows_displayed, clicks, id_str, status, details, echo, registered, blocked, deleted) VALUES ('0', '', '', '', '', '0', '0.00', 'USD', '0', '0', '".$id_str."', '".STATUS_DRAFT."', '', '', '".time()."', '0', '0')");
                $id = $icdb->insert_id;
            } else $id = $banner_details['id'];


            $title = trim(stripslashes($_REQUEST['title']));
            $email = trim(stripslashes($_REQUEST['email']));
            $url = trim(stripslashes($_REQUEST['url']));
            $period = intval($_REQUEST['period']);
            $return_url = trim(stripslashes($_REQUEST['return']));
            $type = intval($_REQUEST['type']);

            if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $return_url) || strlen($return_url) == 0) $return_url = $_SERVER["HTTP_REFERER"];
            if ($title == 'Banner title (required)') $title = '';
            if ($url == 'Link (optional)') $url = '';
            $error = '';
            if (strlen($title) > 128) $error .= '<li>Banner title is too long.</li>';
            else if (strlen($title) < 3) $error .= '<li>Banner title is too short.</li>';
            if ($email == '') {
                $error .= '<li>Your e-mail address is required.</li>';
            } else if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email)) {
                $error .= '<li>You have entered an invalid e-mail address.</li>';
            } else if (strlen($email) > 64) {
                $error .= '<li>Your email is too long.</li>';
            }
            if ($period < $options['minimum_days']) $error .= '<li>Rotation period must be at least '.$options['minimum_days'].' days.</li>';
            if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url) && strlen($url) > 0) {
                $error .= '<li>Website URL must be valid URL.</li>';
            } else if (strlen($url) > 192) {
                $error .= '<li>Your website URL is too long.</li>';
            }
            $type_details = $icdb->get_row("SELECT t1.*, t2.total FROM ".$icdb->prefix."types t1 LEFT JOIN (SELECT type_id, COUNT(*) AS total FROM ".$icdb->prefix."banners WHERE registered+24*3600*days_purchased >= '".time()."' AND status = '".STATUS_ACTIVE."' AND deleted = '0' GROUP BY type_id) t2 ON t2.type_id = t1.id WHERE t1.id = '".$type."' AND t1.deleted = '0'");
            if (!$type_details) $error .= '<li>Invalid banner type.</li>';
            if (is_uploaded_file($_FILES["file"]["tmp_name"])) {
                $ext = "";
                if (($pos = strrpos($_FILES["file"]["name"], ".")) !== false) {
                    $ext = strtolower(substr($_FILES["file"]["name"], $pos));
                }
                if ($ext != ".jpg" && $ext != ".jpeg" && $ext != ".gif" && $ext != ".png") $error .= '<li>Banner image must be JPEG, GIF or PNG file.</li>';
                else {
                    list($width, $height, $imagetype, $attr) = getimagesize($_FILES["file"]["tmp_name"]);
                    if ($width != $type_details["width"] || $height != $type_details["height"]) $error .= '<li>Image size must be '.$type_details["width"].'x'.$type_details["height"].'.</li>';
                    else {
                        $image = "banner_".md5(microtime().$_FILES["file"]["tmp_name"]).$ext;
                        if (!move_uploaded_file($_FILES["file"]["tmp_name"], ABSPATH."/files/".$image)) {
                            $error .= '<li>Can not save uploaded image.</li>';
                        }
                    }
                }
            } else $error .= '<li>Banner image must be uploaded.</li>';



            if ($error != '') {
                $html .= '<div class="ubm_error_message">Attention! Please correct the errors below and try again.';
                $html .= '<ul class="ubm_error_messages">'.$error.'</ul>';
                $html .= '</div>';
                $icdb->query("UPDATE ".$icdb->prefix."banners SET 
					echo = '".mysqli_real_escape_string($icdb->link,$html)."'
					WHERE id = '".$id."'");
            } else {
                $amount = number_format($period*$type_details["price"]/10, 2, ".", "");

                /*These details save in session and get on payment sucecess*/

                $payment_type = "banner-advertise";
                $access_token = uniqid();
                $banner_trans_desc = htmlspecialchars($type_details["title"], ENT_QUOTES).' ('.$type_details["width"].'x'.$type_details["height"].')';

                $_SESSION['quickad'][$access_token]['name'] = $title;
                $_SESSION['quickad'][$access_token]['amount'] = $amount;
                $_SESSION['quickad'][$access_token]['payment_type'] = $payment_type;
                $_SESSION['quickad'][$access_token]['trans_desc'] = $banner_trans_desc;
                $_SESSION['quickad'][$access_token]['product_id'] = $id;
                /*End These details save in session and get on payment sucecess*/
                $payment_url = $link['PAYMENT']."/" . $access_token;

                $html .= '
<div class="ubm_confirmation_info">
	<table class="ubm_confirmation_table">
		<tr><td style="width: 170px"><strong>Title:</strong></td><td class="ubm_confirmation_data">'.(empty($title) ? '-' : htmlspecialchars($title, ENT_QUOTES)).'</td></tr>
		<tr><td><strong>Website:</strong></td><td class="ubm_confirmation_data">'.(empty($url) ? '-' : '<a href="'.$url.'" target="_blank">'.htmlspecialchars($url, ENT_QUOTES).'</a>').'</td></tr>
		<tr><td><strong>Banner:</strong></td><td class="ubm_confirmation_data">'.htmlspecialchars($type_details["title"], ENT_QUOTES).' ('.$type_details["width"].'x'.$type_details["height"].')'.(!empty($type_details['preview_url']) ? '<br /><a href="'.add_url_parameters($type_details["preview_url"], array ("ubm_show" => $id_str)).'" target="_blank">live preview</a>' : '').'</td></tr>
		<tr><td><strong>E-Mail:</strong></td><td class="ubm_confirmation_data">'.htmlspecialchars($email, ENT_QUOTES).'</td></tr>
		<tr><td><strong>Price:</strong></td><td class="ubm_confirmation_price">'.$amount.' '.$config['currency_code'].'</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
	</table>
	<div class="ubm_signup_buttons">';


                $html .= '
		<input type="button" class="ubm_submit" id="ubm_bitpay" value="Confirm and pay" onclick="ubm_bitpay('.$id.', \''.$payment_url.'\');">
		<input type="button" class="ubm_submit" id="ubm_bitpay_edit" value="Edit info" onclick="ubm_edit();">
		<img id="ubm_loading2" class="ubm_loading" src="'.$url_base.'img/loading.gif" alt="">';



                $html .= '</div>';

                $html .= '</div>';
                $icdb->query("UPDATE ".$icdb->prefix."banners SET 
					type_id = '".$type."', 
					title = '".mysqli_real_escape_string($icdb->link,$title)."', 
					email = '".mysqli_real_escape_string($icdb->link,$email)."', 
					url = '".mysqli_real_escape_string($icdb->link,$url)."', 
					days_purchased = '".number_format(floatval($period), 2, ".", "")."', 
					amount = '".number_format(floatval($amount), 2, ".", "")."',
					currency = '".$config['currency_code']."',
					echo = '".mysqli_real_escape_string($icdb->link,$html)."',
					details = '".mysqli_real_escape_string($icdb->link,$return_url)."',
					file = '".mysqli_real_escape_string($icdb->link,$image)."' 
					WHERE id = '".$id."'");
            }
            exit;
            break;

        case 'ubm_postsubmit':
            $html = '';
            $id_str = trim($_REQUEST['ubm_id_str']);
            $id_str = preg_replace('/[^a-zA-Z0-9]/', '', $id_str);
            if (strlen($id_str) < 16) die();
            $banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id_str = '".$id_str."' AND deleted = '0'");
            if ($banner_details && $banner_details['status'] != STATUS_DRAFT) die();
            $html = $banner_details['echo'];
            if ($jsonp_enabled) {
                $html_object = new stdClass();
                $html_object->html = $html;
                echo $jsonp_callback.'('.json_encode($html_object).')';
            } else echo $html;
            exit;
            break;

        default:
            break;
    }
}
?>