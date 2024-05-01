<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';


	function find_lists_by_name(PDO $pdo, string $listName){
		$sql = "SELECT *
				FROM reading_list
				WHERE list_name LIKE :listName;";
		
		$list = pdo($pdo, $sql, ['listName' => "%$listName%"])->fetchAll();		
		return $list;
	}


	function get_all_user_lists(PDO $pdo, $userId) {
	    	$sql = "SELECT * FROM reading_list WHERE userID = :userId";
		$lists = pdo($pdo, $sql, ['userId' => $userId])->fetchAll();		

	    	return $lists;
	}
	// CHNAGE '1' to $userId so its for the user who is logged in
	// $allLists = ($_SERVER["REQUEST_METHOD"] == "POST") ? $lists : get_all_user_lists($pdo, '1');
// Closing PHP tag  ?> 

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
		<main>

			<div class="list-lookup-container">
				<div class="list-lookup-container">
					<h1>List Lookup</h1>
					<form action="list.php" method="POST">
						<div class="form-group">
							<label for="listName">List Name: </label>
						        <input type="text" id="listName" name="listName" required>
						</div>

						<button type="submit">Lookup List</button>
						<button onclick="location.href='new-list.php'; return false;" type="button">Add New List</button>

					</form>

<div class = "search-results">
<h1>Search Results</h1>

			<?php
        // Check if the request method is POST (i.e., form submitted)
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve the value of the 'listName' field from the POST data
            $listName = $_POST['listName'];
            // Search for lists by name
            $lists = find_lists_by_name($pdo, $listName);
            // Check if any lists are found
            if ($lists) {
                // If lists are found, display them
foreach ($lists as $list) {
    echo "<p><a href='list_books.php?listID=" . $list['listID'] . "&listName=" . $list['list_name'] . "'>" . $list['list_name'] . "</a></p>";
}

            } else {
                // If no lists are found, display a message
                echo "<p>No lists found.</p>";
            }
        }
        ?>
</div>
				</div>
				


				<div class="list-names">
					<h2>Your Lists</h2>
					<ul>
						<?php 
						// Assuming $pdo is your database connection
						// Retrieve userID from the session
						$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : null;

						if ($userID === null) {
							// If the user is not logged in, display the message and link
							echo '<li><a href="login.php">Log in</a> to make a list.</li>';
						} else {

						// Query to fetch lists belonging to the current user
						$sql = "SELECT * FROM reading_list WHERE userID = ?";
						$statement = $pdo->prepare($sql);
						$statement->execute([$userID]);
						$userLists = $statement->fetchAll(PDO::FETCH_ASSOC);

						// Iterate through the user's lists
						foreach ($userLists as $list): ?>
							<li><a href="list_books.php?listID=<?= $list['listID'] ?>&listName=<?= $list['list_name'] ?>">
							<?= $list['list_name'] ?></a></li>
						<?php endforeach; } ?>
					</ul>				</div>
				
				

			</div>
		</main>

	</body>

</html>
