<?php
$error = "";
$registration_status = false;

function handleLoginForm(){
	global $connection;
	global $base_url;
	if(isset($_POST['login_submit']) and !empty($_POST['login_submit'])){
		$user = $_POST['username'];
		$password = $_POST['password'];
		$role = $_POST['role'];
		if(strlen($user)<255 and strlen($password)<72){
			$user = filterStringForSqlInjections($user);
			$password = filterStringForSqlInjections($password);
			$password = encPass($password);
			$role = filterStringForSqlInjections($role);
			sqlQueryForLogin($user, $password, $role);
		}
	}
}


function handleRegistrationForm(){
	global $base_url;
	global $error;
	if(isset($_POST['register_submit']) and !empty($_POST['username'])){
		if(isset($_POST['fname']) and isset($_POST['lname']) and isset($_POST['username']) and isset($_POST['pass1']) and isset($_POST['pass2']) and isset($_POST['email'])){
			if($_POST['fname']!="" and $_POST['lname']!="" and $_POST['username']!="" and $_POST['pass1']!="" and $_POST['pass2']!="" and $_POST['email']!=""){
				$fname = filterStringForSqlInjections($_POST['fname']);
				$lname = filterStringForSqlInjections($_POST['lname']);
				$username = filterStringForSqlInjections($_POST['username']);
				$pass1 = filterStringForSqlInjections($_POST['pass1']);
				$pass2 = filterStringForSqlInjections($_POST['pass2']);
				$email = filterStringForSqlInjections($_POST['email']);
				$role = filterStringForSqlInjections($_POST['role']);
				if(strlen($pass1)<=32 and strlen($pass2)<=32){ // checking the password for valid length
					if(strlen($pass1)>=8 and strlen($pass2)>=8){ // checking the password for valid length
						if(verifyUsername($username)){
							if(checkUsernameAvailibility($username, $email)){ // username availibility check
								$pass = encPass($pass1); // Encrypting The Password
								doSqlQueryForAccountRegister($fname, $lname, $username, $pass, $email, $role);
								// sendOtpEmail($fname, $lname, $username, $email, $role);
							}
							else{
								$error = "Username Not Available or Email Already Registered. Try Another";
							}
						}
					}
					else{
						$error = "Password must be greater than equal to 8 characters.";
					}
				}
				else{
					$error = "Password must be less than equal to 32 characters.";
				}
			}
			else{
				// $error = "Password must be less than equal to 32 characters.";
			}
		}
		else{
			$error = "Some values of this form is missing. Please fill this form correctly.";
		}
	}
}

function sendOtpEmail($fname, $lname, $username, $email, $role){
	global $connection;
	global $base_url;
	global $main_f_title;
	global $main_title;
	global $registration_status;
	global $otp_status;
	$auth_key = substr($email, 0, 3);
	$auth_key .= substr($username, 0, 4);
	$auth_key .= rand(11111111,99999999);
	$auth_key .= substr($fname, 0, 1);
	$auth_key .= substr($lname, 0, 1);
	$email = mysqli_real_escape_string($connection, $email);
	$email = str_replace(' ', '', $email);
	$email = strtolower($email);
	$otp = rand(100000,999999);
	$date = date('Y-n-d');
	$message = 'Dear ' . $fname . ' ' . $lname . '

OTP for your account on ' . $main_f_title . ' is 

' . $otp . '

You can also click on this link to verify your account 
' . $base_url . '/admin/account-verify.php?user=' . $username . '&key=' . $auth_key . '

Please do not share this OTP or Account Verification Link to anyone.

Please Note That
This is a system generated email so please do not reply to it.

For more info You can visit Our Blog ' . $base_url;
	$subject = 'OTP for your Account of ' . $main_title;
	$send_from = "subscribers@techfacts007.in";
	$headers = 'From: ' . $send_from;
	$mail = mail($email, $subject, $message, $headers);
	if($mail){
		$otp_insert_qs = "INSERT into `users_otp`(`username`,`otp`,`otp_date`, `authentication_key`) VALUES('$username',$otp,'$date','$auth_key')";
		$otp_insert_q = mysqli_query($connection, $otp_insert_qs);
		if($otp_insert_q){
			$_SESSION['otp_username'] = $username;
			$otp_status = true;
			$registration_status = true;
			// $_SESSION['otp_key'] = $auth_key;
			// header('Location: ' . $base_url . '/admin/account-verify.php?user=' . $username . '&email=' . $email);
		}
		else{
			echo mysqli_error($connection);
		}
	}
}


