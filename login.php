<?php include 'includes/header-member.php'; 
?>
<?php
    if ($logged_in) {                              // If already logged in
        echo "Logged in successful";
	header('Location: list.php');              // Redirect to list page
        // exit;                                      // Stop further code running
    }    

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {     // If form submitted
        $user_email    = $_POST['email'];          // Email user sent
        $user_password = $_POST['password'];       // Password user sent

        if (login($user_email, $user_password)) {
            $_SESSION['user_email'] = $user_email; // Set session variable
            $_SESSION['logged_in'] = true;
            header('Location: list.php');
            exit;
        } else {
            echo "Invalid email or password. Please try again.";
        }
    }

    // Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    // Retrieve form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $username = $_POST['username'];
    $email_signup = $_POST['email_signup'];
    $password_signup = $_POST['password_signup'];
    $repassword_signup = $_POST['repassword_signup'];

    // Verify if passwords match
    if ($password_signup !== $repassword_signup) {
        echo "Passwords do not match. Please try again.";
        exit;
    }

    // Prepare SQL statement to insert new user into the database
    $sql = "INSERT INTO users (first_name, last_name, username, email, password) VALUES (?, ?, ?, ?, ?)";

    // Prepare and execute the SQL statement
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$first_name, $last_name, $username, $email_signup, sha1($password_signup)]);

    // Check if the user was successfully added
    if ($stmt->rowCount() > 0) {
        echo "User registration successful!";
        login($email_signup, $password_signup);
        header('Location: list.php');
    } else {
        echo "User registration failed. Please try again.";
    }
}
?>


<!DOCTYPE>
<html>
	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>Book Inventory</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>
    <body>
        <div class="login_container">
            <form class="form" id="login" method="POST" action="login.php">
                <h1 class="form_title">Login</h1>

                <div class="form_message form_message--error"></div>

                <div class="form_input-group">
                    <input type="email" name="email" class="form_input" autofocus placeholder="Username or email">
                    <div class="form_input-error-message"></div>
                </div>

                <div class="form_input-group">
                    <input type="password" name="password" class="form_input" autofocus placeholder="Password">
                    <div class="form_input-error-message"></div>
                </div>

                <button class="form_button" type="submit" name="login">Login</button>

                <p class="form_text">
                    Don't have an account?
                    <a class="form_link" href="./" id="linkCreateAccount">Sign Up</a>
                </p>
            </form>



            <form class="form form--hidden" id="createAccount" method="POST" action="login.php?action=signup">
                <h1 class="form_title">Sign Up</h1>

                <div class="form_message form_message--error"></div>

                <div class="form_input-group">
                    <input type="text" name="first_name" class="form_input" autofocus placeholder="First Name">
                    <div class="form_input-error-message"></div>
                </div>
                <div class="form_input-group">
                    <input type="text" name="last_name" class="form_input" autofocus placeholder="Last Name">
                    <div class="form_input-error-message"></div>
                </div>
                <div class="form_input-group">
                    <input type="text" name="username" id="signupUsername" class="form_input" autofocus placeholder="Username">
                    <div class="form_input-error-message"></div>
                </div>
                <div class="form_input-group">
                    <input type="text" name="email_signup" class="form_input" autofocus placeholder="Email Address">
                    <div class="form_input-error-message"></div>
                </div>

                <div class="form_input-group">
                    <input type="password" name="password_signup" class="form_input" autofocus placeholder="Password">
                    <div class="form_input-error-message"></div>
                </div>
                <div class="form_input-group">
                    <input type="password" name="repassword_signup" class="form_input" autofocus placeholder="Confirm password">
                    <div class="form_input-error-message"></div>
                </div>

                <input type="hidden" name="action" value="signup">

                <button class="form_button" type="submit" name="signup">Sign Up</button>
                <p class="form_text">
                    Already have an account?
                    <a class="form_link" href="./" id="linkLogin">Log in</a>
                </p>
            </form>
        </div>
    <script>
        function setFormMessage(formElement, type, message) {
            const messageElement = formElement.querySelector(".form_message");

            messageElement.textContent = message;
            messageElement.classList.remove("form_message--success", "form_message--error");
            messageElement.classList.add(`form_message--${type}`);
        }

        function setInputError(inputElement, message) {
            inputElement.classList.add("form_input--error");
            inputElement.parentElement.querySelector(".form_input-error-message").textContent = message;
        }

        function clearInputError(inputElement) {
            inputElement.classList.remove("form_input--error");
            inputElement.parentElement.querySelector(".form_input-error-message").textContent = "";
        }

        // Hides/Reveals Login and Signup Forms
        document.addEventListener("DOMContentLoaded", () => {
            const loginForm = document.querySelector("#login");
            const createAccountForm = document.querySelector("#createAccount");

            document.querySelector("#linkCreateAccount").addEventListener("click", e => {
                e.preventDefault();
                loginForm.classList.add("form--hidden");
                createAccountForm.classList.remove("form--hidden");
            });

            document.querySelector("#linkLogin").addEventListener("click", e => {
                e.preventDefault();
                loginForm.classList.remove("form--hidden");
                createAccountForm.classList.add("form--hidden");
            });

        });
    </script>
    </body>
</html>



<?php include 'includes/footer.php'; ?>
