<?php
if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM users WHERE user_id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_array($query_run);
        $name = $user['username'];

        if (isset($_POST['update_user'])) {
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
            $query = "UPDATE users SET user_firstname='$firstname', user_surname='$lastname', user_nickname='$nickname', user_address='$address' ,user_telephone='$telephone' ,user_email='$email' ,user_gender='$sex',user_picture='$filename' WHERE user_id='$user_id' ";
            $query_run = mysqli_query($con, $query);

            if ($query_run) {
                $_SESSION['message'] = "แก้ไขข้อมูล $name สำเร็จ";
                $_SESSION['alert'] = "success";
                $alert = '<script type="text/javascript">';
                $alert .= 'window.location.href = "?page=manage-user";';
                $alert .= '</script>';
                echo $alert;
                exit();
            } else {
                $_SESSION['message'] = "แก้ไขข้อมูลไม่สำเร็จ";
                $_SESSION['alert'] = "danger";
            }
        }
?>
        <div class="row">
            <div class="col-md-12">
                <div class="app-card shadow-sm">
                    <div class="app-card-header p-3">
                        <h4>แก้ไขข้อมูลผู้ใช้ระบบ : <?= $user['username'] ?>
                            <a href="?page=manage-user" class="btn btn-danger float-end">กลับ <i class="fa-solid fa-xmark"></i></a>
                        </h4>
                    </div>
                    <div class="app-card-body p-4">
                        <?php include('../sql/message.php'); ?>
                        <form class="row g-3" action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">

                            <div class="col-md-4">
                                <label>ชื่อจริง</label>
                                <input type="text" name="firstname" value="<?= $user['user_firstname'] ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>นามสกุล</label>
                                <input type="text" name="lastname" value="<?= $user['user_surname'] ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label>ชื่อเล่น</label>
                                <input type="text" name="nickname" value="<?= $user['user_nickname'] ?>" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>เพศ</label>
                                <input type="text" name="sex" value="<?= $user['user_gender'] ?>" class="form-control" required>
                            </div>

                            <div class="col-md-4"">
                                <label>เบอร์โทรศัพท์</label>
                                <input type=" text" name="telephone" value="<?= $user['user_telephone'] ?>" class="form-control" required>
                            </div>
                            <div class="col-md-4"">
                                <label>อีเมล</label>
                                <input type=" email" name="email" value="<?= $user['user_email'] ?>" class="form-control" placeholder="Email (optional)">
                            </div>
                            <div class="col-md-6">
                                <label>ที่อยู่</label>
                                <input type="text" name="address" value="<?= $user['user_address'] ?>" class="form-control" placeholder="Address (optional)">
                            </div>
                            <div class="col-md-6">
                                <label>รูปภาพ</label>
                                <input class="form-control " accept="image/*" onchange="loadFile(event)" type='file' name="user_picture">
                                <input type="hidden" name="old_user_picture" value="<?= $user['user_picture'] ?>">
                                <img class="m-4" id="output" width="150" height="150" src="../assets/images/profile-pic/<?= $user['user_picture']; ?>" alt="user_picture" />
                            </div>
                            <div class="">
                                <button type="submit" name="update_user" class="btn btn-primary"><i class="fa-solid fa-user-gear"></i> แก้ไขข้อมูล</button>
                            </div>
                        </form>
                <?php
            } else {
                echo "<h4>No Such Id Found</h4>";
            }
        }
                ?>
                    </div>
                </div>
            </div>
        </div>