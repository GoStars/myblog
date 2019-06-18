<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';

    // Create session
    session_start();

    // Check for submit
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'emptyfields';
            header('Location: ../index.php');
            exit();
        } else {
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../errors/502.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 's', $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                // Check result from DB
                if ($row = mysqli_fetch_assoc($result)) {
                    // Check if passwords match
                    $password_check = password_verify($password, $row['password']);

                    if ($password_check == false) {
                        $_SESSION['error'] = 'wrongpassword';
                        header('Location: ../index.php');
                        exit();
                    } else if ($password_check == true) {
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['email'] = $row['email'];
                        
                        $_SESSION['success'] = 'login';
                        header('Location: ../index.php');
                        exit();
                    } else {
                        $_SESSION['error'] = 'wrongpassword';
                        header('Location: ../index.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'nouser';
                    header('Location: ../index.php');
                    exit();
                }
            }
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
    