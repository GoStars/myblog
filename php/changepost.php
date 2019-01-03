<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $title = test_input($_POST['title']);
        $description = test_input($_POST['description']);
        $body = test_input($_POST['body']);

        // Check for empty fields
        if (empty($title) || empty($description) || empty($body)) {
            // Store post id in session
            session_start();
            $_SESSION['update_id'] = $update_id;
            header('Location: ../editpost.php?error=emptyeditpostfield');
            // Stop script
            exit();
        } else {
            $query = "UPDATE posts SET title = ?, description = ?, body = ? WHERE id = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../index.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'sssi', $title, $description, $body, $update_id);
                mysqli_stmt_execute($stmt);
                header('Location: ../dashboard.php?success=editpost');
                exit();
            }        
        }
        
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn); 
    } else {
        header('Location: ../index.php');
        exit();
    }

 