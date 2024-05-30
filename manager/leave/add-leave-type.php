<div class="row">
    <div class="col-12">
        <div class="app-card-body">
            <!-- ----------------------------- Body -------------------------- -->
            <div class="row">
                <div class="col-md-5">
                    <div class="app-card shadow-sm">
                        <div class="app-card-header p-3">
                            <h4>เพิ่มประเภทการลางาน
                                <a href="?page=manage-leave-type" class="btn btn-danger float-end">กลับ</a>
                            </h4>
                        </div>
                        <div class="app-card-body p-4">
                            <?php
                            if (isset($_POST['add_leave_type'])) {

                                $leaveType = mysqli_real_escape_string($con, $_POST['leaveType']);
                                $description = mysqli_real_escape_string($con, $_POST['description']);

                                $query = "INSERT INTO leave_work_type (leaveType, description) VALUES (?, ?)";
                                $stmt = mysqli_prepare($con, $query);

                                // Bind parameters
                                mysqli_stmt_bind_param($stmt, "ss", $leaveType, $description);
                                // Execute query
                                $query_run = mysqli_stmt_execute($stmt);

                                if ($query_run) {
                                    $_SESSION['message'] = "เพิ่มประเภทการลางานสำเร็จ";
                                    $_SESSION['alert'] = "success";
                                    $alert = '<script type="text/javascript">';
                                    $alert .= 'window.location.href = "?page=manage-leave-type";';
                                    $alert .= '</script>';
                                    echo $alert;
                                    exit();
                                } else {
                                    $_SESSION['message'] = "เพิ่มประเภทการลางานไม่สำเร็จ";
                                    $_SESSION['alert'] = "danger";
                                }
                            }
                            ?>
                            <?php include('../sql/message.php'); ?>
                            <form class="row g-3" action="" method="POST" enctype="multipart/form-data">

                                <div class="col-md-12">
                                    <label>ประเภทการลา</label>
                                    <input type="text" name="leaveType" class="form-control" placeholder="LeaveType" required>
                                </div>

                                <div class="col-md-12">
                                    <label>คำอธิบาย</label>
                                    <input type="text" name="description" class="form-control" placeholder="Description" required>
                                </div>

                                <div class="mx-2 mt-4">
                                    <button type="submit" name="add_leave_type" class="btn btn-primary"><i class="fa-solid fa-plus"></i> เพิ่ม</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

        </div><!--//col-->
    </div><!--//row-->

</div><!--//container-fluid-->