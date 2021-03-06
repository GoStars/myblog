<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';

    // Create session
    session_start();

    // Check for submit
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'emptyfields';
            header('Location: ../../index.php');
            exit();
        } else {
            $query = "SELECT u.*, a.avatar_path, a.avatar_status FROM users as u 
                INNER JOIN avatars as a ON a.user_id = u.id 
                WHERE u.email = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../../errors/502.php');
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
                        header('Location: ../../index.php');
                        exit();
                    } else if ($password_check == true) {
                        $user_status = $row['user_status'];
                        $_SESSION['id'] = $row['id'];

                        if ($user_status != 1) {
                            header('Location: ../../activateuser.php');
                            exit();
                        }
                        
                        $_SESSION['name'] = $row['name'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['user_status'] =  $user_status;
                        $_SESSION['avatar_path'] = str_replace('../../', '../', $row['avatar_path']);
                        $_SESSION['avatar_status'] = $row['avatar_status'];
                        
                        $_SESSION['success'] = 'login';
                        header('Location: ../../index.php');
                        exit();
                    } else {
                        $_SESSION['error'] = 'wrongpassword';
                        header('Location: ../../index.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'nouser';
                    header('Location: ../../index.php');
                    exit();
                }
            }
        }
    } else {
        header('Location: ../../index.php');
        exit();
    }
    