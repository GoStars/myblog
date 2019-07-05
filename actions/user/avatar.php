<?php
    require_once '../../config/globals.php';
    require_once '../../config/db.php';

    session_start();

    if (isset($_POST['submit'])) {
        $update_id = $_POST['update_id'];
        $avatar_path = '../'.$_SESSION['avatar_path'];
        $avatar = $_FILES['avatar'];
        $avatar_name = $avatar['name'];
        $avatar_tmp_name = $avatar['tmp_name'];
        $avatar_size = $avatar['size'];
        $avatar_err = $avatar['error'];
        $avatar_type = $avatar['type'];
        $avatar_ext = explode('.', $avatar_name);
        $avatar_actual_ext = strtolower(end($avatar_ext));
        $allowed = ['jpg', 'jpeg', 'png'];
        $avatar_status = 0;

        // Validate user
        if ($update_id == $_SESSION['id']) {
            // Store user id in session
            $_SESSION['update_id'] = $update_id;

            if ($avatar_err == 0) {
                if (in_array($avatar_actual_ext, $allowed)) {
                    if ($avatar_size < 10000) {
                        $new_avatar_name = 'profile'.$update_id.'.'.$avatar_actual_ext;
                        $avatar_dir_name = dirname($avatar_path);
                        $avatar_dest = $avatar_dir_name.'/'.$new_avatar_name;

                        // Update avatar
                        $query = "UPDATE avatars SET avatar_status = ?, avatar_path = ? WHERE user_id = ?";
                        $stmt = mysqli_stmt_init($conn);

                        if (!mysqli_stmt_prepare($stmt, $query)) {
                            $_SESSION['error'] = 'sqlerror';
                            header('Location: ../../errors/502.php');
                            exit();
                        } else {
                            mysqli_stmt_bind_param($stmt, 'isi', $avatar_status, $avatar_dest, $update_id);
                            mysqli_stmt_execute($stmt);

                            // Get new avatar_path
                            $query = "SELECT avatar_status, avatar_path FROM avatars WHERE user_id = ?";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $query)) {
                                $_SESSION['error'] = 'sqlerror';
                                header('Location: ../../errors/502.php');
                                exit();
                            } else {
                                // Delete old avatar
                                if (file_exists($avatar_path)) {
                                    if (!unlink($avatar_path)) {
                                        $_SESSION['error'] = 'deletefile';
                                        header('Location: ../../updateuser.php');
                                        exit();
                                    }
                                }
                                move_uploaded_file($avatar_tmp_name, $avatar_dest);
                                
                                mysqli_stmt_bind_param($stmt, 'i', $update_id);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                $row = mysqli_fetch_assoc($result);

                                $_SESSION['avatar_path'] = str_replace('../../', '../', $row['avatar_path']);
                                $_SESSION['avatar_status'] = $row['avatar_status'];

                                $_SESSION['success'] = 'avatarupdate';
                                header('Location: ../../updateuser.php');
                                exit();
                            }
                        }
                    } else {
                        $_SESSION['error'] = 'filetoobig';
                        header('Location: ../../updateuser.php');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = 'wrongfiletype';
                    header('Location: ../../updateuser.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = 'avatarerror';
                $_SESSION['error_code'] = $avatar_err;
                header('Location: ../../updateuser.php');
                exit();
            } 
        } else {
            $_SESSION['error'] = 'usernotfound';
            header('Location: ../../index.php');
            exit();
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../../index.php');
        exit();
    }