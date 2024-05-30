<?php
if (isset($_POST['apply'])) {

    $empid = $_SESSION['user_id'];
    $leaveType = $_POST['leavetype'];
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $description = $_POST['description'];
    $status = 0;
    $isread = 0;
    $file_name = $_FILES["fileToUpload"]["name"];
    $file_type = $_FILES["fileToUpload"]["type"];
    $file_tmp_name = $_FILES["fileToUpload"]["tmp_name"];

    if ($_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK && !empty($file_tmp_name)) {
        $file_content = file_get_contents($file_tmp_name);
    } else {
        // Handle the error or set a default value
        $file_content = ""; // Set a default value or handle the error as needed
    }
    if ($fromdate > $todate) {
        $_SESSION['message'] = "วันจาก ควรมากกว่า วันถึง";
        $_SESSION['alert'] = "danger";
    } else {
        // File upload handling 
        $target_dir = "../assets/file/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 5000000) { // Adjust the file size limit as needed
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "pdf" && $imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only PDF, DOC, DOCX, JPG, JPEG, PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        // $file_name = $_FILES["fileToUpload"]["name"];
        // $file_path = $target_file;
        $query = "INSERT INTO leave_work (leave_work_type, date_from, date_to, description, status, isRead, user_id, file_name, file_type, file_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssssss", $leaveType, $fromdate, $todate, $description, $status, $isread, $empid, $file_name, $file_type, $file_content);
            $query_run = mysqli_stmt_execute($stmt);
            if ($query_run) {
                $_SESSION['message'] = "ทำรายการ ลางานสำเร็จ";
                $_SESSION['alert'] = "success";
                $alert = '<script type="text/javascript">';
                $alert .= 'window.location.href = "?page=leave-history";';
                $alert .= '</script>';
                echo $alert;
                exit();
            } else {
                echo "Error: " . mysqli_error($con);
                $_SESSION['message'] = "ทำรายการ ลางานไม่สำเร็จ";
                $_SESSION['alert'] = "danger";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($con);
            // Handle the error gracefully
        }
    }
}
?>
<div class="row">
    <div class="col-12">
        <!-- ----------------------------- Body -------------------------- -->
        <div class="col-md-6">
            <div class="app-card shadow-sm">
                <div class="app-card-header p-3 border-bottom-0">
                    <div class="row align-items-center gx-3">
                        <div class="col-auto">
                            <div class="app-icon-holder">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-raised-hand" viewBox="0 0 16 16">
                                    <path d="M6 6.207v9.043a.75.75 0 0 0 1.5 0V10.5a.5.5 0 0 1 1 0v4.75a.75.75 0 0 0 1.5 0v-8.5a.25.25 0 1 1 .5 0v2.5a.75.75 0 0 0 1.5 0V6.5a3 3 0 0 0-3-3H6.236a1 1 0 0 1-.447-.106l-.33-.165A.83.83 0 0 1 5 2.488V.75a.75.75 0 0 0-1.5 0v2.083c0 .715.404 1.37 1.044 1.689L5.5 5c.32.32.5.754.5 1.207" />
                                    <path d="M8 3a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                </svg>
                            </div><!--//icon-holder-->
                        </div><!--//col-->
                        <div class="col-auto">
                            <h4 class="app-card-title">ทำรายการลางาน</h4>
                        </div><!--//col-->
                    </div><!--//row-->
                </div><!--//app-card-header-->
                <div class="app-card-body px-4 w-100">
                    <?php include('../sql/message.php'); ?>
                    <form class="row g-3" action="" method="POST" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <select class="form-select" name="leavetype" autocomplete="off">
                                <option value="">---- เลือกประเภทการลา ----</option>
                                <?php
                                $sql = "SELECT leaveType FROM leave_work_type";
                                $result = mysqli_query($con, $sql);

                                if ($result) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                        <option value="<?php echo htmlentities($row['leaveType']); ?>"><?php echo htmlentities($row['leaveType']); ?></option>
                                <?php
                                    }
                                    mysqli_free_result($result);
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>จาก</label>
                            <input type="date" name="fromdate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>ถึง</label>
                            <input type="date" name="todate" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label>คำอธิบาย</label>
                            <textarea class="form-control" name="description" style="height: 100px" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>ไฟล์แนบ</label>
                            <input type="file" name="fileToUpload" class="form-control" id="fileToUpload">
                        </div>
                        <div class="mb-2 mx-2 my-4 mb-4">
                            <button type="submit" name="apply" class="btn btn-primary">ทำรายการ</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div><!--//row-->
</div><!--//container-fluid-->