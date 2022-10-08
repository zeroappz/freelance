<?php
function get_options() {
	global $icdb, $options;
	$rows = $icdb->get_rows("SELECT * FROM ".$icdb->prefix."options");
	foreach ($rows as $row) {
		if (array_key_exists($row['options_key'], $options)) $options[$row['options_key']] = $row['options_value'];
	}
}

function update_options() {
	global $icdb, $options;
	foreach ($options as $key => $value) {
	    if($key == 'enable_approval'){
	        update_option('qbm_enable_approval',$value);
        }
		$option = $icdb->get_row("SELECT * FROM ".$icdb->prefix."options WHERE options_key = '".mysqli_real_escape_string($icdb->link,$key)."'");
		if ($option) {
			$icdb->query("UPDATE ".$icdb->prefix."options SET options_value = '".mysqli_real_escape_string($icdb->link,$value)."' WHERE options_key = '".mysqli_real_escape_string($icdb->link,$key)."'");
		} else {
			$icdb->query("INSERT INTO ".$icdb->prefix."options (options_key, options_value) VALUES ('".mysqli_real_escape_string($icdb->link,$key)."', '".mysqli_real_escape_string($icdb->link,$value)."')");
		}
	}
}

function populate_options() {
	global $icdb, $options;
	foreach ($options as $key => $value) {
		if ($key != 'password') {
			if (isset($_POST[$key])) {
				if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
					$options[$key] = stripslashes($_POST[$key]);
				}
				else $options[$key] = $_POST[$key];
			}
		}
	}
}

function check_options() {
	global $icdb, $options;
	$errors = array();

	if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $options['owner_email']) || strlen($options['owner_email']) == 0) $errors[] = 'E-mail for notifications must be valid e-mail address';
	if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $options['from_email']) || strlen($options['from_email']) == 0) $errors[] = 'Sender e-mail must be valid e-mail address';
	if (strlen($options['from_name']) < 3) $errors[] = 'Sender name is too short';
	if (strlen($options['success_email_subject']) < 3) $errors[] = 'Successful donation e-mail subject must contain at least 3 characters';
	else if (strlen($options['success_email_subject']) > 64) $errors[] = 'Successful donation e-mail subject must contain maximum 64 characters';
	if (strlen($options['success_email_body']) < 3) $errors[] = 'Successful donation e-mail body must contain at least 3 characters';
	if (strlen($options['failed_email_subject']) < 3) $errors[] = 'Failed donation e-mail subject must contain at least 3 characters';
	else if (strlen($options['failed_email_subject']) > 64) $errors[] = 'Failed donation e-mail subject must contain maximum 64 characters';
	if (strlen($options['failed_email_body']) < 3) $errors[] = 'Failed donation e-mail body must contain at least 3 characters';
	if (strlen($options['stats_email_subject']) < 3) $errors[] = 'Statistics e-mail subject must contain at least 3 characters';
	else if (strlen($options['stats_email_subject']) > 64) $errors[] = 'Statistics e-mail subject must contain maximum 64 characters';
	if (strlen($options['stats_email_body']) < 3) $errors[] = 'Statistics e-mail body must contain at least 3 characters';
	//if (empty($options['signup_page'])) $errors[] = 'Sign up page must be defined.';
	if (!is_numeric($options['minimum_days']) || intval($options['minimum_days']) < 5) $errors[] = 'Minimum days number must be 5 or higher';
	if (empty($errors)) return true;
	return $errors;
}

function get_fingerprint($api_login_id, $transaction_key, $amount, $fp_sequence, $fp_timestamp) {
	if (function_exists('hash_hmac')) {
		return hash_hmac("md5", $api_login_id . "^" . $fp_sequence . "^" . $fp_timestamp . "^" . $amount . "^", $transaction_key); 
	}
	return bin2hex(mhash(MHASH_MD5, $api_login_id . "^" . $fp_sequence . "^" . $fp_timestamp . "^" . $amount . "^", $transaction_key));
}

