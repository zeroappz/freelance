<?php
// ** MySQL settings - You can get this info from your web host ** //
require_once('config.php');
/** The name of the database */
define('DB_NAME', $config['db']['name']);

/** MySQL database username */
define('DB_USER', $config['db']['user']);

/** MySQL database password */
define('DB_PASSWORD', $config['db']['pass']);

/** MySQL hostname */
define('DB_HOST', $config['db']['host']);

/** Database Table prefix. */
define('TABLE_PREFIX', $config['db']['pre'].'qbm_');

?>