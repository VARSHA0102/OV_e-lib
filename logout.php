<?php
    session_start();
    unset($_SESSION['ADMIN_ID']);
    unset($_SESSION['STUDENT_ID']);
    session_destroy();
    header("location:index.php");
?>