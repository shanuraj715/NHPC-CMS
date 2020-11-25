
<?php
if(isset($_POST['cat_rename_submit'])){
	rename_block();
}
elseif(isset($_POST['cat_delete_submit'])){
	delete_block();
}

if(isset($_POST['update_category_submit'])){
	$catid = $_POST['catid'];
	$cat_new_name = $_POST['cat_new_title'];
	renameCategory($catid, $cat_new_name, $cat_change);
}
elseif(isset($_POST['delete_category_submit'])){
	$catid = $_POST['catid'];
	$delete_radio_status = $_POST['cat_delete_radio_status'];
	deleteCategory($catid, $delete_radio_status);
}

if(isset($_POST['insert_category'])){
	global $connection;
	$cat_name = $_POST['cat_name'];
	$sql = "INSERT into category(cat_title) VALUES('$cat_name')";
	$query = mysqli_query($connection, $sql);
	if($query){
		header('Location : ' . $base_url . '/admin/dashboard.php?source=category&action=category');
	}
}

function renameCategory($catid, $cat_name){
	global $connection;
	$sql = "UPDATE category set cat_title = '$cat_name' where cat_id = $catid";
	$query = mysqli_query($connection, $sql);
	if($query){
		header('Location: ' . $base_url . '/admin/dashboard.php?source=category&action=category');
	}
	else{
		echo mysqli_error($connection);
	}
}

function deleteCategory($catid, $delete_radio_status){
	global $connection;
	$sql = "DELETE from category where cat_id = $catid";
	$query = mysqli_query($connection, $sql);
	if($delete_radio_status=='change'){
		$sql = "UPDATE posts set category = 1 where category = $catid";
		$query = mysqli_query($connection, $sql);
		if($query){
			header('Location: ' . $base_url . '/admin/dashboard.php?source=category&action=category');
		}
	}
}

function cat_list(){
	global $connection;
	$cat_list_qs = "SELECT * from category";
	$cat_list_q = mysqli_query($connection, $cat_list_qs);
	$sno = 1;
	while($cat_list=mysqli_fetch_assoc($cat_list_q)){
		$cat = $cat_list['cat_id'];
		$cat_count_qs = "SELECT count(category) as total from posts where category = $cat and status = 'published'";
		$cat_count_q = mysqli_query($connection, $cat_count_qs);
		$cat_count = mysqli_fetch_assoc($cat_count_q);
		 ?>
		<tr class="cat_list_row">
			<td id="sno" class="cat_list_td"><?php echo $sno; ?></td>
			<td id="cat_id" class="cat_list_td"><?php echo $cat_list['cat_id'];?></td>
			<td id="cat_name" class="cat_list_td">
				<a href="/?category=<?php echo $cat_list['cat_id'];?>" target="_blank" class="cat_link"><?php echo ucwords($cat_list['cat_title']);?></a>
			</td>
			<td id="total_post" class="cat_list_td"><?php echo $cat_count['total'];?></td>
			<td id="rename" class="cat_list_td">
				<form action="" method="POST">
					<input type="hidden" name="cat_id" value="<?php echo $cat_list['cat_id'];?>">
					<input type="submit" name="cat_rename_submit" value="Rename">
				</form>
			</td>
			<td id="delete" class="cat_list_td">
				<form action="" method="POST">
					<input type="hidden" name="cat_id" value="<?php echo $cat_list['cat_id'];?>">
					<input type="submit" name="cat_delete_submit" value="Delete">
				</form>
			</td>
		</tr>
	<?php
	$sno += 1;
	}
}