function page_switcher ($_urlbase, $_currentpage, $_totalpages) {
	$pageswitcher = "";
	if ($_totalpages > 1) {
		$pageswitcher = '<div class="tablenav bottom"><div class="tablenav-pages">Pages: <ul class="pagination pagiation-links">';
		if (strpos($_urlbase,"?") !== false) $_urlbase .= "&amp;";
		else $_urlbase .= "?";
		if ($_currentpage == 1) $pageswitcher .= "<li class='paginate_button active'><a>1</a></li> ";
		else $pageswitcher .= " <li class='paginate_button'><a class='page' href='".$_urlbase."p=1'>1</a></li> ";

		$start = max($_currentpage-3, 2);
		$end = min(max($_currentpage+3,$start+6), $_totalpages-1);
		$start = max(min($start,$end-6), 2);
		if ($start > 2) $pageswitcher .= " <b>...</b> ";
		for ($i=$start; $i<=$end; $i++) {
			if ($_currentpage == $i) $pageswitcher .= " <li class='paginate_button active'><a>".$i."</a></li> ";
			else $pageswitcher .= " <li class='paginate_button'><a class='page 2' href='".$_urlbase."p=".$i."'>".$i."</a> </li>";
		}
		if ($end < $_totalpages-1) $pageswitcher .= " <b>...</b> ";

		if ($_currentpage == $_totalpages) $pageswitcher .= " <li class='paginate_button active'><a>".$_totalpages."</a></li> ";
		else $pageswitcher .= " <li class='paginate_button'><a class='page 3' href='".$_urlbase."p=".$_totalpages."'>".$_totalpages."</a></li> ";
		$pageswitcher .= "</ul></div></div>";
	}
	return $pageswitcher;
}

function random_string($_length = 16) {
	$symbols = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = "";
	for ($i=0; $i<$_length; $i++) {
		$string .= $symbols[rand(0, strlen($symbols)-1)];
	}
	return $string;
}

function period_to_string($period) {
	$period_str = "";
	$days = floor($period/(24*3600));
	$period -= $days*24*3600;
	$hours = floor($period/3600);
	$period -= $hours*3600;
	$minutes = floor($period/60);
	if ($days > 1) $period_str = $days.' days, ';
	else if ($days == 1) $period_str = $days.' day, ';
	if ($hours > 1) $period_str .= $hours.' hours, ';
	else if ($hours == 1) $period_str .= $hours.' hour, ';
	else if (!empty($period_str)) $period_str .= '0 hours, ';
	if ($minutes > 1) $period_str .= $minutes.' minutes';
	else if ($minutes == 1) $period_str .= $minutes.' minute';
	else $period_str .= '0 minutes';
	return $period_str;
}

function cut_string($_string, $_limit=40) {
	if (strlen($_string) > $_limit) return substr($_string, 0, $_limit-3)."...";
	return $_string;
}

function add_url_parameters($_base, $_params) {
	if (strpos($_base, "?")) $glue = "&";
	else $glue = "?";
	$result = $_base;
	if (is_array($_params)) {
		foreach ($_params as $key => $value) {
			$result .= $glue.rawurlencode($key)."=".rawurlencode($value);
			$glue = "&";
		}
	}
	return $result;
}

function send_thanksgiving_email($tags, $vals, $payer_email) {
	global $options;
	$body = str_replace($tags, $vals, $options['success_email_body']);
	if (!empty($payer_email)) {
		$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
		$mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
		$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		mail($payer_email, $options['success_email_subject'], $body, $mail_headers);
	}
	$body = str_replace($tags, $vals, 'Dear Administrator!'.PHP_EOL.PHP_EOL.'We would like to inform you that {payer_name} ({payer_email}) paid {amount} {currency} for banner "{banner_title}" via {gateway} on {transaction_date}.'.($options['enable_approval'] == 'on' ? PHP_EOL.'Please review banner and approve/reject it.' : '').PHP_EOL.PHP_EOL.'Thanks,'.PHP_EOL.'Quick Banner Manager');
	$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
	$mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
	$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
	mail($options['owner_email'], 'Completed payment received', $body, $mail_headers);
}

function send_failed_email($tags, $vals, $payer_email) {
	global $options;
	$body = str_replace($tags, $vals, $options['failed_email_body']);
	if (!empty($payer_email)) {
		$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
		$mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
		$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
		mail($payer_email, $options['failed_email_subject'], $body, $mail_headers);
	}
	$body = str_replace($tags, $vals, 'Dear Administrator!'.PHP_EOL.PHP_EOL.'We would like to inform you that {payer_name} ({payer_email}) paid {amount} {currency} for banner "{banner_title}" via {gateway} on {transaction_date}. This is non-completed payment.'.PHP_EOL.'Payment status: {payment_status}'.PHP_EOL.PHP_EOL.'Thanks,'.PHP_EOL.'Quickad Banner Manager');
	$mail_headers = "Content-Type: text/plain; charset=utf-8\r\n";
	$mail_headers .= "From: ".$options['from_name']." <".$options['from_email'].">\r\n";
	$mail_headers .= "X-Mailer: PHP/".phpversion()."\r\n";
	mail($options['owner_email'], 'Non-completed payment received', $body, $mail_headers);
}

