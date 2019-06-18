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
        $query = "SELECT p.id, p.title, p.description, p.body, u.name 
            FROM posts AS p 
            INNER JOIN users AS u ON p.user_id = u.id 
            WHERE p.id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: ../errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_bind_result($stmt, $id, $title, $description, $body, $name);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $post = mysqli_fetch_array($result);
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    ?>
    <!-- Check if user has rights to editing post -->
    <?php if (isset($_SESSION['id'])) : ?>
        <?php if ($_SESSION['name'] == $post['name']) : ?>
            <div class="container">
                <h1>Edit Post</h1>
                <?php
                    if (isset($_SESSION['error'])) {
                        if ($_SESSION['error'] == 'emptyeditpostfield') {
                            echo '<p class="text-warning">Fill in all fields!</p>';
                        }
                    }
                ?>
                <form method="POST" action="php/changepost.php">
                    <div class="form-group">
                        <label>Title</label>
                        <input class="form-control" type="text" name="title" value="<?php echo $post['title']; ?>">
                    </div>
                    <div class="form-group">
                        <fieldset disabled="">
                            <label class="control-label" for="disabledInput">Author</label>
                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $post['name']; ?>" disabled="">
                        </fieldset>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"><?php echo $post['description']; ?></textarea> 
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea class="form-control" name="body"><?php echo $post['body']; ?></textarea> 
                    </div>
                    <input type="hidden" name="update_id" value="<?php echo $post['id']; ?>">
                    <input class="btn btn-primary" type="submit" name="submit" value="Submit">
                </form>
            </div>
        <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
        <?php endif; ?>
    <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
    <?php endif; ?>
<?php require 'inc/footer.php'; ?>

