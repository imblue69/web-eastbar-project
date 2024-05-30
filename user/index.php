<?php
session_start();
require '../sql/dbcon.php';
if (strlen($_SESSION['username']) == 0) {
	header('location:../index.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<?php include('include/head.php') ?>

<body class="app">
	<?php include('include/header.php') ?>
	<div class="app-wrapper">
		<div class="app-content">
			<div class="container-xl pt-3 p-md-3 p-lg-4">
				<?php
				$pageMappings = array(
					'logout' => 'logout/index.php',
					'profile' => 'profile/index.php',
					'leave-history' => 'leave/leave-history.php',
					'leave-apply' => 'leave/leave-apply.php',
					'leave-details' => 'leave/leave-details.php',
					'worktime' => 'worktime/worktime.php',
					'work-schedule' => 'schedule/work-schedule.php',
					'check_worktime' => 'worktime/check_worktime.php'
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
	<?php include('include/script.php') ?>
</body>

</html>