<?php
if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM users WHERE user_id='$user_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_array($query_run);
?>
        <div class="row">
            <div class="col-md-12">
                <div class="app-card shadow-sm">
                    <div class="app-card-header p-3">
                        <h4>รายละเอียดข้อมูลผู้ใช้ระบบ : <?= $user['username'] ?>
                            <a href="?page=manage-user" class="btn btn-danger float-end">กลับ <i class="fa-solid fa-xmark"></i></a>
                        </h4>
                    </div>
                    <div class="app-card-body p-4">

                        <form class="row g-3">
                            <div class="col-md-4">
                                <label>ชื่อผู้ใช้</label>
                                <p class="form-control">
                                    <?= $user['username'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>ตำแหน่งงาน</label>
                                <p class="form-control">
                                    <?= $user['user_type'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>อีเมล</label>
                                <p class="form-control">
                                    <?= $user['user_email'] ?>
                                </p>
                            </div>
                            <!-- --------------------------------- -->
                            <div class="col-md-4">
                                <label>ชื่อจริง</label>
                                <p class="form-control">
                                    <?= $user['user_firstname'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>นามสกุล</label>
                                <p class="form-control">
                                    <?= $user['user_surname'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>ชื่อเล่น</label>
                                <p class="form-control">
                                    <?= $user['user_nickname'] ?>
                                </p>
                            </div>
                            <!-- --------------------------------- -->
                            <div class="col-md-4">
                                <label>เพศ</label>
                                <p class="form-control">
                                    <?= $user['user_gender'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>เบอร์โทรศัพท์</label>
                                <p class="form-control">
                                    <?= $user['user_telephone'] ?>
                                </p>
                            </div>
                            <div class="col-md-4"></div>
                            <!-- --------------------------------- -->
                            <div class="col-md-6">
                                <label>ที่อยู่</label>
                                <p class="form-control">
                                    <?= $user['user_address'] ?>
                                </p>
                            </div>

                            <div class="col-md-6">

                                <label>รูปภาพ</label>
                                <br>
                                <img class="" id="output" width="150" height="150" src="../assets/images/profile-pic/<?= $user['user_picture']; ?>" alt="user_picture" />
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