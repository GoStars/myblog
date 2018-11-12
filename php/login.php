<?php
    require '../conf/config.php';
    require '../conf/db.php';

    // Check for submit
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            header('Location: ../index.php?error=emptyfields');
            exit();
        } else {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../index.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                // Check result from DB
                if ($row = mysqli_fetch_assoc($result)) {
                    // Check if passwords match
                    $passwordCheck = password_verify($password, $row['password']);

                    if ($passwordCheck == false) {
                        header('Location: ../index.php?error=wrongpassword');
                        exit();
                    } else if ($passwordCheck == true) {
                        // Create session
                        session_start();
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['email'] = $row['email'];

                        header('Location: ../index.php?success=login');
                        exit();
                    } else {
                        header('Location: ../index.php?error=wrongpassword');
                        exit();
                    }
                } else {
                    header('Location: ../index.php?error=nouser');
                    exit();
                }
            }
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
    