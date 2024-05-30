<div class="row">
    <div class="col-12">
        <div class="app-card-body">
            <!-- ----------------------------- Body -------------------------- -->
            <div class="row">
                <div class="col-md-12">
                    <div class="app-card shadow-sm">
                        <div class="app-card-header p-3">
                            <h4>เพิ่มผู้ใช้ระบบ
                                <a href="?page=manage-user" class="btn btn-danger float-end">กลับ <i class="fa-solid fa-xmark"></i></a>
                            </h4>
                        </div>
                        <div class="app-card-body p-4">
                            <?php
                            if (isset($_POST['add_user'])) {

                                $username = mysqli_real_escape_string($con, $_POST['username']);
                                $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
                                $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
                                $nickname = mysqli_real_escape_string($con, $_POST['nickname']);
                                $address = mysqli_real_escape_string($con, $_POST['address']);
                                $telephone = mysqli_real_escape_string($con, $_POST['telephone']);
                                $email = mysqli_real_escape_string($con, $_POST['email']);
                                $sex = mysqli_real_escape_string($con, $_POST['sex']);

                                if (empty($username)) {
                                    die(header("Location: ../manager/?page=manage-user"));
                                } else {
                                    $query_check_username = "SELECT username FROM users WHERE username = '$username' ";
                                    $call_back_query_check_username = mysqli_query($con, $query_check_username);
                                    if (mysqli_num_rows($call_back_query_check_username) > 0) {
                                        $_SESSION['message'] = "Username already used";
                                        $_SESSION['alert'] = "danger";
                                    } else {
                                        $length = random_int(97, 128);
                                        $salt_user = bin2hex(random_bytes($length));
                                        $password1 = 1234 . $salt_user;
                                        $algo = PASSWORD_ARGON2ID;
                                        $options = [
                                            'cost' => PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                                            'time_cost' => PASSWORD_ARGON2_DEFAULT_TIME_COST,
                                            'threads' => PASSWORD_ARGON2_DEFAULT_THREADS
                                        ];
                                        $password = password_hash($password1, $algo, $options);

                                        if (isset($_FILES['user_picture']['name'])) {
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
                                                    }
                                                } else {
                                                    $newfilename = time() . $filename;
                                                    if (move_uploaded_file($filetmp, $target . $newfilename)) {
                                                        $filename = $newfilename;
                                                    }
                                                }
                                            } else {
                                                // Invalid file type
                                                $filename = "default_images_user.jpg";
                                            }
                                        } else {
                                            // No file uploaded
                                            $filename = "default_images_user.jpg";
                                        }

                                        $query = "INSERT INTO users (username, user_firstname, user_surname, user_nickname, user_address, user_telephone, user_email, user_gender, user_picture, password, salt_user, user_type)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'employee')";
                                        $stmt = mysqli_prepare($con, $query);
                                        mysqli_stmt_bind_param($stmt, "sssssssssss", $username, $firstname, $lastname, $nickname, $address, $telephone, $email, $sex, $filename, $password, $salt_user);
                                        $query_run = mysqli_stmt_execute($stmt);

                                        if ($query_run) {
                                            $_SESSION['message'] = "เพิ่มผู้ใช้ระบบสำเร็จ";
                                            $_SESSION['alert'] = "success";
                                            $alert = '<script type="text/javascript">';
                                            $alert .= 'window.location.href = "?page=manage-user";';
                                            $alert .= '</script>';
                                            echo $alert;
                                            exit();
                                        } else {
                                            $_SESSION['message'] = "เพิ่มผู้ใช้ระบบไม่สำเร็จ";
                                            $_SESSION['alert'] = "danger";
                                        }
                                    }
                                }
                            }

                            ?>
                            <?php include('../sql/message.php'); ?>
                            <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                                <div class="col-md-4">
                                    <label>ชื่อผู้ใช้ระบบ</label>
                                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="col-md-4">
                                    <label>รหัสผ่าน</label>
                                    <p class="form-control">
                                        1234
                                    </p>
                                </div>
                                <div class="col-md-4"> </div>
                                <div class="col-md-4">
                                    <label>ชื่อจริง</label>
                                    <input type="text" name="firstname" placeholder="Firstname" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>นามสกุล</label>
                                    <input type="text" name="lastname" placeholder="Surname" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>ชื่อเล่น</label>
                                    <input type="text" name="nickname" placeholder="Nickname" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>เพศ</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" id="inlineRadio1" value="ชาย" checked>
                                        <label class="form-check-label" for="inlineRadio1">ชาย</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="sex" id="inlineRadio2" value="หญิง">
                                        <label class="form-check-label" for="inlineRadio2">หญิง</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>เบอร์โทรศัพท์</label>
                                    <input type="text" name="telephone" placeholder="Telephone" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>อีเมล</label>
                                    <input type="email" name="email" placeholder="Email (optional)" class="form-control" >
                                </div>
                                <div class="col-md-6">
                                    <label>ที่อยู่</label>
                                    <input type="text" name="address" placeholder="Address (optional)" class="form-control" >
                                </div>

                                <div class="col-md-6">
                                    <label>รูปภาพ</label>
                                    <input class="form-control " accept="image/*" onchange="loadFile(event)" type='file' name="user_picture">
                                    <img class="m-4" id="output" width="150" height="150" />
                                </div>

                                <div class="">
                                    <button type="submit" name="add_user" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้ระบบ</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div><!--//col-->
    </div><!--//row-->

</div><!--//container-fluid-->