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

    // Free Result
    mysqli_free_result($result);

    // Close Connection
    mysqli_close($conn);
?>

<?php if ($pages > 1) : ?>
    <div>
        <ul class="pagination">
            <?php if ($page == 1) : ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#">&laquo;</a>
                </li>
            <?php else : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $page - 1; ?>">&laquo;</a>
                </li>
            <?php endif; ?>
            
            <?php 
                for ($i = 1; $i <= $pages; $i++) {
                    if ($page == $i) {
                        echo '<li class="page-item active">
                        <a class="page-link" href="'.$_SERVER['SCRIPT_NAME'].'?page='.$i.'">'.$i.'</a>
                        </li>';
                    } else {
                        echo '<li class="page-item">
                        <a class="page-link" href="'.$_SERVER['SCRIPT_NAME'].'?page='.$i.'">'.$i.'</a>
                        </li>';
                    }
                }
            ?>

            <?php if ($page < $pages) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=<?php echo $page + 1; ?>">&raquo;</a>
                </li>
            <?php else : ?>
                <li class="page-item disabled">
                    <a class="page-link" href="#">&raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
<?php endif; ?>