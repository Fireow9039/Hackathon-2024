<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['username'])) {
    header("Location: professor_login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['video'])) {
        $video = $_FILES['video']['name'];
        $target = "uploads/" . basename($video);
        move_uploaded_file($_FILES['video']['tmp_name'], $target);

        $sql = "INSERT INTO videos (title, filename) VALUES ('" . $_POST['title'] . "', '$video')";
        $conn->query($sql);
    }

    if (isset($_POST['generate_quiz'])) {
        $video_id = $_POST['video_id'];
        $questions = generateQuiz($video_id); // Call the Python script
        foreach ($questions as $question) {
            // Insert questions into database
            $sql = "INSERT INTO quizzes (video_id, question, option_a, option_b, option_c, option_d, correct_option) VALUES (
                '$video_id', '{$question['question']}', '{$question['options']['a']}', '{$question['options']['b']}', '{$question['options']['c']}', '{$question['options']['d']}', '{$question['correct_option']}')";
            $conn->query($sql);
        }
    }
}

function generateQuiz($video_id) {
    // Call Python script here
    $command = escapeshellcmd("python generate_quiz.py $video_id");
    $output = shell_exec($command);
    return json_decode($output, true);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, Professor</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Video:</label>
        <input type="file" name="video" required>
        <input type="submit" value="Upload Video">
    </form>

    <h2>Generate Quiz</h2>
    <form method="post">
        <label>Select Video:</label>
        <select name="video_id">
            <?php
            $result = $conn->query("SELECT * FROM videos");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['title']}</option>";
            }
            ?>
        </select>
        <input type="submit" name="generate_quiz" value="Generate Quiz">
    </form>

    <h2>Uploaded Videos</h2>
    <?php
    $result = $conn->query("SELECT * FROM videos");
    while ($row = $result->fetch_assoc()) {
        echo "<p>{$row['title']} - <a href='uploads/{$row['filename']}'>View Video</a></p>";
    }
    ?>

    <h2>View Quiz Results</h2>
    <form method="post" action="view_results.php">
        <label>Select Video:</label>
        <select name="video_id">
            <?php
            $result = $conn->query("SELECT * FROM videos");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['title']}</option>";
            }
            ?>
        </select>
        <input type="submit" value="View Results">
    </form>
</body>
</html>
