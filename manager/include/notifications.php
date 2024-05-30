<div class="app-utility-item app-notifications-dropdown dropdown">
    <a class="dropdown-toggle no-toggle-arrow" id="notifications-dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" title="Notifications">
        <!--//Bootstrap Icons: https://icons.getbootstrap.com/ -->
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z" />
            <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
        </svg>
        <?php
        $sql = "SELECT COUNT(*) as count FROM leave_work WHERE isRead = 0";
        $result = mysqli_query($con, $sql);
        if ($result) {
            // ดึงข้อมูลเป็น associative array
            $row = mysqli_fetch_assoc($result);
            // นับจำนวนแถวที่ตรงเงื่อนไขแล้วเก็บลงในตัวแปร $no
            $no = $row['count'];
        }
        ?>
        <span class="icon-badge"><?php echo $no; ?></span>
    </a><!--//dropdown-toggle-->

    <div class="dropdown-menu p-0" aria-labelledby="notifications-dropdown-toggle">
        <div class="dropdown-menu-content">
            <?php
            $isread = 0;
            $sql = "SELECT * FROM leave_work
            JOIN users ON leave_work.user_id = users.user_id 
            WHERE leave_work.IsRead=?";
            $query = $con->prepare($sql);
            $query->bind_param('i', $isread);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                    <div class="item p-3">
                        <div class="row gx-2 justify-content-between align-items-center">
                            <div class="col-auto">
                                <div class="app-icon-holder">
                                    <img class="mx-1 rounded-circle" src="../assets/images/profile-pic/<?= $row['user_picture'] ?>" width="40" height="40" alt="user profile">

                                </div>
                            </div><!--//col-->
                            <div class="col">
                                <div class="info ps-3">
                                    <div class="desc">
                                        <b>
                                            <?php echo htmlentities($row['user_firstname'] . " " . $row['user_surname']); ?> (<?php echo htmlentities($row['user_nickname']); ?>)
                                        </b>
                                        <br/>
                                        <?php echo htmlentities($row['leave_work_type']); ?> 
                                        <br/>
                                        : <?php echo htmlentities($row['description']); ?>
                                    </div>
                                    <div class="meta mt-4"> <?php echo htmlentities($row['postingDate']); ?> </div>
                                </div>
                            </div><!--//col-->
                        </div><!--//row-->
                        <a class="link-mask" href="?page=leave-details&id=<?php echo htmlentities($row['leave_work_id']); ?>"></a>
                    </div>
                <?php
                    $no++;
                } ?>
                <div class="dropdown-menu-footer p-2 text-center">
                    <a href="?page=manage-leave">View all</a>
                </div>
            <?php } ?>
        </div><!--//dropdown-menu-content-->



    </div><!--//dropdown-menu-->
</div><!--//app-utility-item-->