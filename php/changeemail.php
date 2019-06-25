<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    session_start();

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $email = test_input($_POST['email']);
        $confirm_password = test_input($_POST['confirm_password']);
        
        // Validate user
        if ($update_id == $_SESSION['id']) {
            // Store user id in session
            $_SESSION['update_id'] = $update_id;
            // Check for empty input
            if (empty($email)) {
                $_SESSION['error'] = 'emptyemail';
                header('Location: ../edituser.php');
                // Stop script
                exit();
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check email
                $_SESSION['error'] = 'invalidemail';
                header('Location: ../edituser.php');
                exit();
            } else if (empty($confirm_password)) {
                $_SESSION['error'] = 'emptyconfirmpassword';
                header('Location: ../edituser.php');
                exit();
            } else {
                // Check if email already exist
                $query = "SELECT email FROM users WHERE email = ?";
                // Create prepared statement
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    $_SESSION['error'] = 'sqlerror';
                    header('Location: ../errors/502.php');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $email);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    $result_check = mysqli_stmt_num_rows($stmt);

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
                        $password_check = password_verify($confirm_password, $row['password']);

                        if ($result_check > 0) {
                            $_SESSION['error'] = 'emailtaken';
                            header('Location: ../edituser.php');
                            exit();
                        } else if ($password_check == false) {
                            $_SESSION['error'] = 'wrongconfirmpassword';
                            header('Location: ../edituser.php');
                            exit();
                        } else {
                            // Update email
                            $query = "UPDATE users SET email = ? WHERE id = ?";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $query)) {
                                $_SESSION['error'] = 'sqlerror';
                                header('Location: ../errors/502.php');
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, 'si', $email, $update_id);
                                mysqli_stmt_execute($stmt);

                                $_SESSION['success'] = 'emailupdate';
                                header('Location: ../edituser.php');
                                exit();
                            }
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
