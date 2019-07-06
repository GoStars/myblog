<?php
    function users($conn) {
        $query = "CREATE TABLE users (
            id int(11) NOT NULL AUTO_INCREMENT,
            name varchar(30) NOT NULL,
            email varchar(150) NOT NULL,
            password varchar(255) NOT NULL,
            user_status int(11) NOT NULL DEFAULT '1',
            PRIMARY KEY (id)
        )";

        if (mysqli_query($conn, $query)) {
            echo 'Table users created successfully!'.PHP_EOL;
        } else {
            echo 'Error creating table: '.mysqli_error($conn).PHP_EOL;
        }
    }
