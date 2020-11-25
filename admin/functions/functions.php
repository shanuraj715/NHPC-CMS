<?php


function checkLoginStatus(){

	if(!isset($_SESSION['username']) and !isset($_SESSION['userid'])){

		header('Location: ' . $base_url . '/admin/login.php');

	}

	else{

		header('Location: ' . $base_url . '/admin/dashboard.php');

	}

}



function ifLogged(){

	if(isset($_SESSION['username']) and isset($_SESSION['userid'])){

		header('Location: ' . $base_url . '/admin/dashboard.php');
		exit();

	}

}



function ifNotLogged(){

	global $login_key;

	if(!isset($_SESSION['username']) and !isset($_SESSION['userid'])){

		header('Location: ' . $base_url . '/admin/login.php');

	}

	else{

		if($_SESSION['login_key']!=$login_key){

			$redirect_page = $base_url . '/admin/login.php';

			header('Location: ' . $base_url . '/admin/logout.php?redirect_page=' . $redirect_page);

		}

	}

}



function encPass($pass){

	$hash = "$2y$10$";

	$salt = "iamagoodcoderofphp1996";

	$salt_hash = $hash . $salt;

	$encrypted = crypt($salt_hash, $pass);

	return $encrypted;

}





function filterStringForSqlInjections($data){

	global $connection;

	$data = mysqli_real_escape_string($connection, $data);

	return $data;

}







function setSession($uid, $fname, $lname, $uname, $uemail, $urole){

	global $base_url;

	global $login_key;

	$_SESSION['userid'] = $uid;

	$_SESSION['fname'] = $fname;

	$_SESSION['lname'] = $lname;

	$_SESSION['username'] = $uname;

	$_SESSION['email'] = $uemail;

	$_SESSION['role'] = $urole;

	$date = date('d-F-Y');

	$time = date('h:i:s A');

	$_SESSION['date'] = $date;

	$_SESSION['time'] = $time;

	$_SESSION['login_key'] = $login_key;

	if(isset($_SESSION['userid'])){

		header('Location: ' . $base_url . '/admin/dashboard.php');

	}

	else{

		header('Location: ' . $base_url . '/admin/login.php');

	}

}



function admin_sidebar(){

	global $connection;

	global $base_url;

	sidebarMenuForUsers(); ?>

	<div class="dashboard_sidebar_divider"></div>

	<div class="sidebar_btn_block">

		<a id="edit_profile" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=account&action=edit_profile">Edit Profile</a>

	</div>

	<div class="dashboard_sidebar_divider"></div>

	<div class="sidebar_btn_block">

		<span id="edit_profile" class="sidebar_link"><?php echo 'Your IP : '. userIP();?></span>

	</div>

	<div class="sidebar_btn_block">

		<a id="edit_profile" class="sidebar_link" href="<?php echo $base_url;?>/admin/logout.php">Logout Your Account</a>

	</div>

<?php

}



function sidebarMenuForUsers(){

	global $connection;

	global $base_url;

	if($_SESSION['role']=='admin'){ ?>

		<div class="sidebar_btn_block">

			<a id="insert" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=navbar&action=insert">Insert Navbar Link</a>

		</div>

		<div class="sidebar_btn_block">

			<a id="edit" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=navbar&action=edit">Edit Navbar</a>

		</div>

		<div class="sidebar_btn_block">

			<a id="delete" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=navbar&action=delete">Delete Navbar Links</a>

		</div>

		<div class="sidebar_btn_block">

			<a id="fix_nav_error" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=navbar&action=fix_nav_error">Fix Errors In Navigation</a>

		</div>

		<div class="dashboard_sidebar_divider"></div>

		<div class="sidebar_btn_block">

			<a id="users" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=users&action=users">View Users</a>

		</div>

		<?php

	}

	elseif($_SESSION['role']=='nodal_officer'){ ?>

		<div class="sidebar_btn_block">

			<a id="view" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=view">Manage Posts</a>

		</div>

		<div class="sidebar_btn_block">

			<a id="create" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=create">Create New Post</a>

		</div>

		<div class="sidebar_btn_block">

			<a id="update" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=post&action=update">Update Existing Post</a>

		</div>

		<div class="dashboard_sidebar_divider"></div>

		<div class="sidebar_btn_block">

			<a id="category" class="sidebar_link" href="<?php echo $base_url;?>/admin/dashboard.php?source=category&action=category">Manage Categories</a>

		</div>

		<?php

	}

}



function dashboardPage(){

	global $base_url;

	global $page_url;

	if(!isset($_GET['source'])){

		include './include_pages/welcome.php';

	}

	else{

		$page = $_GET['source'];

		if(file_exists('./include_pages/' . $page . '.php')){

			switch($page){

				case "navbar":

					if($_SESSION['role']=='admin'){

						include './include_pages/navbar.php';

					}

					else{

						show404();

					}

					break;

				case "post":

					if($_SESSION['role']=='nodal_officer'){

						include './include_pages/post.php';

					}

					else{

						show404();

					}

					break;

				case "category":

					if($_SESSION['role']=='nodal_officer'){

						include './include_pages/category.php';

					}

					else{

						show404();

					}

					break;

				case "users":

					if($_SESSION['role']=='admin'){

						include './include_pages/users.php';

					}

					else{

						show404();

					}

					break;

				case "account":

					include './include_pages/account.php';

					break;

				default:

					include './include_pages/welcome.php';

			}

		}

		else{ ?>

			<div class="dashboard404">

				<img src="<?php echo $base_url;?>/images/404.png">

			</div>

			<?php

		}

		

	}

}



