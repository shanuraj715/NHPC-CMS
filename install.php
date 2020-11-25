<?php
ob_start();
if(file_exists('./web_files/db.php') and file_exists('./web_files/global_vars.php')){
	header('Location: ./index.php');
	exit();
}
if(isset($_POST['submit'])){
	include './admin/functions/functions.php';
	$site_url = $_POST['site_url'];
	$site_title = $_POST['site_title'];
	$site_title_f = $_POST['site_title_f'];
	$db_host = $_POST['db_host'];
	$db_user = $_POST['db_user'];
	$db_pass = $_POST['db_pass'];
	$db_name = $_POST['db_name'];
	$admin_email = $_POST['admin_email'];
	$admin_pass = $_POST['admin_pass'];
	$admin_pass = encPass($admin_pass);
	$file = fopen('./web_files/db.php', 'w');
	fwrite($file, "
<?php
	\$dbserver = '$db_host';
	\$dbuser = '$db_user';
	\$dbpass = '$db_pass';
	\$dbname = '$db_name';
	\$connection = mysqli_connect(\$dbserver, \$dbuser, \$dbpass, \$dbname);
	if(!\$connection){
		echo 'Connection to the database has not been established.' . mysqli_error(\$connection);
	}

?>
	");
	fclose($file);




	$global_vars = fopen('./web_files/global_vars.php', 'w');
	fwrite($global_vars,"
<?php
	\$base_url = '$site_url';
	\$main_f_title = '$site_title_f';
	\$main_title = '$site_title';
?>
	" );
	fclose($global_vars);




	$connection = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if($connection){
		$sql_file = fopen('./install.sql', 'r');
		while(!feof($sql_file)){
			$string = fgets($sql_file);
			$query = mysqli_query($connection, $string);
		}
	}
	fclose($sql_file);

	$admin_registration_qs = "INSERT INTO users(userid, username, password, email, age, role, status) VALUES (20151231,'admin01','$admin_pass','$admin_email',18,'admin','active')";
	$admin_registration_q = mysqli_query($connection, $admin_registration_qs);
	if($admin_registration_q){
		header('Location: ./');
		exit();
	}
	else{
		echo "Some Error Occured";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>CMS Install</title>
	<link rel="icon" href="./images/install.png">
	<script src="https://kit.fontawesome.com/49d14b2c4c.js"></script>
	<style type="text/css">
		body{padding: 0; margin: 0;}
		.page_bg{background: #2f3542; min-height: 100vh; min-width: 100vw;}
		.install_form_container{background: #3dc1d3; position: absolute; top: 50%;
			left: 50%; transform: translate(-50%, -50%); min-width: 720px; border-radius: 15px; border: solid #dfe4ea 3px; overflow: hidden; padding: 0 40px 20px 40px;}
			.install_image_block{display: flex;}
			.install_image_block img{width: 80px;}
			.install_image_block span{font-size: 40px; line-height: 80px; font-weight: bold; flex-grow: 10; text-align: center; width: 100%; color: #222f3e;}
			.site_input{margin: 10px 0; display: flex; background: rgba(229, 80, 57,0.2); padding: 5px 10px; border-radius: 5px; color: #2c3e50; border: solid white 2px;}
			i{font-size: 25px;}
			.site_input input[type=text], .site_input input[type=email], .site_input input[type=password]{ font-size: 18px; flex-grow: 10; margin-left: 10px; outline: none; background: none; border: none;}
			.site_input input[type=text]::placeholder, .site_input input[type=email]::placeholder, .site_input input[type=password]::placeholder{color: #c8d6e5;}
			.btns{margin: 30px 0 0 0; padding: 0 20%;}
			.btns input[type=submit]{background: #57606f; border-radius: 4px; border: solid #ffa502 2px; font-size: 24px; color: #ced6e0; padding: 5px 30px; width: 100%;}
			.divider{min-height: 2px; background: #2f3542; margin: 15px 10px;}
	</style>
</head>
<body>
	<div class="page_bg">
		<div class="install_form_container">
			<form action="" method="POST">
				<div class="install_image_block">
					<img src="./images/install.png">
					<span>CMS Installation</span>
				</div>
				<div class="site_input">
					<i class="fas fa-link"></i>
					<input type="text" name="site_url" placeholder="CMS Directory Address. Ex: http://www.xyz.com [Do not add slash (/) after path]">
				</div>
				<div class="site_input">
					<i class="fas fa-heading"></i>
					<input type="text" name="site_title" placeholder="Web Site Short Title. Ex: CMS">
				</div>
				<div class="site_input">
					<i class="fas fa-heading"></i>
					<input type="text" name="site_title_f" placeholder="Web Site Long Title. Ex: Content Manangement System">
				</div>
				<div class="divider"></div>
				<div class="site_input">
					<i class="fas fa-server"></i>
					<input type="text" name="db_host" placeholder="Database Host/Server Address">
				</div>
				<div class="site_input">
					<i class="fas fa-user-tie"></i>
					<input type="text" name="db_user" placeholder="Database Username">
				</div>
				<div class="site_input">
					<i class="fas fa-lock"></i>
					<input type="text" name="db_pass" placeholder="Database Password">
				</div>
				<div class="site_input">
					<i class="fas fa-heading"></i>
					<input type="text" name="db_name" placeholder="Database Name">
				</div>
				<div class="divider"></div>
				<div class="site_input">
					<i class="fas fa-envelope"></i>
					<input type="email" name="admin_email" placeholder="Enter Your Email Id. For Login">
				</div>
				<div class="site_input">
					<i class="fas fa-lock"></i>
					<input type="password" name="admin_pass" placeholder="Set Your Password">
				</div>
				<div class="btns">
					<input type="submit" name="submit" value="Install Now">
				</div>
				
			</form>
		</div>
	</div>
</body>
</html>