<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	function add_new_list(PDO $pdo, string $userId, string $listName, string $desc){
		// start transaction
		$pdo->beginTransaction();
		// create new group
		$sql = "INSERT INTO reading_list (list_name, userID, description, date_created) VALUES (:listName, :userId, :desc, CURDATE());";
		$stmt = pdo($pdo, $sql, ['listName' => $listName, 'userId' => $userId, 'desc' => $desc]);		

		// Commit transaction
		$pdo->commit();
	}

$created = False;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitNewList'])) {
        $newListName = $_POST['newListName'];
        $desc = $_POST['listDesc'];
        add_new_list($pdo, $_SESSION['userID'], $newListName, $desc); 
        $created = True;
    } 
    
}
	
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

			<div class="list-create-container">
				<div class="list-create-container">
					<h1>Create List</h1>
					<form action="new-list.php" method="POST">
              <div class="form-group">
                  <label for="newListName">New List Name:</label>
                  <input type="text" id="newListName" name="newListName" required>
              </div>
              <div class="form-group">
                  <label for="listDesc">Description:</label>
                  <input type="text" id="listDesc" name="listDesc" required>
              </div>
              <button type="submit" name="submitNewList">Add New List</button>
          </form>
				</div>	
        
				<?php if ($created): ?>
					<p>List "<?php echo $newListName; ?>" has been created.</p>
					<?php
						// Redirect back to list.php after displaying the message
						header("Location: list.php");
						exit; // Make sure to exit after redirection
					?>
				<?php endif; ?>

			</div>

		</main>

	</body>

</html>
