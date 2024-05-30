<?php

if (isset($_POST['delete_leave_type'])) {
    $id = mysqli_real_escape_string($con, $_POST['delete_leave_type']);
    $leaveType = mysqli_real_escape_string($con, $_POST['leaveType']);

    $query = "DELETE FROM leave_work_type WHERE id ='$id' ";
    $query_run = mysqli_query($con, $query);


    if ($query_run) {
        $_SESSION['message'] = "ลบประเภทการลางาน $leaveType สำเร็จ";
        $_SESSION['alert'] = "success";
        $alert = '<script type="text/javascript">';
        $alert .= 'window.location.href = "?page=manage-leave-type";';
        $alert .= '</script>';
        echo $alert;
        exit();
    } else {
        $_SESSION['message'] = "ลบประเภทการลางานไม่สำเร็จ";
        $_SESSION['alert'] = "danger";
    }
}
?>

<div class="row">
    <div class="col-12">
        <div class="app-card-body p-0 p-lg-0">
            <!-- ----------------------------- Body -------------------------- -->
            <?php include('../sql/message.php'); ?>

            <div class="row">
                <div class="col-md-12">
                    <div class="app-card shadow-sm">
                        <div class="app-card-header p-3">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-auto">
                                    <h4 class="app-card-title">จัดการประเภทการลางาน</h4>
                                </div><!--//col-->
                                <div class="col-auto">
                                    <div class="card-header-action">
                                        <a href="?page=add-leave-type" class="btn btn-primary"><i class="fa-solid fa-plus"></i> เพิ่มประเภทการลางาน</a>
                                    </div><!--//card-header-actions-->
                                </div><!--//col-->
                            </div><!--//row-->
                        </div>
                        <div class="app-card-body p-4">

                            <table class="table table-striped" id="mytable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>ประเภทการลา</th>
                                        <th>คำอธิบาย</th>
                                        <th>วันที่สร้าง</th>
                                        <th>เมนูการจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = "SELECT * FROM leave_work_type";
                                    $query_run = mysqli_query($con, $query);
                                    $cnt = 1;
                                    if (mysqli_num_rows($query_run) > 0) {
                                        foreach ($query_run as $no) {
                                    ?>
                                            <tr>
                                                <td><b><?= $cnt ?></b></td>
                                                <td><?= $no['leaveType']; ?></td>
                                                <td><?= $no['description']; ?></td>
                                                <td><?= $no['creationDate']; ?></td>
                                                <td>
                                                    <a href="?page=edit-leave-type&id=<?= $no['id']; ?>" class="btn btn-success btn-sm"><i class="fa-solid fa-gear"></i></a>
                                                    <form action="" method="POST" class="d-inline">
                                                        <input type="hidden" name="leaveType" value="<?= $no['leaveType']; ?>">
                                                        <button type="submit" onclick="return confirm('ยืนยันการลบ')" name="delete_leave_type" value="<?= $no['id']; ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                    <?php
                                            $cnt++;
                                        }
                                    } else {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                    ?>

                                </tbody>
                            </table>
                            <script type="text/javascript">
                                $(document).ready(function() {
                                    $('#mytable').DataTable({
                                        searching: false, 
										paging: false, 
										info: false,
										pageLength: 100,
                                        language: {
                                            "decimal": "",
                                            "emptyTable": "No data available in table",
                                            "info": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายชื่อ",
                                            "infoEmpty": "Showing 0 to 0 of 0 entries",
                                            "infoFiltered": "(filtered from _MAX_ total entries)",
                                            "infoPostFix": "",
                                            "thousands": ",",
                                            "lengthMenu": "แสดง _MENU_ รายชื่อ",
                                            "loadingRecords": "Loading...",
                                            "processing": "",
                                            "search": "ค้นหา:",
                                            "zeroRecords": "No matching records found",
                                            "paginate": {
                                                "first": "First",
                                                "last": "Last",
                                                "next": "หน้าถัดไป",
                                                "previous": "ก่อนหน้า"
                                            },
                                            "aria": {
                                                "orderable": "Order by this column",
                                                "orderableReverse": "Reverse order this column"
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--//col-->
    </div><!--//row-->

</div><!--//container-fluid-->