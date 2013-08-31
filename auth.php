<?php
include('includes/config.inc.php');

$api_key                 = API_KEY;
$api_secret              = API_SECRET;
$default_redirect        = "/apply.php";
$permissions             = "write";
$path_to_phpFlickr_class = "./phpFlickr/";

ob_start();
require_once($path_to_phpFlickr_class . "phpFlickr.php");
unset($_SESSION['phpFlickr_auth_token']);
 
if (!empty($_GET['extra'])) {
	$redirect = $_GET['extra'];
}

$f = new phpFlickr($api_key, $api_secret);

if (empty($_GET['frob'])) {
    $f->auth($permissions, false);
} else {
    $f->auth_getToken($_GET['frob']);
}

if (empty($redirect)) {
	header("Location: " . $default_redirect);
} else {
	header("Location: " . $redirect);
}

?>