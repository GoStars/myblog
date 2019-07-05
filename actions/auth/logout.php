<?php
    if (isset($_POST['logout'])) {
        session_start();
        session_unset();
        session_destroy();

        session_start();
        $_SESSION['success'] = 'logout';
        header('Location: ../../index.php');
        exit();
    } else {
        header('Location: ../../index.php');
        exit();
    }
    
    