<?php
require_once('config.php');
foreach (glob(APPPATH."*") as $dir) {
    if(is_dir($dir) && file_exists($dir.'/_links.php')){
        require_once $dir.'/_links.php';
    }
}
