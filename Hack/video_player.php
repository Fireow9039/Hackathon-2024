<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['username'])) {
    header("Location: student_login.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Welcome, Student</h1>
    <h2>Available Videos</h2>
    <?php
    $result = $conn->query("SELECT * FROM videos");
    while ($row = $result->fetch_assoc()) {
        echo "<p>{$row['title']} - <a href='video_player.php?video_id={$row['id']}'>Watch Video</a></p>";
    }
    ?>
</body>
</html>
