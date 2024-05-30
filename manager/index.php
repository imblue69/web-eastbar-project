<?php
session_start();
require '../sql/dbcon.php';
if (strlen($_SESSION['username']) == 0) {
	header('location:../index.php');
}

?>
<!DOCTYPE html>
<html lang="th">
<?php include('include/head.php') ?>

<body class="app">
	<?php include('include/header.php') ?>
	<div class="app-wrapper">
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<?php
				$pageMappings = array(
					'manage-user' => 'manage-user/manage-user.php',
					'add-user' => 'manage-user/add-user.php',
					'user-view' => 'manage-user/user-view.php',
					'user-edit' => 'manage-user/user-edit.php',
					'user-resetpass' => 'manage-user/user-resetpass.php',
					'logout' => 'logout/index.php',
					'profile' => 'profile/index.php',
					'manage-leave' => 'leave/manage-leave.php',
					'manage-leave-type' => 'leave/manage-leave-type.php',
					'add-leave-type' => 'leave/add-leave-type.php',
					'edit-leave-type' => 'leave/edit-leave-type.php',
					'leave-details' => 'leave/leave-details.php',
					'manage-work-schedule' => 'schedule/index.php',
					'check-worktime-day' => 'worktime/check-worktime-day.php',
					'check-worktime-person' => 'worktime/check-worktime-person.php',
					'worktime' => 'worktime/worktime.php',
					'work-schedule' => 'schedule/work-schedule.php'
				);

				$page = isset($_GET['page']) ? $_GET['page'] : '';

				if (empty($page)) {
					include('dashboard/index.php');
				} elseif (array_key_exists($page, $pageMappings)) {
					include($pageMappings[$page]);
				}
				?>
			</div><!--//app-content-->
			<?php include('include/footer.php') ?>
		</div><!--//app-wrapper-->
	</div>

</body>
<?php include('include/script.php') ?>

</html>