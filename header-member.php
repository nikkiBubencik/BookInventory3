<?php
	require_once 'includes/sessions.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Book Inventory Pages</title>
    <link href="css/styles.css" rel="stylesheet">
  </head>
  <body>
    <div class="page">
      <header>
        <div class="header-left">
          <div class="logo">
            <img src="imgs/book-logo.jpg" alt="Book Inventory Logo">
              </div>
              <nav>
                <ul>
                  <li><a href="book-cat.php">Book Catalog</a></li>
                  <li><a href="groups.php">Groups</a></li>
                  <li><a href="list.php">Lists</a></li>
                  <li><a href="about.php">About</a></li>
                </ul>
              </nav>
            </div>
            
        <div class="header-right">
        <ul>
          <?php if ($logged_in) : ?>
            <li>
              <div class="welcome">
                <p>Welcome <?= $_SESSION['username']; ?></p>
              </div>  
            </li>
            <li><a href="account.php">Account</a></li>
            <li><a href="logout.php">Log Out</a></li>
            <?php else : ?>
                <li><a href="login.php">Log In</a></li>
            <?php endif; ?>
        </ul>        </div>
      </header>
      <section>
