<link rel="stylesheet" href="<?php echo base_url ?>assets/fullcalendar/lib/main.min.css">
<script src="<?php echo base_url ?>assets/js/jquery-3.6.0.min.js"></script>
<!-- <script src="<?php echo base_url ?>assets/js/bootstrap.min.js"></script> -->
<script src="<?php echo base_url ?>assets/fullcalendar/lib/main.min.js"></script>
<script src='<?php echo base_url ?>assets/fullcalendar/lib/locales/th.js'></script>
<style>
    :root {
        --bs-success-rgb: 71, 222, 152 !important;
    }

    html,
    body {
        height: 100%;
        width: 100%;
    }

    .btn-info.text-light:hover,
    .btn-info.text-light:focus {
        background: #000;
    }

    table,
    body,
    td,
    tfoot,
    th,
    thead,
    tr {
        border-color: #ededed !important;
        border-style: solid;
        border-width: 1px !important;
    }

    .highlight-weekend {
        background-color: #f5f5f5;
        /* Change background color */
    }
</style>
<?php
// ------------------------------- add schedule --------------------------------------
if (isset($_POST['add'])) {
    $id = $_POST['id'] ?? '';
    $title = mysqli_real_escape_string($con, $_POST['title'] ?? '');
    $form_type = $_POST['form_type'] ?? '';
    $description = mysqli_real_escape_string($con, $_POST['description'] ?? '');

    if ($form_type == 2) {
        $start_datetime_post = $_POST['start_datetime'] ?? '';
        $end_datetime_post = $_POST['end_datetime'] ?? '';
        if ($start_datetime_post > $end_datetime_post) {
            $_SESSION['message'] = "วันเริ่มต้น ต้องไม่มากกว่า วันถึง";
            $_SESSION['alert'] = "danger";
        } else {
            $start_time = "12:00:00";
            $end_time = "12:00:00";
            // Combine date and time inputs to form datetime strings
            $start_datetime = $start_datetime_post . ' ' . $start_time;
            $end_datetime = $end_datetime_post . ' ' . $end_time;

            if (empty($id)) {
                $stmt = $con->prepare("INSERT INTO `schedule_list` (`title`,`form_type`,`description`,`start_datetime`,`end_datetime`) 
                VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $form_type, $description, $start_datetime, $end_datetime);
            } else {
                $stmt = $con->prepare("UPDATE `schedule_list` SET `title`=?,`form_type`=?, `description`=?, `start_datetime`=?, `end_datetime`=? WHERE `id`=?");
                $stmt->bind_param("sssssi", $title, $form_type, $description, $start_datetime, $end_datetime, $id);
            }
        }
    } elseif ($form_type == 1) {
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        if ($start_time > $end_time) {
            $_SESSION['message'] = "เวลาเข้างาน ต้องไม่มากกว่า เวลาออกงาน";
            $_SESSION['alert'] = "danger";
        } else {
            $date = $_POST['date'] ?? '';
            // Combine date and time inputs to form datetime strings
            $start_datetime = $date . ' ' . $start_time;
            $end_datetime = $date . ' ' . $end_time;

            $job1 = mysqli_real_escape_string($con, $_POST['job_position_1'] ?? '');
            $job2 = mysqli_real_escape_string($con, $_POST['job_position_2'] ?? '');
            $job3 = mysqli_real_escape_string($con, $_POST['job_position_3'] ?? '');
            $job4 = mysqli_real_escape_string($con, $_POST['job_position_4'] ?? '');
            $job5 = mysqli_real_escape_string($con, $_POST['job_position_5'] ?? '');
            $job6 = mysqli_real_escape_string($con, $_POST['job_position_6'] ?? '');

            if (empty($id)) {
                $stmt = $con->prepare("INSERT INTO `schedule_list` (`title`,`form_type`,`description`,`start_datetime`,`end_datetime`,`job_position_1`,`job_position_2`,`job_position_3`,`job_position_4`,`job_position_5`,`job_position_6`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $title, $form_type, $description, $start_datetime, $end_datetime, $job1, $job2, $job3, $job4, $job5, $job6);
            } else {
                $stmt = $con->prepare("UPDATE `schedule_list` SET `title`=?,`form_type`=?, `description`=?, `start_datetime`=?, `end_datetime`=?, `job_position_1`=?, `job_position_2`=?, `job_position_3`=?, `job_position_4`=?, `job_position_5`=?, `job_position_6`=? WHERE `id`=?");
                $stmt->bind_param("sssssssssssi", $title, $form_type, $description, $start_datetime, $end_datetime, $job1, $job2, $job3, $job4, $job5, $job6, $id);
            }
        }
    }
    // Check if $stmt is set and not null before execution
    if (isset($stmt) && $stmt) {
        $save = $stmt->execute();
        if ($save) {
            $_SESSION['message'] = "เพิ่มกำหนดการสำเร็จ";
            $_SESSION['alert'] = "success";
            $stmt->close();
            //header("Location: ?page=manage-work-schedule");
            echo "<script>location.replace('?page=manage-work-schedule')</script>";
            exit();
        } else {
            $_SESSION['message'] = "เพิ่มกำหนดการไม่สำเร็จ";
            $_SESSION['alert'] = "danger";
            $stmt->close();
        }
    }
}
?>
<div class="container py-4" id="page-container">
    <?php include('../sql/message.php'); ?>
    <div class="row gy-4">
        <div class="col-md-8">
            <div class="app-card app-card-stat shadow-sm">
                <div class="app-card-body p-3 p-lg-4">
                    <div id="calendar"></div>
                </div><!--//app-card-body-->
            </div><!--//app-card-->
        </div><!--//col-->

        <div class="col-md-4">
            <div class="app-card shadow-sm">
                <div class="app-card-header p-3">
                    <h5 class="app-card-title">เพิ่มกำหนดการ</h5>
                </div>
                <form action="" method="POST" id="schedule-form">
                    <div class="app-card-body p-2">
                        <div class="container-fluid">
                            <input type="hidden" name="id" value="">
                            <div class="form-group mb-2">
                                <label for="form_type" class="control-label">หัวข้อกำหนดการ</label>
                                <select class="form-select" name="form_type" autocomplete="off">
                                    <option value="0">--เลือกหัวข้อกำหนดการ--</option>
                                    <option value="1">วันทำงาน</option>
                                    <option value="2">อื่นๆ</option>
                                </select>
                            </div>
                            <div id="form">
                                <div class="form-group mb-2">
                                    <label for="title" class="control-label">หัวข้อ</label>
                                    <input type="text" class="form-control form-control-sm " name="title" id="title" value="" required>
                                </div>
                                <div class="form-group mb-2">
                                    <label for="description" class="control-label">คำอธิบาย</label>
                                    <textarea rows="3" class="form-control form-control-sm " name="description" id="description" style="height: 75px" required>-</textarea>
                                </div>
                                <div id="job-etc">
                                    <div class="form-group mb-2">
                                        <label for="start_datetime" class="control-label">เริ่ม</label>
                                        <input type="date" class="form-control form-control-sm " name="start_datetime" id="start_datetime" lang="th">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="end_datetime" class="control-label">ถึง</label>
                                        <input type="date" class="form-control form-control-sm " name="end_datetime" id="end_datetime" lang="th">
                                    </div>
                                </div>
                                <div id="job-po">
                                    <div class="form-group mb-2">
                                        <label for="date" class="control-label">วัน</label>
                                        <input type="date" class="form-control form-control-sm" name="date" id="date" lang="th">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="start_time" class="control-label">เวลาเข้างาน</label>
                                        <input type="time" class="form-control form-control-sm" name="start_time" id="start_time" lang="th">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="end_time" class="control-label">เวลาออกงาน</label>
                                        <input type="time" class="form-control form-control-sm" name="end_time" id="end_time" lang="th">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_1" class="control-label">ครัว</label>
                                        <select class="form-select" name="job_position_1" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ งานครัว--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_2" class="control-label">ย่าง</label>
                                        <select class="form-select" name="job_position_2" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ งานย่าง--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_3" class="control-label">แคชเชียร์</label>
                                        <select class="form-select" name="job_position_3" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ แคชเชียร์--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_4" class="control-label">เสิร์ฟ 1</label>
                                        <select class="form-select" name="job_position_4" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ เสิร์ฟ 1--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_5" class="control-label">เสิร์ฟ 2</label>
                                        <select class="form-select" name="job_position_5" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ เสิร์ฟ 2--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="job_position_6" class="control-label">เสิร์ฟ 3</label>
                                        <select class="form-select" name="job_position_6" autocomplete="off">
                                            <option value="-">--บุคคลที่รับผิดชอบ เสิร์ฟ 3--</option>
                                            <?php
                                            $sql = "SELECT * FROM users";
                                            $result = mysqli_query($con, $sql);

                                            if ($result) {
                                                while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                    <option value="<?php echo htmlentities($row['user_nickname']); ?>"><?php echo htmlentities($row['user_nickname']); ?></option>
                                            <?php
                                                }
                                                mysqli_free_result($result);
                                            }
                                            ?>
                                            <option value="-">--ไม่มี--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="app-card-footer pb-4" id="form2">
                        <div class="text-center">
                            <button class="btn btn-primary btn-sm" type="submit" name="add" form="schedule-form"><i class="fa fa-save"></i> บันทึก</button>
                            <a class="btn btn-default border btn-sm " href="?page=manage-work-schedule" type="button" form="schedule-form"><i class="fa-solid fa-xmark"></i></i> ยกเลิก</a>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<!-- Event Details Modal -->
