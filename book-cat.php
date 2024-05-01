<?php   										// Opening PHP tag
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';		

	function search_books_by_name(PDO $pdo, string $bookName){
		$sql = "SELECT * 
				FROM books 
					WHERE title LIKE :bookName 
				LIMIT 25;";
		
		$books = pdo($pdo, $sql, ['bookName' => "%$bookName%"])->fetchAll();		
		return $books;
	}
	function search_books_by_author(PDO $pdo, string $authorName){
		$sql = "SELECT *
				FROM books
					WHERE authors LIKE :authorName
				LIMIT 25;";
		
		$books = pdo($pdo, $sql, ['authorName' => "%$authorName%"])->fetchAll();		
		return $books;
	}
	function search_books_by_year(PDO $pdo, string $year){
		$sql = "SELECT *
				FROM books
					WHERE year_published LIKE :year
				LIMIT 25;";
		
		$books = pdo($pdo, $sql, ['year' => "%$year%"])->fetchAll();		
		return $books;
	}
	
	// Check if the request method is POST (i.e, form submitted)
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		$searchBy = $_POST['searchBy'] ?? "title";
		$bookName = $_POST['bookName'] ?? "";
		
		// Check which radio button is selected
		if ($searchBy == "title") {
			// Code for searching by title
			$books = search_books_by_name($pdo, $bookName);
		} elseif ($searchBy == "author") {
			// Code for searching by author
			$books = search_books_by_author($pdo, $bookName);
		} elseif ($searchBy == "year") {
			// Code for searching by year
			$books = search_books_by_year($pdo, $bookName);
		}
	}
	else{
		$bookName = "";
		$searchBy = "title";
	}
 ?> 

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

			<div class="book-lookup-container" style="width:40%; margin:auto; text-align:center; margin-bottom:10px;">
				<div class="book-lookup-container" >
					<h1>Book Lookup</h1>
					<form action="book-cat.php" method="POST">
						<label for="searchBy">Search By: </label>
						<div class="form-group" style="display:flex;">
							<input type="radio" id="title" name="searchBy" value="title" checked>
							<label for="title">Title</label>&nbsp;&nbsp;&nbsp;
							<input type="radio" id="author" name="searchBy" value="author">
							<label for="author">Author</label>&nbsp;&nbsp;&nbsp;
							<input type="radio" id="year" name="searchBy" value="year">
							<label for="year">Year Published</label>
						</div>
						</div>
						<div class="form-group" style="width:100%;">
						        <input type="text" id="bookName" name="bookName" >
						</div>
						<button type="submit">Lookup Book</button>
					</form>
				</div>
				
				<div class="Books-names" style="margin:auto; width: 40%; text-align:center; background-color: lightgray;">
				<ul style="list-style-type: none; padding: 0;">
				        
						<ul style="list-style-type: none; padding: 0;">
                    <?php if (isset($books) && !empty($books)): ?>
                        <?php foreach ($books as $book): ?>
                            <li><a href="book.php?bookID=<?= $book['bookID']; ?>"><?= $book['title'] ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>No records found.</li>
                    <?php endif; ?>



				</ul>
				</div>
			</div>

		</main>

	</body>

</html>
