<?php
$user = $_SESSION['user_id'];
$username = $_SESSION['username'];
$query = "SELECT * FROM users WHERE user_id='$user' ";
$query_run = mysqli_query($con, $query);
$result = mysqli_fetch_array($query_run);

if (isset($_POST) && !empty($_POST)) {
    if (isset($_POST['profile'])) {
        $user_id = mysqli_real_escape_string($con, $_POST['user_id']);

        $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
        $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
        $nickname = mysqli_real_escape_string($con, $_POST['nickname']);
        $address = mysqli_real_escape_string($con, $_POST['address']);
        $telephone = mysqli_real_escape_string($con, $_POST['telephone']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $sex = mysqli_real_escape_string($con, $_POST['sex']);
        $old_pic = mysqli_real_escape_string($con, $_POST['old_user_picture']);

        if (isset($_FILES['user_picture']['name']) && !empty($_FILES['user_picture']['name'])) {
            $extension = array("jpeg", "jpg", "png");
            $target = '../assets/images/profile-pic/';
            $filename = $_FILES['user_picture']['name'];
            $filetmp = $_FILES['user_picture']['tmp_name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);

            if (in_array($ext, $extension)) {
                // Check if the file already exists
                if (!file_exists($target . $filename)) {
                    if (move_uploaded_file($filetmp, $target . $filename)) {
                        // File successfully uploaded
                    } else {
                        // Handle upload failure
                    }
                } else {
                    // Generate a new filename to avoid overwriting
                    $newfilename = time() . $filename;
                    if (move_uploaded_file($filetmp, $target . $newfilename)) {
                        $filename = $newfilename;
                    } else {
                        // Handle upload failure
                    }
                }
            } else {
                // Invalid file type
                echo "Invalid file type. Please upload a JPEG, JPG, or PNG file.";
            }
        } else {
            // No file uploaded
            $filename = $old_pic;
        }
        $query = "UPDATE users SET user_firstname='$firstname', user_surname='$lastname', user_nickname='$nickname', user_address='$address' 
        ,user_telephone='$telephone' ,user_email='$email' ,user_gender='$sex',user_picture='$filename' WHERE user_id='$user' ";
        $query_run = mysqli_query($con, $query);

        $name = $_SESSION['username'];
        $query_check_account = "SELECT * FROM users WHERE username = '$name'";
        $call_back_check_account = mysqli_query($con, $query_check_account);
        $result_check_account = mysqli_fetch_assoc($call_back_check_account);

        if ($query_run) {
            $_SESSION['user_picture'] = $filename;
            $_SESSION['message'] = "อัปเดตข้อมูลโปรไฟล์ใหม่สำเร็จ";
            $_SESSION['alert'] = "success";
            $alert = '<script type="text/javascript">';
            $alert .= 'window.location.href = "?page=profile";';
            $alert .= '</script>';
            echo $alert;
            exit();
        } else {
            $_SESSION['message'] = "ไม่สามารถอัปเดตข้อมูลโปรไฟล์ใหม่ได้";
            $_SESSION['alert'] = "danger";
        }
    }
    if (isset($_POST['changepassword'])) {
        $password = htmlspecialchars(mysqli_real_escape_string($con, $_POST['old_password']));
        $query_check_account = "SELECT * FROM users WHERE username = '$username'";
        $call_back_check_account = mysqli_query($con, $query_check_account);

        if (mysqli_num_rows($call_back_check_account) == 1) {
            $result_check_account = mysqli_fetch_assoc($call_back_check_account);
            $hash = $result_check_account['password'];
            $password = $password . $result_check_account['salt_user'];
            if (password_verify($password, $hash)) {
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

                    $query = "UPDATE users SET 	password ='$new_to_update_password', salt_user = '$salt_user' WHERE user_id='$user' ";
                    $query_run = mysqli_query($con, $query);
                    if ($query_run) {
                        $_SESSION['message'] = "เปลี่ยนรหัสผ่านใหม่สำเร็จ";
                        $_SESSION['alert'] = "success";
                    } else {
                        $_SESSION['message'] = "เปลี่ยนรหัสผ่านใหม่ไม่สำเร็จ";
                        $_SESSION['alert'] = "danger";
                    }
                }
            } else {
                $_SESSION['message'] = "รหัสผ่านเก่าไม่ถูกต้อง";
                $_SESSION['alert'] = "danger";
            }
        }
    }
}

?>

<h1 class="app-page-title">บัญชีของฉัน</h1>
<?php include('../sql/message.php'); ?>
<div class="row gy-4">
    <div class="col-12 col-lg-6">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="app-card app-card-account shadow-sm d-flex flex-column align-items-start">
                <div class="app-card-header p-3 border-bottom-0">
                    <div class="row align-items-center gx-3">
                        <div class="col-auto">
                            <div class="app-icon-holder">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0zM8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm6 5c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                </svg>
                            </div><!--//icon-holder-->

                        </div><!--//col-->
                        <div class="col-auto">
                            <h4 class="app-card-title">โปรไฟล์</h4>
                        </div><!--//col-->
                    </div><!--//row-->
                </div><!--//app-card-header-->

                <div class="app-card-body px-4 w-100">
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-auto">
                                <div class="item-label mb-2"><strong>รูปภาพ</strong></div>
                                <div class="item-data">

                                    <input type="hidden" name="old_user_picture" value="<?= $result['user_picture'] ?>">
                                    <img class="m-4" id="output" width="150" height="150" src="../assets/images/profile-pic/<?= $result['user_picture']; ?>" alt="user_picture" />
                                    <input class="form-control " accept="image/*" onchange="loadFile(event)" type='file' name="user_picture">

                                </div>
                            </div><!--//col-->
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>ชื่อ</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="firstname" value="<?= $result['user_firstname'] ?>" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>นามสกุล</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="lastname" value="<?= $result['user_surname'] ?>" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>ชื่อเล่น</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="nickname" value="<?= $result['user_nickname'] ?>" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>เบอร์โทรติดต่อ</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="telephone" value="<?= $result['user_telephone'] ?>" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>เพศ</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="sex" value="<?= $result['user_gender'] ?>" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>อีเมล์</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="email" value="<?= $result['user_email'] ?>" placeholder="Email (optional)"></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>ที่อยู่</strong></div>
                                <div class="item-data"> <input type="text" class="form-control" name="address" value="<?= $result['user_address'] ?> " placeholder="Address (optional)"></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                </div><!--//app-card-body-->
                <div class="app-card-footer p-4 mt-auto">
                    <input type="hidden" name="profile">
                    <input type="submit" class="btn app-btn-secondary" value="อัปเดตข้อมูล" />
                </div><!--//app-card-footer-->
            </div><!--//app-card-->
        </form>
    </div><!--//col-->

    <div class="col-12 col-lg-6">
        <form action="" method="post">
            <div class="app-card shadow-sm d-flex flex-column align-items-start">
                <div class="app-card-header p-3 border-bottom-0">
                    <div class="row align-items-center gx-3">
                        <div class="col-auto">
                            <div class="app-icon-holder">
                                <i class="fa-solid fa-key p-2" fill="currentColor"></i>
                            </div><!--//icon-holder-->

                        </div><!--//col-->
                        <div class="col-auto">
                            <h4 class="app-card-title">เปลี่ยนรหัสผ่าน</h4>
                        </div><!--//col-->
                    </div><!--//row-->
                </div><!--//app-card-header-->
                <div class="app-card-body px-4 w-100">
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>รหัสผ่านเก่า</strong></div>
                                <div class="item-data"> <input type="password" class="form-control" name="old_password" placeholder="รหัสผ่านเก่า" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>รหัสผ่านใหม่</strong></div>
                                <div class="item-data"> <input type="password" class="form-control" name="new_password" placeholder="รหัสผ่านใหม่" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                    <div class="item border-bottom py-3">
                        <div class="row justify-content-between align-items-center">
                            <div class="">
                                <div class="item-label"><strong>ยืนยันรหัสผ่าน</strong></div>
                                <div class="item-data"> <input type="password" class="form-control" name="confirmnew_password" placeholder="ยืนยันรหัสผ่าน" required></div>
                            </div>
                        </div><!--//row-->
                    </div><!--//item-->
                </div><!--//app-card-body-->

                <div class="app-card-footer p-4">
                    <input type="hidden" name="changepassword">
                    <input type="submit" class="btn app-btn-secondary" value="เปลี่ยนรหัสผ่านใหม่" />
                </div><!--//app-card-footer-->

            </div><!--//app-card-->
        </form>
    </div>

</div><!--//row-->
<script>
    var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
</script>