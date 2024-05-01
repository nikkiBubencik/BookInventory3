<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	  $validUser = 0;
	  $groupId = $_GET['groupId'];
	  $groupName = $_GET['groupName'];

	function add_group_user(PDO $pdo, string $fname, string $lname, string $groupId, $validUser){
		// start transaction
	    $pdo->beginTransaction();
	    // get userId
	    $userIdSql = "SELECT userID from users WHERE first_name = :fname and last_name = :lname;";
	    $userId = pdo($pdo, $userIdSql, ['fname' => $fname, 'lname' => $lname])->fetch();
	
	    if(!$userId){
	        $validUser = 1;
	        $pdo->rollBack();
	        return $validUser;
	    }
	    // see if user in group
	    $UserInGroupQuery = "SELECT COUNT(*) AS count FROM user_groups WHERE userID = :userId AND groupID = :groupId;";
	    $countResult = pdo($pdo, $UserInGroupQuery, ['groupId' => $groupId, 'userId' => $userId['userID']])->fetch();
	    if($countResult['count'] > 0) {
	        $validUser = 2;
	        $pdo->rollBack();
	        return $validUser;
	    }
  
	    // add user to group
	    $userGroupSql = "INSERT INTO user_groups (groupID, userID) VALUES (:groupId, :userId);";
	    $stmt = pdo($pdo, $userGroupSql, ['groupId' => $groupId, 'userId' => $userId['userID']]); 

	    // Commit transaction
	    $pdo->commit();
	    return $validUser;
	}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitNewGroupUser'])) {
        $fname = $_POST['newUserFname'];
        $lname = $_POST['newUserLname'];
        $validUser = add_group_user($pdo, $fname, $lname, $groupId, $validUser); 
        
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

			<div class="group-add-container">
				<div class="group-add-container">
					<h1>Add User to Group</h1>
					<form action="add-group-user.php?groupId=<?= $groupId ?>&groupName=<?= $groupName ?>" method="POST">
              <div class="form-group">
                  <label for="newUserFname">First Name:</label>
                  <input type="text" id="newUserFname" name="newUserFname" required>
              </div>
              <div class="form-group">
                  <label for="newUserLname">Last Name:</label>
                  <input type="text" id="newUserLname" name="newUserLname" required>
              </div>

              <button type="submit" name="submitNewGroupUser">Add New Group Member</button>
          </form>
				</div>	
           				<?php if(isset($_POST['submitNewGroupUser'])): ?>
					    <?php if($validUser == 0): ?>
					        <p><?= $_POST['newUserFname'] ?> <?= $_POST['newUserLname'] ?> has been added to <?= $groupName ?></p>
					    <?php elseif($validUser == 1): ?>
					        <p><?= $_POST['newUserFname'] ?> <?= $_POST['newUserLname'] ?> is not a valid user</p>
					    <?php elseif($validUser == 2): ?>
					        <p><?= $_POST['newUserFname'] ?> <?= $_POST['newUserLname'] ?> already in <?= $groupName ?></p>
					    <?php endif; ?>
					<?php endif; ?> 

			</div>

		</main>

	</body>

</html>
