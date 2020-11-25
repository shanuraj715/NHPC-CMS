<?php
global $connection;
$today_date = date('Y-m-d');
if($_GET['action']=='delete'){
	if(isset($_GET['post_id'])){
		$post_id = $_GET['post_id'];
		deletePost($post_id);
	}
}
if(isset($_POST['delete_confirm'])){
	if($_POST['delete_confirm']=="Yes"){
		deletePostFinally();
	}
	else{
		header('Location: ' . $base_url . '/admin/dashboard.php?source=post&action=view');
	}
}

if(isset($_POST['create_post_submit'])){
	$post_title = $_POST['post_title'];
	$post_title = filterStringForSqlInjections($post_title);
	$post_author = $_POST['post_author'];
	$post_author = filterStringForSqlInjections($post_author);
	$post_category = $_POST['post_category'];
	$post_category = filterStringForSqlInjections($post_category);
	$post_status = $_POST['post_status'];
	$post_status = filterStringForSqlInjections($post_status);
	$post_image = $_FILES['image']['name'];
	$post_image_temp = $_FILES['image']['tmp_name'];
	$post_tags = $_POST['post_tags'];
	$post_tags = filterStringForSqlInjections($post_tags);
	$post_content = $_POST['post_content'];
	$post_content = filterStringForSqlInjections($post_content);
	$post_date = date('y-m-d');

	$image_status = move_uploaded_file($post_image_temp, "../images/posts/$post_image");
	if($image_status){
		$insert_qs = "INSERT into posts(title, author, category, status, post_image, tags, post_date, content) VALUES('$post_title', '$post_author', '$post_category', '$post_status', '$post_image', '$post_tags', '$post_date', '$post_content')";
		$insert_q = mysqli_query($connection, $insert_qs);
		if($insert_q){
			echo "Post Created";
		}
		else{
			echo "Unable to create post." . mysqli_error($connection);
		}
	}
	else{
		echo "Post not created because the selected image was not successfully uploaded.";
	}
}

