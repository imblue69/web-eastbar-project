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

                <div class="col-12 col-lg-6">
                    <div class="app-card app-card-stats-table shadow-sm">
                        <div class="app-card-header p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h4 class="app-card-title">เวลาเข้างาน</h4>
                                </div><!--//col-->
                                <div class="col-auto">
                                    <div class="card-header-action">
                                        <a href="?page=worktime">บันทึกเวลาเข้าทำงาน</a>
                                    </div><!--//card-header-actions-->
                                </div><!--//col-->
                            </div><!--//row-->
                        </div><!--//app-card-header-->
                        <div class="app-card-body p-3 p-lg-4">
                            <?php
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
                                </form>
                        </div>
                    <?php
                            } else {
                                echo "<h1 class='text-center pt-4'><i class='fa-regular fa-calendar-minus'></i> วันนี้ท่านไม่มีงาน</h1>";
                            }
                            $show_form->close();
                    ?>
                    </div><!--//app-card-body-->
                </div><!--//app-card-->
            </div><!--//col-->

        </div><!--//row-->

    </div><!--//col-->
</div><!--//row-->

</div><!--//container-fluid-->
<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert("เบราว์เซอร์ของคุณไม่รองรับการเช็คพิกัด");
        }
    }

    function showPosition(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        // ส่งค่าพิกัดไปยังเซิร์ฟเวอร์ด้วย AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "worktime/check_location.php?lat=" + latitude + "&long=" + longitude, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = xhr.responseText;
                // อัพเดท session หลังจากได้ค่าพิกัด
                if (response == "success") {
                    window.location.reload();
                }
            }
        };
        xhr.send();
    }

    // เรียกใช้งาน getLocation() เมื่อหน้าเว็บโหลดเสร็จ
    window.onload = function() {
        getLocation();
    };

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