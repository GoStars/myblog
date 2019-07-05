<?php
    require_once 'config/globals.php';
    require_once 'config/db.php';

    // Records per page
    $perPage = 5;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $start = ($page - 1) * $perPage;

    // Create Query
    $query = "SELECT p.*, u.name, a.avatar_path 
        FROM posts as p 
        INNER JOIN users AS u ON p.user_id = u.id 
        INNER JOIN avatars AS a ON a.user_id = u.id
        ORDER BY p.updated_at DESC LIMIT $start, $perPage";

    // Get Result
    $result = mysqli_query($conn, $query);

    // Fetch Data
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<?php require 'includes/header.php'; ?>
    <div class="container">
        <h1>Posts</h1>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <!-- Display all posts -->
            <?php foreach ($posts as $post) : ?>
                <div class="card card-body bg-light">
                    <h3><?php echo $post['title']; ?></h3>
                    <small>
                        <?php 
                            $avatar_path = str_replace('../../', '../', $post['avatar_path']);
                            $user_avatar = '<img class="small-image-source" src="myblog/'.$avatar_path.'"> ';
                            
                            // Display created or updated post date
                            if ($post['created_at'] >= $post['updated_at']) {
                                echo 'Created at '.$post['created_at'].' by '.$user_avatar.$post['name'];
                            } else {
                                echo 'Updated at '.$post['updated_at'].' by '.$user_avatar.$post['name'];
                            }
                        ?>
                    </small>
                    <p><?php echo $post['description']; ?></p>
                    <a class="btn" href="<?php echo ROOT_URL; ?>showpost.php?id=<?php echo $post['id']; ?>">Read More</a>
                </div>
            <?php endforeach; ?>
            <!-- Pagination -->
            <?php require 'includes/pagination.php'; ?>
        <?php else : ?>
            <div class="card border-primary">
                <div class="card-header">Posts not found</div>
                <div class="card-body">
                    <p class="card-text">There are no posts in database!</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php require 'includes/footer.php'; ?>