<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';

    if (isset($_POST['reset-request-submit'])) {
        // Create tokens
        $selector = bin2hex(random_bytes(8));
        // Authenticate the user
        $token = random_bytes(32);
        // Create link
        $url = 'http://localhost/projects/myblog/createpassword.php?selector='.$selector.'&validator='.bin2hex($token);
        // Create token expiration date 
        $expires = date('U') + 1800;
        $userEmail = $_POST['email'];

        // Check for empty field
        if (empty($userEmail)) {
            header('Location: ../forgotpassword.php?error=emptyemail');
            // Stop script
            exit();
        } else if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { // Check email
            header('Location: ../forgotpassword.php?error=invalidemail');
            exit();
        } else {
            // Check if email exist
            $query = "SELECT email FROM users WHERE email = ?";
            // Create prepared statement
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                header('Location: ../errors/502.php?error=sqlerror');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 's', $userEmail);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $resultCheck = mysqli_stmt_num_rows($stmt);

                if ($resultCheck == 0) {
                    header('Location: ../forgotpassword.php?error=emailnotfound');
                } else {
                    // Delete existing token
                    $query = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        header('Location: ../errors/502.php?error=sqlerror');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 's', $userEmail);
                        mysqli_stmt_execute($stmt);
                    }

                    $query = "INSERT INTO pwdreset (pwdResetEmail, pwdResetSelector, pwdResetToken, pwdResetExpires) VALUES (?, ?, ?, ?)";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        header('Location: ../errors/502.php?error=sqlerror');
                        exit();
                    } else {
                        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
                        mysqli_stmt_bind_param($stmt, 'ssss', $userEmail, $selector, $hashedToken, $expires);
                        mysqli_stmt_execute($stmt);
                    }
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    // Send an e-mail
                    $to = $userEmail;
                    $subject = 'Password Reset';

                    $message = '<p>We recieved a password reset request. The link to reset your password make this request, you can ignore this email.</p>';
                    $message .= '<p>Here is your password reset link: </br>';
                    $message .= '<a href="'.$url.'">'.$url.'</a></p>';

                    $headers = "From: My Blog <myblog@gmail.com>\r\n";
                    $headers .= "Reply-To: <myblog@gmail.com>\r\n";
                    $headers .= "Content-type: text/html\r\n"; // Allow HTML

                    mail($to, $subject, $message, $headers);

                    header("Location: ../forgotpassword.php?success=reset");
                }
            }
        }
    } else {
        header("Location: ../forgotpassword.php");
    }
