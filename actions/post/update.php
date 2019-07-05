<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';
    require_once '../../functions/test_input.php';

    session_start();

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $title = test_input($_POST['title']);
        $description = test_input($_POST['description']);
        $body = test_input($_POST['body']);
        $id = $_SESSION['id'];

        $query = "SELECT id FROM posts WHERE user_id = '$id'";

        // Get Result
        $result = mysqli_query($conn, $query);

        // Fetch Data
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Get post IDs
        foreach ($posts as $post) {
            foreach ($post as $key => $value) {
                $arr[] = $value;
            }
        }

        // Check array
        if (in_array($update_id, $arr)) {
            // Check for empty fields
            if (empty($title) || empty($description) || empty($body)) {
                // Store post id in session
                $_SESSION['update_id'] = $update_id;

                $_SESSION['error'] = 'emptyeditpostfield';
                header('Location: ../../updatepost.php');
                // Stop script
                exit();
            } else {
                $query = "UPDATE posts SET title = ?, description = ?, body = ? WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    $_SESSION['error'] = 'sqlerror';
                    header('Location: ../../errors/502.php');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, 'sssi', $title, $description, $body, $update_id);
                    mysqli_stmt_execute($stmt);

                    $_SESSION['success'] = 'editpost';
                    header('Location: ../../dashboard.php');
                    exit();
                }        
            }
        } else {
            $_SESSION['error'] = 'postnotfound';
            header('Location: ../../dashboard.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn); 
    } else {
        header('Location: ../../index.php');
        exit();
    }

 