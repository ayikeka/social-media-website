<?php 

if (isset($_POST['login_button'])){

	$email = filter_var($_POST['log_email'],FILTER_SANITIZE_EMAIL); //sanitises email before submission i.e email is in correct format

	$_SESSION['log_email'] = $email; //stores the email into session variable
	$password = md5($_POST['log_password']); //encrypts password and gets password

	$check_database_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND password='$password'"); //checking for email and password
	$check_login_query = mysqli_num_rows($check_database_query); // returns the no. of rows with email and password either 1 or 0

	if ($check_login_query == 1){
		$row = mysqli_fetch_array($check_database_query); //fetches data and stores in row array
		$username = $row['username']; //finds username in the row array

		$user_closed_query = mysqli_query($con, "SELECT * FROM users WHERE email='$email' AND user_closed='yes'");
		if (mysqli_num_rows($user_closed_query) ==1){
			$reopen_account = mysqli_query($con, "UPDATE users SET user_closed='no' WHERE email='$email'");
		}

		$_SESSION['username'] = $username;
		header("Location: index.php");
		$_SESSION['log_email']='';
		exit();
	}
	else {
		array_push($error_array, "Email or password incorrect !<br>");
	}

}

 ?>
