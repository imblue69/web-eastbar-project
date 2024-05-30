<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <title>Login - East Bar</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="East-Bar website">

    <link rel="shortcut icon" href="../assets/images/logo.jpg">

    <!-- FontAwesome JS-->
    <script defer src="assets/plugins/fontawesome/js/all.min.js"></script>

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="assets/css/portal.css">
</head>

<body class="app app-login p-0">
    <div class="row g-0 app-auth-wrapper">
        <div class="col-12 col-md-7 col-lg-6 auth-main-col text-center p-5">
            <div class="d-flex flex-column align-content-end">
                <div class="app-auth-body mx-auto">
                    <div class="app-auth-branding mb-4"><a class="app-logo" href=""><img class="logo-icon me-2 rounded-circle" src="assets/images/logo.jpg" alt="logo"></a></div>
                    <h2 class="auth-heading text-center mb-5">Login to East-Bar</h2>
                    <div class="auth-form-container text-start">
                        <?php
                        //print_r($_SESSION);
                        include('sql/message.php'); ?>
                        <form action="sql/check_login.php" method="post" class="auth-form login-form">
                            <div class="email mb-3">
                                <label for="username" class="sr-only">Username</label>
                                <input id="username" name="username" type="text" class="form-control" placeholder="Username" required autocomplete="username">
                            </div><!--//form-group-->
                            <div class="password mb-3">
                                <label for="password" class="sr-only">Password</label>
                                <input id="password" name="password" type="password" class="form-control signin-password" placeholder="Password" required autocomplete="current-password">
                            </div><!--//form-group-->
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn app-btn-primary w-100 theme-btn mx-auto">Log In</button>
                            </div>
                        </form>


                        <!-- <div class="auth-option text-center pt-5">No Account? Sign up <a class="text-link" href="signup.html">here</a>.</div> -->
                    </div><!--//auth-form-container-->

                </div><!--//auth-body-->



            </div><!--//flex-column-->
        </div><!--//auth-main-col-->

        <div class="col-12 col-md-5 col-lg-6 h-100 auth-background-col">
            <div class="auth-background-holder">
            </div>
            <div class="auth-background-mask"></div>
            <div class="auth-background-overlay p-3 p-lg-5">
            </div><!--//auth-background-overlay-->
        </div>

    </div><!--//row-->
</body>

</html>