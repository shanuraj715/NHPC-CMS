<?php

function posts(){
	global $connection; global $base_url;
	$post_query_string = "SELECT post_id, title, author, category, post_image, post_date, content ";
	$post_query_string .= "FROM posts ";
	$post_query_string .= "WHERE status = 'published' ";
	if(isset($_GET['category'])){
		$category = $_GET['category'];
		$post_query_string .= "and category = '$category' ";
	}
	$post_query_string .= "ORDER BY post_date DESC";
	$post_query = mysqli_query($connection, $post_query_string);
	$post_result = mysqli_fetch_assoc($post_query);
	if($post_result['title']!=NULL){
		do{ 
			postBlock($post_result);
		}while($post_result = mysqli_fetch_assoc($post_query));
	}
	else{ ?>
		<div class="err_404_block">
			<img src="<?php echo $base_url;?>/images/no_post.png" class="error404">
		</div>
		<?php
	}
}

function limitWordsOfPost($data){
	$length = 400;
	if(strlen($data)>$length){
		$data = substr($data, 0, $length);
	}
	return $data . '......';
}

function searchPosts($search){
	global $connection;
	global $base_url;
	$search = mysqli_real_escape_string($connection, $search);
	$search_query_string = "SELECT post_id, title, author, category, post_image, post_date, content ";
	$search_query_string .= "FROM posts ";
	$search_query_string .= "WHERE status = 'published' and tags LIKE '%$search%' or title LIKE '%$search%' and status = 'published'";
	$search_query_string .= "ORDER BY post_date DESC";
	$search_query = mysqli_query($connection,$search_query_string);
	$post_result = mysqli_fetch_assoc($search_query);
	if($post_result['title']!=NULL){
		do{ 
			postBlock($post_result);
		}while($post_result = mysqli_fetch_assoc($search_query));
	}
	else{ ?>
		<div class="err_404_block">
			<img src="<?php echo $base_url;?>/images/404.png" class="error404">
		</div>
		<?php
	}
}

function postBlock($post_result){
	global $base_url;
	global $connection;
	global $cat_data;
	$catid = $post_result['category'];
	$cat_name = "SELECT * from category where cat_id = $catid";
	$cat_name_query = mysqli_query($connection, $cat_name);
	$cat_name_data = mysqli_fetch_assoc($cat_name_query);
	?>
	<div class="post_block">
		<div class="image_block">
			<img src="<?php echo $base_url;?>/images/posts/<?php echo $post_result['post_image'];?>" alt="<?php echo $post_result['post_image'];?>" class="post_image">
		</div>
		<div class="post_title_block">
			<a class="post_title_link" href="<?php echo $base_url;?>/post/<?php echo $post_result['post_id'];?>"><?php echo $post_result['title'];?></a>
		</div>
		<div class="post_meta_block">
			<a class="post_meta_text" href="<?php echo $base_url;?>/?category=<?php echo $cat_name_data['cat_id'];?>" id="post_meta_category"><?php echo ucfirst($cat_name_data['cat_title']);?></a>
			<span class="post_meta_divider"> / </span>
			<span class="post_meta_text" id="post_meta_author">Author: <?php echo $post_result['author'];?></span>
			<span class="post_meta_divider"> / </span>
			<span class="post_meta_text" id="post_meta_date">Date: <?php echo $post_result['post_date'];?></span>
		</div>
		<div class="post_desc_block">
		    <?php
		    $content = $post_result['content'];
		    settype($content, 'string'); ?>
			<p class="post_desc"><?php echo limitWordsOfPost($content);?></p>
		</div>
		<div class="post_read_more_block">
			<a href="<?php echo $base_url;?>/post/<?php echo $post_result['post_id'];?>" class="read_more_btn_home" title="Read more about this post">Continue...</a>
		</div>
		<div class="after_post_divider"></div>
	</div>
	<?php
}

function sidebarLatestPosts(){
	global $connection;
	global $base_url;
	$latest_post_query_string = "SELECT post_id, title, post_image ";
	$latest_post_query_string .= "FROM posts ";
	$latest_post_query_string .= "WHERE status = 'published' ";
	$latest_post_query_string .= "LIMIT 10";
	$latest_post_query = mysqli_query($connection,$latest_post_query_string);
	$latest_posts = mysqli_fetch_assoc($latest_post_query);
	if($latest_posts['title']!=NULL){
		do{ ?>
			<div class="sidebar_post_block">
				<div class="image_block">
					<img src="<?php echo $base_url;?>/images/posts/<?php echo $latest_posts['post_image'];?>" alt="<?php echo $latest_posts['post_image'];?>" class="sidebar_post_image">
				</div>
				<div class="sidebar_post_title_block">
					<a class="sidebar_post_title" href="<?php echo $base_url;?>/post/<?php echo $latest_posts['post_id'];?>"><?php echo $latest_posts['title'];?></a>
				</div>
			</div>
			<?php
		}while($latest_posts = mysqli_fetch_assoc($latest_post_query));
	} ?>
	
	<?php
}


