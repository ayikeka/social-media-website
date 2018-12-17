
<?php 

require "config/config.php";
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");



if (isset($_SESSION['username'])){
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query); 
}
else {
	header("Location: register.php");
}

 ?>
<html>
	<head>
		<title>GreenPage|Iron sharpenenth iron</title>
		<!--META ATTRIBUTE-->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1" >
		<link rel="shortcut icon" href="assets/images/icons/magnifying_glass.png">
		<!--CSS-->
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="assets/css/style.css">
		<link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">

		<!--JAVASCRIPT-->
		<script src="assets/js/jquery.js" type="text/javascript"></script>
		<script src="assets/js/popper.js" type="text/javascript"></script>
		<script src="assets/js/bootstrap.js" type="text/javascript"></script>
		<script src="assets/js/bootbox.js" type="text/javascript"></script>
		<script src="assets/js/jquery.jcrop.js" type="text/javascript"></script>
		<script src="assets/js/jcrop_bits.js" type="text/javascript"></script>
		<script scr="assets/js/lightbox-plus-jquery.js" type="text/javascript"></script>
		<script src="assets/js/grace.js" type="text/javascript"></script>
	</head>
	<body>

		<div class="top_bar" >
			<div class="logo">
				<a href="index.php"><strong>GreenPage</strong></a>
			</div>

			<div class="search">
				
				<form action="search.php" method="GET" name="search_form">
					<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')"  name="q" placeholder="Search..." 
					autocomplete="off"  id="search_text_input" >

					<div class="button_holder">
						<img src="assets/images/icons/magnifying_glass.png">
					</div> 
				</form>

				<div class="search_results" >	
				</div>

				<div class="search_results_footer_empty">
				</div>

			</div>


			<div class="dropdown-top-bar" id="dropdown-panel-top">
				<div  onclick="myFunction()" class="dropdown-top-bar-image">
					<img src="<?php echo $user['profile_pic']; ?> " width=35>
					<?php echo $user['first_name']; ?>
				</div>
				<div class="dropdown-tooltip" id="dropdown-tooltip"></div>
				<div class="dropdown-content" id="content_dropdown">
					<div class="dropdown-content-details">
					 <div class="dropdown-content-image">
					 	<a href="<?php echo $userLoggedIn ?>" title="profile"><img src="<?php echo $user['profile_pic'];?>" width=80></a>
					 </div>
						 <div class="user-details">
						 	<?php echo $user['first_name'] ." ". $user['last_name']; ?><br>
						 	<span class="e-dropdown"><?php echo $user['email']; ?></span>
						 </div>
						 <div class="myAccount-setting">
							<a href="settings.php">My Account</a>
						 </div>
					 </div>
					 <div class="dropdown-top-bar-footer">
					 	<div class="btn-Add-Account"><a href="#">Add Account</a></div>
					 	<div class="btn-Sign-Out"><a href="includes/handlers/logout.php">Sign Out</a></div>
					 </div>  
				</div>
			</div>

			

			<script>
			/* When the user clicks on the button, 
			toggle between hiding and showing the dropdown content */
			function myFunction() {
			    document.getElementById("content_dropdown").classList.toggle("show");
			    document.getElementById("dropdown-tooltip").classList.toggle("show");
			}

			// Close the dropdown if the user clicks outside of it
			window.onclick = function(event) {
			  if (!event.target.matches('.dropdown-top-bar-image')) {
			    var dropdowns = document.getElementsByClassName("dropdown-content");
			    var i;
			    for (i = 0; i < dropdowns.length; i++) {
			      var openDropdown = dropdowns[i];
			      if (openDropdown.classList.contains('show')) {
			        openDropdown.classList.remove('show');
			      }
			    }
			    document.getElementById("dropdown-tooltip").classList.remove("show");
			  }
			}
			</script>

			<div class="">			
				<nav class="top_navigation">
					
					<?php 
						//Unread Messages
						$messages = new Message($con, $userLoggedIn);
						$num_messages = $messages->getUnreadNumber();

						//Unread Notifications
						$notifications = new Notification($con, $userLoggedIn);
						$num_notifications = $notifications->getUnreadNumber();

						//Number of friend Request
						$user_obj = new User($con, $userLoggedIn);
						$num_requests = $user_obj->getNumberOfFriendRequests();

					 ?>

					<a href="index.php" title="home"><i class="material-icons">home</i></a>
					
					<a href="requests.php" title="friend requests" ><i class="material-icons">favorite</i>
					<?php
					if ($num_requests > 0) {
						echo "<span class='notification_badge' id='unread_request' >".$num_requests."</span>";
					}
					?>
					</a>
					<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')" title="messages"><i class="material-icons">mail</i> 
					<?php
					if($num_messages > 0)
					echo "<span class='notification_badge' id='unread_message' >".$num_messages."</span>";
					?>
					</a>
					<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')" title="notifications"><i class="material-icons">notifications</i>
					<?php
					if($num_notifications > 0)
					echo "<span class='notification_badge' id='unread_notification' >".$num_notifications."</span>";
					?>
					</a>
				</nav>
			</div>
			
			<div class="dropdown_data_window" style="height: 0px; border:none;" >
				
			</div>
			<input type="hidden"  id="dropdown_data_type" value="">

		</div>

		<script>
	 			var userLoggedIn = '<?php echo $userLoggedIn; ?>';

	 			$(document).ready(function(){

	 				$('.dropdown_data_window').scroll(function(){
	 					var inner_height = $('.dropdown_data_window').innerHeight(); //Div containing data
	 					var scroll_top = $('.dropdown_data_window').scrollTop();
	 					var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
	 					var noMoreData = $('.postsdropdown_data_window_area').find('.noMoreDropdownData').val();

	 					if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData=='false'){
	 						$('#loading').show();

	 					var pageName; //Holds name of page to send ajax request to
	 					var type = $('.#dropdown_data_type').val();

	 					if(type == 'notification')
	 						pageName = 'ajax_load_notifications.php';
	 					else if(type == 'message')
	 						pageName = 'ajax_load_messages.php';

	 					var ajaxReq = $.ajax({
	 						url: "includes/handlers/" + pageName,
	 						type: "POST",
	 						data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
	 						cache: false, 

	 						success: function(response){
	 							$('.dropdown_data_window').find('.nextPageDropdownData').remove(); // Remove current .nextPage
	 							$('.dropdown_data_window').find('.noMoreDropdownData').remove(); // Removes empty posts

	 							$('.dropdown_data_window').append(response);
	 						}

	 					});

	 					} // End if statement

	 					return false;
	 				});// End (window).scroll(function())


	 			});



	 		</script>

		<div class="wrapper">