<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';
	require_once 'includes/sessions.php';

	$deleteGroup = $_GET['deleteGroup'] ?? False;
	$groupId = $_GET['groupId'] ?? '';

	function search_groups_by_name(PDO $pdo, string $groupName, $userId){
		$sql = "SELECT *
			FROM groups as g join user_groups as ug on g.groupID = ug.groupID
			WHERE group_name LIKE :groupName and userID = :userId;";
		
		$group = pdo($pdo, $sql, ['groupName' => "%$groupName%", 'userId' => $userId])->fetchAll();		
		return $group;
	}

	function leave_group(PDO $pdo, string $groupID, string $userId){
		//begin transaction
		$pdo->beginTransaction();
		// delete user from group
		$sql = "DELETE FROM user_groups WHERE groupID = :groupID and userID = :userId;";
		$stmt = pdo($pdo, $sql, ['groupID' => $groupID, 'userId' => $userId]);

		// get count of users in group
		$memberCountSql = "SELECT count(*) as count FROM user_groups 
  				WHERE groupID = :groupID
      				GROUP BY groupID;";
		$memberCountResult = pdo($pdo, $memberCountSql, ['groupID' => $groupID])->fetch();

		// if no one remains in group delete it
		if($memberCountResult['count'] == 0 ){
			// delete group lists
			$deleteGroupLists = "DELETE FROM group_lists WHERE groupID = :groupID;";
			$stmt = pdo($pdo, $deleteGroupLists, ['groupID' => $groupID]);
			// delete group 
			$deletGroupSql = "DELETE FROM groups WHERE groupID = :groupID;";
			$stmt = pdo($pdo, $deletGroupSql, ['groupID' => $groupID]);

		}
		$pdo->commit();
	}

	if($deleteGroup){
		// *** CHANGE '1' TO USER ONCE LOGIN
		leave_group($pdo, $groupId, '1');
	}
	// Check if the request method is POST (i.e, form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Retrieve the value of the 'bookName' field from the POST data
		$groupName = $_POST['groupName'];
	}
	else{
		$groupName = '';
	}
	$allGroups = search_groups_by_name($pdo, $groupName, $_SESSION['userID']);
	
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
					<h1>Group Lookup</h1>
					<form action="groups.php" method="POST">
						<div class="form-group">
							<label for="groupName">Group Name: </label>
						        <input type="text" id="groupName" name="groupName" required>
						</div>

						<button type="submit">Lookup Group</button>
						<button onclick="location.href='new-group.php'; return false;" type="button">Add New Group</button>

					</form>
				</div>
				
				<div class="Group-names">
					<h2>Your Groups</h2>
					<ul style="list-style-type: none; padding: 0;">
				        <?php foreach ($allGroups as $group): ?>
						<li><a href="groupLists.php?groupID=<?= $group['groupID'] ?>&groupName=<?= $group['group_name'] ?>">
						<?= $group['group_name'] ?></a>
						</li>
			
					<hr>
				        <?php endforeach; ?>
				    	</ul>
				</div>
				
				

			</div>

		</main>

	</body>

</html>
