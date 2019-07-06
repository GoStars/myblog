<?php
    function pwd_reset($conn) {
        $query = "CREATE TABLE pwd_reset (
            id int(11) NOT NULL AUTO_INCREMENT,
            email text NOT NULL,
            selector text NOT NULL,
            token longtext NOT NULL,
            expires text NOT NULL,
            PRIMARY KEY (id)
        )";

        if (mysqli_query($conn, $query)) {
            echo 'Table pwd_reset created successfully'.PHP_EOL;
        } else {
            echo 'Error creating table: '.mysqli_error($conn).PHP_EOL;
        }
    }
