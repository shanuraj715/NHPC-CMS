<?php
include '../web_files/config.php';
include '../web_files/db.php';
include './functions/functions.php';
ifNotLogged(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="icon" href="./images/fevicon.png">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/dashboard.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/edit_navbar.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/post.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/account.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/users.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>/admin/css/category.css">
	<script type="text/javascript" src="<?php echo $base_url;?>/admin/js/sidebar_functions.js"></script>
	<title><?php echo strtoupper($_SESSION['username']) . ' - ' . ucwords($_SESSION['role']) . ' Dashboard';?></title>

	<script type="text/javascript" src="<?php echo $base_url;?>/admin/functions/functions.js"></script>
</head>
<body>
	<div class="dashboard_page_container">
		<div class="dashboard_top_navbar">
			<div class="navbar_left">
				<a class="cms_title bottom_border" href="<?php echo $base_url;?>/admin/dashboard.php"><?php echo $main_title;?></a>
			</div>
			<div class="navbar_right_block">
				<div class="navbar_right_elements">
					<?php $image = getUserImage(); ?>
					<div class="user_image_nav_block">
						<img class="user_image_nav" src="<?php echo $base_url;?>/images/users/<?php echo $image;?>">
					</div>
					<span class="dashboard_top_userid navbar_btns bottom_border">User Id : <?php echo $_SESSION['userid'];?></span>
					<a href="<?php echo $base_url;?>/admin/dashboard.php?source=account&action=edit_profile" class="navbar_btns bottom_border"><?php echo $_SESSION['username'];?></a>
					<a href="<?php echo $base_url;?>/admin/logout.php"><img class="logout_img_btn" src="<?php echo $base_url;?>/admin/images/logout.png"></a>
				</div>
			</div>
		</div>
		<div class="dashboard_content_container">
			<div class="dashboard_sidebar">
				<?php admin_sidebar();?>
				<script type="text/javascript">
					var url = window.location.href;
					var url = new URL(url);
					var source = url.searchParams.get("source");
					var action = url.searchParams.get("action");
					var element = document.getElementById(action);
					element.classList.add("sidebar_link_active");
				</script>
			</div>
			<div class="dashboard_dynamic_page_data">
				<?php dashboardPage();?>
			</div>
		</div>
		<div class="dashboard_footer_container">
			<span class="dashboard_footer_text">Copyright &copy; <?php date('Y');?> | All Rights Reserved | <?php echo $main_f_title;?></span>
		</div>
	</div>
</body>
</html>