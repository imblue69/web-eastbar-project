<?php
$user = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_nickname = $_SESSION['user_nickname'];
$location_check = $_SESSION['location'];

date_default_timezone_set("Asia/Bangkok");
$entry_time_user = date("H:i:s");

if (isset($_POST['in'])) {
    if ($location_check == 1) {
        // Fetch today's schedule for the user
        $sql = "SELECT time(start_datetime) AS start_date, time(end_datetime) AS end_date, id
                FROM schedule_list 
                WHERE DATE(start_datetime) = CURDATE()
                AND form_type = '1' 
                AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            $schedule_id = $row["id"];

            // Check if the user has already recorded attendance
            $stmt_Check = $con->prepare("SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ?");
            $stmt_Check->bind_param("ii", $user, $schedule_id);
            $stmt_Check->execute();
            $attendance_result = $stmt_Check->get_result();
            $stmt_Check->close();

            if ($attendance_result->num_rows > 0) {
                $_SESSION['message'] = "คุณได้ทำการบันทึก เวลาเข้างาน แล้ว";
                $_SESSION['alert'] = "danger";
                echo "<script>location.replace('?page=worktime')</script>";
                exit();
            } else {
                // Calculate entry status
                $entry_time_user = date("H:i:s");
                $entry_status_check = strtotime($row["start_date"]) + 15 * 60;

                $entry_status = ($entry_time_user <= date("H:i:s", $entry_status_check)) ? "ตรงเวลา" : "สาย";

                // Retrieve and format user's job positions
                $job_positions = [
                    "ครัว" => "job_position_1",
                    "ย่าง" => "job_position_2",
                    "แคชเชียร์" => "job_position_3",
                    "เสิร์ฟ 1" => "job_position_4",
                    "เสิร์ฟ 2" => "job_position_5",
                    "เสิร์ฟ 3" => "job_position_6"
                ];

                $sql_job = "SELECT job_position_1, job_position_2, job_position_3, job_position_4, job_position_5, job_position_6
                            FROM schedule_list 
                            WHERE DATE(start_datetime) = CURDATE()
                            AND form_type = '1' 
                            AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";

                $stmt_job = $con->prepare($sql_job);
                $stmt_job->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                $stmt_job->execute();
                $result = $stmt_job->get_result();

                $used_job_positions = [];
                while ($row_job = $result->fetch_assoc()) {
                    foreach ($job_positions as $job_name => $job_field) {
                        if ($row_job[$job_field] === $user_nickname) {
                            $used_job_positions[] = $job_name;
                        }
                    }
                }
                $stmt_job->close();

                $used_job_positions_str = implode(', ', $used_job_positions);

                // Insert attendance record
                $stmt = $con->prepare("INSERT INTO attendance (user_id, entry_time, exit_time, entry_time_user, entry_status, schedule_id, job_position) 
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");
                if ($stmt) {
                    $stmt->bind_param("sssssss", $user, $row["start_date"], $row["end_date"], $entry_time_user, $entry_status, $schedule_id, $used_job_positions_str);
                    $save = $stmt->execute();
                    $stmt->close();

                    if ($save) {
                        $_SESSION['message'] = "บันทึก เวลาเข้างาน สำเร็จ";
                        $_SESSION['alert'] = "success";
                        echo "<script>location.replace('?page=worktime')</script>";
                        exit();
                    } else {
                        $_SESSION['message'] = "ไม่สำเร็จ: " . $stmt->error;
                        $_SESSION['alert'] = "danger";
                        echo "<script>location.replace('?page=worktime')</script>";
                        exit();
                    }
                } else {
                    $_SESSION['message'] = "Prepare failed: " . $con->error;
                    $_SESSION['alert'] = "danger";
                    echo "<script>location.replace('?page=worktime')</script>";
                    exit();
                }
            }
        } else {
            $_SESSION['message'] = "ไม่พบตารางงานวันนี้";
            $_SESSION['alert'] = "danger";
            echo "<script>location.replace('?page=worktime')</script>";
            exit();
        }
    } else {
        $_SESSION['message'] = "คุณไม่ได้อยู่ในพื้นที่";
        $_SESSION['alert'] = "danger";
        echo "<script>location.replace('?page=worktime')</script>";
        exit();
    }
}


?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        function updateTime() {
            $.ajax({
                url: 'https://east-bar.com/manager/worktime/get_time.php',
                method: 'GET',
                success: function(response) {
                    $('#clock').text(response);
                }
            });
        }
        setInterval(updateTime, 1000); // Update every 1 second
    });
