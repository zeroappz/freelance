<?php
define("ROOTPATH", dirname(__DIR__));
define("APPPATH", ROOTPATH."/php/");
define("ADMINPATH", __DIR__);

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

$admin_url = $config['site_url']."admin/";
define("ADMINURL", $admin_url);

$mysqli = db_connect();
admin_session_start();
if (!checkloggedadmin()) {
    headerRedirect(ADMINURL.'login.php');
}
include('header.php');