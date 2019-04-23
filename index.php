<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';

    // Records per page
    $perPage = 5;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $perPage;

    // Create Query
    $query = "SELECT * FROM posts ORDER BY updated_at DESC LIMIT $start, $perPage";

    // Get Result
    $result = mysqli_query($conn, $query);

    // Fetch Data
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php require 'inc/header.php'; ?>
    <div class="container">
        <h1>Posts</h1>
        <!-- Display all posts -->
        <?php foreach ($posts as $post) : ?>
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
        <!-- Pagination -->
        <div>
            <ul class="pagination">
                <?php if ($page == 1) : ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">&laquo;</a>
                    </li>
                <?php else : ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?php echo $page - 1; ?>">&laquo;</a>
                    </li>
                <?php endif; ?>

                <?php
                    // Create query
                    $query = 'SELECT * FROM posts ORDER BY updated_at DESC';

                    // Get result
                    $result = mysqli_query($conn, $query);

                    // Get record count
                    $records = mysqli_num_rows($result);

                    // Round up
                    $pages = ceil($records / $perPage);

                    if ($page > $pages) {
                        $_SESSION['error'] = 'pagenotfound';
                        header('Location: errors/404.php');
                        exit();
                    }

                    for ($i = 1; $i <= $pages; $i++) {
                        if ($page == $i) {
                            echo '<li class="page-item active">
                            <a class="page-link" href="index.php?page='.$i.'">'.$i.'</a>
                            </li>';
                        } else {
                            echo '<li class="page-item">
                            <a class="page-link" href="index.php?page='.$i.'">'.$i.'</a>
                            </li>';
                        }
                    }
                    // Free Result
                    mysqli_free_result($result);

                    // Close Connection
                    mysqli_close($conn);
                ?>

                <?php if ($page < $pages) : ?>
                    <li class="page-item">
                        <a class="page-link" href="index.php?page=<?php echo $page + 1; ?>">&raquo;</a>
                    </li>
                <?php else : ?>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">&raquo;</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
<?php require 'inc/footer.php'; ?>