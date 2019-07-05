<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';
    require_once '../../functions/test_input.php';
    require_once '../../functions/initial_avatar.php';

    session_start();

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $name = test_input($_POST['name']);
        $email = test_input($_POST['email']);
        $password = test_input($_POST['password']);
        $password_repeat = test_input($_POST['password_repeat']);
        
        // Check for empty fields
        if (empty($name) || empty($email) || empty($password) || empty($password_repeat)) {
            $_SESSION['error'] = 'emptyfield';
            // Save correct data into fields
            header('Location: ../../createuser.php?name='.$name.'&email='.$email);
            // Stop script
            exit();
        } else if (!preg_match('/^[a-zA-Z0-9]*$/', $name) && !filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check name and email
            $_SESSION['error'] = 'invalidnameandemail';
            header('Location: ../../createuser.php');
            exit();
        } else if (!preg_match('/^[a-zA-Z0-9]*$/', $name)) { // Check name
            $_SESSION['error'] = 'invalidname';
            header('Location: ../../createuser.php?email='.$email);
            exit();
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check email
            $_SESSION['error'] = 'invalidemail';
            header('Location: ../../createuser.php?name='.$name);
            exit();
        } else if ($password !== $password_repeat) { // Compare passwords
            $_SESSION['error'] = 'passwordcheck';
            header('Location: ../../createuser.php?name='.$name.'&email='.$email);
            exit();
        } else {
            // Check if user already exist
            $query = "SELECT name, email FROM users WHERE name = ? OR email = ?";
            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../../errors/502.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'ss', $name, $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $result_check = mysqli_stmt_num_rows($stmt);

                if ($result_check > 0) {
                    $_SESSION['error'] = 'nameoremailtaken';
                    header('Location: ../../createuser.php');
                    exit();
                } else {
                    // Insert new user into DB
                    $query = "INSERT INTO users(name, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        $_SESSION['error'] = 'sqlerror';
                        header('Location: ../../errors/502.php');
                        exit();
                    } else {
                        // Hash password
                        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $password_hashed);
                        mysqli_stmt_execute($stmt);

                        // Get new user id
                        $query = "SELECT id FROM users WHERE name = ?";
                        $stmt = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt, $query)) {
                            $_SESSION['error'] = 'sqlerror';
                            header('Location: ../../errors/502.php');
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, 's', $name);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $row = mysqli_fetch_assoc($result);

                            $user_id = $row['id'];
                            
                            // Generate initial avatar
                            $name_first_char = $name[0];
                            $path = AVATAR_PATH;
                            $font = FONT_PATH;
                            $target_path = create_avatar_image($name_first_char, $path, $font, $user_id);

                            $query = "INSERT INTO avatars(user_id, avatar_path) VALUES (?, ?)";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $query)) {
                                $_SESSION['error'] = 'sqlerror';
                                header('Location: ../../errors/502.php');
                                exit();
                            } else {
                                mysqli_stmt_bind_param($stmt, 'is', $user_id, $target_path);
                                mysqli_stmt_execute($stmt);

                                $_SESSION['success'] = 'registration';
                                header('Location: ../../index.php');
                                exit();
                            }
                        }
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../../createuser.php');
        exit();
    }
