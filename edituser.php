<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';
?>

<?php require 'inc/header.php'; ?>
    <?php
        // Get ID
        if (isset($_SESSION['update_id'])) {
            $id = $_SESSION['update_id'];
        } else {
            $id = $_GET['id'];
        }

        // Create Query
        $query = "SELECT id, name, email, password FROM users WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: ../errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_bind_result($stmt, $id, $name, $email, $password);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $post = mysqli_fetch_array($result);
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);

        unset($_SESSION['update_id']);
    ?>
    <?php if (isset($_SESSION['id'])) : ?>
        <?php if ($_SESSION['name'] == $post['name']) : ?>
            <div class="container">
                <h1>Profile Settings</h1>
                <hr>
                <h2>Change E-mail</h2>
                <?php 
                    if (isset($_SESSION['error'])) {
                        if ($_SESSION['error'] == 'emptyemail') {
                            echo '<p class="text-warning">E-mail is required!</p>';
                        } else if ($_SESSION['error'] == 'invalidemail') {
                            echo '<p class="text-warning">Invalid e-mail!</p>';
                        } else if ($_SESSION['error'] == 'emailtaken') {
                            echo '<p class="text-warning">E-mail already taken!</p>';
                        } else if ($_SESSION['error'] == 'emptyconfirmpassword') {
                            echo '<p class="text-warning">Password is required!</p>';
                        } else if ($_SESSION['error'] == 'wrongconfirmpassword') {
                            echo '<p class="text-warning">Wrong confirm password!</p>';
                        }
                    } else if (isset($_SESSION['success'])) {
                        if ($_SESSION['success'] == 'emailupdate') {
                            echo '<p class="text-success">E-mail edited successfully!</p>';
                        }
                    }
                ?>
                <!-- Change email -->
                <form method="POST" action="php/changeemail.php">
                    <div class="form-group">
                        <fieldset disabled="">
                            <label class="control-label" for="disabledInput">Name</label>
                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $post['name']; ?>" disabled="">
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input class="form-control" type="text" name="email" value="<?php echo $post['email']; ?>">
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input class="form-control" type="password" name="confirm_password">
                    </div>
                    <input type="hidden" name="update_id" value="<?php echo $post['id']; ?>">
                    <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                </form>

                <hr>

                <h2>Change Password</h2>

                <?php 
                    if (isset($_SESSION['error'])) {
                        if ($_SESSION['error'] == 'emptypassword') {
                            echo '<p class="text-warning">Fill in all fields!</p>';
                        } else if ($_SESSION['error'] == 'passwordcheck') {
                            echo '<p class="text-warning">Your passwords did not match!</p>';
                        } else if ($_SESSION['error'] == 'wrongoldpassword') {
                            echo '<p class="text-warning">Wrong old password!</p>';
                        }
                    } else if (isset($_SESSION['success'])) {
                        if ($_SESSION['success']  == 'passwordupdate') {
                            echo '<p class="text-success">Password edited successfully!</p>';
                        } 
                    }
                ?>

                <!-- Change password -->
                <form method="POST" action="php/changepassword.php">
                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label>Repeat Password</label>
                        <input class="form-control" type="password" name="password_repeat">
                    </div>
                    <div class="form-group">
                        <label>Old Password</label>
                        <input class="form-control" type="password" name="old_password">
                    </div>
                    <input type="hidden" name="update_id" value="<?php echo $post['id']; ?>">
                    <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                </form>

                <hr>

                <h2>Change Avatar Image</h2>

                <!-- Change avatar -->
                <form method="POST" action="php/changeavatar.php" enctype="multipart/form-data">
                    <input type="file" name="avatar">
                    <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                </form>

                <hr>

                <h2>Deactivate Account</h2>
            </div>
        <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
        <?php endif; ?>
    <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
    <?php endif; ?>
<?php require 'inc/footer.php'; ?>