<div class="modal fade" tabindex="-1" data-bs-backdrop="static" id="event-details-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <div class="modal-header ">
                <h5 class="modal-title">รายละเอียดกำหนดการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <dl>
                        <dt class="text-muted">หัวข้อ</dt>
                        <dd id="title" class="fw-bold fs-4 ms-4"></dd>
                        <dt class="text-muted">คำอธิบาย</dt>
                        <dd id="description" class="ms-4"></dd>
                        <dt class="text-muted">เริ่ม</dt>
                        <dd id="start" class="ms-4"></dd>
                        <dt class="text-muted">ถึง</dt>
                        <dd id="end" class="ms-4"></dd>
                        <div id="work-day">
                            <dt class="text-muted">ครัว</dt>
                            <dd id="job_position_1" class="ms-4"></dd>
                            <dt class="text-muted">ย่าง</dt>
                            <dd id="job_position_2" class="ms-4"></dd>
                            <dt class="text-muted">แคชเชียร์</dt>
                            <dd id="job_position_3" class="ms-4"></dd>
                            <dt class="text-muted">เสิร์ฟ 1</dt>
                            <dd id="job_position_4" class="ms-4"></dd>
                            <dt class="text-muted">เสิร์ฟ 2</dt>
                            <dd id="job_position_5" class="ms-4"></dd>
                            <dt class="text-muted">เสิร์ฟ 3</dt>
                            <dd id="job_position_6" class="ms-4"></dd>
                        </div>
                    </dl>
                </div>
            </div>
            <div class="modal-footer" id="leave-bt">
                <div class="text-end">
                    <button type="button" class="btn btn-primary btn-sm" id="edit" data-id=""><i class="fa-solid fa-gear"></i> แก้ไข</button>
                    <button type="button" class="btn btn-danger btn-sm" id="delete" data-id=""><i class="fa-solid fa-trash-can"></i> ลบ</button>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"><i class="fa-solid fa-minus"></i> ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Event Details Modal -->

