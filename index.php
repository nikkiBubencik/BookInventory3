<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	function get_book(PDO $pdo, string $id) {

		// SQL query to retrieve book information based on the book ID
		$sql = "SELECT * 
			FROM books
			WHERE bookID= :id;";	// :id is a placeholder for value provided later 
		                               // It's a parameterized query that helps prevent SQL injection attacks and ensures safer interaction with the database.


		// Execute the SQL query using the pdo function and fetch the result
		$book = pdo($pdo, $sql, ['id' => $id])->fetch();		// Associative array where 'id' is the key and $id is the value. Used to bind the value of $id to the placeholder :id in  SQL query.

		// Return the book information (associative array)
		return $book;
	}

	$book1 = get_book($pdo, '2');



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
  			<section class="book-catalog">
				<div class="book-lookup-container">
					<h1>Search Books</h1>
					<form action="book-cat.php" method="POST">
						<div class="form-books">
							<label for="title">Book Title:</label>
						        <input type="text" id="title" name="title" required>
						</div>

						<button type="submit">Lookup Book</button>
					</form>
				</div>
				
  				<div class="book-card">
  					<!-- Create a hyperlink to book.php page with book ID as parameter -->

  					<!-- Displaytitle of book -->
					<a href="book.php?bookID=<?= $book1['bookID'] ?>">
  						<h2><?= $book1['title'] ?></h2>

  					<!-- Display authors -->
  					<p><?= $book1['authors'] ?></p>
					</a>
  				</div>

   			</section>
  		</main>

	</body>
</html>