function get_auth_style() {
	$style = '
<style>body {
	background-color: #F8F8F8;
	color: #444;
	position: relative;
}
div.page {
	border: 1px solid #AAA;
	background-color: #FFF;
	border-radius: 5px;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	-o-border-radius: 5px;
	-ms-border-radius: 5px;
	-khtml-border-radius: 5px;
	box-shadow: rgba(128, 128, 128, 1) 0 4px 30px;
	-moz-box-shadow: rgba(128,128,128,1) 0 4px 30px;
	-webkit-box-shadow: rgba(128, 128, 128, 1) 0 4px 30px;
	-khtml-box-shadow: rgba(128,128,128,1) 0 4px 30px;	
	-o-box-shadow: rgba(128,128,128,1) 0 4px 30px;	
	-ms-box-shadow: rgba(128,128,128,1) 0 4px 30px;	
	padding: 10px 20px 10px 20px;
	margin-top: 30px;	
}
td.label {
	font-weight: bold;
}
input.input_text {
	line-height: 18px !important;
	font-weight: normal;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	-o-border-radius: 3px;
	-ms-border-radius: 3px;
	-khtml-border-radius: 3px;
	border-radius: 3px;
	padding: 4px 6px;
	border: 1px solid #AAA;
	border-spacing: 0;
	font-family: arial, verdana;
	-moz-box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;
	margin: 0px;
	background-color: #FFF !important;
	margin-right: 5px;
}
span.comment {
	color: #AAA;
}
input#x_card_num {
	width: 200px;
}
input#x_exp_date {
	width: 80px;
}
div.grayboxouter {
	background-color: transparent;
	padding: 0px;
}
div.graybox {
	line-height: 22px; 
	padding: 3px 10px 3px 10px; 
	color:#8a1f11;
	border: 1px solid #FBC2C4; 
	border-radius: 5px; 
	-moz-border-radius: 5px; 
	-webkit-border-radius:5px; 
	font-size: 13px;
	font-family: arial, verdana;
	background: #FBE3E4;
}
div.grayboxhdr {
	font-weight: bold;
	font-size: 14px;
}
div#diverrormsgs ul {
	padding: 0px 0px 0px 15px;
}
a.ubm_return, input[type="submit"] {
	display: inline-block;
	*display: inline;
	padding: 4px 14px;
	margin-bottom: 0;
	margin-top: 10px;
	*margin-left: .3em;
	font-size: 14px;
	line-height: 20px;
	*line-height: 20px;
	text-align: center;
	vertical-align: middle;
	cursor: pointer;
	border: 1px solid #bbbbbb;
	*border: 0;
	border-bottom-color: #a2a2a2;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	*zoom: 1;
	-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	color: #ffffff;
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
	background-color: #5bb75b;
	*background-color: #51a351;
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#62c462), to(#51a351));
	background-image: -webkit-linear-gradient(top, #62c462, #51a351);
	background-image: -o-linear-gradient(top, #62c462, #51a351);
	background-image: linear-gradient(to bottom, #62c462, #51a351);
	background-image: -moz-linear-gradient(top, #62c462, #51a351);
	background-repeat: repeat-x;
	border-color: #51a351 #51a351 #387038;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	filter: progid:dximagetransform.microsoft.gradient(startColorstr="#ff62c462", endColorstr="#ff51a351", GradientType=0);
	filter: progid:dximagetransform.microsoft.gradient(enabled=false);
	font: 14px/18px Tahoma, Geneva, sans-serif !important;
}
a.ubm_return:hover, input[type="submit"]:hover {
	text-decoration: none;
	background-position: 0 -15px;
	-webkit-transition: background-position 0.1s linear;
	-moz-transition: background-position 0.1s linear;
	-o-transition: background-position 0.1s linear;
	transition: background-position 0.1s linear;
	color: #ffffff;
	background-color: #51a351;
	*background-color: #499249;
}
a.ubm_return {
	text-decoration: none;
}
div.ubm_returnbox {
	margin: 30px auto;
	width: 600px;
	padding: 15px 20px;
}</style>';
	return str_replace(array("\n", "\r", "\t"), array("", "", ""), $style);
}

function install() {
	global $icdb;
	$table_name = $icdb->prefix."types";
	if($icdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			id int(11) NULL AUTO_INCREMENT,
			title varchar(255) COLLATE utf8_unicode_ci NULL,
			width int(11) NULL,
			height int(11) NULL,
			slots int(11) NULL,
			price float NULL,
			preview_url varchar(255) COLLATE utf8_unicode_ci NULL,
			status int(11) NULL DEFAULT '".STATUS_ACTIVE."',
			details text COLLATE utf8_unicode_ci NULL,
			registered int(11) NULL,
			deleted int(11) NULL DEFAULT '0',
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$icdb->query($sql);
	}
	$table_name = $icdb->prefix."banners";
	if($icdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			id int(11) NULL AUTO_INCREMENT,
			type_id int(11) NULL,
			title varchar(255) COLLATE utf8_unicode_ci NULL,
			email varchar(255) COLLATE utf8_unicode_ci NULL,
			url varchar(255) COLLATE utf8_unicode_ci NULL,
			file varchar(255) COLLATE utf8_unicode_ci NULL,
			days_purchased int(11) NULL,
			amount float NULL,
			currency varchar(15) COLLATE utf8_unicode_ci NULL,
			cat_id int(11) DEFAULT NULL,
            sub_cat_id int(11) DEFAULT NULL,
            country char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
            state char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
            city char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
			shows_displayed int(11) NULL,
			clicks int(11) NULL,
			id_str varchar(63) COLLATE utf8_unicode_ci NULL,
			status int(11) NULL DEFAULT '".STATUS_DRAFT."',
			details text COLLATE utf8_unicode_ci NULL,
			echo text COLLATE utf8_unicode_ci NULL,
			registered int(11) NULL,
			blocked int(11) NULL DEFAULT '0',
			deleted int(11) NULL DEFAULT '0',
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$icdb->query($sql);
	}
	$table_name = $icdb->prefix."transactions";
	if($icdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			id int(11) NULL AUTO_INCREMENT,
			banner_id int(11) NULL,
			payer_name varchar(255) COLLATE utf8_unicode_ci NULL,
			payer_email varchar(255) COLLATE utf8_unicode_ci NULL,
			gross float NULL,
			currency varchar(15) COLLATE utf8_unicode_ci NULL,
			payment_status varchar(63) COLLATE utf8_unicode_ci NULL,
			transaction_type varchar(63) COLLATE utf8_unicode_ci NULL,
			txn_id varchar(255) COLLATE utf8_unicode_ci NULL,
			details text COLLATE utf8_unicode_ci NULL,
			created int(11) NULL,
			deleted int(11) NULL DEFAULT '0',
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$icdb->query($sql);
	}
	$table_name = $icdb->prefix."log";
	if($icdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
				id int(11) NULL auto_increment,
				banner_id int(11) NULL,
				type_id int(11) NULL,
				session_id varchar(255) COLLATE utf8_unicode_ci NULL,
				created int(11) NULL,
				UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$icdb->query($sql);
	}
	$table_name = $icdb->prefix."options";
	if($icdb->get_var("SHOW TABLES LIKE '".$table_name."'") != $table_name) {
		$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
			id int(11) NULL AUTO_INCREMENT,
			options_key varchar(255) COLLATE utf8_unicode_ci NULL,
			options_value text COLLATE utf8_unicode_ci NULL,
			UNIQUE KEY id (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$icdb->query($sql);
	}
	if (!file_exists(ABSPATH.'/files')) {
		if (mkdir(ABSPATH.'/files')) {
			if (!file_exists(ABSPATH.'/files/index.html')) {
				file_put_contents(ABSPATH.'/files/index.html', 'Silence is the gold!');
			}
		}
	}
	if (!file_exists(ABSPATH.'/files/index.html')) {
		$_SESSION['error'] = '<strong>Important!</strong> Please create folder <em>'.ABSPATH.'/files/</em> and set 0777 permissions.';
	}
}
?>