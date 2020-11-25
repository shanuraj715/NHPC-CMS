<?php 
if(!file_exists('./web_files/db.php') and !file_exists('./web_files/global_vars.php')){
	ob_start();
	header('Location: ./installation');
	exit();
}
include "./web_files/config.php";
include './web_files/db.php';
include './functions/functions.php'; ?>
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
			<?php if(isset($_GET['category'])){ ?>
				<div class="post_search_title_block">
					<?php $cat_id = $_GET['category'];
					$sql = "SELECT * from category where cat_id = $cat_id";
					$query = mysqli_query($connection, $sql);
					global $cat_data;
					$cat_data = mysqli_fetch_assoc($query); ?>
					<span class="post_search_title">Sorting By Category <strong><?php echo ucfirst($cat_data['cat_title']);?></strong></span>
				</div>
	<?php } ?>
			<?php posts();?>
		</div>
		<div class="sidebar_container">
			<?php include './includes/sidebar.php';?>
		</div>
	</div>
	<?php include './includes/footer.php';?>
</body>
</html>