<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	$book_id = $_GET['bookID'];

	function book_info(PDO $pdo, string $id){
		$sql = " SELECT *
				FROM books
				WHERE bookID= :id;";

		$info = pdo($pdo, $sql, ['id' => $id])->fetch();	

		return $info;
	}

	$info = book_info($pdo, $book_id);

// Closing PHP tag  ?> 

<!DOCTYPE>
<html>

	<head>
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  		<title>book Inventory</title>
  		<link rel="stylesheet" href="css/style.css">
  		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
	</head>

	<body>
		<main>
			<div class="book-details-container">
			<?php if (isset($_SESSION['userID'])) : ?>
				<button onclick="location.href='add-book.php?bookId=<?= $info['bookID'] ?>'; return false;" type="button">Add to List</button>
				<?php endif; ?>


				<a href="javascript:window.history.back();" class="back-button">Back</a>
				
				<div class="book-details">

				<!-- Display title of book -->
			        <h1><?= $info['title'] ?></h1>

			        <hr />

			        <h3>Book Information</h3>

			        <!-- Display authors -->
			        <p><strong>Authors:</strong> <?= $info['authors'] ?></p>

			        <!-- Display average rating -->
			        <p><strong>Average Rating(Good Reads): </strong>  <?= $info['ave_rating'] ?></p>

			        <!-- Display ISBN -->
			        <p><strong>ISBN: </strong> <?= $info['ISBN'] ?></p>

			        <!-- Display page count -->
			        <p><strong>Number of Pages: </strong> <?= $info['page_count'] ?></p>

				<p><strong>Publisher: </strong> <?= $info['publisher'] ?></p>			        
				
				<p><strong>Year Published: </strong> <?= $info['year_published'] ?></p>

				<a href="reviews.php?bookID=<?= $info['bookID'] ?>&title=<?= $info['title'] ?>">Customer Reviews</a>

			    </div>
			</div>
		</main>

	</body>
</html>