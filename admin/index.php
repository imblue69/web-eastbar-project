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
		<div class="app-content pt-3 p-md-3 p-lg-4">
			<div class="container-xl">
				<?php
				//print_r($_SESSION);
				if (!isset($_GET['page']) && empty($_GET['page'])) {
					include('dashboard/index.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'manage-user') {
					include('manage-user/manage-user.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'add-user') {
					include('manage-user/add-user.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'user-view') {
					include('manage-user/user-view.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'user-edit') {
					include('manage-user/user-edit.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'user-resetpass') {
					include('manage-user/user-resetpass.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'logout') {
					include('logout/index.php');
				} elseif (isset($_GET['page']) && $_GET['page'] == 'profile') {
					include('profile/index.php');
				}
				?>
			</div><!--//app-content-->
			<?php include('include/footer.php') ?>
		</div><!--//app-wrapper-->
	</div>
	<?php include('include/script.php') ?>
</body>

</html>