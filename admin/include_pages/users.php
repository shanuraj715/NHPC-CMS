

<?php
global $connection; ?>

<?php include 'users_functions.php';
if(isset($_POST['update_account_status_submit'])){
	update_account_status_submit();
} ?>
<div class="page_container">
	<div class="action_title_block">
		<span class="action_title">Users</span>
	</div>
	<div class="page_work_data_container">
		<div class="post_stats_block">
			<?php
			global $total_users;
			global $total_active;
			global $total_pending;
			global $deleted;
			allUsers(); ?>
			<div class="post_stat_block1">
				<a href="/admin/dashboard.php?source=users&action=users" class="post_stat_title">Total Users</a>
				<span class="post_stat_value"><?php echo $total_users; ?></span>
			</div>
			<div class="post_stat_block1">
				<a href="/admin/dashboard.php?source=users&action=users&sort=active" class="post_stat_title">Active Users</a>
				<span class="post_stat_value"><?php echo $total_active; ?></span>
			</div>
			<div class="post_stat_block1">
				<a href="/admin/dashboard.php?source=users&action=users&sort=pending" class="post_stat_title">Pending Users</a>
				<span class="post_stat_value"><?php echo $total_pending; ?></span>
			</div>
			<div class="post_stat_block1">
				<a href="/admin/dashboard.php?source=users&action=users&sort=deleted" class="post_stat_title">Deleted Users</a>
				<span class="post_stat_value"><?php echo $deleted; ?></span>
			</div>
		</div>
		<div class="users_list_container">
			<table class="users_list_table">
				<tr class="users_list_tr_heading">
					<th id="userid" class="users_list_th">User ID</th>
					<th id="fname" class="users_list_th">F. Name</th>
					<th id="lname" class="users_list_th">L. Name</th>
					<th id="username" class="users_list_th">Username</th>
					<th id="email" class="users_list_th">Email</th>
					<th id="mobile" class="users_list_th">Mobile</th>
					<th id="gender" class="users_list_th">Gender</th>
					<th id="role" class="users_list_th">Role</th>
					<th id="status" class="users_list_th">Status</th>
				</tr>
				<?php allUsersData(); ?>
			</table>
		</div>
	</div>
	<?php
	if(isset($_POST['update_account_status_submit'])){
		if($_POST['userid']!=""){
			$userid = $_POST['userid'];
			$user_data_qs = "SELECT * FROM users where userid = $userid";
			$user_data_q = mysqli_query($connection, $user_data_qs);
			$user_data = mysqli_fetch_assoc($user_data_q); ?>
			<div class="update_status_container">
				<div class="update_status_block">
					<div class="update_title_block">
						<span class="update_title">Update Account Status</span>
						<div class="close_btn">
							<a href="<?php echo $base_url;?>/admin/dashboard.php?source=users&action=users"><img src="/images/close.png"></a>
						</div>
					</div>
					<div class="update_content_block">
						<span class="update_content">Please Select The Account Status Of User <strong><?php echo $_POST['userid'];?></strong><br>Previous account status is <strong><?php echo $user_data['status'];?></strong></span>
						<hr>
					</div>
					<form action="" method="POST">
						<div class="text2">
							<span class="account_status_text_active">Active</span>
							<span class="account_status_text_pending">Pending</span>
							<span class="account_status_text_pending">deleted</span>
						</div>
						<div class="slider_container">
							<input type="hidden" name="userid" value="<?php echo $_POST['userid'];?>">
							<input type="range" name="status" min="1" max="3" class="slider">
						</div>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="update_account_status_submit" value="Update Account">
						</div>
					</form>
				</div>
			</div>
	<?php
		}
	} ?>
	
</div>