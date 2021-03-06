<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP Blog</title>
        <link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/cerulean/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="public/css/index.css">
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">My Blog</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarColor02">
                <?php
                    if (isset($_SESSION['id']) && isset($_SESSION['user_status'])) {
                        echo '<ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="createpost.php">Add Post</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="updateuser.php?id='.$_SESSION['id'].'">Profile Settings</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        </ul>
                        <form class="form-inline my-2 my-lg-0" method="POST" action="./actions/auth/logout.php">
                        <p class="text-primary my-2 mr-sm-2">Hello, 
                        <img class="image-source" src="myblog/'.$_SESSION['avatar_path'].'"> '.$_SESSION['name'].'!</p>
                        <input class="btn btn-secondary my-2 my-sm-0" type="submit" name="logout" value="Log Out">
                        </form>';
                    } else {
                        echo '<ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                        </li>
                        </ul>
                        <form method="POST" action="./actions/auth/login.php">
                        <div class="form-row">
                        <div class="from-group col-md-4">
                        <input class="form-control" type="email" name="email" placeholder="E-mail">
                        </div>
                        <div class="from-group col-md-4">
                        <input class="form-control" type="password" name="password" placeholder="Password">
                        <a class="nav-link" href="resetpwd.php">Forgot your password?</a>
                        </div>
                        <div class="from-group col-md-2">
                        <input class="btn btn-secondary" type="submit" name="login" value="Log In">
                        </div>
                        <div class="from-group col-md-2">
                        <a class="nav-link" href="createuser.php">Registration</a>
                        </div> 
                        </div> 
                        </form>';
                    }
                ?>
            </div>
        </nav>
        <br>
        <!-- Messages -->
        <div class="container">
            <?php 
                if (isset($_SESSION['error'])) {
                    if ($_SESSION['error'] == 'emptyfields') {
                        echo '<p class="text-warning">Fill in all fields!</p>';
                    } else if ($_SESSION['error'] == 'wrongpassword') {
                        echo '<p class="text-warning">Wrong password!</p>';
                    } else if ($_SESSION['error'] == 'nouser') {
                        echo '<p class="text-warning">User not found!</p>';
                    } else if ($_SESSION['error'] == 'accessdenied') {
                        echo '<p class="text-warning">Access denied!</p>';
                    } else if ($_SESSION['error'] == 'validation') {
                        echo '<p class="text-warning">Could not validate your request!</p>';
                    } else if ($_SESSION['error'] == 'usernotfound') {
                        echo '<p class="text-warning">User not found!</p>';
                    }
                } else if (isset($_SESSION['success'])) {
                    if ($_SESSION['success'] == 'login') {
                        echo '<p class="text-success">Log In successful!</p>';
                    } else if ($_SESSION['success'] == 'logout') {
                        echo '<p class="text-info">Log Out successful!</p>';
                    } else if ($_SESSION['success'] == 'passwordupdated') {
                        echo '<p class="text-success">Password updated successfully!</p>';
                    } else if ($_SESSION['success'] == 'registration') {
                        echo '<p class="text-success">Registration successful!</p>';
                    } else if ($_SESSION['success'] == 'deactivateaccount') {
                        echo '<p class="text-success">Account successfully deactivated!</p>';
                    } else if ($_SESSION['success'] == 'activateaccount') {
                        echo '<p class="text-success">Account successfully activated!</p>';
                    }
                }
            ?>
        </div>
        
