<?php
    function posts($conn) {
        $query = "CREATE TABLE posts (
            id int(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            title varchar(25) NOT NULL,
            description varchar(150) DEFAULT NULL,
            body text NOT NULL,
            created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY user_id (user_id),
            FULLTEXT KEY body (body),
            CONSTRAINT posts_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id)
        )";

        if (mysqli_query($conn, $query)) {
            echo 'Table posts created successfully'.PHP_EOL;
        } else {
            echo 'Error creating table: '.mysqli_error($conn).PHP_EOL;
        }
    }
