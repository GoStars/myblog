<?php
    // Database connections
    // Production
    $url = getenv('JAWSDB_URL');
    $dbparts = parse_url($url);

    if (!empty($url)) {
        define('DB_HOST', $dbparts['host']);
        define('DB_USER', $dbparts['user']);
        define('DB_PASS', $dbparts['pass']);
        define('DB_NAME', ltrim($dbparts['path'], '/'));
    } else {
        // Local
        define('DB_HOST', '');
        define('DB_USER', '');
        define('DB_PASS', '');
        define('DB_NAME', '');
    }

    // URLs
    define('ROOT_URL', '/projects/myblog/');

    // Paths
    define('AVATAR_PATH', '../../public/images/profile/');
    define('FONT_PATH', '../../public/fonts/gd-files/gd-font.gdf');
