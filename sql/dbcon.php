<?php
// if (!defined('base_url')) define('base_url', 'https://east-bar.com/');
if (!defined('base_url')) define('base_url', 'http://localhost:8080/web/web-eastbar/');
$con = mysqli_connect("localhost", "root", "", "east_bar_db");

if (!$con) {
    $_SESSION['message'] = "Connection DB Failed ";
    die('Connection Failed' . mysqli_connect_error());
}
?>