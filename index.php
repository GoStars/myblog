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
    $query = "SELECT p.*, u.name 
        FROM posts as p 
        INNER JOIN users AS u ON p.user_id = u.id 
        ORDER BY p.updated_at DESC LIMIT $start, $perPage";

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
                            echo 'Created at '.$post['created_at'].' by '.$post['name'];
                        } else {
                            echo 'Updated at '.$post['updated_at'].' by '.$post['name'];
                        }
                    ?>
                </small>
                <p><?php echo $post['description']; ?></p>
                <a class="btn" href="<?php echo ROOT_URL; ?>post.php?id=<?php echo $post['id']; ?>">Read More</a>
            </div>
        <?php endforeach; ?>
        <!-- Pagination -->
        <?php require 'inc/pagination.php'; ?>
    </div>
<?php require 'inc/footer.php'; ?>