<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';

    // Check for delete
    if (isset($_POST['delete'])) {
        // Get form data
        $delete_id = $_POST['delete_id'];

        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            header('Location: ../index.php?error=sqlerror');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $delete_id);
            mysqli_stmt_execute($stmt);
            header('Location: ../index.php?success=deletepost');
            exit();
        }

        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../index.php');
        exit();
    }

