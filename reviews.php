<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	$book_id = $_GET['bookID'];
  	$book_title = $_GET['title'];


	function get_reviews(PDO $pdo, string $id){
		$sql = " SELECT r.rating, r.review_text, u.username
				FROM reviews as r JOIN users as u ON r.userID = u.userID
				WHERE bookID= :id";
		// switch ($sortOrder) {
		// 	case 'highest_rating':
		// 		$sql .= "r.rating DESC";
		// 		break;
		// 	// case 'lowest_rating':
		// 	// 	$sql .= "r.rating ASC";
		// 	// 	break;
		// 	// case 'oldest':
		// 	// 	$sql .= "u.date_added ASC";
		// 	// 	break;
		// 	// case 'newest':
		// 	// 	$sql .= "u.date_added DESC";
		// 	// 	break;
		// 	default:
		// 		// Default to sorting by highest rating
		// 		$sql .= "r.rating DESC";
		// 		break;
		// }
		$review = pdo($pdo, $sql, ['id' => $id])->fetchAll();	

		return $review;
	}

	// Default sort order
	// $sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'highest_rating';

	$all_reviews = get_reviews($pdo, $book_id);

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
				
				<div class="book-details">

					<!-- Display title of book -->
			        <h1><?= $book_title ?></h1>

			        <hr />
				<form action="add-review.php?bookID=<?= $book_id ?>" method="POST">
		                    <button type="submit" name="addReview">Add Review</button>
		                </form>
					
				<!-- Dropdown menu for sorting -->
<!-- 			        <div class="dropdown">
					<button class="dropbtn">Sort by</button>
					<div class="dropdown-content">
					    	<a href="?sort=highest_rating" <?php if ($sortOrder == 'highest_rating') echo 'class="selected"'; ?>>Highest Rating</a>
						<a href="?sort=lowest_rating" <?php if ($sortOrder == 'lowest_rating') echo 'class="selected"'; ?>>Lowest Rating</a>
						<a href="?sort=oldest" <?php if ($sortOrder == 'oldest') echo 'class="selected"'; ?>>Oldest</a>
						<a href="?sort=newest" <?php if ($sortOrder == 'newest') echo 'class="selected"'; ?>>Newest</a>
				  	</div>
				</div> -->
                <a href="book.php?bookID=<?= $book_id ?>">Back</a>
				<br>	
			        <h3>Reviews</h3>

			        <!-- Display all reviews -->
			        <ul>
					<?php if (empty($all_reviews)): ?>
    <!-- No reviews message -->
    <p>No reviews, yet.</p>
<?php else: ?>
    <!-- Display all reviews -->
    <ul>
        <?php foreach ($all_reviews as $review): ?>
            <li>
                <strong>Rating:</strong> <?= $review['rating'] ?><br>
                <strong>Review:</strong> <?= $review['review_text'] ?><br>
                <strong>User:</strong> <?= $review['username'] ?>
            </li>
            <hr>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
			        </ul>
			        
			    </div>
			</div>
		</main>

	</body>
</html>
