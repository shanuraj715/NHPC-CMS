<?php include './web_files/config.php';
include './web_files/db.php';
include './functions/functions.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php include './includes/linked_files.php';?>
	<title>Search Result of <?php echo searchData() . ' - ' . $main_title;?></title>
</head>
<body>
	<?php $search = searchData(); ?>
	<?php include './includes/header.php';?>
	<div class="page_container">
		<div class="posts_container">
			<div class="post_search_title_block">
				<span class="post_search_title">Search Result of <strong><?php echo $search;?></strong>
			</div>
			<?php echo searchPosts(searchData());?>
		</div>
		<div class="sidebar_container">
			<?php include './includes/sidebar.php';?>
		</div>
	</div>
	<?php include './includes/footer.php';?>
</body>
</html>