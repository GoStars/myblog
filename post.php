<?php
    require 'conf/config.php';
    require 'conf/db.php';

    // Check for delete
    if (isset($_POST['delete'])) {
        // Get form data
        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);

        $query = "DELETE FROM posts WHERE id = {$delete_id}";

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
    <div class="container">
        <a class="btn btn-default" href="<?php echo ROOT_URL; ?>">Back</a>
        <h1><?php echo $post['title']; ?></h1>
        <small>
            Created on <?php echo $post['created_at']; ?> by
            <?php echo $post['author']; ?>
        </small>
        <p><?php echo $post['body']; ?></p>
        <hr>
        <!-- Check if user is author of a post -->
        <?php if (isset($_SESSION['id']) && $_SESSION['name'] == $post['author']) : ?>
            <form class="float-right" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                <input class="btn btn-danger" type="submit" name="delete" value="Delete">
            </form>
            <a class="btn btn-default" href="<?php echo ROOT_URL; ?>editpost.php?id=<?php echo $_GET['id']; ?>">Edit</a>
        <?php endif; ?>
    </div>
<?php include 'inc/footer.php'; ?>