<?php
//Start Session();
session_start();

//Create constants to store Non Repeating Values
define('SITEURL', 'https://food-order-web.herokuapp.com/');
define('LOCALHOST', 'us-cdbr-east-04.cleardb.com');
define('DB_USERNAME', 'bbbdd980d145aa');
define('DB_PASSWORD', '35a8f353');
define('DB_NAME', 'heroku_42cb7f8104668b9');


$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or  die(mysqli_error());//Database connection
$db_select = mysqli_select_db($conn, DB_NAME) or die (mysql_error());// Selecting database
?>

