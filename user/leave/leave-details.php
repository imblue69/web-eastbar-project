<?php
if (isset($_GET['id'])) {
    $leave_work_id = mysqli_real_escape_string($con, $_GET['id']);
    $query = "SELECT * FROM leave_work 
          INNER JOIN users ON leave_work.user_id = users.user_id
          WHERE leave_work.leave_work_id='$leave_work_id'";
    $query_run = mysqli_query($con, $query);

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
                                    <a href="?page=leave-history" class="btn btn-danger float-end">กลับ</a>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    Attached File
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">ไฟล์แนบ</h1>
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