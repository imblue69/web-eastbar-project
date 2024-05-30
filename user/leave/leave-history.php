<div class="row">
	<div class="col-12">
		<div class="app-card-body p-0 p-lg-0">
			<!-- ----------------------------- Body -------------------------- -->
			<?php include('../sql/message.php'); ?>

			<div class="row g-4 mb-4">

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">ประวัติการลาทั้งหมด</h4>
							<?php
							$eid = $_SESSION['user_id'];
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE user_id = $eid";
							$result = $con->query($sql);
							if ($result->num_rows > 0) {
								$row = $result->fetch_assoc();
								$count = $row["no"];
								echo '<div class="stats-figure">' . $count . '</div>';
							} else {
								echo '<div class="stats-figure">#</div>';
							}
							?>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">รอการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE user_id = $eid AND status = 0";
							$result = $con->query($sql);
							if ($result->num_rows > 0) {
								$row = $result->fetch_assoc();
								$count = $row["no"];
								echo '<div class="stats-figure">' . $count . '</div>';
							} else {
								echo '<div class="stats-figure">#</div>';
							}
							?>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">ได้รับการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE user_id = $eid AND status = 1";
							$result = $con->query($sql);
							if ($result->num_rows > 0) {
								$row = $result->fetch_assoc();
								$count = $row["no"];
								echo '<div class="stats-figure">' . $count . '</div>';
							} else {
								echo '<div class="stats-figure">#</div>';
							}
							?>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

				<div class="col-6 col-lg-3">
					<div class="app-card app-card-stat shadow-sm h-100">
						<div class="app-card-body p-3 p-lg-4">
							<h4 class="stats-type mb-1">ไม่ได้รับการอนุมัติ</h4>
							<?php
							$sql = "SELECT COUNT(*) AS no FROM leave_work WHERE user_id = $eid AND status = 2";
							$result = $con->query($sql);
							if ($result->num_rows > 0) {
								$row = $result->fetch_assoc();
								$count = $row["no"];
								echo '<div class="stats-figure">' . $count . '</div>';
							} else {
								echo '<div class="stats-figure">#</div>';
							}
							?>
							<div class="stats-meta">รายการ</div>
						</div><!--//app-card-body-->
					</div><!--//app-card-->
				</div><!--//col-->

			</div><!--//row-->

			<div class="row">
				<div class="col-md-12">
					<div class="app-card shadow-sm">
						<div class="app-card-header p-3">
							<div class="row align-items-center gx-3">
								<div class="col-auto">
									<h4 class="app-card-title">ประวัติการลา</h4>
								</div><!--//col-->
							</div><!--//row-->
							<!-- <h4>
								ประวัติการลา
							</h4> -->
						</div>
						<div class="app-card-body p-4">

							<table class="table table-striped" id="mytable">
								<thead>
									<tr>
										<th>#</th>
										<th>ประเภทการลา</th>
										<th>จาก</th>
										<th>ถึง</th>
										<th>วันที่ทำรายการ</th>
										<th>สถานะ</th>
										<th>เมนูการจัดการ</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$eid = $_SESSION['user_id'];
									$sql = "SELECT leave_work.leave_work_id as lid, leave_work_type, date_from,
									date_to, description, postingDate, remarkDate, remark, status 
									FROM leave_work WHERE user_id = ? ORDER BY lid DESC";
									$query = mysqli_prepare($con, $sql);
									mysqli_stmt_bind_param($query, "s", $eid);
									mysqli_stmt_execute($query);
									$result = mysqli_stmt_get_result($query);

									if ($result) {
										$cnt = 1;
										while ($row = mysqli_fetch_assoc($result)) {
									?>
											<tr>
												<td><b><?php echo htmlentities($cnt); ?></b></td>
												<td><?php echo htmlentities($row['leave_work_type']); ?></td>
												<td><?php echo htmlentities($row['date_from']); ?></td>
												<td><?php echo htmlentities($row['date_to']); ?></td>
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
												<td><a href="?page=leave-details&id=<?php echo htmlentities($row['lid']); ?>" class="btn btn-info btn-sm">ดูรายละเอียด</a></td>
											</tr>
									<?php
											$cnt++;
										}
										mysqli_free_result($result);
									} else {
										echo "Error: " . mysqli_error($con);
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