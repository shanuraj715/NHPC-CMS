<?php
include './web_files/config.php';
include './web_files/db.php';
include './functions/functions.php';
getPost();
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $main_f_title;?></title>
		<?php include './includes/linked_files.php';?>
	</head>
	<body>
		<?php include './includes/header.php';?>
		<div class="page_container">
			<div class="posts_container">
				<?php showPost($post_data);?>
			</div>
			<div class="sidebar_container">
				<?php include './includes/sidebar.php';?>
			</div>
		</div>
		<?php include './includes/footer.php';?>
	</body>
</html>