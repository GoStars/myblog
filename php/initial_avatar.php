<?php
    function createAvatarImage($string, $path, $font) {
        // Create avatar folder
        if (!is_dir($path)) {
            mkdir($path);
        }

        $imageFilePath = $path.$string.'.png';

        // Base avatar image used to center text string on top of it
        $avatar = imagecreatetruecolor(60, 60);
        $bgColor = imagecolorallocate($avatar, 211, 211, 211);
        imagefill($avatar, 0, 0, $bgColor);
        $avatarTextColor = imagecolorallocate($avatar, 0, 0, 0);
        // Load the gd font
        $fontFilePath = imageloadfont($font);
        imagestring($avatar, $fontFilePath, 10, 10, $string, $avatarTextColor);
        imagepng($avatar, $imageFilePath);
        imagedestroy($avatar);

        return $imageFilePath;
    }
    