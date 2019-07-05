<?php require 'includes/header.php'; ?>
    <div class="container">
        <h1>Add Post</h1>
        <?php 
            if (isset($_SESSION['error'])) {
                if ($_SESSION['error'] == 'emptypostfield') {
                    echo '<p class="text-warning">Fill in all fields!</p>';
                }
            }

            $title = (isset($_GET['title'])) ? $_GET['title'] : '';
            $description = (isset($_GET['description'])) ? $_GET['description'] : '';
            $body = (isset($_GET['body'])) ? $_GET['body'] : '';
        ?>
        <form method="POST" action="actions/post/create.php">
            <div class="form-group">
                <label>Title</label>
                <input class="form-control" type="text" name="title" value="<?php echo $title; ?>">
            </div>
            <div class="form-group">
                <fieldset disabled="">
                    <label class="control-label" for="disabledInput">Author</label>
                    <input class="form-control" id="disabledInput" type="text" placeholder="<?php echo $_SESSION['name']; ?>" disabled="">
                </fieldset>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea class="form-control" name="description"><?php echo $description; ?></textarea> 
            </div>
            <div class="form-group">
                <label>Body</label>
                <textarea class="form-control" name="body"><?php echo $body; ?></textarea> 
            </div>
            <input class="btn btn-primary" type="submit" name="submit" value="Submit">
        </form>
    </div>
<?php require 'includes/footer.php'; ?>