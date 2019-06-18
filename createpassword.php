<?php require 'inc/header.php'; ?>
    <div class="container">
        <h1>Create New Password</h1>
        <?php 
            $selector = $_GET['selector'];
            $validator = $_GET['validator'];

            if (isset($_SESSION['error'])) {
                if ($_SESSION['error'] == 'pwdempty') {
                    echo '<p class="text-warning">Fill in all fields!</p>';
                } else if ($_SESSION['error'] == 'pwdcheck') {
                    echo '<p class="text-warning">Your passwords did not match!</p>';
                } else if ($_SESSION['error'] == 'fetch') {
                    echo '<p class="text-warning">There was an error!</p>';
                }
            }

            if (strlen($selector) !== 16 || strlen($validator) !== 64) {
                $_SESSION['error'] == 'validation';
                header('Location: index.php');
            } else {
                if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) {
                    echo '<form method="POST" action="php/resetpassword.php">
                    <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" type="password" name="password">
                    </div>
                    <div class="form-group">
                    <label>Repeat Password</label>
                    <input class="form-control" type="password" name="password_repeat">
                    </div>
                    <input type="hidden" name="selector" value="'.$selector.'">
                    <input type="hidden" name="validator" value="'.$validator.'">
                    <input class="btn btn-primary" type="submit" name="reset_password_submit" value="Submit">
                    </form>';
                } else {
                    $_SESSION['error'] == 'validation';
                    header('Location: index.php');
                }
            }
        ?>
    </div>
<?php require 'inc/footer.php'; ?>