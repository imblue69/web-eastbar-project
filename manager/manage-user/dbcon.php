<?php

$con = mysqli_connect("localhost", "root", "", "east_Bar_DB");

if (!$con) {
    $_SESSION['message'] = "Connection DB Failed ";
    die('Connection Failed' . mysqli_connect_error());
}

?>