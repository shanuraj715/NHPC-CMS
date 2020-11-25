<?php



session_start();

ob_start();



include 'global_vars.php';



date_default_timezone_set('Asia/Kolkata');



$page_url_original = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

$page_url = str_replace('&', '%20', $page_url_original);





// $page = parse_url($page_url_original);



// echo $sourceUrl = $page['host'];







// the login_key is used to logout all users from their dashboard.

// to logout all users, change this login_key data.

$login_key = "sixteencharacter";



?>