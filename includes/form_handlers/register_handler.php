
<?php
//Decclaring variables
$fname =""; //First name
$lname =""; //Last Name
$em = ""; //email
$em2 = ""; //email 2
$password = ""; //password
$password2 = ""; //password 2
$date = ""; //sign up date
$error_array = array(); //Holds error messages

if (isset($_POST['register_button'])){

	//Registration form values

	//First Name
	$fname = strip_tags($_POST['reg_fname']); //Remove html tags
	$fname = str_replace(' ','',$fname); // Remove space
	$fname = ucfirst(strtolower($fname)); //Uppercase First Letter
	$_SESSION['reg_fname'] = $fname; //Stores first name in session varible

	//Last Name
	$lname = strip_tags($_POST['reg_lname']); //Remove html tags
	$lname = str_replace(' ','',$lname); // Remove space
	$lname = ucfirst(strtolower($lname)); //Uppercase First Letter
	$_SESSION['reg_lname'] = $lname; //Stores last name in session varible


	//Email
	$em = strip_tags($_POST['reg_email']); //Remove html tags
	$em = str_replace(' ','',$em); // Remove space
	$em = ucfirst(strtolower($em)); //Uppercase First Letter
	$_SESSION['reg_email'] = $em; //Stores email in session varible


	//Email 2
	$em2 = strip_tags($_POST['reg_email2']); //Remove html tags
	$em2 = str_replace(' ','',$em2); // Remove space
	$em2 = ucfirst(strtolower($em2)); //Uppercase First Letter
	$_SESSION['reg_email2'] = $em2; //Stores email2 in session varible


	//Password
	$password = strip_tags($_POST['reg_password']); //Remove html tags
	$password2 = strip_tags($_POST['reg_password2']); //Remove html tags

	//Date
	$date = date('Y-m-d');//Current date

	if ($em == $em2){

		//Check if Email is in Valid format
		if (filter_var($em, FILTER_VALIDATE_EMAIL)){

			$em = filter_var($em, FILTER_VALIDATE_EMAIL);

			//Check if email already exists
			$e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

			//Count the number of rows returned
			$num_rows = mysqli_num_rows($e_check);

			if ($num_rows > 0){
				array_push($error_array, "Email already exists <br>") ;
			}

		}else{
			array_push($error_array, "Invalid Format <br>");
		}

	}else{
		array_push($error_array,"Emails don't match <br>");

	}

	if(strlen($fname) >25 || strlen($fname) < 2){
		array_push($error_array,"Your first name must be between 2 and 25 characters <br>");
	}
	if(strlen($lname) >25 || strlen($lname) < 2){
		array_push($error_array,"Your last name must be between 2 and 25 characters <br>");
	}
	if ($password != $password2){
		array_push($error_array,"Your passwords do not match <br>");
	}else{
		if (preg_match('/[^A-Za-z0-9]/', $password)){
			array_push($error_array,"Your password can only contain english characters and numbers <br>");
		}
	}
	if (strlen($password) < 5 || strlen($password) > 30){
		array_push($error_array,"Your password must be between 5 and 30 <br>");
	}

		// This is what happens when we have no error
	if (empty($error_array)){
		$password = md5($password); //encrypts the password before sending it into the database

		//Generate username by concatenating first name and lastname
		$username = strtolower($fname. "_" .$lname);
		$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='username'");

		$i = 0;
		//if username exists add number to it
		while (mysqli_num_rows($check_username_query) != 0) {
			$i++; //Increase i by 1
			$username = $username. "_" .$i;
			$check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='username'");

		}

		//Assigning a profile picture

		/*function get_rand_numbers($ran_val){

			switch ($rand_val) {
	    	case 0:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_alizarin.png";
	        	break;
	    	case 1:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_amethyst.png";
	        	break;
	   		case 2:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_sun_flower.png";
	        	break;
	        case 3:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_turqoise.png";
	        	break;
	        case 4:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
	        	break;
	        case 5:
	        	$profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
	        	break;
			}
		}*/

		$rand = rand(1,2); //Creating a random number between 1 and 2
		if ($rand == 1)
			$profile_pic = "assets/images/profile_pics/defaults/head_alizarin.png";
		elseif ($rand == 2)
			$profile_pic = "assets/images/profile_pics/defaults/head_amethyst.png";


			$query = mysqli_query($con, "INSERT INTO users VALUES('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");
			array_push($error_array, "<span style='color: #14C800'><br> You're all set go ahead and login!</span><br>");
		//Clear session variables
		$_SESSION['reg_fname']='';
		$_SESSION['reg_lname']='';
		$_SESSION['reg_email']='';
		$_SESSION['reg_email2']='';
	}
}
?>
