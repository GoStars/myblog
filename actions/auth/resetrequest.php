<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';

    session_start();

    if (isset($_POST['reset_password_submit'])) {
        // Create tokens
        $selector = bin2hex(random_bytes(8));
        // Authenticate the user
        $token = random_bytes(32);
        // Create link
        $url = 'http://localhost/projects/myblog/newpwd.php?selector='.$selector.'&validator='.bin2hex($token);
        // Create token expiration date 
        $expires = date('U') + 1800;
        $user_email = $_POST['email'];

        // Check for empty field
        if (empty($user_email)) {
            $_SESSION['error'] = 'emptyemail';
            header('Location: ../../resetpwd.php');
            // Stop script
            exit();
        } else if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) { // Check email
            $_SESSION['error'] = 'invalidemail';
            header('Location: ../../resetpwd.php');
            exit();
        } else {
            // Check if email exist
            $query = "SELECT email FROM users WHERE email = ?";
            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../../errors/502.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 's', $user_email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $result_check = mysqli_stmt_num_rows($stmt);

                if ($result_check == 0) {
                    $_SESSION['error'] = 'emailnotfound';
                    header('Location: ../../resetpwd.php');
                } else {
                    // Delete existing token
                    $query = "DELETE FROM pwd_reset WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        $_SESSION['error'] = 'sqlerror';
                        header('Location: ../../errors/502.php');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 's', $user_email);
                        mysqli_stmt_execute($stmt);
                    }

                    $query = "INSERT INTO pwd_reset(email, selector, token, expires) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        $_SESSION['error'] = 'sqlerror';
                        header('Location: ../../errors/502.php');
                        exit();
                    } else {
                        $hashed_token = password_hash($token, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'ssss', $user_email, $selector, $hashed_token, $expires);
                        mysqli_stmt_execute($stmt);
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    // Send an e-mail
                    $to = $user_email;
                    $subject = 'Password Reset';

                    $message = '<p>We recieved a password reset request. The link to reset your password make this request, you can ignore this email.</p>';
                    $message .= '<p>Here is your password reset link: </br>';
                    $message .= '<a href="'.$url.'">'.$url.'</a></p>';

                    $headers = "From: My Blog <myblog@gmail.com>\r\n";
                    $headers .= "Reply-To: <myblog@gmail.com>\r\n";
                    $headers .= "Content-type: text/html\r\n"; // Allow HTML

                    mail($to, $subject, $message, $headers);

                    $_SESSION['success'] = 'reset';
                    header("Location: ../../resetpwd.php");
                }
            }
        }
    } else {
        header("Location: ../../resetpwd.php");
    }
