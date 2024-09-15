<?php
session_start();
include('db_config.php');

if (!isset($_SESSION['username'])) {
    header("Location: professor_login.php");
}

$video_id = $_POST['video_id'];

$sql = "SELECT students.username, COUNT(*) AS correct_answers
        FROM quiz_results
        JOIN students ON quiz_results.student_id = students.id
        WHERE quiz_results.video_id = '$video_id' AND quiz_results.is_correct = 1
        GROUP BY students.username";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Quiz Results for Video ID: <?php echo htmlspecialchars($video_id); ?></h1>
    <table>
        <thead>
            <tr>
                <th>Student Username</th>
                <th>Correct Answers</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['username']}</td>
                    <td>{$row['correct_answers']}</td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
