<?php
// Database connection
include 'db_config.php';

// Get the video ID from the URL
if (isset($_GET['video_id'])) {
    $video_id = $_GET['video_id'];

    // Fetch video details from the database
    $query = "SELECT * FROM videos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $video = $result->fetch_assoc();
        $video_path = $video['video_path'];
        $quiz_id = $video['quiz_id']; // Assume the quiz ID is linked to the video in the database
    } else {
        echo "Video not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Watch Video</title>
    <script>
        function showQuiz() {
            // Show the quiz section once the video is finished
            document.getElementById("quiz_section").style.display = "block";
        }
    </script>
</head>
<body>

<h1><?php echo $video['title']; ?></h1>

<!-- Video section -->
<video width="600" controls onended="showQuiz()">
    <source src="<?php echo $video_path; ?>" type="video/mp4">
    Your browser does not support the video tag.
</video>

<!-- Quiz section (initially hidden) -->
<div id="quiz_section" style="display:none;">
    <h2>Quiz</h2>
    <form method="POST" action="submit_quiz.php">
        <?php
        // Fetch quiz questions generated by Gemini AI for this video
        $query = "SELECT * FROM quiz_questions WHERE quiz_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($question = $result->fetch_assoc()) {
                echo "<p>".$question['question']."</p>";
                echo "<input type='radio' name='answer[".$question['id']."]' value='A'> ".$question['option_a']."<br>";
                echo "<input type='radio' name='answer[".$question['id']."]' value='B'> ".$question['option_b']."<br>";
                echo "<input type='radio' name='answer[".$question['id']."]' value='C'> ".$question['option_c']."<br>";
                echo "<input type='radio' name='answer[".$question['id']."]' value='D'> ".$question['option_d']."<br>";
            }
        } else {
            echo "No quiz available for this video.";
        }
        ?>

        <!-- Submit button for quiz -->
        <input type="submit" value="Submit Quiz">
    </form>
</div>

</body>
</html>
