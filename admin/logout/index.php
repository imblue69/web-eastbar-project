<?php
session_destroy();
unset($_SESSION['user_id']);
$alert = '<script type="text/javascript">';
$alert .= 'alert("ออกจากระบบ");';
$alert .= 'window.location.href = "../";';
$alert .= '</script>';
echo $alert;
exit();
?>