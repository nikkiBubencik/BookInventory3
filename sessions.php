<?php
require_once 'database-connection.php'; // Include the database connection file

session_start();                                         // Start/renew session
                                                         // Note: must be called before any output is sent to the browser

$logged_in = $_SESSION['logged_in'] ?? false;            // If $_SESSION['logged_in'] not null, user's logged in

function login($email, $password)                                         // Remember user passed login
{
    // true = deletes old session
    
    global $pdo; // Access the $pdo variable defined in database-connection.php
    
    // Query to check if the user exists and the password matches
    $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
    $statement = pdo($pdo, $sql, [$email, sha1($password)]);
    
    if ($statement->rowCount() == 1) {
        $user = $statement->fetch(PDO::FETCH_ASSOC); // Fetch user data
        session_regenerate_id(true);                         // Prevents session fixation attacks
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $user['username']; // Set the username in the session
        $_SESSION['userID'] = $user['userID']; // Set the username in the session
        return true;
    }
    $_SESSION['userID'] = null;
    return false;
}


function logout()                                        // Terminate the session
{
    $_SESSION = [];                                      // Clears all session variables

    $params = session_get_cookie_params();               // Get session cookie parameters
    setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'],
        $params['secure'], $params['httponly']);         // Delete session cookie (by setting it to a past value, which effectively removes it from the client's browser)

    session_destroy();                                   // Delete session file on the server
}

function require_login($logged_in)                       // Check if user logged in
{
    if ($logged_in == false) {                           // If not logged in
        header('Location: login.php');                   // Send to login page
        exit;                                            // Stop rest of page running
    }
}