</script>

<div class="row">
    <!-- ----------------------------- Body -------------------------- -->
    <div class="col-md-6">
        <div class="app-card shadow-sm">
            <div class="app-card-header p-3">
                <div class="row justify-content-between align-items-center gx-3">
                    <div class="col-auto">
                        <h4 class="app-card-title">บันทึกเวลาเข้าทำงาน</h4>
                    </div><!--//col-->
                    <div class="col-auto">
                        <div class="card-header-action">
                            <a href="?page=check_worktime">เช็คเวลาการเข้างาน</a>
                        </div><!--//card-header-actions-->
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-header-->

            <div class="app-card-body px-4 mt-4">
                <?php
                include('../sql/message.php');
                ?>
                <form class="row" action="" method="POST" enctype="multipart/form-data">
                    <?php
                    if ($_SESSION['location'] == "1") {
                    ?>
                        <div class="text-center fs-4 badge text-success">
                            <i class="fa-solid fa-check" style="color: #5cb377;"></i> <?= $_SESSION['text']; ?>
                        </div>
                    <?php
                    } elseif ($_SESSION['location'] == "0") {
                    ?>
                        <div class="text-center fs-4 badge text-danger">
                            <i class="fa-solid fa-xmark" style="color: #ff0000;"></i> <?= $_SESSION['text']; ?>
                        </div>
                    <?php
                    }
                    ?>
                    <h1 class="text-center" id="clock">00:00:00</h1>
                    <h4 class="text-center" id="date"></h4>
                    <label class="col-12 fs-4 fw-bold">เวลา</label>
                    <div class="d-flex justify-content-between col-12">
                        <label class="ms-5 fs-5">เวลาเข้างาน</label>
                        <label class="me-5 fs-5 fw-bold">16:30</label>
                    </div>

                    <label class="col-12 fs-4 fw-bold">สถานะ</label>
                    <div class="d-flex justify-content-between col-12">
                        <label class="ms-5 fs-5">สถานะเวลาเข้า</label>
                        <label class="me-5 fs-5 fw-bold">
                            <?php
                            // Fetch the schedule ID for today's date with the specified form type and user nickname
                            $sql = "
                                        SELECT id
                                        FROM schedule_list 
                                        WHERE DATE(start_datetime) = CURDATE()
                                        AND form_type = '1' 
                                        AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)
                                    ";
                            $stmt = $con->prepare($sql);
                            $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $schedule = $result->fetch_assoc();
                            $stmt->close();

                            if ($schedule) {
                                $schedule_id = $schedule["id"];

                                // Fetch attendance data for the user and the fetched schedule ID
                                $queryAttendance = "SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ?";
                                $stmt = $con->prepare($queryAttendance);
                                if ($stmt) {
                                    $stmt->bind_param("ss", $user, $schedule_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    if ($result && $result->num_rows > 0) {
                                        $attendanceData = $result->fetch_array(MYSQLI_ASSOC);
                                        if (isset($attendanceData['entry_status'])) {
                                            echo htmlspecialchars($attendanceData['entry_status'], ENT_QUOTES, 'UTF-8');
                                        } else {
                                            echo "รอการทำรายการ";
                                        }
                                    } else {
                                        echo "รอการทำรายการ";
                                    }
                                    $stmt->close();
                                } else {
                                    echo "Error in query execution.";
                                }
                            } else {
                                echo "รอการทำรายการ";
                            }
                            ?>

                        </label>
                    </div>

                    <div class="d-flex justify-content-center col-12 mb-2 mx-2 my-4 mb-4">
                        <button type="submit" name="in" class="btn btn-primary btn-lg mx-2"><i class="fa-solid fa-right-to-bracket"></i> บันทึกเข้า</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div><!--//container-fluid-->
<?php
?>
<script>
    // รับวันที่ปัจจุบัน
    const today = new Date();

    // อาเรย์ของวัน
    const days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];

    // อาเรย์ของเดือน
    const months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

    // รับวัน, วันที่, เดือน, และปี
    const day = days[today.getDay()];
    const date = today.getDate();
    const month = months[today.getMonth()];
    const year = today.getFullYear();

    // แสดงวันที่
    document.getElementById('date').innerHTML = `วัน${day} ที่ ${date} ${month} พ.ศ. ${year + 543}`;
</script>