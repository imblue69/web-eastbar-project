<?php
$userid = $_SESSION['user_id'];
$sqlConditions = "SELECT attendance.id as lid,
                        users.user_firstname,
                        users.user_surname,
                        users.user_id,
                        users.user_nickname,
                        attendance.job_position,
                        attendance.attendance_date,
                        attendance.entry_time, 
                        attendance.entry_time_user, 
                        attendance.entry_status, 
                        attendance.exit_time, 
                        attendance.exit_time_user, 
                        attendance.exit_status, 
                        attendance.schedule_id
                    FROM attendance 
                    JOIN users ON attendance.user_id = users.user_id WHERE users.user_id = $userid ";
if (isset($_POST['search'])) {
	$date = $_POST['date'];
	$sqlConditions .= " AND attendance.attendance_date = '$date'";
} else {
	$date = date('Y-m-d');
	$sqlConditions .= " AND attendance.attendance_date = '$date'";
}
$sqlConditions .= " ORDER BY lid DESC";
$sql01 = $sqlConditions;

?>
<div class="row">
	<div class="col-12">
		<div class="app-card-body p-0 p-lg-0">
			<!-- ----------------------------- Body -------------------------- -->
			<?php include('../sql/message.php'); ?>
			<div class="row g-4 mb-4">
				<div class="row g-3 align-items-center justify-content-between">
					<div class="col-auto">
						<h1 class="app-page-title mb-0">เช็คเวลาการเข้างาน</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">

								<div class="col-auto">
									<form class="table-search-form row gx-1 align-items-center" action="" method="POST">
										<div class="col-auto">
											<input type="date" name="date" class="">
										</div>
										<div class="col-auto">
											<button type="submit" name="search" class="btn app-btn-secondary">ค้นหา</button>
											<a href="" class="btn btn-danger">รีเซ็ต</a>
										</div>
									</form>
								</div><!--//col-->

							</div><!--//row-->
						</div><!--//table-utilities-->
					</div><!--//col-auto-->
				</div><!--//row-->

			</div><!--//row-->
			<div class="row">
				<div class="col-md-12">
					<div class="app-card shadow-sm">
						<div class="app-card-body p-4">
							<?php
							$sql_time = "SELECT * FROM attendance JOIN schedule_list ON attendance.schedule_id  = schedule_list.id";
							$params = [];
							if (isset($_POST['search'])) {
								$date = $_POST['date'];
								$sql_time .= " WHERE form_type = '1' AND attendance_date = ?";
								$params[] = $date;
							} else {
								$date = date('Y-m-d');
								$sql_time .= " WHERE form_type = '1' AND attendance_date = ?";
								$params[] = $date;
							}

							$stmt = $con->prepare($sql_time);
							if ($stmt) {
								if (count($params) > 0) {
									$stmt->bind_param(str_repeat('s', count($params)), ...$params);
								}

								$stmt->execute();
								$result02 = $stmt->get_result();

								if ($result02->num_rows > 0) {
									$row2 = $result02->fetch_assoc();
									echo "วันที่ : " .  $date . "<br>";
									echo "เวลาเข้างาน : " . htmlentities($row2['entry_time']) . "<br>";
									echo "เวลาออกงาน : " . htmlentities($row2['exit_time']) . "<br>";
								} else {
									echo "No records found";
								}

								$stmt->close();
							} else {
								echo "Error: " . $con->error;
							}
							?>
							<table class="table table-striped" id="table01">
								<thead>
									<tr>
										<th>ตำแหน่งงาน</th>
										<th>เวลาบันทึกเข้า</th>
										<th>เวลาบันทึกออก</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$result01 = $con->query($sql01);

									if ($result01) {
										$cnt = 1;
										while ($row = $result01->fetch_assoc()) {
									?>
											<tr>
												<td><?php echo htmlentities($row['job_position']); ?></td>
												<td><?php echo htmlentities($row['entry_status'] . "  "); ?>(<?php echo htmlentities($row['entry_time_user']); ?>)</td>
												<td>
													<?php
													if (empty($row['exit_status'])) {
														echo "รอการทำรายการ";
													} else {
														echo htmlentities($row['exit_status']) . " (" . htmlentities($row['exit_time_user']) . ")";
													}
													?>
												</td>

											</tr>
									<?php
											$cnt++;
										}
										$result01->free();
									}
									?>
								</tbody>
							</table>
							<script type="text/javascript">
								$(document).ready(function() {
									$('#table01').DataTable({
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