function headerTopBtns(){
	global $base_url;
	if(isset($_SESSION['userid']) and isset($_SESSION['username']) and isset($_SESSION['role'])){ ?>
		<a href="<?php echo $base_url;?>/admin/dashboard.php" class="header_top_btn">Dashboard</a>
		<a href="<?php echo $base_url;?>/admin/logout.php" class="header_top_btn">Logout</a>
		<?php
	}
	else{ ?>
		<a href="<?php echo $base_url;?>/admin/login.php" class="header_top_btn">Login</a>
		<?php
	}
}

function sidebarCategorySort(){
	global $connection;
	global $base_url;
	$category_query_string = "SELECT distinct cat_title, cat_id ";
	$category_query_string .= "from category";
	$category_query = mysqli_query($connection, $category_query_string);
	while($category_result = mysqli_fetch_assoc($category_query)){ ?>
		<div class="sidebar_post_block">
			<div class="sidebar_post_title_block">
				<ul>
					<li>
						<a class="sidebar_post_title" href="<?php echo $base_url;?>/?category=<?php echo $category_result['cat_id'];?>"><?php echo ucfirst($category_result['cat_title']);?></a>
					</li>
				</ul>
			</div>
		</div>

	<?php
	}
}

function getPost(){
	global $connection;
	global $base_url;
	if(isset($_GET['post']) and !empty($_GET['post'])){
		$id = $_GET['post'];
		$post_qs = "SELECT * from posts where post_id = '$id'";
		if(!isset($_SESSION['role'])=='nodal_officer'){
			$post_qs .= " and status = 'published'";
		}
		$post_q = mysqli_query($connection, $post_qs);
		global $post_data;
		$post_data = mysqli_fetch_assoc($post_q);
		if($post_data['post_id']==NULL){
			$post = false;
		}
		else{
			$post = true;
		}
	}
}

function showPost($post_data){ // getting post data from getPost() function 
	global $base_url;
	global $page_url;
	global $connection; ?>
	<div class="post_show_container">
		<div class="post_title_block">
			<span class="post_title"><?php echo $post_data['title'];?></span>
		</div>
		<?php
		if($post_data['post_id']==true){
			$cat_id = $post_data['category'];
			$cat = "SELECT cat_title from category where cat_id = $cat_id";
			$cat_q = mysqli_query($connection, $cat);
			$cat_name = mysqli_fetch_assoc($cat_q); ?>
			<div class="post_meta_block">
				<span class="post_category">Category - <?php echo ucwords($cat_name['cat_title']);?></span>
			</div>
			<div class="post_meta_block">
				<span class="post_author">Author - <?php echo $post_data['author'];?></span>
			</div>
			<div class="post_meta_block">
				<span class="post_date">Date Published - <?php echo $post_data['post_date'];?></span>
			</div>
			<div class="post_image_block">
				<img class="view_post_image" src="<?php echo $base_url;?>/images/posts/<?php echo $post_data['post_image'];?>">
			</div>
			<div class="post_content_block">
				<p class="post_content"><?php echo $post_data['content'];?></p>
			</div>
			<div class="after_post_divider"></div>
			<div class="post_tags_block">
				<span class="post_tag_title">Tags in this post: </span>
				<?php
				$tags = $post_data['tags'];
				$trimmed_tags = explode(', ', $tags);
				$trimmed_tags = explode(',', $tags);
				foreach ($trimmed_tags as $index => $value) {
				$value = str_replace(' ', '&nbsp;', $value); ?>
					<a class="tag_link" href="<?php echo $base_url;?>/search.php?s=<?php echo str_replace('&nbsp;', ' ', $value);?>" title="Seach this tag on this site"><?php echo $value;?></a>
					<?php
				} ?>
			</div>
			<?php
		}
		else{ ?>
			<div class="err_404_block">
				<img src="<?php echo $base_url;?>/images/404.png" class="error404">
			</div>
			<?php
		} ?>
	</div>
	<?php
}
?>