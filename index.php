<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';

    // Create Query
    $query = 'SELECT * FROM posts ORDER BY updated_at DESC';

    // Get Result
    $result = mysqli_query($conn, $query);

    // Fetch Data
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Free Result
    mysqli_free_result($result);

    // Close Connection
    mysqli_close($conn);
?>

<?php require 'inc/header.php'; ?>
    <div class="container">
        <h1>Posts</h1>
        <!-- Display all posts -->
        <?php foreach($posts as $post) : ?>
            <div class="card card-body bg-light">
                <h3><?php echo $post['title']; ?></h3>
                <small>
                    <?php 
                        // Display created or updated post date
                        if ($post['created_at'] >= $post['updated_at']) {
                            echo 'Created at '.$post['created_at'].' by '.$post['author'];
                        } else {
                            echo 'Updated at '.$post['updated_at'].' by '.$post['author'];
                        }
                    ?>
                </small>
                <p><?php echo $post['description']; ?></p>
                <a class="btn" href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">Read More</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php require 'inc/footer.php'; ?>