<?php
// Database connection
include 'db_config.php';

// Assume the student is logged in, and we have their student ID
$student_id = $_SESSION['student_id'];

// Get the quiz answers submitted by the student
$answers = $_POST['answer'];

// Loop through each question, check the answer, and calculate the score
$score = 0;
foreach ($answers as $question_id => $selected_option) {
    // Fetch the correct answer from the database
    $query = "SELECT correct_option FROM quiz_questions WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();

    // Check if the selected option is correct
    if ($selected_option == $question['correct_option']) {
        $score++;
    }
}

// Store the result in the database
$query = "INSERT INTO quiz_results (student_id, quiz_id, score) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $student_id, $quiz_id, $score);
$stmt->execute();

// Redirect to a result page
header("Location: quiz_result.php?quiz_id=".$quiz_id."&score=".$score);
exit();
?>
