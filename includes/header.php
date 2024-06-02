<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Pulse</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="/" style="display: flex; align-items: center; color: #ffffff;">
                    <img src="../assets/images/logo.png" alt="Property Pulse" height="50" width="50">
                    <h1 style="font-size: 1.5rem; font-weight: 700; margin-left: 10px; color: #ffffff;">Property Pulse</h1>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="properties.php">Properties</a></li>
                    <?php if (isset($_SESSION['user_id'])) : ?>
                        <li><a href="upload.php" class="btn btn-solid-inverse">List a Property</a></li>
                        <li class="user-menu">
                            <a href="#" class="btn btn-solid-inverse">
                                <i class="fa fa-user"></i> 
                            </a>
                            <ul class="user-menu-dropdown">
                                <li><a href="#">My Properties</a></li>
                                <li><a href="purchases.php">My Purchases</a></li>
                                <li><a href="logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else : ?>
                        <li><a href="signup.php" class="btn btn-outline-inverse">Register</a></li>
                        <li><a href="login.php" class="btn btn-solid-inverse">Sign In</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>