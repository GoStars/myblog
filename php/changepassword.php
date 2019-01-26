<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = $_POST['update_id'];
        $new_password = test_input($_POST['new_password']);
        $passwordRepeat = test_input($_POST['password-repeat']);
        $old_password = test_input($_POST['old_password']);

        // Store post id in session
        session_start();
        $_SESSION['update_id'] = $update_id;

        // Check for empty fields
        if (empty($new_password) || empty($passwordRepeat) || empty($old_password)) {
            // Save correct data into fields
            header('Location: ../edituser.php?error=emptypassword');
            // Stop script
            exit();
        } else if ($new_password !== $passwordRepeat) { // Compare passwords
            header('Location: ../edituser.php?error=passwordcheck');
            exit();
        } else {
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
                $passwordCheck = password_verify($old_password, $row['password']);

                if ($passwordCheck == false) {
                    header('Location: ../edituser.php?error=wrongconfirmpassword');
                    exit();
                } else {
                    // Update password
                    $query = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        header('Location: ../edituser.php?error=sqlerror');
                        exit();
                    } else {
                        // Hash password
                        $passwordHashed = password_hash($new_password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'si', $passwordHashed, $update_id);
                        mysqli_stmt_execute($stmt);
                        header('Location: ../edituser.php?success=passwordupdate');
                        exit();
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
