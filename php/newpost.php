<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $title = test_input($_POST['title']);
        $author = $_POST['author'];
        $description = test_input($_POST['description']);
        $body = test_input($_POST['body']);

        // Check for empty fields
        if (empty($title) || empty($description) || empty($body)) {
            // Save correct data into fields
            header('Location: ../addpost.php?error=emptypostfield&title='.$title.'&description='.$description.'&body='.$body);
            // Stop script
            exit();
        } else {
            $query = "INSERT INTO posts(title, author, description, body) VALUES(?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../index.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'ssss', $title, $author, $description, $body);
                mysqli_stmt_execute($stmt);
                header('Location: ../dashboard.php?success=addpost');
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
    