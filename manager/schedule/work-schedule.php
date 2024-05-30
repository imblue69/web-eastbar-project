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
    $fields = [
        'title', 'form_type', 'description', 'start_datetime', 'end_datetime',
        'job_position_1', 'job_position_2', 'job_position_3',
        'job_position_4', 'job_position_5', 'job_position_6'
    ];

    $values = array_map(function ($field) use ($con) {
        return mysqli_real_escape_string($con, $_POST[$field] ?? '');
    }, $fields);

    if (empty($id)) {
        $stmt = $con->prepare("INSERT INTO `schedule_list` (`title`,`form_type`,`description`,`start_datetime`,`end_datetime`,`job_position_1`,`job_position_2`,`job_position_3`,`job_position_4`,`job_position_5`,`job_position_6`) 
        VALUES (?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", ...$values);
    } else {
        array_push($values, $id);
        $stmt = $con->prepare("UPDATE `schedule_list` SET `title`=?,`form_type`=?, `description`=?, `start_datetime`=?, `end_datetime`=?, `job_position_1`=?, `job_position_2`=?, `job_position_3`=?, `job_position_4`=?, `job_position_5`=?, `job_position_6`=? WHERE `id`=?");
        $stmt->bind_param("sssssssssssi", ...$values);
    }

    $save = $stmt->execute();

    if ($save) {
        $_SESSION['message'] = "เพิ่มกำหนดการสำเร็จ";
        $_SESSION['alert'] = "success";
        //header("Location: ?page=manage-work-schedule");
        echo "<script>location.replace('?page=manage-work-schedule')</script>";
        exit();
    } else {
        $_SESSION['message'] = "เพิ่มกำหนดการไม่สำเร็จ";
        $_SESSION['alert'] = "danger";
    }
    $stmt->close();
    $con->close();
}



?>
<div class="container py-4" id="page-container">
    <?php include('../sql/message.php'); ?>
    <div class="row gy-4">
        <div class="col-12">
            <div class="app-card app-card-stat shadow-sm">
                <div class="app-card-body p-3 p-lg-4">
                    <div id="calendar"></div>
                </div><!--//app-card-body-->
            </div><!--//app-card-->
        </div><!--//col-->
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
            <div id="leave-bt">
            </div>
            <div class="modal-footer">
                <div class="text-end">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Event Details Modal -->

<?php
$user_nickname = $_SESSION['user_nickname'];

// Prepare the SQL statement
$stmt = $con->prepare("SELECT * FROM `schedule_list` 
WHERE 
`form_type` != '1'
OR `job_position_1` = ? 
OR `job_position_2` = ?
OR `job_position_3` = ?
OR `job_position_4` = ?
OR `job_position_5` = ?
OR `job_position_6` = ?");

// Bind the parameters
$stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

$sched_res = [];

// Fetch the schedules
foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['start_datetime']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['end_datetime']));
    $sched_res[$row['id']] = $row;
}

// Close the statement
$stmt->close();

if (isset($con)) {
    $con->close();
}
?>

<script>
    var scheds = $.parseJSON('<?= json_encode($sched_res) ?>')
</script>
<script src="<?php echo base_url ?>assets/js/script.js"></script>