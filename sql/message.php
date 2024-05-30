<?php
    if(isset($_SESSION['message'])) :
?>

    <div class="alert alert-<?= $_SESSION['alert']; ?> alert-dismissible fade show" role="alert">
        <strong>!</strong> <?= $_SESSION['message']; ?> <strong>!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

<?php 
    unset($_SESSION['message']);
    endif;
?>