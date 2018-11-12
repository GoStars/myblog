<?php
    require 'conf/config.php';
    require 'conf/db.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $update_id = mysqli_real_escape_string($conn, $_POST['update_id']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $body = mysqli_real_escape_string($conn, $_POST['body']);

        $query = "UPDATE posts SET title='$title', body='$body' WHERE id = {$update_id}";

        if (mysqli_query($conn, $query)) {
            header('Location: '.ROOT_URL.'');
        } else {
            echo 'ERROR: '.mysqli_error($conn);
        }
    }

    // Get ID
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Create Query
    $query = "SELECT * FROM posts WHERE id = $id";

    // Get Result
    $result = mysqli_query($conn, $query);

    // Fetch Data
    $post = mysqli_fetch_assoc($result);
    // var_dump($posts);

    // Free Result
    mysqli_free_result($result);

    // Close Connection
    mysqli_close($conn);
?>

<?php include 'inc/header.php'; ?>
    <!-- Check if user has rights to editing post -->
    <?php if (isset($_SESSION['id']) && $_SESSION['name'] == $post['author']) : ?>
        <div class="container">
            <h1>Edit Post</h1>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label>Title</label>
                    <input class="form-control" type="text" name="title" value="<?php echo $post['title']; ?>">
                </div>
                <div class="form-group">
                    <fieldset disabled="">
                        <label class="control-label" for="disabledInput">Author</label>
                        <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $_SESSION['name']; ?>" disabled="">
                    </fieldset>
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea class="form-control" name="body"><?php echo $post['body']; ?></textarea> 
                </div>
                <input type="hidden" name="author" value="<?php echo $_SESSION['name']; ?>">
                <input type="hidden" name="update_id" value="<?php echo $post['id']; ?>">
                <input class="btn btn-primary" type="submit" name="submit" value="Submit">
            </form>
        </div>
    <?php else : header('Location: index.php?error=accessdenied'); exit(); ?>
    <?php endif; ?>
<?php include 'inc/footer.php'; ?>