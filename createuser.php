<?php require 'includes/header.php'; ?>
    <div class="container">
        <h1>Registration</h1>
        <?php 
            if (isset($_SESSION['error'])) {
                if ($_SESSION['error'] == 'emptyfield') {
                    echo '<p class="text-warning">Fill in all fields!</p>';
                } else if ($_SESSION['error'] == 'invalidnameandemail') {
                    echo '<p class="text-warning">Invalid name and e-mail!</p>';
                } else if ($_SESSION['error'] == 'invalidname') {
                    echo '<p class="text-warning">Invalid name!</p>';
                } else if ($_SESSION['error'] == 'invalidemail') {
                    echo '<p class="text-warning">Invalid e-mail!</p>';
                } else if ($_SESSION['error'] == 'passwordcheck') {
                    echo '<p class="text-warning">Your passwords did not match!</p>';
                } else if ($_SESSION['error'] == 'nameoremailtaken') {
                    echo '<p class="text-warning">Name or e-mail already taken!</p>';
                }
            }

            $name = (isset($_GET['name'])) ? $_GET['name'] : '';
            $email = (isset($_GET['email'])) ? $_GET['email'] : '';
        ?>
        <form method="POST" action="actions/auth/registration.php">
            <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" name="name" value="<?php echo $name; ?>">
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input class="form-control" type="text" name="email" value="<?php echo $email; ?>">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
            </div>
            <div class="form-group">
                <label>Repeat Password</label>
                <input class="form-control" type="password" name="password_repeat">
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Submit">
        </form>
    </div>
<?php require 'includes/footer.php'; ?>