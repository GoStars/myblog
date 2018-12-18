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
        $query = "SELECT id, title, author, description, body FROM posts WHERE id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            header('Location: ../index.php?error=sqlerror');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_bind_result($stmt, $id, $title, $author, $description, $body);
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
        <?php if ($_SESSION['name'] == $post['author']) : ?>
            <div class="container">
                <h1>Edit Post</h1>
                <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'emptyeditpostfield') {
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
                            <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $post['author']; ?>" disabled="">
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
        <?php else : header('Location: index.php?error=accessdenied'); exit(); ?>
        <?php endif; ?>
    <?php else : header('Location: index.php?error=accessdenied'); exit(); ?>
    <?php endif; ?>
<?php require 'inc/footer.php'; ?>

