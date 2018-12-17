<?php 
include("includes/header.php");

if(isset($_POST['cancel'])){
	header("Location: settings.php");
}

if (isset($_POST['close_account'])) {
	$close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLogged'");
	session_destroy();
	header("Location: register.php");
}

 ?>

 <div class="main_column column">
	
	<h4>CLose Account</h4>

	Are you sure you want to close your acccount ?<br><br>
	Closing your account will hide all your activity sessions from other users.<br><br>
	But please be reminded that, you can always go back to your account by logging in again.<br><br>

	<form action="close_account.php" method="POST">
		<input type="submit" name="close_account" class="danger" id="close_account" value="Yes! Close it!">
		<input type="submit" name="cancel" class="success" id="" value="Noope!">
	</form>


 </div>