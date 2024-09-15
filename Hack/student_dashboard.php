<?php
session_start();
include 'db_config.php';

// Ensure the student is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: Student_login.php");
    exit();
}

// Get student ID from session
$student_id = $_SESSION['student_id'];

// Fetch the list of available videos
$query_videos = "SELECT * FROM videos";
$result_videos = $conn->query($query_videos);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
</head>
<body>

<h1>Welcome, Student</h1>

<h2>Available Videos</h2>

<?php
if ($result_videos->num_rows > 0) {
    // Loop through each video
    while ($row = $result_videos->fetch_assoc()) {
        // Display the video title with a watch video link
        echo "<p>{$row['title']} - <a href='watch_video.php?video_id={$row['id']}'>Watch Video</a></p>";

        // Fetch the student's quiz result for the current video (if they have taken the quiz)
        $quiz_id = $row['quiz_id'];
        $query_quiz_result = "SELECT * FROM quiz_results WHERE quiz_id = ? AND student_id = ?";
        $stmt = $conn->prepare($query_quiz_result);
        $stmt->bind_param("ii", $quiz_id, $student_id);
        $stmt->execute();
        $result_quiz = $stmt->get_result();

        // Check if a quiz result exists for the current video
        if ($result_quiz->num_rows > 0) {
            $quiz_result = $result_quiz->fetch_assoc();
            // Display the score
            echo "<p>Score: " . $quiz_result['score'] . "/10</p>";
        } else {
            echo "<p>No quiz submitted yet for this video.</p>";
        }
    }
} else {
    echo "<p>No videos available.</p>";
}
?>

</body>
</html>
