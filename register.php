<?php
require "config/config.php";
require "includes/form_handlers/register_handler.php";
require "includes/form_handlers/login_handler.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>GreenPage - Log In or Sign Up</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register_style.css">
	<script src="assets/js/jquery.js" type="text/javascript"></script>
	<script src="assets/js/register.js" type="text/javascript"></script>
</head>
<body>

	<?php

	if(isset($_POST['register_button'])){
		echo '
		<script>

		$(document).ready(function(){
			$(".first").hide();
			$(".second").show();

		});

		</script>
		';

	}


	 ?>

	<div class="wrapper">
		<div class="login-box">
			<div class="login_header">
				<h1>GreenPage !</h1>
				Login or signup below!
			</div>
			<div class="first">
				<form action="register.php" method="POST">
					<input type="email" name="log_email" placeholder="Email Address" value="<?php
					if (isset($_SESSION['log_email'])){
						echo $_SESSION['log_email'];
					}
					?>" required>
					<br>
					<input type="password" name="log_password" placeholder="Password">
					<br>
					<input type="submit" name="login_button" value="Login">
					<br>
					<p>Need an account?<a href="#" id="signup" class="signup"> Register!</a></p>
					<?php if (in_array("Email or password incorrect !<br>", $error_array))
					echo "<div style='color:#EA2027; margin:0;'>Email or password incorrect !</div><br>" ?>
				</form>
			</div>
			<br>
			<div class="second">
				<form action="register.php" method="POST">
					<input type="text" name="reg_fname" placeholder="First Name" value="<?php
					if (isset($_SESSION['reg_fname'])){
						echo $_SESSION['reg_fname'];
					}
					?>" required>
					<br>
					<?php if (in_array("Your first name must be between 2 and 25 characters <br>", $error_array))
					echo "<div style='color:#EA2027; margin:0;'>Your first name must be between 2 and 25 characters</div> <br>"; ?>

					<input type="text" name="reg_lname" placeholder="Last Name" value="<?php
					if (isset($_SESSION['reg_lname'])){
						echo $_SESSION['reg_lname'];
					}?>" required>
					<br>
					<?php if (in_array("Your last name must be between 2 and 25 characters <br>", $error_array))
					echo "<div style='color:#EA2027; margin:0;'>Your last name must be between 2 and 25 characters</div> <br>"; ?>

					<input type="email" name="reg_email" placeholder="Email" value="<?php
					if (isset($_SESSION['reg_email'])){
						echo $_SESSION['reg_email'];
					}?>" required>
					<br>
					<input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
					if (isset($_SESSION['reg_email2'])){
						echo $_SESSION['reg_email2'];
					}?>" required;
					<br><br>
					<?php if (in_array("Email already exists <br>", $error_array))
					echo "<span style='color:#EA2027;'>Email already exists</span> <br>";
					elseif (in_array("Invalid Format <br>", $error_array))
					echo "<span style='color:rgb(54, 61, 194);'>Invalid Format</span> <br>";
					elseif (in_array("Emails don't match <br>", $error_array))
					echo "<span style='color:rgb(54, 61, 194);'>Emails don't match</span> <br>";
					?>
					<input type="password" name="reg_password" placeholder="password" required>
					<br>
					<input type="password" name="reg_password2" placeholder="Confirm password" required>
					<br>
					<?php if (in_array("Your passwords do not match <br>", $error_array))
					echo " <span style='color:rgb(54, 61, 194) margin:0;'>Your passwords do not match</span> <br>";
					elseif (in_array("Your password can only contain english characters and numbers <br>", $error_array))
					echo "<span style='color:rgb(54, 61, 194); margin:0;'>Your password can only contain english characters and numbers</span> <br>";
					elseif (in_array("Your password must be between 5 and 30 <br>", $error_array))
					echo "<span style='color:rgb(54, 61, 194); margin:0;'>Your password must be between 5 and 30</span> <br>";
					 ?>
					<input type="submit" name="register_button" value="Register">
					<br>
					<p>Already have an account<a href="#" id="signin" class="signin"> Login!</a></p>
					<?php if (in_array("<span style='color: #14C800'> You're all set go ahead and login!</span> <br>", $error_array))
					echo "<span style='color: #14C800'> <br>You're all set go ahead and login!</span> <br>";?>

				</form>
			</div>

		</div>
	</div>

</body>
</html>
