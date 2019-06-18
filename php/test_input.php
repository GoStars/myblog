<?php
    function test_input($data) {
        // Strip unnecessary characters
        $data = trim($data);
        // Remove backslashes
        $data = stripslashes($data);
        // HTML escaped code
        $data = htmlspecialchars($data);
        
        return $data;
    }