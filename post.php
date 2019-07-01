<?php
    require_once 'conf/config.php';
    require_once 'conf/db.php';
?>

<?php require 'inc/header.php'; ?>
    <?php
        // Get ID
        $id = $_GET['id'];

        // Create Query
        $query = "SELECT p.id, p.title, p.body, p.created_at, p.updated_at, u.name, a.avatar_path 
            FROM posts AS p 
            INNER JOIN users AS u ON p.user_id = u.id 
            INNER JOIN avatars AS a ON a.user_id = u.id
            WHERE p.id = ?";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $query)) {
            $_SESSION['error'] = 'sqlerror';
            header('Location: errors/502.php');
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_bind_result($stmt, $id, $title, $body, $created_at, $updated_at, $name, $avatar_path);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $post = mysqli_fetch_array($result);
        }
        mysqli_stmt_close($stmt);
        // Close connection (save resources)
        mysqli_close($conn);
    ?>
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
                $user_avatar = '<img class="small-image-source" src="myblog/'.$post['avatar_path'].'"> ';
                if ($post['created_at'] >= $post['updated_at']) {
                    echo 'Created at '.$post['created_at'].' by '.$user_avatar.$post['name'];
                } else {
                    echo 'Created at '.$post['created_at'].' by '.$user_avatar.$post['name'];
                    echo '<br>Updated at '.$post['updated_at'].' by '.$user_avatar.$post['name'];
                }
            ?>
        </small>
        <hr>
        <p><?php echo $post['body']; ?></p>
        <hr>
        <!-- Check if user is author of a post -->
        <?php if (isset($_SESSION['id']) && $_SESSION['name'] == $post['name']) : ?>
            <form class="float-right" method="POST" action="php/removepost.php">
                <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>">
                <input class="btn btn-danger" type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure that you want to delete <?php echo $post['title']; ?>?')">
            </form>
            <a class="btn btn-secondary" href="<?php echo ROOT_URL; ?>editpost.php?id=<?php echo $_GET['id']; ?>">Edit</a>
        <?php endif; ?>
    </div>
<?php require 'inc/footer.php'; ?>