<div class="row">
	<div class="col-12">
		<div class="app-card-body p-0 p-lg-0">
			<!-- ----------------------------- Body -------------------------- -->
			<?php include('..//sql/message.php'); ?>

			<div class="row">
				<div class="col-md-12">
					<div class="app-card shadow-sm">
						<div class="app-card-header p-3">
							<div class="row justify-content-between align-items-center">
								<div class="col-auto">
									<h4 class="app-card-title">จัดการข้อมูลผู้ใช้ระบบ</h4>
								</div><!--//col-->
								<div class="col-auto">
									<div class="card-header-action">
										<a href="?page=add-user" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> เพิ่มผู้ใช้ระบบ</a>
									</div><!--//card-header-actions-->
								</div><!--//col-->
							</div><!--//row-->
						</div>
						<div class="app-card-body p-4">

							<table class="table table-striped" id="mytable">
								<thead>
									<tr>
										<th>รูปภาพ</th>
										<th>ชื่อ</th>
										<th>เบอร์โทรศัพท์</th>
										<th>ตำแหน่งงาน</th>
										<th width="180">เมนูการจัดการผู้ใช้ระบบ</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$query = "SELECT * FROM users";
									$query_run = mysqli_query($con, $query);

									if (mysqli_num_rows($query_run) > 0) {
										foreach ($query_run as $user) {
									?>
											<tr>
												<td><img width="75" height="75" src="../assets/images/profile-pic/<?= $user['user_picture']; ?>" alt="user_picture"></td>
												<td><?= $user['user_firstname'] . ' ' . $user['user_surname'] . ' (' . $user['user_nickname'] . ')'; ?></td>

												<td><?= $user['user_telephone']; ?></td>
												<td><?= $user['user_type']; ?></td>
												<td>
													<a href="?page=user-view&id=<?= $user['user_id']; ?>" class="btn btn-info btn-sm mt-1"><i class="fa-solid fa-magnifying-glass"></i></a>
													<?php if ($user['user_type'] !== 'admin' && $user['user_type'] !== 'manager') : ?>
														<a href="?page=user-edit&id=<?= $user['user_id']; ?>" class="btn btn-success btn-sm mt-1"><i class="fa-solid fa-user-gear"></i></a>
														<form action="../sql/code.php" method="POST" class="d-inline mt-1">
															<input type="hidden" name="username" value="<?= $user['username']; ?>">
															<button type="submit" onclick="return confirm('ยืนยันการลบ')" name="delete_user" value="<?= $user['user_id']; ?>" class="btn btn-danger btn-sm mt-1"><i class="fa-solid fa-user-xmark"></i></button>
														</form>
														<a href="?page=user-resetpass&id=<?= $user['user_id']; ?>" class="btn btn-secondary btn-sm mt-1"><i class="fa-solid fa-key"></i></a>
													<?php endif; ?>
												</td>
											</tr>
									<?php
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