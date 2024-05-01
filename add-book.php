<?php
require 'includes/database-connection.php'; // Database connection script
include 'includes/header-member.php'; 		// Header file

// Get the book ID from the URL parameter
$bookId = $_GET['bookId'];
$bookName = get_book_title($pdo, $bookId);
  // $bookName = $_GET['bookName'];
$listName = '';

// get book title from database
function get_book_title(PDO $pdo, string $bookId) {
    $sql = "SELECT title FROM books WHERE bookID = :bookId";
    $bookTitle = pdo($pdo, $sql, ['bookId' => $bookId])->fetch();
    return $bookTitle['title'];
}

// add a book to a list
function add_book_to_list(PDO $pdo, string $bookId, int $listId, $listNotFound) {
    $pdo->beginTransaction();

    // Check if the book already exists in the list
    $bookExistsQuery = "SELECT COUNT(*) AS count FROM user_books WHERE listID = :listId AND bookID = :bookId";
    $bookExistsResult = pdo($pdo, $bookExistsQuery, ['listId' => $listId, 'bookId' => $bookId])->fetch();
    if ($bookExistsResult['count'] > 0) {
        // If the book exists in the list, set listNotFound flag to 2
        $listNotFound = 2;
        $pdo->rollBack();
        return $listNotFound;
    }

    // Insert the book into the list
    $sql = "INSERT INTO user_books (listID, bookID, date_added) VALUES (:listId, :bookId, CURDATE())";
    $stmt = pdo($pdo, $sql, ['listId' => $listId, 'bookId' => $bookId]);

    $pdo->commit();
    return $listNotFound;
}

// add a new list
function add_new_list(PDO $pdo, string $userId, string $listName, string $desc) {
    $pdo->beginTransaction();

    // Insert a new list into the database
    $sql = "INSERT INTO reading_list (list_name, userID, description, date_created) VALUES (:listName, :userId, :desc, CURDATE())";
    $stmt = pdo($pdo, $sql, ['listName' => $listName, 'userId' => $userId, 'desc' => $desc]);

    // Get the ID of the newly inserted list
    $newListId = $pdo->lastInsertId();
    $pdo->commit();
    return $newListId;
}

// Initialize variables
$listNotFound = 0;
$created = false;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['lists'])) {
        // Process existing lists
        $selectedLists = $_POST['lists'];
        if (is_array($selectedLists)) {
            foreach ($selectedLists as $selectedListId) {
                // Add the book to each selected existing list
                $listNotFound = add_book_to_list($pdo, $bookId, $selectedListId, $listNotFound);
            }
        }
    } else {
        // Handle case where no lists are selected
        echo '<span style="color: red;">Please select one or more lists to add the book to.</span>';
    }

    if (isset($_POST['submitNewList'])) {
        // Process new lists
        if (isset($_POST['newListName']) && is_array($_POST['newListName'])) {
            $listDesc = isset($_POST['listDesc']) ? $_POST['listDesc'] : array();
            foreach ($_POST['newListName'] as $key => $newListName) {
                if (!empty($newListName)) {
                    // Add the new list and add the book to it
                    $desc = isset($listDesc[$key]) ? $listDesc[$key] : ''; 
                    $newListId = add_new_list($pdo, $_SESSION['userID'], $newListName, $desc);
                    $created = true;
                    add_book_to_list($pdo, $bookId, $newListId, $listNotFound);
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
<main>
    <!-- Back button -->
    <a href="javascript:window.history.back();" class="back-button">Back</a>

    <!-- Add book to list form -->
    <div class="add-book-list-container">
        <h1>Add <?= $bookName ?> to your Lists</h1>
        <form action="add-book.php?bookId=<?= $bookId ?>" method="POST">
            <div class="form-group">
                <label for="listName">Select Lists or Enter New List Name:</label>
                <!-- Existing lists -->
                <?php
                $userID = $_SESSION['userID'];
                $sql = "SELECT * FROM reading_list WHERE userID = ?";
                $statement = $pdo->prepare($sql);
                $statement->execute([$userID]);
                $userLists = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($userLists as $list) {
                    echo '<div class="checkbox-container">';
                    echo '<input type="checkbox" id="' . $list['listID'] . '" name="lists[]" value="' . $list['listID'] . '">';
                    echo '<label for="' . $list['listID'] . '">' . $list['list_name'] . '</label>';
                    echo '</div>';
                }
                ?>
            </div>
            <!-- New list input fields -->
            <div id="newListContainer">
                <!-- New List input fields will be dynamically added here -->
            </div>
            <!-- Button to add new list -->
            <button type="button" id="addNewListButton">+ New List</button>
            <!-- Hidden input field to indicate new list submission -->
            <input type="hidden" name="submitNewList" value="true">
            <!-- Submit button -->
            <button type="submit">Add Book to List(s)</button>
        </form>
    </div>
    <!-- Display messages after form submission -->
    <?php if(isset($_POST['lists'])): ?>
        <?php if($listNotFound == 1): ?>
            <p> <?= $listName ?> not found</p>
        <?php elseif($listNotFound == 0): ?>
            <p><?= $bookName ?> has been added to the selected list(s) </p>
        <?php elseif($listNotFound == 2): ?>
            <p><?= $bookName ?> is already in one or more of the selected list(s) </p>
        <?php endif; ?>
    <?php endif; ?>
</main>

<!-- JavaScript code to handle dynamic addition of new list input fields -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var newListContainer = document.getElementById('newListContainer');
        var addNewListButton = document.getElementById('addNewListButton');
        var newListCount = 0;

        addNewListButton.addEventListener('click', function () {
            // Create a container for new list input fields
            var container = document.createElement('div');
            var newListInput = document.createElement('input');
            newListInput.type = 'text';
            newListInput.name = 'newListName[]';
            newListInput.placeholder = 'Enter New List Name';

            // Create an input field for list description
            var newListDescInput = document.createElement('input');
            newListDescInput.type = 'text';
            newListDescInput.name = 'listDesc[]';
            newListDescInput.placeholder = 'Enter List Description';

            // Create a remove button for the new list input fields
            var removeButton = document.createElement('button');
            removeButton.innerHTML = 'x';
            removeButton.type = 'button';
            removeButton.addEventListener('click', function () {
                // Remove the container when the remove button is clicked
                newListContainer.removeChild(container);
                newListCount--;
            });

            // Append input fields and remove button to the container
            container.appendChild(newListInput);
            container.appendChild(newListDescInput);
            container.appendChild(removeButton);

            // Append the container to the newListContainer
            newListContainer.appendChild(container);

            // Add a line break
            newListContainer.appendChild(document.createElement('br'));

            newListCount++;
        });

        // Update hidden input field value based on new list input fields
        submitButton.addEventListener('click', function () {
            var newListInputs = document.querySelectorAll('#newListContainer input[type="text"]');
            var submitNewList = false;

            // Check if any new list input field is filled
            newListInputs.forEach(function (input) {
                if (input.value.trim() !== '') {
                    submitNewList = true;
                }
            });

            // Set the value of hidden input field accordingly
            if (submitNewList) {
                document.getElementById('submitNewList').value = 'true';
            }
        });
    });
</script>

</body>
</html>
