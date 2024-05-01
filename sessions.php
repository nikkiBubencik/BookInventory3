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
    // $statement = pdo($pdo, $sql, [$email, sha1($password)]);

    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        die('Error in preparing statement: ' . $pdo->errorInfo()[2]);
    }

    $stmt->execute([$email, sha1($password)]);
    if ($stmt->errorCode() !== '00000') {
        die('Error executing statement: ' . $stmt->errorInfo()[2]);
    }

    $statement = $stmt->fetch(PDO::FETCH_ASSOC);

    
    if ($stmt->rowCount() == 1) {
        session_regenerate_id(true);                         // Prevents session fixation attacks
        $_SESSION['logged_in'] = true;
        $_SESSION['userID'] = $statement['userID']; // Set session variable
        $_SESSION['username'] = $statement['username'];
        echo $_SESSION['username'];
        return true;
    }
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