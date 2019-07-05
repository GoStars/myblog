<?php require 'includes/header.php'; ?>
    <?php if (isset($_SESSION['id'])) : ?>
        <div class="container">
            <div class="card border-primary">
                <div class="card-header">Account Activation</div>
                <div class="card-body">
                    <p class="card-text">Your account is deactivated! To activate it click button below.</p>
                    <form method="POST" action="actions/user/activate.php">
                        <input class="btn btn-primary" type="submit" name="activate" value="Activate">
                    </form>
                </div>
            </div>
        </div>
    <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
    <?php endif; ?>
<?php require 'includes/footer.php'; ?>