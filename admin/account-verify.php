<?php
include '../web_files/config.php';
include '../web_files/db.php';

$account_status = 'otp_not_verified';

if(isset($_GET['user']) and isset($_GET['key'])){
	$username = $_GET['user'];
	$key = $_GET['key'];
	$sql = "SELECT * from users_otp where username = '$username' and authentication_key = '$key'";
	$query = mysqli_query($connection, $sql);
	$result = mysqli_fetch_assoc($query);
	if($result['otp']!=NULL){
		deleteRowFromOtpTable($username);
		$account_status = 'otp_verified';
		session_destroy();
	}
	else{
		$account_status = 'otp_not_verified';
	}
}
elseif(isset($_SESSION['otp_username'])){
	$username = $_SESSION['otp_username'];
	if(isset($_POST['acc_otp']) and is_numeric($_POST['acc_otp'])){
		$otp = $_POST['acc_otp'];
		$otp = mysqli_real_escape_string($connection, $otp);
		$sql = "SELECT * from users_otp where otp = $otp and username = '$username' ORDER BY ID DESC LIMIT 1";
		$query = mysqli_query($connection, $sql);
		$result = mysqli_num_rows($query);
		if($result==1){
			deleteRowFromOtpTable($username);
			$account_status = 'otp_verified';
			session_destroy();
		}
		else{
			$account_status = 'otp_not_verified';
		}
	}
}
else{
	header('Location: ' . $base_url . '/admin');
}

function deleteRowFromOtpTable($username){
	global $connection;
	global $base_url;
	$sql = "DELETE from users_otp where username = '$username'";
	$query = mysqli_query($connection, $sql);
	if(!$query){
		exit();
	}
	updateUserStatus($username);
}

function updateUserStatus($username){
	global $connection;
	global $base_url;
	$sql = "UPDATE users SET status = 'pending' where username = '$username'";
	$query = mysqli_query($connection, $sql);
	if(!$query){
		exit();
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/account-verify.css">
	<title>Verify Your Account</title>
</head>
<body>
	<?php
	if($account_status == 'otp_verified'){ ?>
		<div class="page_background">
			<div class="page_content">
				<div class="message_header_block">
					<span class="message_header">Message From <?php echo $main_title; ?></span>
				</div>
				<div class="message_block">
					<p class="message congo">Congratulations...</p>
					<p class="message text">Your Email is successfully verified. We will verify your account details and it can take upto 7 working days. Once we approve your account then you can login to your dashboard.</p>
					<p class="message">We will inform you via email when your account will activate or deactivate</p>
					<p class="message greeting">Thanks...</p>
					<div class="back_btn_block">
						<a class="back_btn" href="<?php echo $base_url;?>">Home</a>
					</div>
				</div>
			</div>
		</div>
	<?php
	} ?>
</body>
</html>