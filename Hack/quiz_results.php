<?php
// Database connection
include 'db_config.php';

// Get the quiz ID and score from the URL
$quiz_id = $_GET['quiz_id'];
$score = $_GET['score'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Result</title>
</head>
<body>

<h1>Quiz Results</h1>
<p>Your score: <?php echo $score; ?>/10</p>

<!-- Show answer explanations -->
<h2>Answer Explanations</h2>
<?php
// Fetch the questions and explanations from the database
$query = "SELECT question, correct_option, explanation FROM quiz_questions WHERE quiz_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$result = $stmt->get_result();

while ($question = $result->fetch_assoc()) {
    echo "<p>Question: ".$question['question']."</p>";
    echo "<p>Correct Answer: ".$question['correct_option']."</p>";
    echo "<p>Explanation: ".$question['explanation']."</p>";
}
?>

</body>
</html>
