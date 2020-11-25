<?php
include '../web_files/config.php';
include '../web_files/db.php';
include './functions/functions.php';
include './functions/login_registration_functions.php';

ifLogged();
handleRegistrationForm();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/admin-login.css">
	<title>Register to <?php echo $main_title;?></title>
</head>
<body>
	<div class="login_Page_container">
		<?php if($error!=""){ ?>
			<div class="show_error"><span><?php echo $error;?></span></div>
			<?php
		} ?>
		<div class="login_form_container">
			<form action="register.php" method="post">
				<fieldset class="login_form">
					<legend>Register to <?php echo $main_title;?></legend>
					<div class="form_data">
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/admin/images/username.png" class="login_image">
							<div class="input_double">
								<input class="login_input_2blocks" type="text" name="fname" placeholder="Enter your first name" required>
								<input class="login_input_2blocks" type="text" name="lname" placeholder="Enter your first name" required>
							</div>
						</div>
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/admin/images/username.png" class="login_image">
							<input class="login_input" type="text" name="username" placeholder="Set  your username" required>
						</div>
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/admin/images/password.png" class="login_image">
							<div class="input_double">
								<input class="login_input_2blocks" type="password" name="pass1" placeholder="Enter your password" required>
								<input class="login_input_2blocks" type="password" name="pass2" placeholder="Enter your password" required>
							</div>
						</div>
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/images/social/email.png" class="login_image">
							<input class="login_input" type="email" name="email" placeholder="Enter your email id" required>
						</div>
						<div class="radio_selector_block">
							<div class="role_block">
								<span class="select_role">Select Role</span>
								<input type="radio" class="role_radio" id="role_admin" name="role" value="admin" required>
								<label for="role_admin">Admin</label>
							</div>
							<div class="role_block">
								<input type="radio" class="role_radio" id="role_no" name="role" value="nodal_officer" required>
								<label for="role_no">Nodal Officer</label>
							</div>
							<div class="role_block">
								<input type="radio" class="role_radio" id="role_other" name="role" value="other" required>
								<label for="role_other">Other</label>
							</div>
						</div>
						<div class="login_form_btns">
							<input type="submit" name="register_submit" value="Register Your Account" class="login_submit_btn">
						</div>
						<hr>
						<div class="create_account_block">
							<span class="account_login_reg_text">Not An Account.</span>
							<a id="create_account_link" href="<?php echo $base_url; ?>/admin/login.php" class="account_login_reg_text"><img class="create_account_img" src="<?php echo $base_url; ?>/admin/images/login.png"></a>
						</div>
						<div class="back_btn_block">
							<a class="back_btn" href="<?php echo $base_url;?>">Homepage</a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		<?php 
		if($registration_status or (isset($_SESSION['flag_otp'])) == true){ ?>
			<div class="registration_successful_notice_container">
				<div class="registration_successful_notice_block">
					<div class="message_title_block">
						<span class="message">Message from Admin</span>
					</div>
					<div class="message_content_block">
						<span class="message_content">We have received your request for account registration. <br> Please Enter The OTP.<br> OTP is sent on your provided Email ID.<br><br><strong>Please Check Your Email</strong></span>
						<div class="otp_form_register_page">
							<form action="account-verify.php" method="post">
								<div class="otp_input_box_register_page">
									<label for="otp">OTP</label>
									<input type="text" name="acc_otp" placeholder="Enter OTP" required>
								</div>
								<div class="otp_submit_btn">
									<input type="submit" name="otp_submit" value="Submit">
								</div>
							</form>
						</div>
						<div class="back_btn_block">
							<a class="back_btn" href="<?php echo $base_url;?>">Home</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		} ?>
	</div>
</body>
</html>