<?php
    if (isset($_GET['error']) && $_GET['error'] == 'sqlerror') {
        echo '<p>502 Bad Gateway</p>
        <a href="../index.php">Go to home</a>';
    } else {
        header('Location: ../index.php');
        exit();
    }
