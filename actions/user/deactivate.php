<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';
    require_once '../../functions/test_input.php';

    session_start();

    if (isset($_POST['deactivate'])) {
        $update_id = $_POST['update_id'];
        $confirm_password = test_input($_POST['confirm_password']);
        $user_status = 0;

        // Validate user
        if ($update_id == $_SESSION['id']) {
            // Store user id in session
            $_SESSION['update_id'] = $update_id;
            
            if (empty($confirm_password)) {
                $_SESSION['error'] = 'deactivateemptyconfirmpassword';
                header('Location: ../../updateuser.php');
                exit();
            } else {
                $query = "SELECT password FROM users WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    $_SESSION['error'] = 'sqlerror';
                    header('Location: ../../errors/502.php');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $update_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    // Check if passwords match
                    $password_check = password_verify($confirm_password, $row['password']);

                    if ($password_check == false) {
                        $_SESSION['error'] = 'deactivatewrongconfirmpassword';
                        header('Location: ../../updateuser.php');
                        exit();
                    } else {
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
                            $_SESSION['success'] = 'deactivateaccount';
                            header('Location: ../../index.php');
                            exit();
                        }
                    }
                }
            }
        } else {
            $_SESSION['error'] = 'usernotfound';
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
