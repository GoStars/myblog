<?php
    session_start();
     
    if (isset($_SESSION['error']) && $_SESSION['error'] == 'sqlerror') {
        echo '<p>502 Bad Gateway</p>
        <a href="../index.php">Go to home</a>';
        unset($_SESSION['error']);
    } else {
        header('Location: ../index.php');
        exit();
    }
