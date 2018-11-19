<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP Blog</title>
        <link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/cerulean/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/index.css">
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
                    if (isset($_SESSION['id'])) {
                        echo '<ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="addpost.php">Add Post</a>
                        </li>
                        </ul>
                        <form class="form-inline my-2 my-lg-0" method="POST" action="./php/logout.php">
                        <p class="text-primary my-2 mr-sm-2">Hello, '.$_SESSION['name'].'!</p>
                        <input class="btn btn-secondary my-2 my-sm-0" type="submit" name="logout" value="Log Out">
                        </form>';
                    } else {
                        echo '<ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                        </li>
                        </ul>
                        <form class="form-inline my-2 my-lg-0" method="POST" action="./php/login.php">
                        <input class="form-control mr-sm-2" type="email" name="email" placeholder="Email">
                        <input class="form-control mr-sm-2" type="password" name="password" placeholder="Password">
                        <input class="btn btn-secondary my-2 my-sm-0" type="submit" name="login" value="Log In">
                        <a class="nav-link" href="adduser.php">Registration</a>
                        </form>';
                    }
                ?>
            </div>
        </nav>
        <br>
        <!-- Errors -->
        <div class="container">
            <?php 
                if (isset($_GET['error'])) {
                    if ($_GET['error'] == 'emptyfields') {
                        echo '<p class="text-warning">Fill in all fields!</p>';
                    } else if ($_GET['error'] == 'wrongpassword') {
                        echo '<p class="text-warning">Wrong password!</p>';
                    } else if ($_GET['error'] == 'nouser') {
                        echo '<p class="text-warning">User not found!</p>';
                    } else if ($_GET['error'] == 'accessdenied') {
                        echo '<p class="text-warning">Access denied!</p>';
                    } else if ($_GET['error'] == 'emptyeditpostfield') {
                        echo '<p class="text-warning">Fill in all fields!</p>';
                    }
                } else if (isset($_GET['success'])) {
                    if ($_GET['success'] == 'login') {
                        echo '<p class="text-success">Log In successful!</p>';
                    } else if ($_GET['success'] == 'logout') {
                        echo '<p class="text-info">Log Out successful!</p>';
                    } else if ($_GET['success'] == 'addpost') {
                        echo '<p class="text-success">Post added successfully!</p>';
                    } else if ($_GET['success'] == 'editpost') {
                        echo '<p class="text-success">Post edited successfully!</p>';
                    }
                }
            ?>
        </div>
        
