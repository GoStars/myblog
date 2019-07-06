<?php
    require_once '../config/globals.php';
    require_once '../config/db.php';
    require_once 'create_users_table.php';
    require_once 'create_avatars_table.php';
    require_once 'create_posts_table.php';
    require_once 'create_password_reset_table.php';

    function up($conn) {
        users($conn);
        avatars($conn);
        posts($conn);
        pwd_reset($conn);
        mysqli_close($conn);
    }

    function down($conn) {
        $query = "DROP TABLE IF EXISTS avatars, posts, pwd_reset, users";

        if (mysqli_query($conn, $query)) {
            echo 'Tables deleted successfully'.PHP_EOL;
        } else {
            echo 'Error deleting tables: '.mysqli_error($conn).PHP_EOL;
        }
    }

    // CLI
    if (isset($argc)) {
        if ($argc == 2) {
            if ($argv[1] == '-up') {
                // Create tables
                up($conn);
            } else if ($argv[1] == '-down') {
                // Drop all tables
                down($conn);
            } else {
                echo 'Wrong argument!';
            }
        } else {
            echo 'Must be one argument!';
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
    
