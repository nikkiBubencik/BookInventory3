<?php
// Include database connection script
require 'includes/database-connection.php';
include 'includes/header-member.php'; 		// Header file

// Get user ID from session
$userId = $_SESSION['userID'];

// Query to fetch user account information
$sql = "SELECT first_name, last_name, email, username FROM users WHERE userID = ?";
$statement = $pdo->prepare($sql);
$statement->execute([$userId]);
$userInfo = $statement->fetch(PDO::FETCH_ASSOC);

// Handle password reset form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['password']) && isset($_POST['confirm_password'])) {
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm_password'];

        // Validate password and confirm password match
        if ($password === $confirmPassword) {
            // Update the user's password in the database
            $updateSql = "UPDATE users SET password = ? WHERE userID = ?";
            $updateStatement = $pdo->prepare($updateSql);
            $updateStatement->execute([sha1($password), $userId]);

            // Redirect to account.php with a success message
            header("Location: account.php?reset=success");
            exit();
        } else {
            // Passwords do not match, display an error message
            $resetError = "Passwords do not match.";
        }
    }
       // Handle username change form submission
       if (isset($_POST['new_username'])) {
        $newUsername = $_POST['new_username'];

        // Update the user's username in the database
        $updateUsernameSql = "UPDATE users SET username = ? WHERE userID = ?";
        $updateUsernameStatement = $pdo->prepare($updateUsernameSql);
        $updateUsernameStatement->execute([$newUsername, $userId]);

        // Redirect to account.php with a success message
        header("Location: account.php?username=success");
        exit();
    }

    // Handle name change form submission
    if (isset($_POST['first_name']) && isset($_POST['last_name'])) {
        $newFirstName = $_POST['first_name'];
        $newLastName = $_POST['last_name'];

        // Update the user's name in the database
        $updateNameSql = "UPDATE users SET first_name = ?, last_name = ? WHERE userID = ?";
        $updateNameStatement = $pdo->prepare($updateNameSql);
        $updateNameStatement->execute([$newFirstName, $newLastName, $userId]);

        // Redirect to account.php with a success message
        header("Location: account.php?name=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Information</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <style>
        .password-reset-form, .username-change-form, .name-change-form {
            display: none;
        }
    </style>
</head>
<body>
<main>
    <!-- Display user account information -->
    <div class="account-info-container">
        <h1>Account Information</h1>
        <?php if (isset($_GET['reset']) && $_GET['reset'] === 'success') : ?>
            <p class="reset-success">Password reset successful.</p>
        <?php endif; ?>
        <div class="account-info">
            <p><strong>First Name:</strong> <?= $userInfo['first_name'] ?></p>
            <p><strong>Last Name:</strong> <?= $userInfo['last_name'] ?></p>
            <p><strong>Email:</strong> <?= $userInfo['email'] ?></p>
            <p><strong>Username:</strong> <?= $userInfo['username'] ?></p>
        </div>
        <!-- Buttons to reveal forms -->
        <button id="revealReset">Reset Password</button>
        <button id="revealChangeUsername">Change Username</button>
        <button id="revealChangeName">Change Name</button>
    </div>
    <div id="passwordResetContainer">
        <!-- Password reset form -->
        <div class="password-reset-form">
            <h2>Reset Password</h2>
            <form id="passwordResetForm" action="account.php" method="POST">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if (isset($resetError)) : ?>
                    <p class="error-message"><?= $resetError ?></p>
                <?php endif; ?>
                <!-- Button to submit password reset -->
                <button type="submit">Reset Password</button>
                <!-- Button to cancel password reset -->
                <button type="button" class="cancelReset">Cancel</button>
            </form>
        </div>
        <!-- Username change form -->
        <div class="username-change-form">
            <h2>Change Username</h2>
            <form id="usernameChangeForm" action="account.php" method="POST">
                <label for="new_username">New Username:</label>
                <input type="text" id="new_username" name="new_username" required>
                <!-- Button to submit username change -->
                <button type="submit">Change Username</button>
                <!-- Button to cancel username change -->
                <button type="button" class="cancelUsernameChange">Cancel</button>
            </form>
        </div>
        <!-- Change name form -->
        <div id="nameChangeContainer">
            <div class="name-change-form">
                <h2>Change Name</h2>
                <form id="nameChangeForm" action="account.php" method="POST">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" value="<?= $userInfo['first_name'] ?>" required>
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" value="<?= $userInfo['last_name'] ?>" required>
                    <button type="submit">Change Name</button>
                    <button type="button" id="cancelNameChange">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Button to reveal password reset fields
        var revealResetBtn = document.getElementById('revealReset');
        // Password reset form
        var passwordResetForm = document.querySelector('.password-reset-form');
        // Button to reveal username change fields
        var revealChangeUsernameBtn = document.getElementById('revealChangeUsername');
        // Username change form
        var usernameChangeForm = document.querySelector('.username-change-form');
        // Button to reveal name change fields
        var revealChangeNameBtn = document.getElementById('revealChangeName');
        // Name change form
        var nameChangeForm = document.querySelector('.name-change-form');
        
        // Add event listener to reveal password reset fields
        revealResetBtn.addEventListener('click', function () {
            passwordResetForm.style.display = 'block';
            usernameChangeForm.style.display = 'none'; // Hide username change form if it's open
            nameChangeForm.style.display = 'none'; // Hide name change form if it's open
        });

        // Add event listener to reveal username change fields
        revealChangeUsernameBtn.addEventListener('click', function () {
            usernameChangeForm.style.display = 'block';
            passwordResetForm.style.display = 'none'; // Hide password reset form if it's open
            nameChangeForm.style.display = 'none'; // Hide name change form if it's open
        });

        // Add event listener to reveal name change fields
        revealChangeNameBtn.addEventListener('click', function () {
            nameChangeForm.style.display = 'block';
            passwordResetForm.style.display = 'none'; // Hide password reset form if it's open
            usernameChangeForm.style.display = 'none'; // Hide username change form if it's open
        });

        // Add event listener to cancel password reset
        document.querySelectorAll('.cancelReset').forEach(function(cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                document.getElementById('password').value = '';
                document.getElementById('confirm_password').value = '';
                passwordResetForm.style.display = 'none';
                revealResetBtn.style.display = 'block';
            });
        });

        // Add event listener to cancel username change
        document.querySelectorAll('.cancelUsernameChange').forEach(function(cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                document.getElementById('new_username').value = '';
                usernameChangeForm.style.display = 'none';
                revealChangeUsernameBtn.style.display = 'block';
            });
        });

        // Add event listener to cancel name change
        document.querySelectorAll('.cancelNameChange').forEach(function(cancelBtn) {
            cancelBtn.addEventListener('click', function () {
                document.getElementById('first_name').value = '';
                document.getElementById('last_name').value = '';
                nameChangeForm.style.display = 'none';
                revealChangeNameBtn.style.display = 'block';
            });
        });
    });
</script>

</body>
</html>
