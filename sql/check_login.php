<?php
session_start();
require 'dbcon.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = htmlspecialchars(mysqli_real_escape_string($con, $_POST['username']));
    $password = htmlspecialchars(mysqli_real_escape_string($con, $_POST['password']));
    $query_check_account = "SELECT * FROM users WHERE username = '$username'";
    $call_back_check_account = mysqli_query($con, $query_check_account);
    if (mysqli_num_rows($call_back_check_account) == 1) {
        $result_check_account = mysqli_fetch_assoc($call_back_check_account);
        $hash = $result_check_account['password'];
        $password = $password . $result_check_account['salt_user'];

        if (password_verify($password, $hash)) {
            if ($result_check_account['user_type'] == 'manager') {
                $_SESSION['user_id'] = $result_check_account['user_id'];
                $_SESSION['user_type'] = $result_check_account['user_type'];
                $_SESSION['username'] = $result_check_account['username'];
                $_SESSION['user_nickname'] = $result_check_account['user_nickname'];
                $_SESSION['user_picture'] = $result_check_account['user_picture'];
                die(header('Location: ../manager/'));
            } elseif ($result_check_account['user_type'] == 'admin') {
                $_SESSION['user_id'] = $result_check_account['user_id'];
                $_SESSION['user_type'] = $result_check_account['user_type'];
                $_SESSION['username'] = $result_check_account['username'];
                $_SESSION['user_nickname'] = $result_check_account['user_nickname'];
                $_SESSION['user_picture'] = $result_check_account['user_picture'];
                die(header('Location: ../admin/'));
            } elseif ($result_check_account['user_type'] == 'employee') {
                $_SESSION['user_id'] = $result_check_account['user_id'];
                $_SESSION['user_type'] = $result_check_account['user_type'];
                $_SESSION['username'] = $result_check_account['username'];
                $_SESSION['user_nickname'] = $result_check_account['user_nickname'];
                $_SESSION['user_picture'] = $result_check_account['user_picture'];
                die(header('Location: ../user/'));
            }
        } else {
            $_SESSION['message'] = "รหัสผ่านไม่ถูกต้อง";
            $_SESSION['alert'] = "danger";
            header("Location: ../index.php");
            exit(0);
        }
    } else {
        $_SESSION['message'] = "ชื่อผู้ใช้ไม่ถูกต้อง";
        $_SESSION['alert'] = "danger";
        header("Location: ../index.php");
        exit(0);
    }
} else {
    $_SESSION['message'] = "กรุณากรอกชื่อผู้ใช้และรหัสผ่าน";
    $_SESSION['alert'] = "danger";
    header("Location: ../index.php");
    exit(0);
}

$conn->close();
