<?php 
include("includes/header.php");
	
	$message_obj = new Message($con, $userLoggedIn);

	if(isset($_GET['profile_username'])){
		$username = $_GET['profile_username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
		$user_array = mysqli_fetch_array($user_details_query);

		$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
	}

	if(isset($_POST['remove_friend'])){
		$user = new User($con, $userLoggedIn);
		$user->removeFriend($username);
	}

	if(isset($_POST['add_friend'])){
		$user = new User($con, $userLoggedIn);
		$user->sendRequest($username);
	}

	if(isset($_POST['respond_request'])){
		header("Location: requests.php");
	}

	if(isset($_POST['post_messsage'])){
		if(isset($_POST['message_body'])){
			$body=mysqli_real_escape_string($con, $_POST['message_body']);
			$date = date("Y-m-d H:i:s");
			$message_obj->sendMessage($username, $body, $date);
		}

	echo "<script type='text/javascript'>
		$(document).ready(function(){
			echo 'Hello Message';
			$('#messages_div').show();
		});

		</script>";

	}

 ?>

 		<style type="text/css">
 			.wrapper{
 				margin-left: 0px;
 				padding-left: 0px;
 			}
 		</style>

 		<div class="profile_left" >
 			<!--<span class="upload_icon"><a href="upload.php" title="upload Picture"><i class="">insert photo</i></a></span>-->
			<img id="Profile_Image" src="<?php echo $user_array['profile_pic']; ?>" alt="Profile Picture" style="width: 100%;">

			<div class="profile_info" >
				<p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
				<p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
				<p><?php echo "Friends: " . $num_friends; ?></p>
			</div>	

			<form action="<?php echo $username; ?>" method="POST">
				<?php $profile_user_obj = new User($con, $username); 
				if($profile_user_obj -> isClosed()){
					header("Location: user_closed.php");
				}

				$logged_in_user_obj = new User($con, $userLoggedIn);

				if ($userLoggedIn != $username){

					if($logged_in_user_obj->isFriend($username)){
						echo '<input type="submit" name="remove_friend" class="danger" id="danger" value="Remove Friend"<br>';
					}
					else if ($logged_in_user_obj->didReceiveRequest($username)){
						echo '<input type="submit" name="respond_request" class="warning" value="Respond Request"<br>';
					}
					else if ($logged_in_user_obj->didSendRequest($username)){
						echo '<input type="submit" name="Send_request" class="default" value="Request Sent"<br>';
					}
					else echo 
							 '<input type="submit" name="add_friend" class="success" value="Add Friend"<br>';

				}

				?>
			</form>	

			<!-- The Modal For Loading Picture  -->
			<div id="Picture_Modal" class="modal_picture">
			  <span class="close_profile">&times;</span>
			  <img class="modal-content_picture" id="img01">
			  <div id="caption"></div>
			</div>

			<script>
			// Get the modal
			var modal = document.getElementById('Picture_Modal');

			// Get the image and insert it inside the modal - use its "alt" text as a caption
			var img = document.getElementById('Profile_Image');
			var modalImg = document.getElementById("img01");
			var captionText = document.getElementById("caption");
			img.onclick = function(){
			    modal.style.display = "block";
			    modalImg.src = this.src;
			    captionText.innerHTML = this.alt;
			}

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close_profile")[0];

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() { 
			    modal.style.display = "none";
			}
			</script>


			<!-- Trigger/Open The Modal -->
		    <button type="submit" id="modal_Btn" data-toggle="modal_Profile" data-target="#post_form"><i class="material-icons">create</i> Post</button>

		    <?php 
		    	if($userLoggedIn != $username){
		    	echo '<div class="profile_info_bottom">';
		    		echo $logged_in_user_obj->getMutualFriends($username) . " mutual friends";
		    	echo '</div>';
		    	}

		     ?>

 		</div>

 		<div class="profile_main_column column">

				<div class="tab" id="profileTabs">
					<button class="tablinks active"  onclick="openTab(event, 'newsfeed_div')">NewsFeed</button>
					<button class="tablinks" onclick="openTab(event, 'about_div')">About</button>
					<button class="tablinks" onclick="openTab(event, 'messages_div')">Messages</button>
					<button class="tablinks" onclick="openTab(event, 'gallery_div')">Gallery</button>
				</div>

				<div class="tabcontent" id="newsfeed_div" style="display: block;">
				 	<div class="posts_area"></div> 
			 		<img id="loading" src="assets/images/icons/Ellipsis.gif">
			 	</div>

				<div class="tabcontent" id="about_div">
				<h3>GraceChat</h3>
				<p>GraceChat is a social media application that seeks to unite people as one in Christ. </p> 
				</div>

				<div class="tabcontent" id="messages_div">
				 	<?php 

						echo "<h4> You and <a href='".$username."' class='fNlname'>" . $profile_user_obj->getFirstAndLastName() . "</a>'s Chat.</h4><hr><br>";
						echo "<div class='loaded_messages' id='scroll_messages' >";
							echo $message_obj->getMessages($username);
						echo "</div><br>";
				 	?>

					<div class="message_post" >
						<form action="" method="POST">
							<textarea name='message_body' id='message_textarea' placeholder='Type message ...'></textarea>
							<button type='submit' name='post_messsage' id='message_submit'><i class='material-icons' style='color: #e67e20;'>send</i></button>
						</form>
					</div>

					<!--Script Code Prevents whole page from reloading again-->
					<script>
						var div = document.getElementById("scroll_messages");
						div.scrollTop = div.scrollHeight;
					</script>

		 		</div>

		 		<div class="tabcontent" id="gallery_div">
					
					<?php 

						echo "<img src='" . $user['profile_pic'] . "'";
					 ?>

					Will Make This Place a gallery section for saving of pictures!!
				
				</div>

			<script>
			function openTab(evt, tabName) {
			    var i, tabcontent, tablinks;
			    tabcontent = document.getElementsByClassName("tabcontent");
			    for (i = 0; i < tabcontent.length; i++) {
			        tabcontent[i].style.display = "none";
			    }
			    tablinks = document.getElementsByClassName("tablinks");
			    for (i = 0; i < tablinks.length; i++) {
			        tablinks[i].className = tablinks[i].className.replace(" active", "");
			    }
			    document.getElementById(tabName).style.display = "block";
			    evt.currentTarget.className += " active";
			}
			</script>
				 	
		</div>

		<!-- The Modal -->
		<div  class="modal_Profile" id="post_form" >
		  <!-- Modal content -->
		  <div class="modal-content_Profile">
		    <div class="modal-header_Profile">
		      <span class="close">&times;</span>
		      <h2>Say Something !</h2>
		    </div>
		    <div class="modal-body_Profile">
		      <p>This will appear on the user's profile page and also on the newsfeed for your friends to see !</p>
		      <form class="profile_post" action="" method="POST">
		      	<div class="form-group" >
		      		<textarea class="form-control" name="post_body"></textarea>
		      		<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
		      		<input type="hidden" name="user_to" value="<?php echo $username; ?>">
		      	</div>
		      </form>
		    </div>
		    <div class="modal-footer_Profile">
		      <button type="button" onclick="document.getElementById('post_form').style.display='none'" class="btn btn-default" data-dismiss="modal">Close</button>
		      <button type="button" class="btn btn-success" name="post_button" id="submit_profile_post" ><i class="material-icons" style="color: #e67e22;">send</i></button>
		    </div>
		  </div>
		</div>

			<script>

				// Get the modal
				var modal_Profile = document.getElementById('post_form');

				// Get the button that opens the modal
				var btn = document.getElementById("modal_Btn");

				// Get the <span> element that closes the modal
				var span = document.getElementsByClassName("close")[0];

				// When the user clicks the button, open the modal 
				btn.onclick = function() {
				    modal_Profile.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span.onclick = function() {
				    modal_Profile.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
				    if (event.target == modal_Profile) {
				        modal_Profile.style.display = "none";
				    }
				}

			</script>


			<script>
	 			var userLoggedIn = '<?php echo $userLoggedIn; ?>';
	 			var profileUsername = '<?php echo $username;?>'

	 			$(document).ready(function(){

	 				$('#loading').show();

	 				//Original ajax request for loading first posts
	 				$.ajax({
	 					url: "includes/handlers/ajax_load_profile_posts.php",
	 					type: "POST",
	 					data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
	 					cache: false,

	 					success: function(data) {
	 						$('#loading').hide();
	 						$('.posts_area').html(data);
	 					}
	 				});

	 				$(window).scroll(function(){
	 					var height = $('.posts_area').height(); //Div containing posts
	 					var scroll_top = $(this).scrollTop();
	 					var page = $('.posts_area').find('.nextPage').val();
	 					var noMorePosts = $('.posts_area').find('.noMorePosts').val();

	 					if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts=='false'){
	 						$('#loading').show();


	 					var ajaxReq = $.ajax({
	 						url: "includes/handlers/ajax_load_profile_posts.php",
	 						type: "POST",
	 						data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
	 						cache: false, 

	 						success: function(response){
	 							$('.posts_area').find('.nextPage').remove(); // Remove current .nextPage
	 							$('.posts_area').find('.noMorePosts').remove(); // Removes empty posts


	 							$('#loading').hide();
	 							$('.posts_area').append(response);
	 						}

	 					});

	 					} // End if statement

	 					return false;
	 				});// End (window).scroll(function())


	 			});



	 		</script>

		
 		</div>
	</body>
</html>
