<?php
/**
 * Quickad Rating & Reviews - jQuery & Ajax php
 * @author Bylancer
 * @version 1.0
 */

global $db;
global $productid;

define("ROOTPATH", dirname(dirname(__DIR__)));
define("APPPATH", ROOTPATH."/php/");

require_once ROOTPATH . '/includes/autoload.php';
require_once ROOTPATH . '/includes/lang/lang_'.$config['lang'].'.php';

sec_session_start();


if (isset($_GET['productid']) && !empty($_GET['productid'])) {
    $productid = $_GET['productid'];
} else {
    $productid = '';
}
?>