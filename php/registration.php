<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $name = test_input($_POST['name']);
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        $passwordRepeat = test_input($_POST['password-repeat']);

        // Check for empty fields
        if (empty($name) || empty($email) || empty($password) || empty($passwordRepeat)) {
            // Save correct data into fields
            header('Location: ../adduser.php?error=emptyfield&name='.$name.'&email='.$email);
            // Stop script
            exit();
        } else if (!preg_match('/^[a-zA-Z0-9]*$/', $name) && !filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check name and email
            header('Location: ../adduser.php?error=invalidnameandemail');
            exit();
        } else if (!preg_match('/^[a-zA-Z0-9]*$/', $name)) { // Check name
            header('Location: ../adduser.php?error=invalidname&email='.$email);
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check email
            header('Location: ../adduser.php?error=invalidemail&name='.$name);
            exit();
        } else if ($password !== $passwordRepeat) { // Compare passwords
            header('Location: ../adduser.php?error=passwordcheck&name='.$name.'&email='.$email);
            exit();
        } else {
            // Check if user already exist
            $query = "SELECT name, email FROM users WHERE name = ? OR email = ?";
            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../adduser.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'ss', $name, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt);

                if ($resultCheck > 0) {
                    header('Location: ../adduser.php?error=nameoremailtaken');
                    exit();
                } else {
                    // Insert new user into DB
                    $query = "INSERT INTO users(name, email, password) VALUES(?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        header('Location: ../adduser.php?error=sqlerror');
                        exit();
                    } else {
                        // Hash password
                        $passwordHashed = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $passwordHashed);
                        mysqli_stmt_execute($stmt);
                        header('Location: ../adduser.php?registration=success');
                        exit();
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../adduser.php');
        exit();
    }
