<?php
if (isset($_GET['id'])) {
    $leave_work_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM leave_work 
          INNER JOIN users ON leave_work.user_id = users.user_id
          WHERE leave_work.leave_work_id='$leave_work_id'";
    $query_run = mysqli_query($con, $query);

    // code for update the read notification status
    $isread = 1;
    $id = intval($_GET['id']);
    date_default_timezone_set('Asia/Kolkata');
    $admremarkdate = date('Y-m-d G:i:s ', strtotime("now"));

    $sql = "UPDATE leave_work SET isRead=? WHERE leave_work_id=?";
    $query = $con->prepare($sql);
    $query->bind_param('ii', $isread, $id);
    $query->execute();

    // code for action taken on leave
    if (isset($_POST['update'])) {
        $id = intval($_GET['id']);
        $description = $_POST['description'];
        $status = $_POST['status'];
        date_default_timezone_set('Asia/Bangkok');
        $remarkdate = date('Y-m-d G:i:s ', strtotime("now"));

        $sql = "UPDATE leave_work SET remark=?, status=?, remarkDate=? WHERE leave_work_id=?";
        $query = $con->prepare($sql);
        $query->bind_param('sssi', $description, $status, $remarkdate, $id);
        $query->execute();

        if ($status == "1") {
            $title = $_POST['title'];
            $form_type = 3;
            $description_user = $_POST['description_user'];
            $start_datetime_post = $_POST['date_from'] ?? '';
            $end_datetime_post = $_POST['date_to'] ?? '';
            $start_time = "12:00:00";
            $end_time = "12:00:00";
            $start_datetime = $start_datetime_post . ' ' . $start_time;
            $end_datetime = $end_datetime_post . ' ' . $end_time;

            $stmt = $con->prepare("INSERT INTO `schedule_list` (`title`,`form_type`,`description`,`start_datetime`,`end_datetime`) 
            VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $title, $form_type, $description_user, $start_datetime, $end_datetime);
            $stmt->execute();
        }

        if ($query->affected_rows > 0) {
            $_SESSION['message'] = "ตอบกลับสำเร็จ";
            $_SESSION['alert'] = "success";
            $alert = '<script type="text/javascript">';
            $alert .= 'window.location.href = "?page=manage-leave";';
            $alert .= '</script>';
            echo $alert;
            exit();
        } else {
            $_SESSION['message'] = "ตอบกลับไม่สำเร็จ";
            $_SESSION['alert'] = "danger";
        }
    }

    if (mysqli_num_rows($query_run) > 0) {
        $user = mysqli_fetch_array($query_run);
?>
        <div class="row">
            <div class="col-md-10">
                <div class="app-card shadow-sm">
                    <div class="app-card-header p-3">

                        <div class="row align-items-center">
                            <div class="col-auto">
                                <h4 class="app-card-title">รายละเอียดการลา</h4>
                            </div><!--//col-->

                            <div class="col-auto ms-auto pe-4">
                                <div class="card-header-action">
                                    <a href="?page=manage-leave" class="btn btn-danger float-end">กลับ</a>
                                </div><!--//card-header-actions-->
                            </div><!--//col-->

                        </div><!--//row-->
                    </div>
                    <div class="app-card-body p-4">

                        <div class="row g-3">
                            <div class="col-md-1">
                                <img class="border border-1 rounded-circle border-success" id="output" width="60" height="60" src="../assets/images/profile-pic/<?= $user['user_picture']; ?>" alt="user_picture" />
                            </div>
                            <div class="col-md-5">
                                <label>ชื่อ</label>
                                <p class="form-control">
                                    <?= $user['user_firstname'] ?> <?= $user['user_surname'] ?> (<?= $user['user_nickname'] ?>)
                                </p>
                            </div>
                            <div class="col-md-2">
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

                            <div class="col-md-4">
                                <label>จาก</label>
                                <p class="form-control">
                                    <?= $user['date_from'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>ถึง</label>
                                <p class="form-control">
                                    <?= $user['date_to'] ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>ไฟล์แนบ</label>
                                <br>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal-file">
                                    Attached File
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal-file" tabindex="-1" aria-labelledby="exampleModalLabel-file" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel-file">ไฟล์แนบ</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center">
                                                    <?php
                                                    $file_name = $user['file_name'];
                                                    $file_type = $user['file_type'];
                                                    $file_content = $user['file_content'];
                                                    if (strpos($file_type, 'image') !== false) {
                                                        // For images
                                                        echo '<img src="data:' . $file_type . ';base64,' . base64_encode($file_content) . '" style="width: 50%; height: auto;" />';
                                                    } elseif ($file_type === 'application/pdf') {
                                                        // For PDFs
                                                        echo '<embed src="data:application/pdf;base64,' . base64_encode($file_content) . '" type="application/pdf" style="width: 100%; height: 800px;" />';
                                                    } else {
                                                        // For other file types, you may handle differently
                                                        echo 'ไม่มีไฟล์แนบ';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label>การลา</label>
                                <p class="form-control">
                                    <?= $user['leave_work_type'] ?>
                                </p>
                            </div>
                            <div class="col-md-8">
                                <label>รายละเอียดการลา</label>
                                <p class="form-control">
                                    <?= $user['description'] ?>
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label>สถานะ</label>
                                <p class="form-control">
                                    <?php $stats =  $user['status'];
                                    if ($stats == 1) {
                                    ?>
                                        <span style="color: green">ได้รับการอนุมัติแล้ว</span>
                                    <?php }
                                    if ($stats == 2) { ?>
                                        <span style="color: red">ไม่ได้รับการอนุมัติ</span>
                                    <?php }
                                    if ($stats == 0) { ?>
                                        <span style="color: blue">รอการอนุมัติ</span>
                                    <?php } ?>
                                </p>
                            </div>
                            <div class="col-md-5">
                                <label>หมายเหตุ (ผู้จัดการ)</label>
                                <p class="form-control">
                                    <?php
                                    if ($user['remark'] == "") {
                                        echo "รอการอนุมัติ";
                                    } else {
                                        echo $user['remark'];
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label>วันที่ตอบกลับ</label>
                                <p class="form-control">
                                    <?php
                                    if ($user['remarkDate'] == "") {
                                        echo "NA";
                                    } else {
                                        echo $user['remarkDate'];
                                    }
                                    ?>
                                </p>
                            </div>

                            <?php
                            if ($stats == 0) {
                            ?>
                                <div class="mb-2 mx-2 mt-4">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">ตอบกลับ</button>
                                </div>
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">ตอบกลับ</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="" method="POST">
                                                    <div class="mb-3">
                                                        <select class="form-select" name="status" required="">
                                                            <option value="">---- เลือกการตอบกลับ ----</option>
                                                            <option value="1">อนุมัติ</option>
                                                            <option value="2">ไม่อนุมัติ</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="message-text" class="col-form-label">คำอธิบาย</label>
                                                        <textarea class="form-control" name="description" style="height: 100px" required></textarea>
                                                    </div> 
                                                    <input type="hidden" name="title" value="<?= $user['user_nickname']. ' : ' .$user['leave_work_type']; ?>"> 
                                                    <input type="hidden" name="date_from" value="<?= $user['date_from']; ?>"> 
                                                    <input type="hidden" name="date_to" value="<?= $user['date_to']; ?>"> 
                                                    <input type="hidden" name="description_user" value="<?= $user['description']; ?>">                                                  
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                                <button type="submit" class="btn btn-primary" name="update">ตอบกลับรายการ</button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
<?php
    } else {
        echo "<h4>No Such Id Found</h4>";
    }
}
?>