<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

  	$bookId = $_GET['bookID'];
  	$bookName = get_book_title($pdo, $bookId);
	$listID = $_GET['listID'];
	$listName = get_list_name($pdo, $listID);

	function remove_book_from_list(PDO $pdo, string $bookId, string $listID){
	    // start transaction
	    $pdo->beginTransaction();
	    	
	    // delete book into list
	    $sql = "DELETE FROM user_books WHERE listID = :listID and bookID = :bookId";
	    $stmt = pdo($pdo, $sql, ['listID' => $listID, 'bookId' => $bookId]);
	
	    // Commit transaction
	    $pdo->commit();
	    return ;
	}

	function get_book_title(PDO $pdo, string $bookId){
		$sql = "SELECT title FROM books WHERE bookID = :bookId";
		$bookTitle = pdo($pdo, $sql, ['bookId' => $bookId])->fetch();
		return $bookTitle['title'];
	}

	function get_list_name(PDO $pdo, string $listID){
		$sql = "SELECT list_name FROM reading_list WHERE listID = :listID";
		$listName = pdo($pdo, $sql, ['listID' => $listID])->fetch();
		return $listName['list_name'];
	}
	
	remove_book_from_list($pdo, $bookId, $listID);
	


	
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
			<div class="rm-book-list-container">
				<div class="rm-book-list-container">
					<button onclick="location.href='list_books.php?listID=<?= $listID ?>'; return false;" type="button">Back to Lists</button>
				</div>
            				<p><?= $bookName ?> has been removed from <?= $listName ?> List </p>
					
			</div>

		</main>

	</body>

</html>
