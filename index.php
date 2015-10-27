<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

session_name('unique_session_name');
session_start();

define('RA_ROOT_PATH', dirname(__FILE__));
define('RA_SYSTEM_ROOT_PATH', RA_ROOT_PATH . '/vendor/rafw');

include RA_SYSTEM_ROOT_PATH . '/core/default_dirset.php';
include RA_SYSTEM_CORE_PATH . '/debug.php';
include RA_SYSTEM_CORE_PATH . '/run.php';