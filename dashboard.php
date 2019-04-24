<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';
?>

<?php require 'inc/header.php'; ?>
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
        $query = "SELECT * FROM posts WHERE author = '$name' ORDER BY updated_at DESC LIMIT $start, $perPage";
        
        // Get Result
        $result = mysqli_query($conn, $query);

        // Fetch Data
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>
    <?php if (isset($_SESSION['id'])) : ?>
        <div class="container">
            <h1>Dashboard</h1>
            <?php
                if (isset($_SESSION['success'])) {
                    if ($_SESSION['success'] == 'addpost') {
                        echo '<p class="text-success">Post added successfully!</p>';
                    } else if ($_SESSION['success'] == 'editpost') {
                        echo '<p class="text-success">Post edited successfully!</p>';
                    } else if ($_SESSION['success'] == 'deletepost') {
                        echo '<p class="text-info">Post deleted successfully!</p>';
                    } 
                }
            ?>
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
                            <td><a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>post.php?dashboard&id=<?php echo $post['id']; ?>">More</a></td>
                            <td><a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>editpost.php?id=<?php echo $post['id']; ?>">Edit</a>
                            </td>
                            <td>
                                <form class="float-right" method="POST" action="php/removepost.php">
                                    <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                                    <input class="btn btn-danger" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure that you want to delete <?php echo $post['title']; ?>?')">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <?php require 'inc/pagination.php'; ?>
        </div>
    <?php else : $_SESSION['error'] = 'accessdenied'; header('Location: index.php'); exit(); ?>
    <?php endif; ?>
<?php require 'inc/footer.php'; ?>