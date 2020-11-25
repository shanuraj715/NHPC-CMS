<?php
include '../web_files/config.php';
include '../web_files/db.php';
include './functions/functions.php';
include './functions/login_registration_functions.php';

ifLogged();
handleLoginForm();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/admin-login.css">
	<title>Login to <?php echo $main_title;?></title>
</head>
<body>
	<div class="login_Page_container">
		<?php if($error!=""){ ?>
			<div class="show_error"><span><?php echo $error;?></span></div>
			<?php
		} ?>
		<div class="login_form_container">
			<form action="login.php" method="post">
				<fieldset class="login_form">
					<legend>Login to <?php echo $main_title;?></legend>
					<div class="form_data">
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/admin/images/username.png" class="login_image">
							<input class="login_input" type="text" name="username" placeholder="Username, Userid or Email Id" required>
						</div>
						<div class="input_box_block">
							<img src="<?php echo $base_url;?>/admin/images/password.png" class="login_image">
							<input class="login_input" type="password" name="password" placeholder="Account Password" required>
						</div>
						<div class="radio_selector_block">
							<div class="role_block">
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
							<input type="submit" name="login_submit" value="Login" class="login_submit_btn">
						</div>
						<hr>
						<div class="create_account_block">
							<span class="account_login_reg_text">Not An Account.</span>
							<a id="create_account_link" href="<?php echo $base_url; ?>/admin/register.php" class="account_login_reg_text"><img class="create_account_img" src="<?php echo $base_url; ?>/admin/images/register.png"></a>
						</div>
						<div class="back_btn_block">
							<a class="back_btn" href="<?php echo $base_url;?>">Homepage</a>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</body>
</html>