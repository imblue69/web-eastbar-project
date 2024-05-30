<header class="app-header fixed-top">

    <div class="app-header-inner">
        <div class="container-fluid py-2">
            <div class="app-header-content">
                <div class="row justify-content-between align-items-center">

                    <div class="col-auto">
                        <a id="sidepanel-toggler" class="sidepanel-toggler d-inline-block d-xl-none" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" role="img">
                                <title>Menu</title>
                                <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"></path>
                            </svg>
                        </a>
                    </div><!--//col-->

                    <div class="app-utilities col-auto">

                        <?php //include('notifications.php') ?>
                        
                        <div class="app-utility-item app-user-dropdown dropdown">
                            <a class="dropdown-toggle" id="user-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                                <img class="mx-1 rounded-circle" src="../assets/images/profile-pic/<?= $_SESSION['user_picture'] ?>" alt="user profile"> <?= $_SESSION['username'] ?>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="user-dropdown-toggle">
                                <li>
                                    <a class="dropdown-item" href="?page=profile">
                                        <i class="fa-solid fa-circle-user"></i> บัญชีของฉัน
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="?page=logout"><i class="fa-solid fa-right-from-bracket"></i> ออกจากระบบ</a></li>
                            </ul>
                        </div><!--//app-user-dropdown-->
                    </div><!--//app-utilities-->

                </div><!--//row-->
            </div><!--//app-header-content-->
        </div><!--//container-fluid-->
    </div><!--//app-header-inner-->

    <div id="app-sidepanel" class="app-sidepanel">
        <div id="sidepanel-drop" class="sidepanel-drop"></div>
        <div class="sidepanel-inner d-flex flex-column">
            <a href="#" id="sidepanel-close" class="sidepanel-close d-xl-none">&times;</a>

            <div class="app-branding">
                <a class="app-logo" href="../manager/"><img class="rounded-circle logo-icon me-2" src="../assets/images/logo.jpg" alt="logo"><span class="logo-text">East Bar</span></a>
            </div><!--//app-branding-->

            <nav id="app-nav-main" class="app-nav app-nav-main flex-grow-1">
                <ul class="app-menu list-unstyled accordion" id="menu-accordion">

                    <li class="nav-item ">
                        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                        <a class="nav-link <?php echo !isset($_GET['page']) && empty($_GET['page']) ? 'active' : '' ?>" href="../manager/">
                            <span class="nav-icon">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.646 1.146a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5v-4H7v4a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6zM2.5 7.707V14H6v-4a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v4h3.5V7.707L8 2.207l-5.5 5.5z" />
                                    <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                                </svg>
                            </span>
                            <span class="nav-link-text">หน้าหลัก</span>
                        </a><!--//nav-link-->
                    </li><!--//nav-item-->

                    <li class="nav-item">
                        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                        <a class="nav-link <?php echo (isset($_GET['page']) && ($_GET['page'] == 'manage-user' || $_GET['page'] == 'user-edit' || $_GET['page'] == 'user-view' || $_GET['page'] == 'add-user' || $_GET['page'] == 'user-resetpass')) ? 'active' : '' ?>" href="?page=manage-user">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z" />
                                </svg>
                            </span>
                            <span class="nav-link-text">ระบบจัดการ ข้อมูลผู้ใช้ระบบ</span>
                        </a><!--//nav-link-->
                    </li><!--//nav-item-->

                    <li class="nav-item ">
                        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                        <a class="nav-link <?php echo (isset($_GET['page']) && ($_GET['page'] == 'check-worktime-day')) ? 'active' : '' ?>" href="?page=check-worktime-day">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                                </svg>
                            </span>
                            <span class="nav-link-text">เช็คเวลาการเข้างาน (วัน)</span>
                        </a><!--//nav-link-->
                    </li><!--//nav-item-->


                    <li class="nav-item ">
                        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
                        <a class="nav-link <?php echo (isset($_GET['page']) && ($_GET['page'] == 'check-worktime-person')) ? 'active' : '' ?>" href="?page=check-worktime-person">
                            <span class="nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                    <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71z" />
                                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0" />
                                </svg>
                            </span>
                            <span class="nav-link-text">เช็คเวลาการเข้างาน (รายคน)</span>
                        </a><!--//nav-link-->
                    </li><!--//nav-item-->

                </ul><!--//app-menu-->
            </nav><!--//app-nav-->
        </div><!--//sidepanel-inner-->
    </div><!--//app-sidepanel-->
</header><!--//app-header-->