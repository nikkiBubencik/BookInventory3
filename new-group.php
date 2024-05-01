<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	function add_new_group(PDO $pdo, string $userId, string $groupName){
		// start transaction
    $pdo->beginTransaction();
    // create new group
    $sql = "INSERT INTO groups (group_name) VALUES (:groupName);";
    $stmt = pdo($pdo, $sql, ['groupName' => $groupName]);		

    // get new groupID
    $newGroupId = $pdo->lastInsertId();

    // add user to group
    $userGroupSql = "INSERT INTO user_groups (groupID, userID) VALUES (:groupID, :userId);";
    $stmt = pdo($pdo, $userGroupSql, ['groupID' => $newGroupId, 'userId' => $userId]);

    // Commit transaction
    $pdo->commit();
	}

$created = False;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitNewGroup'])) {
        $newGroupName = $_POST['newGroupName'];
        // create new group
        add_new_group($pdo, $_SESSION['userID'], $newGroupName); 
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

			<div class="group-lookup-container">
				<div class="group-lookup-container">
					<h1>Create Group</h1>
					<form action="new-group.php" method="POST">
              <div class="form-group">
                  <label for="newGroupName">New Group Name:</label>
                  <input type="text" id="newGroupName" name="newGroupName" required>
              </div>

              <button type="submit" name="submitNewGroup">Add New Group</button>
          </form>
				</div>	
        
				<?php if($created): ?>
            <p>Group "<?php echo $newGroupName; ?>" has been created.</p>
					<?php
						// Redirect back to list.php after displaying the message
						header("Location: groups.php");
						exit; // Make sure to exit after redirection
					?>
        <?php endif; ?>


			</div>

		</main>

	</body>

</html>
