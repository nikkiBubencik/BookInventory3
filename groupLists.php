<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	$groupId = $_GET['groupID'] ?? '';
	$groupName = $_GET['groupName'] ?? '';
	

	function search_group_list_by_name(PDO $pdo, string $listName, string $groupId){
		$sql = "SELECT *
			FROM reading_list  as r
        		JOIN group_lists as g on r.listID = g.listID
			WHERE list_name LIKE :listName 
   			AND groupID = :groupId;";
		
		$list = pdo($pdo, $sql, ['listName' => "%$listName%", 'groupId' => $groupId])->fetchAll();		
		return $list;
	}
	

	// Check if the request method is POST (i.e, form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Retrieve the value of the 'bookName' field from the POST data
		$listName = $_POST['listName'];
	}
	else{
		$listName = '';
	}

	$allList = search_group_list_by_name($pdo, $listName, $groupId);
	
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

			<div class="group-list-lookup-container">
				<div class="group-list-lookup-container">
					<h1>Group List Lookup</h1>
					<form action="groupLists.php" method="POST">
						<div class="form-group">
							<label for="listName">Group List Name: </label>
						        <input type="text" id="listName" name="listName" required>
						</div>

						<button type="submit">Lookup Group's List</button>
						<button onclick="location.href='add-group-user.php?groupId=<?= $groupId ?>&groupName=<?= $groupName ?>'; return false;" type="button">Add New User</button>
						<button onclick="location.href='groups.php?deleteGroup=True&groupId=<?= $groupId ?>'; return false;" type="button">Leave Group</button>

					</form>
				</div>
				
				<div class="Group-list-names">
					<h2><?= $groupName ?> List</h2>
					<ul style="list-style-type: none; padding: 0;">
				        <?php foreach ($allList as $list): ?>
						<li><a href="list_books.php?listID=<?= $list['listID'] ?>&list_Name=<?= $list['list_name'] ?>">
						<?= $list['list_name'] ?></a>
						</li>
			
					<hr>
				        <?php endforeach; ?>
				    	</ul>
				</div>
				
				

			</div>

		</main>

	</body>

</html>