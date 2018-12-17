<?php 
include("../../config/config.php");
include("../../includes/Classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query); //Allows us to search for first and last names separately

//IF query contains an underscore, assume user is searching for usernames
if(strpos($query, '_') !== false){
	$userReturnedquery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 5");
}else if(count($names) == 2) {
	//If there are two words assume they are first and last names respectively
	$userReturnedquery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') 
						AND user_closed='no' LIMIT 5");
}else
	$userReturnedquery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 5");

if($query != ""){

	while ($row = mysqli_fetch_array($userReturnedquery)) {
		$user = new User($con, $userLoggedIn);

		if($row['username'] != $userLoggedIn)
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
		else
			$mutual_friends = "";

		echo "<div class='resultDisplay'>
				<a href='" . $row['username'] . "'style='color: #1485BD'>
					<div class='liveSearchProfilePic'>
						<img src='" . $row['profile_pic'] . "'>
					</div>
					<div class='liveSearchText'>
						" . $row['first_name'] . " " . $row['last_name'] . "
						<p style='margin:0'>" . $row['username'] . "</p>
						<p id='grey' style='margin:0'>" .$mutual_friends . "</p>
					</div>
				</a>
			</div>";
	}
}



 ?>