function checkUsernameAvailibility($username, $email){
	global $connection;
	$username_qs = "SELECT username from users where username = '$username' or email = '$email'";
	$username_q = mysqli_query($connection, $username_qs);
	$username = mysqli_fetch_assoc($username_q);
	if($username['username']!=NULL){
		return false;
	}
	else{
		return true;
	}
}

function checkUsernameAvailibilityForUpdation($username){
	global $connection;
	$userid = $_SESSION['userid'];
	if($username==$_SESSION['username']){
		return true;
	}
	else{
		$username_qs = "SELECT username from users where username = '$username'";
		$username_q = mysqli_query($connection, $username_qs);
		$username = mysqli_num_rows($username_q);
		if($username!=0){
			return false;
		}
		else{
			return true;
		}
	}
}

function generateUniqueRandomUserId(){
	global $connection;
	global $base_url;
	do{
		$userid_1 = rand(100000,999999);
		$userid_qs = "SELECT userid from users where userid = $userid_1";
		$userid_q = mysqli_query($connection, $userid_qs);
		$userid = mysqli_fetch_assoc($userid_q);
		$userid_2 = $userid['userid'];
		if($userid_1!=$userid_2){
			$flag = true;
			$userid = $userid_1;
		}
		else{
			$flag = false;
		}
	} while($flag==false);
	return $userid;
	
}




function sqlQueryForLogin($user, $pass, $role){
	global $connection;
	global $error;
	$query_string = "SELECT * ";
	$query_string .= "FROM users ";
	$query_string .= "WHERE (userid = '$user' ";
	$query_string .= "or username = '$user' ";
	$query_string .= "or email = '$user') ";
	$query_string .= "and password = '$pass' ";
	$query_string .= "and role = '$role'";
	// echo $query_string;
	$query = mysqli_query($connection, $query_string);
	$user_result = mysqli_fetch_assoc($query);
	if($user_result['userid']!=NULL){
		if($user_result['status']=="pending"){
			$error = "Your account status is pending. We will check the details and approve shortly.";
		}
		elseif($user_result['status']=='deleted'){
		    $error = "Your account is deleted by Admin. Please Contact us for more information.";
		}
		elseif($user_result['status']=="otp_pending"){
			$fname = $user_result['fname'];
			$lname = $user_result['lname'];
			$username = $user_result['username'];
			$email = $user_result['email'];
			$role = $user_result['role'];
			sendOtpEmail($fname, $lname, $username, $email, $role);
			$_SESSION['flag_otp'] = true;
			header('Location:' . $base_url . '/admin/register.php');
		}
		elseif($user_result['status'] == 'active'){
			$userid = $user_result['userid'];
			$fname = $user_result['fname'];
			$lname = $user_result['lname'];
			$username = $user_result['username'];
			$useremail = $user_result['email'];
			$role = $user_result['role'];
			setSession($userid, $fname, $lname, $username, $useremail, $role); // function on functions.php
		}
		else{
		    header("Location: " . $base_url . '/admin/login.php');
		}
		
	}
	elseif($user_result['userid']==NULL){
		$error = "User Creadentials Does Not Match. Login Again With Correct Details.";
	}
}

function doSqlQueryForAccountRegister($fname, $lname, $username, $password, $email, $role){
	global $connection;
	global $base_url;
	global $error;
	global $registration_status;
	$userid = generateUniqueRandomUserId();
	$reg_qs = "INSERT into users";
	$reg_qs .= "(userid, fname, lname, username, password, email, role) ";
	$reg_qs .= "VALUES($userid, '$fname', '$lname', '$username', '$password', '$email', '$role')";
	$reg_q = mysqli_query($connection, $reg_qs);
	if($reg_q){
		// $registration_status = true;
		sendOtpEmail($fname, $lname, $username, $email, $role);
	}
	else{
		$error = "Something went wrong. Registration not successful. Please Contact the Site Admin" . mysqli_error($connection);
	}
}

# Common Functions





// Password encrypt function is in functions.php file

function verifyUsername($username){
	global $error;
	if(strlen($username)<=32){
		if(strlen($username)>=8){
			if(!ctype_space($username)){
				return true;
			}
			else{
				$error = "Username must not contain any white space. Please remove the space.";
			}
		}
		else{
			$error = "Username must be grater than equal to 8 characters.";
		}
	}
	else{
		$error = "Username must be less than equal to 32 characters.";
	}
}



?>