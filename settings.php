<?php 
include("includes/header.php");
include("includes/form_handlers/settings_handler.php");

 ?>

 <div class="main_column column">
 	
 	<h4>Account Settings</h4>

 	<?php 
 	echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pics'>";
 	 ?><br>
 	 <a href="upload.php">Upload New Profile Picture</a><br><br><br>

 	 <h4>Modify the values and click 'Update Details'</h4>

 	 <?php 
 	 	$user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
 	 	$row = mysqli_fetch_array($user_data_query); 

 	 	$first_name = $row['first_name'];
 	 	$last_name = $row['last_name'];
 	 	$email = $row['email'];

 	  ?>

 	 <form action="settings.php" method="POST">
 	 First name: <input type="text" name="first_name" class="changeDetails" value="<?php echo $first_name ?>"><br>
 	 Second name: <input type="text" name="last_name" class="changeDetails" value="<?php echo $last_name ?>"><br>
 	 Email: <input type="text" name="email" class="changeDetails" value="<?php echo $email ?>"><br>

 	 <?php echo $message; ?>

 	 <input type="submit" class="success" name="update_details" id="save_details" value="Update Details">
 	 </form>

 	 <h4>Change Password</h4>
 	 <form action="settings.php" method="POST">
 	 Old Password: <input type="Password" name="old_password" class="changeDetails"><br>
 	 New Password: <input type="Password" name="new_password_1" class="changeDetails"><br>
 	 New Password Again: <input type="Password" name="new_password_2" class="changeDetails"><br>

 	 <?php echo $password_message; ?>

 	 <input type="submit" class="success" name="update_password" id="save_password" value="Update Password">
 	 </form>

 	 <h4>Close Account</h4>
 	 <form action="settings.php" method="POST">
 	 	<input type="submit" class="danger" name="close_account" id="close_account" value="Close Account">
 	 </form>


 </div>