<?php
$schedules = $con->query("SELECT * FROM `schedule_list`");
$sched_res = [];
foreach ($schedules->fetch_all(MYSQLI_ASSOC) as $row) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}
?>
<?php
if (isset($con)) $con->close();
?>
<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
    document.addEventListener("DOMContentLoaded", function() {
        const formTypeSelect = document.querySelector('select[name="form_type"]');
        const jobForm = document.getElementById('job-po');
        const etcForm = document.getElementById('job-etc');
        const Form = document.getElementById('form');
        const Form2 = document.getElementById('form2');
        const titleInput = document.querySelector('input[name="title"]');

        formTypeSelect.addEventListener('change', function() {
            const selectedValue = this.value;

            if (selectedValue === '0') {
                Form.style.display = 'none';
                Form2.style.display = 'none';
                titleInput.value = "";
            } else if (selectedValue === '1') {
                titleInput.value = "วันทำงาน";
                Form.style.display = 'block';
                Form2.style.display = 'block';
                jobForm.style.display = 'block'; // แสดง form ที่มี id "job-po"
                etcForm.style.display = 'none';
            } else {
                Form.style.display = 'block';
                Form2.style.display = 'block';
                jobForm.style.display = 'none'; // ซ่อน form ที่มี id "job-po"
                etcForm.style.display = 'block';
                titleInput.value = "";
            }
        });

        // เรียกใช้ฟังก์ชันเพื่อตรวจสอบค่าเริ่มต้น
        formTypeSelect.dispatchEvent(new Event('change'));
    });
</script>
<script src="<?php echo base_url ?>assets/js/script.js"></script>