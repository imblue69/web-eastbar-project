<?php
$sqlConditions = "SELECT leave_work.leave_work_id as lid,
                        users.user_firstname,
                        users.user_surname,
                        users.user_id,
                        users.user_nickname,
                        leave_work.leave_work_type,
                        leave_work.postingDate,
                        leave_work.status 
                    FROM leave_work 
                    JOIN users ON leave_work.user_id = users.user_id";

if (isset($_POST['search'])) {
	$date_form = $_POST['date-form'];
	$date_to = $_POST['date-to'];
	$leavetype = $_POST['leavetype'];

	$sqlConditions .= " WHERE leave_work.postingDate BETWEEN '$date_form' AND '$date_to'";

	if (!empty($leavetype)) {
		$sqlConditions .= " AND leave_work.leave_work_type = '$leavetype'";
	}
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
						<h1 class="app-page-title mb-0">จัดการการลา</h1>
					</div>
					<div class="col-auto">
						<div class="page-utilities">
							<div class="row g-2 justify-content-start justify-content-md-end align-items-center">

								<div class="col-auto">
									<form class="table-search-form row gx-1 align-items-center" action="" method="POST">
										<div class="col-auto">
											<select class="" name="leavetype" autocomplete="off">
												<option value="">----ประเภทการลา----</option>
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
										<div class="col-auto">
											<input type="date" name="date-form" class="">
											<input type="date" name="date-to" class="">
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
				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">ประวัติการลาทั้งหมด</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work ";
							$parameters = array();
							
							if (isset($_POST['search'])) {
								$date_form = $_POST['date-form'];
								$date_to = $_POST['date-to'];
								$leavetype = $_POST['leavetype'];

								$sql .= " WHERE postingDate BETWEEN ? AND ?";
								$parameters[] = $date_form;
								$parameters[] = $date_to;

								if (!empty($leavetype)) {
									$sql .= " AND leave_work_type = ?";
									$parameters[] = $leavetype;
								}
							}

							$stmt = $con->prepare($sql);
							if ($stmt) {
								if (!empty($parameters)) {
									$types = str_repeat("s", count($parameters));
									$stmt->bind_param($types, ...$parameters);
								}
								$stmt->execute();
								$stmt->bind_result($count);
								$stmt->fetch();
								$stmt->close();
							}
							?>
							<div class="stats-figure"><?php echo $count; ?></div>
							<div class="stats-meta">รายการ</div>

						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1"><i class="fa-solid fa-question"></i> รอการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 0 ";
							$parameters = array();

							if (isset($_POST['search'])) {
								$date_form = $_POST['date-form'];
								$date_to = $_POST['date-to'];
								$leavetype = $_POST['leavetype'];

								$sql .= " AND postingDate BETWEEN ? AND ?";
								$parameters[] = $date_form;
								$parameters[] = $date_to;

								if (!empty($leavetype)) {
									$sql .= " AND leave_work_type = ?";
									$parameters[] = $leavetype;
								}
							} else {
								$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 0";
							}

							$stmt = $con->prepare($sql);
							if ($stmt) {
								if (!empty($parameters)) {
									$types = str_repeat("s", count($parameters));
									$stmt->bind_param($types, ...$parameters);
								}
								$stmt->execute();
								$stmt->bind_result($count);
								$stmt->fetch();
								$stmt->close();
							}
							?>
							<div class="stats-figure"><?php echo $count; ?></div>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1"><i class="fa-solid fa-check"></i> ได้รับการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 1";
							$parameters = array();

							if (isset($_POST['search'])) {
								$date_form = $_POST['date-form'];
								$date_to = $_POST['date-to'];
								$leavetype = $_POST['leavetype'];

								$sql .= " AND postingDate BETWEEN ? AND ?";
								$parameters[] = $date_form;
								$parameters[] = $date_to;

								if (!empty($leavetype)) {
									$sql .= " AND leave_work_type = ?";
									$parameters[] = $leavetype;
								}
							} else {
								$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 1";
							}

							$stmt = $con->prepare($sql);
							if ($stmt) {
								if (!empty($parameters)) {
									$types = str_repeat("s", count($parameters));
									$stmt->bind_param($types, ...$parameters);
								}
								$stmt->execute();
								$stmt->bind_result($count);
								$stmt->fetch();
								$stmt->close();
							}
							?>
							<div class="stats-figure"><?php echo $count; ?></div>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1"><i class="fa-solid fa-xmark"></i> ไม่ได้รับการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 2";
							$parameters = array();

							if (isset($_POST['search'])) {
								$date_form = $_POST['date-form'];
								$date_to = $_POST['date-to'];
								$leavetype = $_POST['leavetype'];

								$sql .= " AND postingDate BETWEEN ? AND ?";
								$parameters[] = $date_form;
								$parameters[] = $date_to;

								if (!empty($leavetype)) {
									$sql .= " AND leave_work_type = ?";
									$parameters[] = $leavetype;
								}
							} else {
								$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE status = 2";
							}

							$stmt = $con->prepare($sql);
							if ($stmt) {
								if (!empty($parameters)) {
									$types = str_repeat("s", count($parameters));
									$stmt->bind_param($types, ...$parameters);
								}
								$stmt->execute();
								$stmt->bind_result($count);
								$stmt->fetch();
								$stmt->close();
							}
							?>
							<div class="stats-figure"><?php echo $count; ?></div>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

			</div><!--//row-->
			<div class="row">
				<div class="col-md-12">
					<div class="app-card shadow-sm">

						<div class="app-card-body p-4">

							<table class="table table-striped" id="table01">
								<thead>
									<tr>
										<th>#</th>
										<th>ชื่อพนักงาน</th>
										<th>ประเภทการลา</th>
										<th>วันที่ทำรายการ</th>
										<th>สถานะ</th>
										<th>เมนูการจัดการ</th>
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
												<td><b><?php echo htmlentities($cnt); ?></b></td>
												<td>
													<p><a href="?page=user-view&id=<?php echo htmlentities($row['user_id']); ?>" target="_blank" style="text-decoration: underline;"><?php echo htmlentities($row['user_firstname'] . " " . $row['user_surname']); ?> (<?php echo htmlentities($row['user_nickname']); ?>)</a></p>
												</td>
												<td><?php echo htmlentities($row['leave_work_type']); ?></td>
												<td><?php echo htmlentities($row['postingDate']); ?></td>
												<td><?php
													$status = $row['status'];
													if ($status == 1) {
														echo '<span style="color: green"><i class="fa-solid fa-check" style="color: #178017;"></i> ได้รับการอนุมัติแล้ว</span>';
													} elseif ($status == 2) {
														echo '<span style="color: red"><i class="fa-solid fa-xmark" style="color: #ff0000;"></i> ไม่ได้รับการอนุมัติ</span>';
													} elseif ($status == 0) {
														echo '<span style="color: blue"><i class="fa-solid fa-question" style="color: #341eff;"></i> รอการอนุมัติ</span>';
													}
													?>
												</td>
												<td><a href="?page=leave-details&id=<?php echo htmlentities($row['lid']); ?>" class="btn btn-info btn-sm"><i class="fa-solid fa-pen-to-square"></i></a></td>
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