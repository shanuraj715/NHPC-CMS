<?php
global $connection;
if(isset($_POST['insert_navlink_submit']) and isset($_POST['title']) or isset($_POST['update_navlink_submit'])){
	$title = $_POST['title'];
	$url = $_POST['url'];
	if(!empty($_POST['is_parent'])){
		$is_parent = $_POST['is_parent'];
		if($is_parent=='yes_parent'){
			$parent_id = "0";
			$is_parent = 1;
			$child = 0;
		}
		else{
			$parent_id = $_POST['parent_id'];
			$is_parent = 0;
			$child = 1;
		}
		if(isset($_POST['insert_navlink_submit'])){
			$nav_query_string = "INSERT into navigation(title,url,parent,child,parent_id) VALUES('$title', '$url', '$is_parent', '$child', '$parent_id')";
		}
		elseif(isset($_POST['update_navlink_submit'])){
			$id = $_POST['id'];
			$nav_query_string = "UPDATE navigation set title = '$title', url = '$url', parent = $is_parent, child = $child, parent_id = $parent_id WHERE id = $id";
		}
		$nav_query = mysqli_query($connection, $nav_query_string);
		$sql = "SELECT * from navigation where id = $parent_id";
		$sql_query = mysqli_query($connection, $sql);
		$sql_data = mysqli_fetch_assoc($sql_query);
		if($sql_data['child']==0){
			$sql = "UPDATE navigation set child = 1";
			$sql_update = mysqli_query($connection, $sql);
			if($sql_update){
			}
		}
		fixLinks();
		if($nav_query){
			// listOrder($title, $url, $is_parent, $child);
			echo "Done. Query Executed Successfully.";
		}
	}
}
// if(isset($_POST['sort_navlink_submit'])){
// 	if(isset($_POST['list_order_select'])){
// 		$sorting_link = $_POST['list_order_select'];
// 		sortList($sorting_link);
// 	}
	
// }


if(isset($_POST['delete_link_submit'])){
	$id = $_POST['link_id'];
	$sql = "DELETE from navigation where id = $id";
	$query = mysqli_query($connection, $sql);
	if($query){
		echo "Successfully Deleted" . $id;
	}
}

if(isset($_POST['fix_link_submit'])){
	fixLinks();
}

