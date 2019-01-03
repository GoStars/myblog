<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';

    // Get ID
    $id = $_GET['id'];

    // Create Query
    $query = "SELECT id, title, author, body, created_at, updated_at FROM posts WHERE id = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $query)) {
        header('Location: ../index.php?error=sqlerror');
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_bind_result($stmt, $id, $title, $author, $body, $created_at, $updated_at);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $post = mysqli_fetch_array($result);
    }

    mysqli_stmt_close($stmt);
    // Close connection (save resources)
    mysqli_close($conn);
?>

<?php require 'inc/header.php'; ?>
    <div class="container">
        <?php 
            if (isset($_GET['dashboard'])) {
                echo '<a class="btn btn-secondary" href="'.ROOT_URL.'dashboard.php">Back</a>';
            } else {
                echo '<a class="btn btn-secondary" href="'.ROOT_URL.'">Back</a>';
            }
        ?>
        <h1><?php echo $post['title']; ?></h1>
        <small>
            <?php 
                if ($post['created_at'] >= $post['updated_at']) {
                    echo 'Created at '.$post['created_at'].' by '.$post['author'];
                } else {
                    echo 'Updated at '.$post['updated_at'].' by '.$post['author'];
                }
            ?>
        </small>
        <hr>
        <p><?php echo $post['body']; ?></p>
        <hr>
        <!-- Check if user is author of a post -->
        <?php if (isset($_SESSION['id']) && $_SESSION['name'] == $post['author']) : ?>
            <form class="float-right" method="POST" action="php/removepost.php">
                <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                <input class="btn btn-danger" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure that you want to delete <?php echo $post['title']; ?>?')">
            </form>
            <a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>editpost.php?id=<?php echo $_GET['id']; ?>">Edit</a>
        <?php endif; ?>
    </div>
<?php require 'inc/footer.php'; ?>