if(isset($_POST['update_post_submit'])){
	$post_id = $_GET['post_id'];
	$post_title = $_POST['post_title'];
	$post_title = filterStringForSqlInjections($post_title);
	$post_author = $_POST['post_author'];
	$post_author = filterStringForSqlInjections($post_author);
	$post_category = $_POST['post_category'];
	$post_category = filterStringForSqlInjections($post_category);
	$post_status = $_POST['post_status'];
	$post_status = filterStringForSqlInjections($post_status);
	$post_tags = $_POST['post_tags'];
	$post_tags = filterStringForSqlInjections($post_tags);
	$post_content = $_POST['post_content'];
	$post_content = filterStringForSqlInjections($post_content);
	$post_date = $_POST['post_date'];
	$post_date = filterStringForSqlInjections($post_date);
	if(isset($_FILES['image']['name'])){
		if($_FILES['image']['name']==""){
			$update_qs = "UPDATE posts SET title = '$post_title', author = '$post_author', category = '$post_category', status = '$post_status', tags = '$post_tags', post_date = '$post_date', content = '$post_content' where post_id = $post_id";
		}
		else{
			$post_image = $_FILES['image']['name'];
			$post_image_temp = $_FILES['image']['tmp_name'];
			$image_status = move_uploaded_file($post_image_temp, "../images/posts/$post_image");
			$update_qs = "UPDATE posts SET title = '$post_title', author = '$post_author', category = '$post_category', status = '$post_status', post_image = '$post_image', tags = '$post_tags', post_date = '$post_date', content = '$post_content' where post_id = $post_id";
		}
		$update_q = mysqli_query($connection, $update_qs);
		if($update_q){
			echo "Post Updated";
		}
		else{
			echo "Unable to Update post." . mysqli_error($connection);
		}
	}
}


	if($_GET['action']=='view'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Manage Posts</span>
			</div>

			<div class="page_work_data_container">
				<?php 
				global $connection;
				$stat_str = "SELECT status from posts";
				$stat_q = mysqli_query($connection, $stat_str);
				$total = 0;
				$published = 0;
				$draft = 0;
				$trash = 0;
				while($stat = mysqli_fetch_assoc($stat_q)){
					$total += 1;
					if($stat['status']=='published'){
						$published += 1;
					}
					elseif($stat['status']=='draft'){
						$draft += 1;
					}
					elseif($stat['status']=='trash'){
						$trash += 1;
					}
				} ?>
				<div class="post_stats_block">
					<div class="post_stat_block1">
						<a href="/admin/dashboard.php?source=post&action=view" class="post_stat_title">Total Posts</a>
						<span class="post_stat_value"><?php echo $total; ?></span>
					</div>
					<div class="post_stat_block1">
						<a href="/admin/dashboard.php?source=post&action=view&posts=published" class="post_stat_title">Published Posts</a>
						<span class="post_stat_value"><?php echo $published; ?></span>
					</div>
					<div class="post_stat_block1">
						<a href="/admin/dashboard.php?source=post&action=view&posts=draft" class="post_stat_title">Draft Posts</a>
						<span class="post_stat_value"><?php echo $draft; ?></span>
					</div>
					<div class="post_stat_block1">
						<a href="/admin/dashboard.php?source=post&action=view&posts=trash" class="post_stat_title">Trash Posts</a>
						<span class="post_stat_value"><?php echo $trash; ?></span>
					</div>
				</div>
			</div>
			<div class="page_work_data_container">
				<div class="table_container">
					<table class="view_post_table">
						<tr class="view_post_tr_th">
							<th id="post_action" class="view_post_th">Action</th>
							<th id="post_id" class="view_post_th">Post Id</th>
							<th id="post_title" class="view_post_th">Title</th>
							<th id="post_author" class="view_post_th">Author</th>
							<th id="post_category" class="view_post_th">Category</th>
							<th id="post_status" class="view_post_th">Status</th>
							<th class="view_post_th">Image</th>
							<th id="post_date" class="view_post_th">Date</th>
						</tr>
						<?php
						global $connection;
						$post_list = "SELECT * from posts ";
						if(isset($_GET['posts'])){
							$status = $_GET['posts'];
							$post_list .= "WHERE status = '$status' ";
						}
						else{
							$post_list .= "WHERE status != 'trash' ";
						}
						$post_list  .= "order by post_id desc";
						$post_list_query = mysqli_query($connection, $post_list);
						while($have_posts = mysqli_fetch_assoc($post_list_query)){ ?>
							<tr class="view_post_tr">
								<td id="post_action" class="view_post_td grid_view">
									<a class="post_view_action" href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=update&post_id=<?php echo $have_posts['post_id'];?>" title="Edit This Post">Edit</a>
									<a class="post_view_action" href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=delete&post_id=<?php echo $have_posts['post_id'];?>" title="Delete This Post">Delete</a>
								</td>
								<td id="post_id" class="view_post_td"><?php echo $have_posts['post_id'];?></td>
								<td id="post_title" class="view_post_td">
									<a class="manage_post_title_link" href="<?php echo $base_url;?>/post/<?php echo $have_posts['post_id'];?>" target="_blank"><?php echo $have_posts['title'];?></a>
								</td>
								<td id="post_author" class="view_post_td"><?php echo $have_posts['author'];?></td>
								<?php
								$catid = $have_posts['category'];
								$cat_name = "SELECT * from category where cat_id = $catid";
								$cat_name_query = mysqli_query($connection, $cat_name);
								$cat_name_data = mysqli_fetch_assoc($cat_name_query); ?>
								<td id="post_category" class="view_post_td"><?php echo ucwords($cat_name_data['cat_title']);?></td>
								<td id="post_status" class="view_post_td"><?php echo ucfirst($have_posts['status']);?></td>
								<td id="post_image" class="view_post_td"><img alt="<?php echo $have_posts['post_image'];?>" src="<?php echo $base_url;?>/images/posts/<?php echo $have_posts['post_image'];?>"></td>
								<td id="post_date" class="view_post_td"><?php echo $have_posts['post_date'];?></td>
							</tr>
							<?php
						} ?>
						
					</table>
				</div>
			</div>
		</div>
		<?php 
	}









	elseif($_GET['action']=='create'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Create New Post</span>
			</div>
			<div class="page_work_data_container">
				<div class="post_form">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Post Title</span>
							<input class="post_form_input" type="text" name="post_title" required>
						</div>
						<div class="select_btns_seperator">
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Author -->
								<span class="post_form_title">Post Author</span>
								<select class="post_author_select" name="post_author">
									<?php authorList();?>
								</select>
							</div>
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Category -->
								<span class="post_form_title">Post Category</span>
								<select class="post_category_select" name="post_category">
									<?php categoryList();?>
								</select>
							</div>
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Status -->
								<span class="post_form_title">Post Status</span>
								<select class="post_status_select" name="post_status">
									<option value="draft">Draft</option>
									<option value="published">Publish</option>
								</select>
							</div>
						</div>
						<div class="post_form_file_upload_block post_form_blocks_margin">
							<span class="post_form_title">Post Image</span>
							<input class="image_upload_btn" type="file" name="image">
						</div>
						<div class="post_form_textarea_block post_form_blocks_margin">
							<span class="post_form_title">Post Tags</span>
							<textarea class="post_tags" rows="4" cols="100" name="post_tags" placeholder='Enter Tags of this post, Seperated by comma ","'></textarea>
						</div>
						<div class="post_form_date_block post_form_blocks_margin">
							<span class="post_form_title">Post Date</span>
							<span class="post_date"><?php echo $today_date;?>
								<span class="date_info">Not Editable. System Will Store Today Date Automatically. You Can Edit Date By Update Option.
							</span>
						</div>
						<div class="post_form_content_block post_form_blocks_margin">
							<span class="post_form_title">Post Content</span>
							<textarea name="post_content" class="post_content" rows="10" cols="100" placeholder="Enter Post Content" required></textarea>
						</div>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="create_post_submit" value="Create Post">
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php 
	}






























	elseif($_GET['action']=='update'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Update Post</span>
			</div>
			<div class="page_work_data_container">
				<?php if(!isset($_GET['post_id'])){ ?>
					<div class="notification_display_block">
						<span class="notification_text">Plaese select a post to edit. Click On The Button To Select Post</span>
						<div class="btn_align_right">
							<a href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=view" class="submit_btn">Select Post</a>
						</div>
					</div>
				<?php
				exit();
				}
				if(isset($_GET['post_id'])){
					$post_id = $_GET['post_id'];
					$post_data_qs = "SELECT * from posts where post_id = $post_id";
					$post_data_q = mysqli_query($connection, $post_data_qs);
					$post_data = mysqli_fetch_assoc($post_data_q);
				} ?>
				<div class="post_form">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Post Title</span>
							<input class="post_form_input" type="text" value="<?php echo $post_data['title'];?>" name="post_title" required>
						</div>
						<div class="select_btns_seperator">
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Author -->
								<span class="post_form_title">Post Author</span>
								<select class="post_author_select" name="post_author">
									<?php
									$author = $post_data['author'];
									$author_qs = "SELECT * from users where username = '$author'";
									$author_q = mysqli_query($connection, $author_qs);
									$author = mysqli_fetch_assoc($author_q); ?>
									<option value="<?php echo $author['username'];?>"><?php echo $author['username'];?></option>
									<?php authorList();?>
								</select>
							</div>
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Category -->
								<span class="post_form_title">Post Category</span>
								<select class="post_category_select" name="post_category">
									<?php
									$category = $post_data['category'];
									$category_qs = "SELECT * from category where cat_id = '$category'";
									$category_q = mysqli_query($connection, $category_qs);
									$category = mysqli_fetch_assoc($category_q); ?>
									<option value="<?php echo $category['cat_id'];?>"><?php echo ucwords($category['cat_title']);?></option>
									<?php categoryList();?>
								</select>
							</div>
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Status -->
								<span class="post_form_title">Post Status</span>
								<select class="post_status_select" name="post_status">
									<option value="draft">Draft</option>
									<option value="published">Publish</option>
								</select>
							</div>
						</div>
						<div class="post_form_file_upload_block post_form_blocks_margin">
							<span class="post_form_title">Post Image</span>
							<input class="image_upload_btn" type="file" name="image">
						</div>
						<div class="post_form_textarea_block post_form_blocks_margin">
							<span class="post_form_title">Post Tags</span>
							<textarea class="post_tags" rows="4" cols="100" name="post_tags" placeholder='Enter Tags of this post, Seperated by comma ","'><?php echo $post_data['tags'];?></textarea>
						</div>
						<div class="post_form_date_block post_form_blocks_margin">
							<span class="post_form_title">Post Date</span>
							<input class="post_form_input" type="text" value="<?php echo $post_data['post_date'];?>" name="post_date" placeholder='Please Use This Format "YYYY-MM-DD"' required>
						</div>
						<div class="post_form_content_block post_form_blocks_margin">
							<span class="post_form_title">Post Content</span>
							<textarea name="post_content" class="post_content" rows="10" cols="100" placeholder="Enter Post Content" required><?php echo $post_data['content'];?></textarea>
						</div>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="update_post_submit" value="Update Post">
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php 
	}?>
