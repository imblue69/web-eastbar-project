<?php
$user = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_nickname = $_SESSION['user_nickname'];
$query = "SELECT * FROM users WHERE user_id='$user' ";
$query_run = mysqli_query($con, $query);
$result = mysqli_fetch_array($query_run);

date_default_timezone_set("Asia/Bangkok");
$entry_time_user = date("H:i:s");
?>

<h1>หน้าหลัก</h1>

<div class="app-card alert alert-dismissible shadow-sm mb-1 border-left-decoration" role="alert">
    <div class="inner">
        <div class="app-card-body p-3 p-lg-4">
            <h3 class="mb-3">ยินดีต้อนรับ ,<?= $result['user_firstname'] ?> <?= $result['user_surname'] ?> !</h3>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div><!--//app-card-body-->
    </div><!--//inner-->
</div><!--//app-card-->

<div class="row">
    <div class="col-12">
        <div class="app-card-body p-0 p-lg-0">
            <!-- ----------------------------- Body -------------------------- -->

            <div class="row g-4 mb-4 mt-1">
                <div class="col-12 col-lg-6">
                    <div class="app-card app-card-progress-list shadow-sm">
                        <div class="app-card-header p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h4 class="app-card-title">ข้อมูลผู้ใช้</h4>
                                </div><!--//col-->
                                <div class="col-auto">
                                    <div class="card-header-action">
                                        <a href="?page=profile">แก้ไขข้อมูล</a>
                                    </div><!--//card-header-actions-->
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-4">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th scope="row">ชื่อผู้ใช้</th>
                                        <td><?= $result['username']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ชื่อ นามสกุล</th>
                                        <td><?= $result['user_firstname'] . " " . $result['user_surname'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ชื่อเล่น</th>
                                        <td><?= $result['user_nickname'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">เบอร์โทรติดต่อ</th>
                                        <td><?= $result['user_telephone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">อีเมล์</th>
                                        <td><?= $result['user_email']; ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">เพศ</th>
                                        <td><?= $result['user_gender'] ?></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                </div><!--//col-->

            </div><!--//col-->

        </div><!--//row-->

    </div><!--//col-->
</div><!--//row-->

</div><!--//container-fluid-->