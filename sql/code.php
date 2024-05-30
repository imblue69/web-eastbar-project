<?php
session_start();
require 'dbcon.php';

//------------------------------------------- delete_user ----------------------------------
if (isset($_POST['delete_user'])) {
    $user_id = mysqli_real_escape_string($con, $_POST['delete_user']);
    $username = mysqli_real_escape_string($con, $_POST['username']);

    $query = "DELETE FROM users WHERE user_id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    $name = $_SESSION['username'];
    $query_check_account = "SELECT * FROM users WHERE username = '$name'";
    $call_back_check_account = mysqli_query($con, $query_check_account);
    $result_check_account = mysqli_fetch_assoc($call_back_check_account);

    if ($query_run) {
        $_SESSION['message'] = "ลบผู้ใช้ระบบ $username สำเร็จ ";
        $_SESSION['alert'] = "success";
        if ($result_check_account['user_type'] == 'manager') {
            die(header('Location: ../manager/?page=manage-user'));
        } elseif ($result_check_account['user_type'] == 'admin') {
            die(header('Location: ../admin/?page=manage-user'));
        }
        exit(0);
    } else {
        $_SESSION['message'] = "ลบผู้ใช้ระบบไม่สำเร็จ";
        $_SESSION['alert'] = "danger";
        if ($result_check_account['user_type'] == 'manager') {
            die(header('Location: ../manager/?page=manage-user'));
        } elseif ($result_check_account['user_type'] == 'admin') {
            die(header('Location: ../admin/?page=manage-user'));
        }
        exit(0);
    }
}