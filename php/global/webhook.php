<?php
if(isset($_GET['i'])){
    if (file_exists('includes/payments/' . $_GET['i'] . '/webhook.php')) {
        require_once('includes/payments/' . $_GET['i'] . '/webhook.php');
    }
}
die();