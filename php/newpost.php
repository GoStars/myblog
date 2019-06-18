<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    session_start();

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $user_id = $_SESSION['id'];
        $title = test_input($_POST['title']);
        $description = test_input($_POST['description']);
        $body = test_input($_POST['body']);

        // Check for empty fields
        if (empty($title) || empty($description) || empty($body)) {
            // Save correct data into fields
            $_SESSION['error'] = 'emptypostfield';
            header('Location: ../addpost.php?title='.$title.'&description='.$description.'&body='.$body);
            // Stop script
            exit();
        } else {
            $query = "INSERT INTO posts(user_id, title, description, body) VALUES(?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../errors/502.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'isss', $user_id, $title, $description, $body);
                mysqli_stmt_execute($stmt);

                $_SESSION['success'] = 'addpost';
                header('Location: ../dashboard.php');
                exit();
            }
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../addpost.php');
        exit();
    }
    