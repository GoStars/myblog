<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    session_start();

    if (isset($_POST['reset-password-submit'])) {
        $selector = $_POST['selector'];
        $validator = $_POST['validator'];
        $password = test_input($_POST['password']);
        $passwordRepeat = test_input($_POST['password-repeat']);
        $currentDate = date('U');

        if (empty($password) || empty($passwordRepeat)) {
            $_SESSION['error'] = 'pwdempty';
            header("Location: ../createpassword.php?selector=".$selector."&validator=".$validator);
            exit();
        } else if ($password != $passwordRepeat) {
            $_SESSION['error'] = 'pwdcheck';
            header("Location: ../createpassword.php?selector=".$selector."&validator=".$validator);
            exit();
        }

        $query = "SELECT * FROM pwdreset WHERE pwdResetSelector = ? AND pwdResetExpires >= $currentDate";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: ../errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 's', $selector);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (!$row = mysqli_fetch_assoc($result)) {
                $_SESSION['error'] = 'submit';
                header('Location: ../forgotpassword.php');
                exit();
            } else {
                // Convert validator token into binary
                $tokenBin = hex2bin($validator);
                // Match the token inside database
                $tokenCheck = password_verify($tokenBin, $row['pwdResetToken']);

                if ($tokenCheck === false) {
                    $_SESSION['error'] = 'submit';
                    header('Location: ../forgotpassword.php');
                    exit();
                } else if ($tokenCheck === true) {
                    $tokenEmail = $row['pwdResetEmail'];

                    $query = "SELECT * FROM users WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        $_SESSION['error'] = 'sqlerror';
                        header('Location: ../errors/502.php');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 's', $tokenEmail);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (!$row = mysqli_fetch_assoc($result)) {
                            $_SESSION['error'] = 'fetch';
                            header("Location: ../createpassword.php?selector=".$selector."&validator=".$validator);
                            exit();
                        } else {
                            $query =  "UPDATE users SET password = ? WHERE email = ?";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $query)) {
                                $_SESSION['error'] = 'sqlerror';
                                header('Location: ../errors/502.php');
                                exit();
                            } else {
                                $newPwdHash = password_hash($password, PASSWORD_DEFAULT);
                                mysqli_stmt_bind_param($stmt, 'ss', $newPwdHash, $tokenEmail);
                                mysqli_stmt_execute($stmt);

                                // Delete existing token
                                $query = "DELETE FROM pwdreset WHERE pwdResetEmail = ?";
                                $stmt = mysqli_stmt_init($conn);

                                if (!mysqli_stmt_prepare($stmt, $query)) {
                                    $_SESSION['error'] = 'sqlerror';
                                    header('Location: ../errors/502.php');
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, 's', $tokenEmail);
                                    mysqli_stmt_execute($stmt);

                                    $_SESSION['success'] = 'passwordupdated';
                                    header("Location: ../index.php?success=passwordupdated");
                                }
                            }
                        }
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        header("Location: ../index.php");
    }