<?php   										// Opening PHP tag
	
	// Include the database connection script
	require 'includes/database-connection.php';
	include 'includes/header-member.php';

	  $bookID = $_GET['bookID'];

	function add_review(PDO $pdo, $bookID, string  $review_text, $rating, $userId){
		// start transaction
	    $pdo->beginTransaction();
	    // add user to group
	    $AddReviewSQL = "INSERT INTO reviews (bookID, review_text, userID, rating) VALUES (:bookID, :review_text, :userId, :rating);";
	    $stmt = pdo($pdo, $AddReviewSQL, ['bookID' => $bookID, 'review_text' => $review_text, 'userId' => $userId, 'rating' => $rating]); 
	    // Commit transaction
	    $pdo->commit();
	}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitNewReview'])) {
        $review_text = $_POST['review_text'];
        $rating = $_POST['rating'];
        add_review($pdo, $bookID, $review_text, $rating, $_SESSION['userID']); 
        
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
					<h1>Add Review</h1>
					<form action="add-review.php?bookID=<?= $bookID ?>" method="POST">
              <div class="form-group">
                  <label for="review_text">Review:</label>
                  <input type="text" id="review_text" name="review_text" required>
              </div>
              <div class="form-group">
                  <label for="rating">Rating:</label>
                  <input type="text" id="rating" name="rating" required>
              </div>

              <button type="submit" name="submitNewReview">Add Review</button>
          </form>
				</div>	

			<?php
				// Check if the form is submitted
				if(isset($_POST['submitNewReview'])) {
					// Redirect to reviews.php with bookID and title parameters
					// $bookID = $info['bookID'];
					// $title = $info['title'];
					$book_id = $_GET['bookID'];
					$book_title = $_GET['title'];
					header("Location: reviews.php?bookID=$bookID&title=$title");
					exit; // Make sure to exit after redirection
				}
			?>


			</div>

		</main>

	</body>

</html>
