<?php





function allUsers(){
	global $connection;
	$users_list_qs = "SELECT status from users";
	$users_list_q = mysqli_query($connection, $users_list_qs);
	global $total_users;
	global $total_active;
	global $total_pending;
	global $deleted;
	$total_users = 0;
	$total_active = 0;
	$total_pending = 0;
	$deleted = 0;
	while(
	$users_list = mysqli_fetch_assoc($users_list_q)){
		if($users_list['status']=='active'){
			$total_active += 1;
			$total_users += 1;
		}
		elseif($users_list['status']=='pending'){
			$total_pending += 1;
			$total_users += 1;
		}
		elseif($users_list['status']=='deleted'){
			$deleted += 1;
		}
	}
}

function allUsersData(){
	global $connection;
	$userid = $_SESSION['userid'];
	$users_data_qs = "SELECT * from users WHERE userid != $userid";
	if(isset($_GET['sort'])){
		if($_GET['sort']=='active'){
			$users_data_qs .= " and status = 'active'";
		}
		elseif($_GET['sort']=='pending'){
			$users_data_qs .= " and status = 'pending'";
		}
		elseif($_GET['sort']=='deleted'){
			$users_data_qs .= " and status = 'deleted'";
		}
	}
	else{
		$users_data_qs .= " and status != 'deleted' and status != 'otp_pending'";
	}
	$users_data_q = mysqli_query($connection, $users_data_qs);
	while($users_data= mysqli_fetch_assoc($users_data_q)){ ?>
		<tr class="users_list_tr">
			<td id="userid" class="users_list_td"><?php echo $users_data['userid'];?></td>
			<td id="fname" class="users_list_td"><?php echo $users_data['fname'];?></td>
			<td id="lname" class="users_list_td"><?php echo $users_data['lname'];?></td>
			<td id="username" class="users_list_td"><?php echo $users_data['username'];?></td>
			<td id="email" class="users_list_td"><?php echo $users_data['email'];?></td>
			<td id="mobile" class="users_list_td"><?php echo $users_data['mobile'];?></td>
			<td id="gender" class="users_list_td"><?php echo ucwords($users_data['gender']);?></td>
			<td id="role" class="users_list_td"><?php echo ucwords($users_data['role']);?></td>
			<td id="status" class="users_list_td">
				<?php
				if($users_data['status']=='deleted'){
					$class = 'account_operation_btn';
				}
				else{
					$class = 'account_status_btn';
				} ?>
				<form action="" method="POST">
					<input type="hidden" name="userid" value="<?php echo $users_data['userid'];?>">
					<input type="submit" class="<?php echo $class;?>" name="update_account_status_submit" value="<?php echo ucwords($users_data['status']);?>">
				</form>
			</td>
		</tr>
	<?php
	}
}

function update_account_status_submit(){
	global $connection;
	if(isset($_POST['status'])){
		if($_POST['status']==1){
			$status = 'active';
		}
		elseif($_POST['status']==2){
			$status = 'pending';
		}
		elseif($_POST['status']==3){
			$status = 'deleted';
		}
		$userid = $_POST['userid'];
		$update_acc_status = "UPDATE users set status = '$status' WHERE userid = $userid";
		$update_acc = mysqli_query($connection, $update_acc_status);
		if($update_acc){
			header('Location: ' . $base_url . '/admin/dashboard.php?source=users&action=users');
		}
	}
}


?>