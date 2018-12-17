<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u']))
	$user_to = $_GET['u'];
else {
	$user_to = $message_obj->getMostRecentUser();
	if($user_to == false)
		$user_to = 'new';
}

	if($user_to != "new")
		$user_to_obj = new User($con, $user_to);

	if(isset($_POST['post_messsage'])) {

		if(isset($_POST['message_body'])) {
			$body = mysqli_real_escape_string($con, $_POST['message_body']);
			$date = date("Y-m-d H:i:s");
			$message_obj->sendMessage($user_to, $body, $date);
		}
	}

 ?>


<div class="user_details column" id="conversations">
	<h4>Conversations</h4>

	<div class="loaded_conversations">
		<?php echo $message_obj->getConvos() ?>
	</div>
	<br>
	<a href="messages.php?u=new"> Create New Message</a>
</div>



<div class="main_column column" id="main_column">
	<?php
		if ($user_to != "new") {
			echo "<h4> You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a>'s Chat.</h4><hr><br>";
			echo "<div class='loaded_messages' id='scroll_messages' >";
				echo $message_obj->getMessages($user_to);
			echo "</div><br>";
		}
		else{
			echo "<h4> Create New Message</h4>";
		}
	 ?>

	<div class="message_post" >
		<form action="" method="POST">
			<?php
				if ($user_to == "new") {
					echo "Select the friend you would like to send a message. <br><br>";
			?>

				To : <input type='text' onkeyup='getUser(this.value, "<?php echo $userLoggedIn;?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>

			<?php
					echo "<div class='results' ></div>";
				}
				else {
					echo "<textarea name='message_body' id='message_textarea' placeholder='Type message ...'></textarea>";
					echo "<button type='submit' name='post_messsage' id='message_submit'><i class='material-icons' style='color: #e67e20;'>send</i></button>";
				}

			 ?>
		</form>
	</div>

	<!--Script Code Prevents whole page from reloading again-->
	<script>
		var div = document.getElementById("scroll_messages");
		div.scrollTop = div.scrollHeight;
	</script>

</div>
