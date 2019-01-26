<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $email = test_input($_POST['email']);
        $confirm_password = test_input($_POST['confirm_password']);

        // Store post id in session
        session_start();
        $_SESSION['update_id'] = $update_id;

        // Check for empty fields
        if (empty($email)) {
            // Save correct data into fields
            header('Location: ../edituser.php?error=emptyemail');
            // Stop script
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check email
            header('Location: ../edituser.php?error=invalidemail');
            exit();
        } else if (empty($confirm_password)) {
            header('Location: ../edituser.php?error=emptyconfirmpassword');
            exit();
        } else {
            // Check if email already exist
            $query = "SELECT email FROM users WHERE email = ?";
            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../edituser.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 's', $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt);

                $query = "SELECT password FROM users WHERE id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    header('Location: ../index.php?error=sqlerror');
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "i", $update_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    // Check if passwords match
                    $passwordCheck = password_verify($confirm_password, $row['password']);

                    if ($resultCheck > 0) {
                        header('Location: ../edituser.php?error=emailtaken');
                        exit();
                    } else if ($passwordCheck == false) {
                        header('Location: ../edituser.php?error=wrongconfirmpassword');
                        exit();
                    } else {
                        // Update email
                        $query = "UPDATE users SET email = ? WHERE id = ?";
                        $stmt = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt, $query)) {
                            header('Location: ../edituser.php?error=sqlerror');
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, 'si', $email, $update_id);
                            mysqli_stmt_execute($stmt);
                            header('Location: ../edituser.php?success=emailupdate');
                            exit();
                        }
                    }
                }
            } 
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../edituser.php');
        exit();
    }
