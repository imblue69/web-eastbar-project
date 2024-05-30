<?php
session_start();
include('../../sql/dbcon.php');
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "ไม่สำเร็จ";
    $_SESSION['alert'] = "danger";
    $alert = '<script type="text/javascript">';
    $alert .= 'window.location.href = "../?page=manage-work-schedule";';
    $alert .= '</script>';
    echo $alert;
    exit();
}

$delete = $con->query("DELETE FROM `schedule_list` where id = '{$_GET['id']}'");
if ($delete) {
    $_SESSION['message'] = "ลบกำหนดการสำเร็จ";
    $_SESSION['alert'] = "success";
    $alert = '<script type="text/javascript">';
    $alert .= 'window.location.href = "../?page=manage-work-schedule";';
    $alert .= '</script>';
    echo $alert;
    exit();
} else {
    echo "<pre>";
    echo "An Error occured.<br>";
    echo "Error: " . $con->error . "<br>";
    echo "SQL: " . $sql . "<br>";
    echo "</pre>";
}
$con->close();
