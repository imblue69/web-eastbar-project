<div class="row">
    <div class="col-12">
        <div class="app-card-body">
            <!-- ----------------------------- Body -------------------------- -->
            <div class="row">
                <div class="col-md-6">
                    <div class="app-card shadow-sm">
                        <div class="app-card-header p-3">
                            <h4>แก้ไขข้อมูลประเภทการลางาน
                                <a href="?page=manage-leave-type" class="btn btn-danger float-end">กลับ</a>
                            </h4>
                        </div>
                        <div class="app-card-body p-4">
                            <?php
                            if (isset($_GET['id'])) {
                                $id = mysqli_real_escape_string($con, $_GET['id']);
                                $query = "SELECT * FROM leave_work_type WHERE id='$id'";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    $data = mysqli_fetch_array($query_run);
                                    $leaveTypename = $data['leaveType'];

                                    if (isset($_POST['update_leave_type'])) {

                                        $leaveType = mysqli_real_escape_string($con, $_POST['leaveType']);
                                        $description = mysqli_real_escape_string($con, $_POST['description']);

                                        $query = "UPDATE leave_work_type SET leaveType='$leaveType',description='$description' WHERE id='$id' ";
                                        $query_run = mysqli_query($con, $query);

                                        if ($query_run) {
                                            $_SESSION['message'] = "แก้ไขข้อมูลประเภทการลางาน $leaveTypename ใหม่สำเร็จ";
                                            $_SESSION['alert'] = "success";
                                            $alert = '<script type="text/javascript">';
                                            $alert .= 'window.location.href = "?page=manage-leave-type";';
                                            $alert .= '</script>';
                                            echo $alert;
                                            exit();
                                        } else {
                                            $_SESSION['message'] = "แก้ไขข้อมูลประเภทการลางาน $leaveTypename ใหม่ไม่สำเร็จ";
                                            $_SESSION['alert'] = "danger";
                                        }
                                    }
                            ?>
                                    <?php include('../sql/message.php'); ?>
                                    <form class="row g-3" action="" method="POST" enctype="multipart/form-data">

                                        <div class="col-md-12">
                                            <label>ประเภทการลา</label>
                                            <input type="text" name="leaveType" class="form-control" placeholder="LeaveType" value="<?= $data['leaveType'] ?>" required>
                                        </div>

                                        <div class="col-md-12">
                                            <label>คำอธิบาย</label>
                                            <input type="text" name="description" class="form-control" placeholder="Description"value="<?= $data['description'] ?>" required>
                                        </div>

                                        <div class="mx-2 mt-4">
                                            <button type="submit" name="update_leave_type" class="btn btn-primary"><i class="fa-solid fa-gear"></i> แก้ไขข้อมูล</button>
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

        </div><!--//col-->
    </div><!--//row-->

</div><!--//container-fluid-->