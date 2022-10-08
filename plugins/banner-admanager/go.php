<?php
session_start();
if (isset($_GET['debug'])) error_reporting(-1);
else error_reporting(0);
require_once('../../includes/autoload.php');
include_once(dirname(__FILE__).'/inc/config.php');
include_once(dirname(__FILE__).'/inc/settings.php');
include_once(dirname(__FILE__).'/inc/icdb.php');
include_once(dirname(__FILE__).'/inc/functions.php');
$icdb = new ICDB(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD, TABLE_PREFIX);

install();

get_options();

$url_base = ((empty($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] == 'off') ? 'http://' : 'https://').$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
$filename = basename(__FILE__);
if (($pos = strpos($url_base, $filename)) !== false) $url_base = substr($url_base, 0, $pos);

if (isset($_GET['id'])) {
	$id_str = $_GET["id"];
	$id_str = preg_replace('/[^a-zA-Z0-9]/', '', $id_str);
	$banner_details = $icdb->get_row("SELECT * FROM ".$icdb->prefix."banners WHERE id_str = '".$id_str."'");
	if ($banner_details && !empty($banner_details["url"])) {
		$sql = "UPDATE ".$icdb->prefix."banners SET clicks = clicks + 1 WHERE id = '".$banner_details["id"]."'";
		$icdb->query($sql);
		header("Location: ".$banner_details["url"]);
		die();
	}
}
if (!preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $options['signup_page']) || strlen($options['signup_page']) == 0) die('Invalid URL.');
header("Location: ".$options['signup_page']);
die();		
?>