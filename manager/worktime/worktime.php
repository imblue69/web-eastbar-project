<?php
$user = $_SESSION['user_id'];
$username = $_SESSION['username'];
$user_nickname = $_SESSION['user_nickname'];
$location_check = $_SESSION['location'];

date_default_timezone_set("Asia/Bangkok");
$entry_time_user = date("H:i:s");

if (isset($_POST['in'])) {
    if ($location_check == 1) {
        // Fetching start and end time from schedule_list
        $sql = "SELECT time(start_datetime) AS start_date, time(end_datetime) AS end_date , id
        FROM schedule_list 
        WHERE DATE(start_datetime) = CURDATE()
        AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $schedule_id = $row["id"];
        // Checking attendance for each schedule ID
        $stmt_Check = $con->prepare("SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ?");
        $stmt_Check->bind_param("ii", $user, $schedule_id);
        $stmt_Check->execute();
        $attendance_result = $stmt_Check->get_result();

        if ($attendance_result->num_rows > 0) {
            $_SESSION['message'] = "คุณได้ทำการบันทึก เวลาเข้างาน แล้ว";
            $_SESSION['alert'] = "danger";
            echo "<script>location.replace('?page=worktime')</script>";
            exit();
        } else {
            $entry_time_user = date("H:i:s"); // เวลาปัจจุบัน
            // เพิ่มเวลาให้กับ $row["start_date"] ไม่เกิน 15 นาที
            $entry_status_check = strtotime($row["start_date"]);
            $entry_status_check += 15 * 60; // เพิ่มเวลา 15 นาที (15 นาที * 60 วินาที)

            if ($entry_time_user <= date("H:i:s", $entry_status_check)) {
                $entry_status = "ตรงเวลา";
            } else {
                $entry_status = "สาย";
            }

            $job_positions = [
                "ครัว" => "job_position_1",
                "ย่าง" => "job_position_2",
                "แคชเชียร์" => "job_position_3",
                "เสิร์ฟ 1" => "job_position_4",
                "เสิร์ฟ 2" => "job_position_5",
                "เสิร์ฟ 3" => "job_position_6"
            ];

            $sql_job = "SELECT time(start_datetime) AS start_date, time(end_datetime) AS end_date, id, job_position_1, job_position_2, job_position_3, job_position_4, job_position_5, job_position_6
                                        FROM schedule_list WHERE DATE(start_datetime) = CURDATE()
                                        AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
            $stmt_job = $con->prepare($sql_job);
            $stmt_job->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
            $stmt_job->execute();
            $result = $stmt_job->get_result();
            $used_job_positions = [];
            while ($row_job = $result->fetch_assoc()) {
                foreach ($row_job as $key => $value) {
                    if (strpos($key, 'job_position_') === 0 && $value === $user_nickname) {
                        // หาชื่อคำศัพท์จาก associative array
                        $job_name = array_search($key, $job_positions);
                        if ($job_name) {
                            $used_job_positions[] = $job_name;
                        }
                    }
                }
            }
            $stmt_job->close();

            // แปลง $used_job_positions เป็นสตริง
            $used_job_positions_str = implode(', ', $used_job_positions);



            $stmt = $con->prepare("INSERT INTO attendance (user_id, entry_time, exit_time, entry_time_user, entry_status, schedule_id, job_position) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

            if ($stmt) {
                $stmt->bind_param("sssssss", $user, $row["start_date"], $row["end_date"], $entry_time_user, $entry_status, $row["id"], $used_job_positions_str);

                $save = $stmt->execute();

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

                $stmt->close();
            } else {
                $_SESSION['message'] = "Prepare failed: " . $con->error;
                $_SESSION['alert'] = "danger";
                echo "<script>location.replace('?page=worktime')</script>";
                exit();
            }
        }
    } else {
        $_SESSION['message'] = "คุณไม่ได้อยู่ในพื้นที่";
        $_SESSION['alert'] = "danger";
        echo "<script>location.replace('?page=worktime')</script>";
        exit();
    }
}
if (isset($_POST['out'])) {
    if ($location_check == 1) {
        // Fetching start and end time from schedule_list
        $sql = "SELECT time(start_datetime) AS start_date, time(end_datetime) AS end_date , id
        FROM schedule_list 
        WHERE DATE(start_datetime) = CURDATE()
        AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $schedule_id = $row["id"];
        // Checking attendance for each schedule ID
        $stmt_Check = $con->prepare("SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ? ");
        $stmt_Check->bind_param("ii", $user, $schedule_id);
        $stmt_Check->execute();
        $attendance_result = $stmt_Check->get_result();

        if ($attendance_result->num_rows > 0) {

            $stmt_Check = $con->prepare("SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ? AND exit_status = '' ");
            $stmt_Check->bind_param("ii", $user, $schedule_id);
            $stmt_Check->execute();
            $attendance_result = $stmt_Check->get_result();
            $exit_time_user = date("H:i:s"); // เวลาปัจจุบัน

            $exit_time = strtotime($row["end_date"]);
            $before_time = $exit_time - 15 * 60;
            $after_time = $exit_time + 15 * 60;

            if ($attendance_result->num_rows > 0) {

                if ($exit_time_user < date("H:i:s", $before_time)) {
                    $exit_status = "ก่อนเวลา";
                } elseif ($exit_time_user <= date("H:i:s", $after_time)) {
                    $exit_status = "ตรงเวลา";
                } else {
                    $exit_status = "ล่วงเวลา";
                }

                // Inserting attendance record
                $stmt = $con->prepare("UPDATE attendance SET exit_time_user = ?, exit_status = ? WHERE user_id = ? AND schedule_id = ?");
                $stmt->bind_param("ssss", $exit_time_user, $exit_status, $user, $row["id"]);
                $save = $stmt->execute();

                if ($save) {
                    $_SESSION['message'] = "บันทึก เวลาออกงาน สำเร็จ";
                    $_SESSION['alert'] = "success";
                    echo "<script>location.replace('?page=worktime')</script>";
                    exit();
                } else {
                    $_SESSION['message'] = "ไม่สำเร็จ";
                    $_SESSION['alert'] = "danger";
                    echo "<script>location.replace('?page=worktime')</script>";
                    exit();
                }
                $stmt->close();
            } else {
                $_SESSION['message'] = "คุณได้ทำการบันทึก เวลาออก แล้ว";
                $_SESSION['alert'] = "danger";
                echo "<script>location.replace('?page=worktime')</script>";
                exit();
            }
        } else {
            $_SESSION['message'] = "คุณยังไม้ได้ทำการบันทึก เวลาเข้างาน";
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
<script type="text/javascript" src="//api.nostramap.com/nostraapi/v2.0?key=GZHuLPmgeTYTERjNnjOt4ypzHtHyoFPw3j6hXeWhmA2hHxngvsoUHz2NJdQluIeIPCYP1PdntXLV6VsMlSwmAO0=====2"></script>
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
                <div class="row align-items-center gx-3">
                    <div class="col-auto">
                        <div class="app-icon-holder">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z" />
                            </svg>
                        </div><!--//icon-holder-->
                    </div><!--//col-->
                    <div class="col-auto">
                        <h4 class="app-card-title">บันทึกเวลาเข้าทำงาน</h4>
                    </div><!--//col-->
                </div><!--//row-->
            </div><!--//app-card-header-->

            <div class="app-card-body px-4 mt-4">
                <?php
                include('../sql/message.php');

                $sql = "SELECT time(start_datetime) AS start_date FROM schedule_list WHERE 
                DATE(start_datetime) = CURDATE()
                AND form_type = '1' AND ( 
                job_position_1 = ?
                OR job_position_2 = ?
                OR job_position_3 = ?
                OR job_position_4 = ?
                OR job_position_5 = ?
                OR job_position_6 = ?)";

                $show_form = $con->prepare($sql);
                $show_form->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);

                $show_form->execute();
                $show_form_result = $show_form->get_result();

                if ($show_form_result->num_rows > 0) {
                ?>
                    <form class="row" action="" method="POST" enctype="multipart/form-data">
                        <div class="p-4 row-md">
                            <?php include('map.php'); ?>
                        </div>
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
                        <p class="text-center fs-5">
                            <?php

                            // คำศัพท์ที่ใช้แปลงตำแหน่งงาน
                            $job_positions = [
                                "ครัว" => "job_position_1",
                                "ย่าง" => "job_position_2",
                                "แคชเชียร์" => "job_position_3",
                                "เสิร์ฟ 1" => "job_position_4",
                                "เสิร์ฟ 2" => "job_position_5",
                                "เสิร์ฟ 3" => "job_position_6"
                            ];

                            $sql_job = "SELECT time(start_datetime) AS start_date, time(end_datetime) AS end_date, id, job_position_1, job_position_2, job_position_3, job_position_4, job_position_5, job_position_6
                                        FROM schedule_list WHERE DATE(start_datetime) = CURDATE()
                                        AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
                            $stmt_job = $con->prepare($sql_job);
                            $stmt_job->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                            $stmt_job->execute();
                            $result = $stmt_job->get_result();

                            $used_job_positions = [];

                            while ($row_job = $result->fetch_assoc()) {
                                foreach ($row_job as $key => $value) {
                                    if (strpos($key, 'job_position_') === 0 && $value === $user_nickname) {
                                        // หาชื่อคำศัพท์จาก associative array
                                        $job_name = array_search($key, $job_positions);
                                        if ($job_name) {
                                            $used_job_positions[] = $job_name;
                                        }
                                    }
                                }
                            }

                            $stmt_job->close();

                            // แปลง $used_job_positions เป็นสตริง
                            $used_job_positions_str = implode(', ', $used_job_positions);

                            echo "งานที่ท่านได้รับมอบหมาย : " . $used_job_positions_str;

                            ?>
                        </p>
                        <label class="col-12 fs-4 fw-bold">เวลา</label>
                        <div class="d-flex justify-content-between col-12">
                            <label class="ms-5 fs-5">เวลาเข้างาน</label>
                            <label class="me-5 fs-5 fw-bold">
                                <?php
                                // คิวรี่ SQL เพื่อเลือกเวลาเริ่มต้นจากตาราง schedule_list
                                // กรองรายการตามวันที่ปัจจุบันและตำแหน่งงานของผู้ใช้
                                $sql = "SELECT time(start_datetime) AS start_date FROM schedule_list WHERE 
                            DATE(start_datetime) = CURDATE()
                            AND form_type = '1' AND ( 
                            job_position_1 = ?
                            OR job_position_2 = ?
                            OR job_position_3 = ?
                            OR job_position_4 = ?
                            OR job_position_5 = ?
                            OR job_position_6 = ?)";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);

                                // ดำเนินการคำสั่ง SQL
                                $stmt->execute();
                                $result2 = $stmt->get_result();

                                // ตรวจสอบว่ามีแถวที่ส่งกลับหรือไม่
                                if ($result2->num_rows > 0) {
                                    // วนลูปผ่านแต่ละแถวในผลลัพธ์
                                    while ($row = $result2->fetch_assoc()) {
                                        // แสดงวัน/เวลาเริ่มต้น
                                        echo $row["start_date"];
                                    }
                                } else {
                                    // แสดงถ้าไม่มีผลลัพธ์
                                    echo "0 results";
                                }
                                $stmt->close();
                                ?>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between col-12">
                            <label class="ms-5 fs-5">เวลาออกงาน</label>
                            <label class="me-5 fs-5 fw-bold">
                                <?php
                                $sql = "SELECT time(end_datetime) AS end_date FROM schedule_list WHERE 
                            DATE(end_datetime) = CURDATE()
                            AND form_type = '1' AND ( 
                            job_position_1 = ?
                            OR job_position_2 = ?
                            OR job_position_3 = ?
                            OR job_position_4 = ?
                            OR job_position_5 = ?
                            OR job_position_6 = ?)";

                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                                $stmt->execute();
                                $result2 = $stmt->get_result();
                                if ($result2->num_rows > 0) {
                                    // แสดงข้อมูล
                                    while ($row = $result2->fetch_assoc()) {
                                        echo $row["end_date"];
                                    }
                                } else {
                                    echo "0 results";
                                }
                                $stmt->close();
                                ?>
                            </label>
                        </div>

                        <label class="col-12 fs-4 fw-bold">สถานะ</label>
                        <div class="d-flex justify-content-between col-12">
                            <label class="ms-5 fs-5">สถานะเวลาเข้า</label>
                            <label class="me-5 fs-5 fw-bold">
                                <?php
                                $sql = "SELECT id
                                FROM schedule_list 
                                WHERE DATE(start_datetime) = CURDATE()
                                AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $stmt->close();

                                $schedule_id = $row["id"];

                                $queryATT = "SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ?"; // Using prepared statement
                                $stmt = mysqli_prepare($con, $queryATT);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "ss", $user, $schedule_id); // Bind parameter
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $ATTdata = mysqli_fetch_array($result);
                                        if (isset($ATTdata['entry_status'])) {
                                            echo htmlspecialchars($ATTdata['entry_status'], ENT_QUOTES, 'UTF-8'); // Sanitize output
                                        } else {
                                            echo "รอการทำรายการ";
                                        }
                                    } else {
                                        echo "รอการทำรายการ"; // No data found
                                    }
                                } else {
                                    echo "Error: " . mysqli_error($con); // Display error
                                }
                                ?>
                            </label>
                        </div>
                        <div class="d-flex justify-content-between col-12">
                            <label class="ms-5 fs-5">สถานะเวลาออก</label>
                            <label class="me-5 fs-5 fw-bold">
                                <?php
                                $sql = "SELECT id
                                FROM schedule_list 
                                WHERE DATE(start_datetime) = CURDATE()
                                AND form_type = '1' AND (job_position_1 = ? OR job_position_2 = ? OR job_position_3 = ? OR job_position_4 = ? OR job_position_5 = ? OR job_position_6 = ?)";
                                $stmt = $con->prepare($sql);
                                $stmt->bind_param("ssssss", $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname, $user_nickname);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $row = $result->fetch_assoc();
                                $stmt->close();

                                $schedule_id = $row["id"];

                                $queryATT = "SELECT * FROM attendance WHERE user_id = ? AND schedule_id = ?"; // Using prepared statement
                                $stmt = mysqli_prepare($con, $queryATT);
                                if ($stmt) {
                                    mysqli_stmt_bind_param($stmt, "ss", $user, $schedule_id); // Bind parameter
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    if ($result && mysqli_num_rows($result) > 0) {
                                        $ATTdata = mysqli_fetch_array($result);
                                        if (isset($ATTdata['exit_time_user']) && $ATTdata['exit_time_user'] != null) {
                                            echo htmlspecialchars($ATTdata['exit_status'], ENT_QUOTES, 'UTF-8'); // Sanitize output
                                        } else {
                                            echo "รอการทำรายการ";
                                        }
                                    } else {
                                        echo "รอการทำรายการ"; // No data found
                                    }
                                } else {
                                    echo "Error: " . mysqli_error($con); // Display error
                                }
                                ?>
                            </label>
                        </div>
                        <div class="d-flex justify-content-center col-12 mb-2 mx-2 my-4 mb-4">
                            <button type="submit" name="in" class="btn btn-primary btn-lg mx-2"><i class="fa-solid fa-right-to-bracket"></i> บันทึกเข้า</button>
                            <button type="submit" name="out" class="btn btn-primary btn-lg mx-2"><i class="fa-solid fa-right-from-bracket"></i> บันทึกออก</button>
                        </div>
                        <div class="mb-4 pb-4">

                        </div>
                    </form>
            </div>
        <?php
                } else {
                    echo "<h1 class='text-center pt-4'><i class='fa-regular fa-calendar-minus'></i> วันนี้ท่านไม่มีงาน</h1>";
                }
                $show_form->close();
        ?>
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