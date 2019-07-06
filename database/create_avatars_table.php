<?php
    function avatars($conn) {
        $query = "CREATE TABLE avatars (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            avatar_status int(11) NOT NULL DEFAULT '1',
            avatar_path varchar(100) NOT NULL,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            CONSTRAINT avatars_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id)
        )";

        if (mysqli_query($conn, $query)) {
            echo 'Table avatars created successfully'.PHP_EOL;
        } else {
            echo 'Error creating table: '.mysqli_error($conn).PHP_EOL;
        }
    }
