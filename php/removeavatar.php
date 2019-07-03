<?php
    require_once '../conf/config.php';
    require_once '../conf/db.php';
    require_once 'initial_avatar.php';

    session_start();

    // Check for delete
    if (isset($_POST['delete'])) {
        $update_id = $_POST['update_id'];
        $name = $_SESSION['name'];
        $avatar_path = $_SESSION['avatar_path'];
        $avatar_status = 1;

        // Validate user
        if ($update_id == $_SESSION['id']) {
            // Store user id in session
            $_SESSION['update_id'] = $update_id;

            // Check avatar status
            if ($_SESSION['avatar_status'] == 1) {
                $_SESSION['error'] = 'defaultavatar';
                header('Location: ../edituser.php');
                exit();
            }

            // Generate initial avatar
            $name_first_char = $name[0];
            $path = AVATAR_PATH;
            $font = FONT_PATH;
            $target_path = create_avatar_image($name_first_char, $path, $font, $update_id);

             // Update avatar
            $query = "UPDATE avatars SET avatar_status = ?, avatar_path = ? WHERE user_id = ?";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $query)) {
                $_SESSION['error'] = 'sqlerror';
                header('Location: ../errors/502.php');
                exit();
            } else {
                mysqli_stmt_bind_param($stmt, 'isi', $avatar_status, $target_path, $update_id);
                mysqli_stmt_execute($stmt);

                // Get new avatar_path
                $query = "SELECT avatar_status, avatar_path FROM avatars WHERE user_id = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $query)) {
                    $_SESSION['error'] = 'sqlerror';
                    header('Location: ../errors/502.php');
                    exit();
                } else {
                    // Delete current avatar
                    if (file_exists($avatar_path)) {
                        if (!unlink($avatar_path)) {
                            $_SESSION['error'] = 'deletefile';
                            header('Location: ../edituser.php');
                            exit();
                        }
                    }
                    mysqli_stmt_bind_param($stmt, 'i', $update_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);

                    $_SESSION['avatar_path'] = $row['avatar_path'];
                    $_SESSION['avatar_status'] = $row['avatar_status'];

                    $_SESSION['success'] = 'deleteavatar';
                    header('Location: ../edituser.php');
                    exit();
                }
            }
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    } else {
        header('Location: ../index.php');
        exit();
    }