function getUserImage(){

	global $connection;

	$userid = $_SESSION['userid'];

	$user_img_qs = "SELECT image from users where userid = $userid";

	$user_img_q = mysqli_query($connection, $user_img_qs);

	$user_img = mysqli_fetch_assoc($user_img_q);

	if($user_img['image']!=""){

		$image = $user_img['image'];

	}

	else{

		$image = "default_user_image.png";

	}

	return $image;

}



function userIP(){

	$ip = $_SERVER['REMOTE_ADDR'];

	return $ip;

}



function show404(){

	global $base_url; ?>

	<div class="dashboard404">

		<img src="<?php echo $base_url;?>/images/404.png">

	</div>

	<?php

}





























# Now starting the functions of all pages that is included in admin dashboard





function listOrder($title, $url, $is_parent, $is_child){

	global $connection;

	global $base_url; ?>

	<div class="page_container">

		<div class="action_title_block">

			<span class="action_title">Reorder Navigation Button</span>

		</div>

		<div class="page_work_data_container">

			<form action="" method="post" enctype="myltipart/form-data">

				<span class="form_element_title">Select Position</span>

				<select class="list_nav_select" name="list_order_select">

					<option class="list_order" value="beg">At Begening</option>

					<?php 

						$list_query_string = "SELECT * from navigation where parent = '$is_parent' and url != '$url'";

						$list_query = mysqli_query($connection,$list_query_string);

						while($list = mysqli_fetch_assoc($list_query)){ ?>

							<option class="list_order" value="<?php echo $list['id'];?>"><?php echo $list['id'] . ' - After' . $list['title'];?></option>

							<?php

						} ?>

						

				</select>

				<div class="submit_btn_block">

					<input type="submit" class="submit_btn" name="sort_navlink_submit" value="Arrange">

				</div>

			</form>

		</div>

	</div>



<?php

exit();

}



function sortList($link){

	global $connection;

	global $base_url;

	$list_qs = "SELECT * from navigation where id = $link";

	$list_q = mysqli_query($connection, $list_qs);

	$list_data = mysqli_fetch_assoc($list_q);

	$id = $list_data['id'];

	$parent = $list_data['parent'];

	$child = $list_data['child'];

	$list_order = $list_data['list_order'];

	$parent_id = $list_data['parent_id'];

	echo $parent;

	if($link=='beg'){

		$sql = "SELECT * from navigation where parent = '$parent'";

		$sql_query = mysqli_query($connection,$sql);

		while($sql_data = mysqli_fetch_assoc($sql_query)){

			$increment = $sql_data['list_order'] + 1;

			$update_string = "UPDATE navigation set list_order = $increment";

		}

	}

}



function fixLinks(){

	global $connection;

	$sql = "SELECT * from navigation where parent = 1 and child = 1";

	$sql_query = mysqli_query($connection, $sql);

	$flag = false;

	while($sql_data = mysqli_fetch_assoc($sql_query)){

		$parent_id = $sql_data['id'];

		$sql1 = "SELECT * from navigation where parent_id = $parent_id";

		$sql1_query = mysqli_query($connection, $sql1);

		$sql1_data = mysqli_fetch_assoc($sql1_query);

		if($sql1_data['id']<=0){

			$update_sql = "UPDATE navigation set child = 0 where id = $parent_id";

			$update_sql_query = mysqli_query($connection, $update_sql);

			if($update_sql_query){

				$flag = true;

			}

		}

	}

	if($flag==true){

		echo "Successfully Fixed All Links.";

	}

}



function authorList(){

	global $connection;

	$author_qs = "SELECT username from users where role = 'nodal_officer' and status = 'active'";

	$author_q = mysqli_query($connection, $author_qs);

	while($authorlist = mysqli_fetch_assoc($author_q)){ ?>

		<option value="<?php echo $authorlist['username'];?>"><?php echo $authorlist['username'];?></option>

		<?php

	}

}



function categoryList(){

	global $connection;

	$category_qs = "SELECT * from category";

	$category_q = mysqli_query($connection, $category_qs);

	while($categorylist = mysqli_fetch_assoc($category_q)){ ?>

		<option value="<?php echo $categorylist['cat_id'];?>"><?php echo ucwords($categorylist['cat_title']);?></option>

	<?php

	}

}



function deletePost($post_id){

	global $connection; ?>

	<div class="warning_block">

		<span class="warning_text">Do You Really Want To Delete That Post</span>

		<div class="warning_selection_btns">

			<form action="" method="post">

				<input class="warning_btn" type="submit" name="delete_confirm" value="Yes">

				<input class="normal_btn" type="submit" name="delete_confirm" value="No">

			</form>

		</div>

	</div>

	<?php

}



function deletePostFinally(){

	global $base_url;

	global $connection;

	$post_id = $_GET['post_id'];

	$deletepost_qs = "Update posts set status = 'trash' where post_id = $post_id";

	$deletepost_query = mysqli_query($connection, $deletepost_qs);

	if($deletepost_query){

		echo "Post Deleted"; ?>



			<div class="submit_btn_block">

				<a class="submit_btn" href="<?php echo $base_url . '/admin/dashboard.php?source=post&action=view';?>">Done</a>

			</div>

			<?php

	}

}

?>