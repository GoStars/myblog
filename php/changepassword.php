<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    session_start();

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $new_password = test_input($_POST['new_password']);
        $password_repeat = test_input($_POST['password_repeat']);
        $old_password = test_input($_POST['old_password']);

        // Validate user
        if ($update_id == $_SESSION['id']) {
            // Store user id in session
            $_SESSION['update_id'] = $update_id;

            // Check for empty fields
            if (empty($new_password) || empty($password_repeat) || empty($old_password)) {
                $_SESSION['error'] = 'emptypassword';
                header('Location: ../edituser.php');
                // Stop script
                exit();
            } else if ($new_password !== $password_repeat) { // Compare passwords
                $_SESSION['error'] = 'passwordcheck';
                header('Location: ../edituser.php');
                exit();
            } else {
                $query = "SELECT password FROM users WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    $_SESSION['error'] = 'sqlerror';
                    header('Location: ../errors/502.php');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $update_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    // Check if passwords match
                    $password_check = password_verify($old_password, $row['password']);

                    if ($password_check == false) {
                        $_SESSION['error'] = 'wrongoldpassword';
                        header('Location: ../edituser.php');
                        exit();
                    } else {
                        // Update password
                        $query = "UPDATE users SET password = ? WHERE id = ?";
                        $stmt = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt, $query)) {
                            $_SESSION['error'] = 'sqlerror';
                            header('Location: ../errors/502.php');
                            exit();
                        } else {
                            // Hash password
                            $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                            mysqli_stmt_bind_param($stmt, 'si', $password_hashed, $update_id);
                            mysqli_stmt_execute($stmt);

                            $_SESSION['success'] = 'passwordupdate';
                            header('Location: ../edituser.php');
                            exit();
                        }
                    }
                }
            }
        } else {
            $_SESSION['error'] = 'usernotfound';
            header('Location: ../index.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../edituser.php');
        exit();
    }
