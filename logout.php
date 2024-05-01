<?php
// include 'includes/sessions.php';
include 'includes/header-member.php';
logout();                             // Call logout() to terminate session
header('Location: book-cat.php');         // Redirect to book catalog page