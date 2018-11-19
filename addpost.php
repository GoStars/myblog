<?php
    require 'conf/config.php';
    require 'conf/db.php';
    require 'php/test_input.php';

    // Check for submit
    if (isset($_POST['submit'])) {
        // Get form data
        $title = mysqli_real_escape_string($conn, test_input($_POST['title']));
        $author = mysqli_real_escape_string($conn, $_POST['author']);
        $body = mysqli_real_escape_string($conn, test_input($_POST['body']));

         // Check for empty fields
        if (empty($title) || empty($body)) {
            // Save correct data into fields
            header('Location: addpost.php?error=emptypostfield&title='.$title.'&body='.$body);
            // Stop script
            exit();
        } else {
            $query = "INSERT INTO posts(title, author, body) VALUES('$title', '$author', '$body')";
        }

        if (mysqli_query($conn, $query)) {
            header('Location: index.php?success=addpost');
            exit();
        } else {
            echo 'ERROR: '.mysqli_error($conn);
        }
    }
?>

<?php include 'inc/header.php'; ?>
    <div class="container">
        <h1>Add Post</h1>
        <?php 
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'emptypostfield') {
                    echo '<p class="text-warning">Fill in all fields!</p>';
                }
            }

            $title = (isset($_GET['title'])) ? $_GET['title'] : '';
            $body = (isset($_GET['body'])) ? $_GET['body'] : '';
        ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label>Title</label>
                <input class="form-control" type="text" name="title" value="<?php echo $title ?>">
            </div>
            <div class="form-group">
                <fieldset disabled="">
                    <label class="control-label" for="disabledInput">Author</label>
                    <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $_SESSION['name']; ?>" disabled="">
                </fieldset>
            </div>
            <div class="form-group">
                <label>Body</label>
                <textarea class="form-control" name="body"><?php echo $body ?></textarea> 
            </div>
            <input type="hidden" name="author" value="<?php echo $_SESSION['name']; ?>">
            <input class="btn btn-primary" type="submit" name="submit" value="Submit">
        </form>
    </div>
<?php include 'inc/footer.php'; ?>