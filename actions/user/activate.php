<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';

    session_start();

     if (isset($_POST['activate'])) {
        $update_id = $_SESSION['id'];
        $user_status = 1;

        $query = "UPDATE users SET user_status = ? WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: ../../errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'ii', $user_status, $update_id);
            mysqli_stmt_execute($stmt);

            session_unset();
            session_destroy();

            session_start();
            $_SESSION['success'] = 'activateaccount';
            header('Location: ../../index.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
     } else {
        header('Location: ../../index.php');
        exit();
    }