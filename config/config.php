<?php
ob_start(); // TURNS ON OUTPUT BUFFERRING
session_start(); // creates a session to help store variables

$timezone = date_default_timezone_set("Africa/Accra");

$con = mysqli_connect("localhost","root","meek","social"); //connection variable

if (mysqli_connect_errno())
{
echo "Failed to connect ".mysqli_connect_errno();
}

?>