function rename_block(){
	global $connection;
	global $base_url;
	if(isset($_POST['cat_id'])){
	$catid = $_POST['cat_id'];
	$catname_qs = "SELECT cat_id, cat_title, _default from category where cat_id = $catid";
	$catname_q = mysqli_query($connection, $catname_qs);
	$catname = mysqli_fetch_assoc($catname_q); ?>
		<div class="category_popup_container">
			<div class="category_popup_block">
				<div class="popup_title_block">
					<span>Rename <strong><?php echo ucwords($catname['cat_title']);?></strong> Category</span>
					<div class="close_btn">
						<a href="<?php echo $base_url;?>/admin/dashboard.php?source=category&action=category"><img src="<?php echo $base_url; ?>/images/close.png"></a>
					</div>
				</div>
				<div class="popup_message_block">
					<form action="" method="POST">
						<div class="input_block">
							<input type="hidden" name="catid" value="<?php echo $catname['cat_id'];?>">
							<label for="cat_new_name_input">Enter Category Title</label>
							<input type="text" name="cat_new_title" required>
						</div>
						<?php
						if($catname['_default']!=1){ ?>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="update_category_submit" value="Update Category">
						</div>
						<?php
					}
					else{ ?>
						<span>Default Category Can Not Modify.</span>
							<?php
					} ?>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}

function delete_block(){
	global $connection;
	global $base_url;
	if(isset($_POST['cat_id'])){
	$catid = $_POST['cat_id'];
	$catname_qs = "SELECT cat_id, cat_title, _default from category where cat_id = $catid";
	$catname_q = mysqli_query($connection, $catname_qs);
	$catname = mysqli_fetch_assoc($catname_q); ?>
		<div class="category_popup_container">
			<div class="category_popup_block">
				<div class="popup_title_block">
					<span>Rename <strong><?php echo ucwords($catname['cat_title']);?></strong> Category</span>
					<div class="close_btn">
						<a href="<?php echo $base_url;?>/admin/dashboard.php?source=category&action=category"><img src="<?php echo $base_url; ?>/images/close.png"></a>
					</div>
				</div>
				<div class="popup_message_block">
					<form action="" method="POST">
						<div class="rename_cat_radio_btn_block">
							<input type="hidden" name="catid" value="<?php echo $catname['cat_id'];?>">
							<div class="radio_btn">
								<input type="radio" name="cat_delete_radio_status" id="cat_delete_radio_1" value="change" required>
								<label for="cat_delete_radio_1">Set default category name "Uncategorized" on all posts whose category name is <?php echo ucwords($catname['cat_title']);?></label>
							</div>
							<div class="radio_btn">
								<input type="radio" name="cat_delete_radio_status" id="cat_delete_radio_2" value="nochange" required>
								<label for="cat_delete_radio_2">Do Not change category name on posts whose category name is <?php echo ucwords($catname['cat_title']);?></label>
							</div>
						</div>
						<?php
						if($catname['_default']!=1){ ?>
							<div class="submit_btn_block">
								<input type="submit" class="submit_btn" name="delete_category_submit" value="Delete Category">
							</div> 
							<?php
						}
						else{ ?>
							<span>Default Category Can Not Delete Or Modify</span>
							<?php
						} ?>
					</form>
				</div>
			</div>
		</div>
		<?php
	}
}


?>

<div class="page_container">
	<div class="action_title_block">
		<span class="action_title">Manage Posts Category</span>
	</div>
	<div class="page_work_data_container">
		<div class="insert_category_block">
			<form action="" method="POST">
				<span>Insert New Category</span>
				<input type="text" name="cat_name" required>
				<input type="submit" name="insert_category" value="Insert">
			</form>
		</div>
	</div>
	<div class="page_work_data_container">
		<div class="category_list_container">
			<div class="category_list_block">
				<table class="category_list">
					<tr class="cat_head_tr">
						<th id="sno" class="cat_head">S. No.</th>
						<th id="cat_id" class="cat_head">Category ID</th>
						<th id="cat_name" class="cat_head">Category Name</th>
						<th id="total_post" class="cat_head">Total Posts</th>
						<th id="rename" class="cat_head">Rename</th>
						<th id="delete" class="cat_head">Delete</th>
					</tr>
					<?php cat_list(); ?>
				</table>
			</div>
		</div>
	</div>
</div>