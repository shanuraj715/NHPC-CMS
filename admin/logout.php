<?php
include '../web_files/config.php';
global $base_url;
if(isset($_GET['redirect_page'])){
	$page = $_GET['redirect_page'];
}
else{
	$page = $base_url;
}
if(isset($_SESSION['userid']) and isset($_SESSION['username'])){
	session_destroy();
	header('Location: ' . $page);
}
else{
	header('Location: ' . $base_url);
}