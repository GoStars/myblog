<?php
    function create_avatar_image($string, $path, $font, $user_id) {
        // Create avatar folder
        if (!is_dir(str_replace('../../', '../', $path))) {
            mkdir($path, null, true);
        }

        $image_file_path = $path.'profile'.$user_id.'.png';
        // Base avatar image used to center text string on top of it
        $avatar = imagecreatetruecolor(60, 60);
        $bg_color = imagecolorallocate($avatar, 211, 211, 211);
        imagefill($avatar, 0, 0, $bg_color);
        $avatar_text_color = imagecolorallocate($avatar, 0, 0, 0);
        // Load the gd font
        $font_file_path = imageloadfont($font);
        imagestring($avatar, $font_file_path, 10, 10, $string, $avatar_text_color);
        imagepng($avatar, $image_file_path);
        imagedestroy($avatar);

        return $image_file_path;
    }
    