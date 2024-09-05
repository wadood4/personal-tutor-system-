<?php
session_start();
include("Database.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: Student _Login_Register_Page.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// Fetch all visible quizzes
$quizzes_query = "
    SELECT q.id, q.title, q.deadline, q.total_marks, IFNULL(r.marks_obtained, -1) as marks_obtained 
    FROM Quizzes q
    LEFT JOIN (
        SELECT quiz_id, marks_obtained
        FROM Student_Quiz_Results
        WHERE student_id = '$student_id'
    ) r ON q.id = r.quiz_id
    WHERE q.visible = 1
";
$quizzes_result = mysqli_query($conn, $quizzes_query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes</title>
    <style>
        body {
            margin: 0;
            font-family: 'Comic Sans MS', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            color: white;
        }

        .page-header {
            background-color: #141e30;
            color: white;
            padding: 10px 0;
        }

        .navbar {
            display: flex;
            justify-content: center;
        }

        .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .nav-links li {
            margin: 0 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .content h1 {
            margin-bottom: 20px;
            font-size: 36px;
            font-family: 'Impact', sans-serif;
            text-shadow: 2px 2px #6a0572;
        }

        .quiz-item {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
            width: 80%;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            text-align: left;
        }

        .quiz-item h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            text-shadow: 1px 1px #6a0572;
        }

        .quiz-item p {
            margin: 5px 0;
        }

        .quiz-item a {
            color: #1e90ff;
            text-decoration: none;
            font-size: 18px;
        }

        .quiz-item a:hover {
            text-decoration: underline;
        }

        .missed {
            color: red;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .nav-links li {
                margin: 0 10px;
            }

            .content h1 {
                font-size: 28px;
            }

            .quiz-item {
                width: 90%;
            }

            .quiz-item h2 {
                font-size: 20px;
            }

            .quiz-item a {
                font-size: 16px;
            }
        }

    </style>
</head>
<body>
<header class="page-header">
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="Landing_Page.php">Home</a></li>
            <li><a href="Student%20Dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
</header>
<main class="content">
    <h1>Available Quizzes</h1>
<?php
if (mysqli_num_rows($quizzes_result) > 0) {
    $current_date = date('Y-m-d H:i:s');
    while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
        echo "<div class='quiz-item'>";
        echo "<h2>" . htmlspecialchars($quiz['title']) . "</h2>";
        echo "<p><strong>Deadline:</strong> " . htmlspecialchars($quiz['deadline']) . "</p>";
        echo "<p><strong>Total Marks:</strong> " . htmlspecialchars($quiz['total_marks']) . "</p>";

        if ($quiz['marks_obtained'] == -1) {
            if ($current_date > $quiz['deadline']) {
                echo "<p class='missed'>Missed</p>";
            } else {
                echo "<p><a href='Quiz_Taking_Page.php?quiz_id=" . htmlspecialchars($quiz['id']) . "'>Take Quiz</a></p>";
            }
        } else {
            echo "<p><strong>Your Marks:</strong> " . htmlspecialchars($quiz['marks_obtained']) . " (Done)</p>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No quizzes available.</p>";
}
?>
</body>
</html>