?>
<?php
if(isset($_GET['action'])){
	if($_GET['action']=='insert'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Add Links In Navigation Panel</span>
			</div>
			<div class="page_work_data_container">
				<form action="" method="post" enctype="myltipart/form-data">
					<div class="text_input_block">
						<span class="form_element_title">Link Title</span>
						<input type="text" name="title" placeholder="Link Title" required>
					</div>
					<div class="text_input_block">
						<span class="form_element_title">Link URL</span>
						<input type="text" name="url" placeholder="Link URL" required>
					</div>
					<div class="checkbox_input_block">
						<input type="radio" name="is_parent" value="yes_parent" id="is_parent" onclick="toggleContainer();" required>
						<span class="checkbox_title">Check this, If it is Parent Link.</span>
					</div>
					<div class="checkbox_input_block">
						<input type="radio" name="is_parent" value="no_parent" id="is_child" onclick="toggleContainer();" required>
						<span class="checkbox_title">Check this, If it is child Link.</span>
					</div>
					<div id="toggle_container">
						<div class="parent_id_block">
							<span class="form_element_title">Select Its Parent</span>
							<?php 
							global $connection;
							$nav_parents_query_string = "SELECT * from navigation WHERE parent = 1";
							$nav_parent_query = mysqli_query($connection,$nav_parents_query_string); ?>
							<select name="parent_id">
								<option class="parent_id_option" value="0">Select Parent</option>
								<?php
								while($nav_parent_data = mysqli_fetch_assoc($nav_parent_query)){ ?>
									<option class="parent_id_option" value="<?php echo $nav_parent_data['id'];?>"><?php echo $nav_parent_data['title'];?></option>
									<?php
								} ?>								
							</select>
						</div>
					</div>
					<div class="submit_btn_block">
						<input type="submit" class="submit_btn" name="insert_navlink_submit" value="Insert">
					</div>

					
				</form>
			</div>
		</div>
		<?php 
	}
	elseif($_GET['action']=='edit'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Edit Navigation Panel</span>
			</div>
			<div class="page_work_data_container">
				<form action="" method="post">
					<div class="text_input_block">
						<span class="form_element_title">Select Link To Edit</span>
						<select name="link_id">
							<?php
							$sql = "SELECT * from navigation";
							$query = mysqli_query($connection, $sql);
							while($data = mysqli_fetch_assoc($query)){ ?>
								<option class="edit_navlink_option" value="<?php echo $data['id'];?>"><?php echo $data['id'] . ' - ' . $data['title'];?></option>
							<?php
							} ?>
						</select>
					</div>
					<div class="submit_btn_block">
						<input type="submit" class="submit_btn" name="get_link_submit" value="Next">
					</div>
				</form>
					<?php
					$id = "";
					$title = "";
					$url = "";
					$parent = "";
					$child = "";
					$parent_id = "";
					if(isset($_POST['get_link_submit'])){
						$id = $_POST['link_id'];
						$link_data_string = "SELECT * from navigation where id = $id";
						$link_data_query = mysqli_query($connection, $link_data_string);
						$link_data = mysqli_fetch_assoc($link_data_query);
						if($link_data['id']!=NULL){
							$id = $link_data['id'];
							$title = $link_data['title'];
							$url = $link_data['url'];
							$parent = $link_data['parent'];
							$child = $link_data['child'];
							$parent_id = $link_data['parent_id'];
							echo $id;
						}
					} ?>
				<form action="" method="post" enctype="myltipart/form-data">
					<input type="hidden" name="id" value="<?php echo $id;?>">
					<div class="text_input_block">
						<span class="form_element_title">Link Title</span>
						<input type="text" name="title" placeholder="Link Title" value="<?php echo $title;?>" required>
					</div>
					<div class="text_input_block">
						<span class="form_element_title">Link URL</span>
						<input type="text" name="url" placeholder="Link URL" value="<?php echo $url;?>" required>
					</div>
					<div class="checkbox_input_block">
						<input type="radio" name="is_parent" value="yes_parent" id="is_parent" onclick="toggleContainer();" required>
						<span class="checkbox_title">Check this, If it is Parent Link.</span>
					</div>
					<div class="checkbox_input_block">
						<input type="radio" name="is_parent" value="no_parent" id="is_child" onclick="toggleContainer();" required>
						<span class="checkbox_title">Check this, If it is child Link.</span>
					</div>
					<div id="toggle_container">
						<div class="parent_id_block">
							<span class="form_element_title">Select Its Parent</span>
							<?php 
							global $connection;
							$nav_parents_query_string = "SELECT * from navigation WHERE parent = 1";
							$nav_parent_query = mysqli_query($connection,$nav_parents_query_string); ?>
							<select name="parent_id">
								<option class="parent_id_option" value="0">Select Parent</option>
								<?php
								while($nav_parent_data = mysqli_fetch_assoc($nav_parent_query)){ ?>
									<option class="parent_id_option" value="<?php echo $nav_parent_data['id'];?>"><?php echo $nav_parent_data['title'];?></option>
									<?php
								} ?>								
							</select>
						</div>
					</div>
					<?php
					if(isset($_POST['get_link_submit'])){ ?>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="update_navlink_submit" value="Update">
						</div>
						<?php
					} ?>
				</form>
			</div>
		</div>
		<?php 
	}
	elseif($_GET['action']=='delete'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Delete Links from Navigation Panel</span>
			</div>
			<div class="page_work_data_container">
				<form action="" method="post">
					<div class="text_input_block">
						<span class="form_element_title">Select Link To Delete</span>
						<select name="link_id">
							<?php
							$sql = "SELECT * from navigation";
							$query = mysqli_query($connection, $sql);
							while($data = mysqli_fetch_assoc($query)){ ?>
								<option class="edit_navlink_option" value="<?php echo $data['id'];?>"><?php echo $data['id'] . ' - ' . $data['title'];?></option>
							<?php
							} ?>
						</select>
					</div>
					<div class="submit_btn_block">
						<input type="submit" class="submit_btn" name="delete_link_submit" value="Delete">
					</div>
				</form>
				<span class="info_text">Please Use "Fix Errors In Navigation" Option after deleting any link.</span>
			</div>
		</div>
		<?php 
	}








	elseif($_GET['action']=='fix_nav_error'){ ?>
		<div class="page_container">
			<div class="action_title_block">
				<span class="action_title">Remove Errors From Navigation Links</span>
			</div>
			<div class="page_work_data_container">
				<form action="" method="post">
				<span class="info_text">Due to deletion of some links there may be some broken links available or buttons that have no child but still they show dropdown behaviour.<br><br>By Clicking The Fix Now Button. All the important functions will completed automatically. Your Site data will not affected.<br></span>
					<div class="submit_btn_block">
						<input type="submit" class="submit_btn" name="fix_link_submit" value="Fix Now">
					</div>
				</form>
			</div>
		</div>
		<?php 
	}
	else{ ?>
		<div class="dashboard404">
			<img src="<?php echo $base_url;?>/images/404.png">
		</div>
		<?php
	}
}
?>