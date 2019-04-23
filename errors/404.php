<?php
    session_start();
     
    if (isset($_SESSION['error']) && $_SESSION['error'] == 'pagenotfound') {
        echo '<p>404 Page Not Found</p>
        <a href="../index.php">Go to home</a>';
        unset($_SESSION['error']);
    } else {
        header('Location: ../index.php');
        exit();
    }
