<?php
    require_once 'config/globals.php';
    require_once 'config/db.php';
?>

<?php require 'includes/header.php'; ?>
    <?php
        // Records per page
        $perPage = 10;

        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        } else {
            $page = 1;
        }

        $start = ($page - 1) * $perPage;

        $name = $_SESSION['name'];

        // Create Query
        $query = "SELECT p.*, u.name 
            FROM posts AS p 
            INNER JOIN users AS u ON p.user_id = u.id
            WHERE u.name = '$name'
            ORDER BY p.updated_at DESC LIMIT $start, $perPage";
        
        // Get Result
        $result = mysqli_query($conn, $query);

        // Fetch Data
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>
    <?php if (isset($_SESSION['id'])) : ?>
        <div class="container">
            <h1>Dashboard</h1>
            <?php
                if (isset($_SESSION['error'])) {
                    if ($_SESSION['error'] == 'postnotfound') {
                        echo '<p class="text-warning">Post not found!</p>';
                    }
                } else if (isset($_SESSION['success'])) {
                    if ($_SESSION['success'] == 'addpost') {
                        echo '<p class="text-success">Post added successfully!</p>';
                    } else if ($_SESSION['success'] == 'editpost') {
                        echo '<p class="text-success">Post edited successfully!</p>';
                    } else if ($_SESSION['success'] == 'deletepost') {
                        echo '<p class="text-info">Post deleted successfully!</p>';
                    } 
                }
            ?>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Date</th>
                            <th scope="col">Description</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Display all posts -->
                        <?php foreach($posts as $post) : ?>
                            <tr>
                                <th scope="row"><?php echo $post['title']; ?></th>
                                <td><?php 
                                        // Display created or updated post date
                                        if ($post['created_at'] >= $post['updated_at']) {
                                            echo 'Created at '.$post['created_at'];
                                        } else {
                                            echo 'Updated at '.$post['updated_at'];
                                        }
                                    ?>   
                                </td>
                                <td><?php echo $post['description']; ?></td>
                                <td><a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>showpost.php?dashboard&id=<?php echo $post['id']; ?>">More</a></td>
                                <td><a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>updatepost.php?id=<?php echo $post['id']; ?>">Edit</a>
                                </td>
                                <td>
                                    <form class="float-right" method="POST" action="actions/post/delete.php">
                                        <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                                        <input class="btn btn-danger" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure that you want to delete <?php echo $post['title']; ?>?')">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <?php require 'includes/pagination.php'; ?>
            <?php else : ?>
                <div class="card border-primary">
                    <div class="card-header">Posts not found</div>
                    <div class="card-body">
                        <p class="card-text">You don't have any posts! Click Add Post to create one!</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
    <?php endif; ?>
<?php require 'includes/footer.php'; ?>