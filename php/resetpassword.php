<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'test_input.php';

    session_start();

    if (isset($_POST['reset_password_submit'])) {
        $selector = $_POST['selector'];
        $validator = $_POST['validator'];
        $password = test_input($_POST['password']);
        $password_repeat = test_input($_POST['password_repeat']);
        $current_date = date('U');

        if (empty($password) || empty($password_repeat)) {
            $_SESSION['error'] = 'pwdempty';
            header("Location: ../createpassword.php?selector=".$selector."&validator=".$validator);
            exit();
        } else if ($password != $password_repeat) {
            $_SESSION['error'] = 'pwdcheck';
            header("Location: ../createpassword.php?selector=".$selector."&validator=".$validator);
            exit();
        }

        $query = "SELECT * FROM pwd_reset WHERE selector = ? AND expires >= $current_date";
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
                $token_bin = hex2bin($validator);
                // Match the token inside database
                $token_check = password_verify($token_bin, $row['token']);

                if ($token_check === false) {
                    $_SESSION['error'] = 'submit';
                    header('Location: ../forgotpassword.php');
                    exit();
                } else if ($token_check === true) {
                    $token_email = $row['email'];

                    $query = "SELECT * FROM users WHERE email = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $query)) {
                        $_SESSION['error'] = 'sqlerror';
                        header('Location: ../errors/502.php');
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, 's', $token_email);
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
                                $new_pwd_hash = password_hash($password, PASSWORD_DEFAULT);
                                mysqli_stmt_bind_param($stmt, 'ss', $new_pwd_hash, $token_email);
                                mysqli_stmt_execute($stmt);

                                // Delete existing token
                                $query = "DELETE FROM pwd_reset WHERE email = ?";
                                $stmt = mysqli_stmt_init($conn);

                                if (!mysqli_stmt_prepare($stmt, $query)) {
                                    $_SESSION['error'] = 'sqlerror';
                                    header('Location: ../errors/502.php');
                                    exit();
                                } else {
                                    mysqli_stmt_bind_param($stmt, 's', $token_email);
                                    mysqli_stmt_execute($stmt);

                                    $_SESSION['success'] = 'passwordupdated';
                                    header("Location: ../index.php");
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