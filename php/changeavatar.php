<?php
    if (isset($_POST['submit'])) {
        $avatar = $_FILES['avatar'];
        // print_r($avatar);
        $avatar_name = $avatar['name'];
        $avatar_tmp_name = $avatar['tmp_name'];
        $avatar_size = $avatar['size'];
        $avatar_err = $avatar['error'];
        $avatar_type = $avatar['type'];
        $avatar_ext = explode('.', $avatar_name);
        $avatar_actual_ext = strtolower(end($avatar_ext));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (in_array($avatar_actual_ext, $allowed)) {
            if ($avatar_err == 0) {
                if ($avatar_size < 10000) {
                    $new_avatar_name = uniqid('', true).'.'.$avatar_actual_ext;
                    $avatar_dir_name = dirname($_POST['avatar_path']);
                    $avatar_dest = $avatar_dir_name.'/'.$new_avatar_name;

                    move_uploaded_file($avatar_tmp_name, $avatar_dest);
                    header('Location: ../index.php?avatarupdate');
                } else {
                    echo 'File is to big!';
                }
            } else {
                echo 'There was an error!';
            }
        } else {
            echo 'Wrong file type!';
        }
    } else {
        header('Location: ../index.php');
        exit();
    }