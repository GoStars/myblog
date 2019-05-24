<?php
    function createAvatarImage($string) {
        // Create avatar folder
        if (!is_dir('../images')) {
            mkdir('../images');
        }

        $imageFilePath = '../images/'.$string.'.png';

        // Base avatar image used to center text string on top of it
        $avatar = imagecreatetruecolor(60, 60);
        $bgColor = imagecolorallocate($avatar, 211, 211, 211);
        imagefill($avatar, 0, 0, $bgColor);
        $avatarTextColor = imagecolorallocate($avatar, 0, 0, 0);
        // Load the gd font
        $font = imageloadfont('../gd-files/gd-font.gdf');
        imagestring($avatar, $font, 10, 10, $string, $avatarTextColor);
        imagepng($avatar, $imageFilePath);
        imagedestroy($avatar);

        return $imageFilePath;
    }