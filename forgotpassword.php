<?php require 'inc/header.php'; ?>
    <div class="container">
        <h1>Reset Password</h1>
        <?php
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'submit') {
                    echo '<p class="text-warning">Re-submit your reset request!</p>';
                } else if ($_GET['error'] == 'emptyemail') {
                    echo '<p class="text-warning">E-mail is required!</p>';
                } else if ($_GET['error'] == 'invalidemail') {
                    echo '<p class="text-warning">Invalid e-mail!</p>';
                } else if ($_GET['error'] == 'emailnotfound') {
                    echo '<p class="text-warning">E-mail not found!</p>';
                }
            } else if (isset($_GET['success'])) {
                if ($_GET['success'] == 'reset') {
                    echo '<p class="text-success">Check your e-mail!</p>';
                }
            }
        ?>

        <p>An e-mail will be send to you with instructions on how to reset your password.</p>

        <form method="POST" action="php/resetrequest.php">
            <div class="form-group">
                <input class="form-control" type="text" name="email" placeholder="Enter your e-mail address">
            </div>
            <input class="btn btn-primary" type="submit" name="reset-request-submit" value="Submit">
        </form>
    </div>
<?php require 'inc/footer.php'; ?>