<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';

    session_start();

    // Check for delete
    if (isset($_POST['delete'])) {
        // Get form data
        $delete_id = $_POST['delete_id'];

        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: ../errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $delete_id);
            mysqli_stmt_execute($stmt);
            
            $_SESSION['success'] = 'deletepost';
            header('Location: ../dashboard.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../index.php');
        exit();
    }

