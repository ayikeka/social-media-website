<?php
// Ajax allows us to make database calls without reloading
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Post.php");

$limit = 12; //Number of posts to be loaded per call.

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST, $limit);

 ?>
