<?php 
include './functions/login_registration_functions.php';
global $connection;
if(isset($_COOKIE['last_url'])){
	echo $_COOKIE['last_url'];
}
if(isset($_POST['update_user_submit'])){
	$fname = $_POST['fname'];
	$fname = filterStringForSqlInjections($fname);
	$lname = $_POST['lname'];
	$lname = filterStringForSqlInjections($lname);
	$email = $_POST['email'];
	$email = filterStringForSqlInjections($email);
	$mobile = $_POST['mobile'];
	$mobile = filterStringForSqlInjections($mobile);
	$username = $_POST['username'];
	$username = filterStringForSqlInjections($username);
	$password = $_POST['password'];
	$password = filterStringForSqlInjections($password);
	$gender = $_POST['gender'];
	$age = $_POST['age'];
	$old_username = $_POST['old_username'];

	if(strlen($password)<=32){ // checking the password for valid length
		if(strlen($password)>=8){ // checking the password for valid length
			$password = encPass($password); // Encrypting The Password
			if(verifyUsername($username)){
				if(checkUsernameAvailibilityForUpdation($username)){ // username availibility check
					$profile_update_qs = "UPDATE users SET fname = '$fname', lname = '$lname', username = '$username', password = '$password', email = '$email', mobile = '$mobile', gender = '$gender', age = $age";
					if(isset($_FILES['image']['name'])){
						if($_FILES['image']['name']!=""){
							$image = $_FILES['image']['name'];
							$image_temp = $_FILES['image']['tmp_name'];
							$image_status = move_uploaded_file($image_temp, "../images/users/" . $image);
							if($image_status){
								echo "Image Uploaded Successfully.";
							}
							else{
								echo "Image Not Uploaded";
							}
							$profile_update_qs .= ", image = '$image'";
						}
						else{

						}
						$userid = $_SESSION['userid'];
						$profile_update_qs .= " WHERE userid = $userid";
						$profile_update_q = mysqli_query($connection, $profile_update_qs);
						if($profile_update_q){
							$role = $_SESSION['role'];
							if($old_username!=$username){
								$sql = "UPDATE posts set author = '$username' where author = '$old_username'";
								$query = mysqli_query($connection, $sql);
								if($query){
									echo "Posts Author Updated Successfully.<br>";
								}
							}
							setSession($userid, $fname, $lname, $username, $email, $role);
							echo "Profile Updated Successfully";
							// header("refresh:5");
						}
						else{
							echo "Profile Not Updated. " . mysqli_error($connection);
						}
					}
				}
				else{
					$error = "Username Not Available. Try Another";
				}
			}
		}
		else{
			$error = "Password must be greater than 8 characters.";
		}
	}
	else{
		$error = "Password must be less than equal to 32 characters.";
	}













	
	
} ?>

	<?php
	if($_GET['action']=='edit_profile'){
		$userid = $_SESSION['userid'];
	$user_data_qs = "SELECT * from users where userid = $userid";
	$user_data_q = mysqli_query($connection, $user_data_qs);
	$user_data = mysqli_fetch_assoc($user_data_q); ?>
		<div class="page_container">
			<?php if($error!=""){ ?>
				<div class="show_error"><span><?php echo $error;?></span></div>
				<?php
			} ?>
			<div class="action_title_block">
				<span class="action_title">Edit Your Profile</span>
			</div>
			<div class="page_work_data_container">
				<div class="post_form">
					<form action="" method="post" enctype="multipart/form-data">
						<input type="hidden" name="old_username" value="<?php echo $user_data['username'];?>">
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">First Name</span>
							<input class="post_form_input" type="text" name="fname" value="<?php echo $user_data['fname'];?>" required>
						</div>
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Last Name</span>
							<input class="post_form_input" type="text" name="lname" value="<?php echo $user_data['lname'];?>" required>
						</div>
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Email ID</span>
							<input class="post_form_input" type="text" name="email" value="<?php echo $user_data['email'];?>" required>
						</div>
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Mobile No.</span>
							<input class="post_form_input" type="text" name="mobile" value="<?php echo $user_data['mobile'];?>" required>
						</div>
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Username</span>
							<input class="post_form_input" type="text" name="username" value="<?php echo $user_data['username'];?>" required>
						</div>
						<div class="post_form_text_input_block post_form_blocks_margin">
							<span class="post_form_title">Account Password</span>
							<input class="post_form_input" type="password" name="password" value="<?php echo $user_data['password'];?>" required>
						</div>
						<div class="select_btns_seperator">
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Age-->
								<span class="post_form_title">Select Age</span>
								<select class="post_author_select" name="age">
									<option value="<?php echo $user_data['age'];?>"><?php echo $user_data['age'];?></option>
									<?php 
									$age = 18;
									for($i=$age;$i<=60;$i++){?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php
									} ?>
								</select>
							</div>
							<div class="width post_form_select_block post_form_blocks_margin"> <!-- Age-->
								<span class="post_form_title">Gender</span>
								<div class="radio_btn_disp_flex">
									<div id="radio_btn_block">
										<input type="radio" id="male" name="gender" value="male" <?php if($user_data['gender']=='male'){echo 'checked="checked"';}?>>
										<label for="male" class="gender_text">Male</label>
									</div>
									<div id="radio_btn_block">
										<input type="radio" id="female" name="gender" value="female" <?php if($user_data['gender']=='female'){echo 'checked="checked"';}?>>
										<label for="female" class="gender_text">Female</label>
									</div>
								</div>
							</div>
						</div>
						<div class="post_form_file_upload_block post_form_blocks_margin">
							<span class="post_form_title">Post Image</span>
							<input class="image_upload_btn" type="file" name="image">
						</div>
						<div class="submit_btn_block">
							<input type="submit" class="submit_btn" name="update_user_submit" value="Update Profile">
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php 
	}?>
