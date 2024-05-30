<?php
$user_id = mysqli_real_escape_string($con, $_GET['id']);
$query = "SELECT * FROM users WHERE user_id='$user_id'";
$query_run1 = mysqli_query($con, $query);

if (mysqli_num_rows($query_run1) > 0) {
    $user = mysqli_fetch_array($query_run1);
    $name = $user['username'];

    if (isset($_POST) && !empty($_POST)) {
        if (isset($_POST['changepassword'])) {
            $new_password = $_POST['new_password'];
            $confirmnew_password = $_POST['confirmnew_password'];
            if ($new_password != $confirmnew_password) {
                $_SESSION['message'] = "รหัสผ่านใหม่ไม่ถูกต้อง";
                $_SESSION['alert'] = "danger";
            } else {
                $length = random_int(97, 128);
                $salt_user = bin2hex(random_bytes($length));
                $password1 = $new_password . $salt_user;
                $algo = PASSWORD_ARGON2ID;
                $options = [
                    'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                    'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                    'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
                ];
                $new_to_update_password = password_hash($password1, $algo, $options);

                $query = "UPDATE users SET password ='$new_to_update_password', salt_user = '$salt_user' WHERE user_id='$user_id' ";
                $query_run = mysqli_query($con, $query);

                if ($query_run) {
                    // If the query was successful
                    $_SESSION['message'] = "เปลี่ยนรหัสผ่านใหม่ $name สำเร็จ ";
                    $_SESSION['alert'] = "success";

                    // Redirect to manage-user page
                    $alert = '<script type="text/javascript">';
                    $alert .= 'window.location.href = "?page=manage-user";';
                    $alert .= '</script>';
                    echo $alert;
                    exit(); // Exit the script
                } else {
                    // If the query failed
                    $_SESSION['message'] = "เปลี่ยนรหัสผ่านใหม่ไม่สำเร็จ";
                    $_SESSION['alert'] = "danger";
                }
            }
        }
    }
?>
    <?php include('../sql/message.php'); ?>
    <div class="row">
        <div class="col-12 col-lg-6">
            <div class="app-card shadow-sm">
                <div class="app-card-header p-3">
                    <h4>บัญชี : <?= $user['username'] ?>
                        <a href="?page=manage-user" class="btn btn-danger float-end">กลับ <i class="fa-solid fa-xmark"></i></a>
                    </h4>
                </div>
                <div class="app-card-body p-4">
                    <form action="" method="post">
                        <div class="app-card">
                            <div class="app-card-header p-3 border-bottom-0">
                                <div class="row align-items-center gx-3">
                                    <div class="col-auto">
                                        <div class="app-icon-holder">
                                            <i class="fa-solid fa-key" fill="currentColor"></i>
                                        </div><!--//icon-holder-->

                                    </div><!--//col-->
                                    <div class="col-auto">
                                        <h4 class="app-card-title">เปลี่ยนรหัสผ่านใหม่</h4>
                                    </div><!--//col-->
                                </div><!--//row-->
                            </div><!--//app-card-header-->
                            <div class="app-card-body px-4 w-100">
                                <div class="item border-bottom py-3">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="">
                                            <div class="item-label"><strong>รหัสผ่านใหม่</strong></div>
                                            <div class="item-data"> <input type="password" class="form-control" name="new_password" placeholder="รหัสผ่านใหม่" required></div>
                                        </div>
                                    </div><!--//row-->
                                </div><!--//item-->
                                <div class="item py-3">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="">
                                            <div class="item-label"><strong>ยืนยันรหัสผ่าน</strong></div>
                                            <div class="item-data"> <input type="password" class="form-control" name="confirmnew_password" placeholder="ยืนยันรหัสผ่าน" required></div>
                                        </div>
                                    </div><!--//row-->
                                </div><!--//item-->
                            </div><!--//app-card-body-->

                            <div class="app-card-footer">
                                <input type="hidden" name="changepassword">
                                <input type="submit" class="btn app-btn-secondary" value="เปลี่ยนรหัสผ่านใหม่" />
                            </div><!--//app-card-footer-->

                        </div><!--//app-card-->
                    </form>
                </div>
            </div>
        </div>
    </div><!--//row-->



<?php
} else {
    echo "<h4>No Such Id Found</h4>";
}

?>