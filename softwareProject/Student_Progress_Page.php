<?php
include("Database.php");

// Fetch all students and their total grades
$students_query = "SELECT id, username, total_grade FROM Student";
$students_result = mysqli_query($conn, $students_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress</title>
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

        table {
            width: 80%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        table, th, td {
            border: 2px solid white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            font-size: 18px;
        }

        th {
            background-color: rgba(255, 255, 255, 0.2);
        }

        td {
            background-color: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 600px) {
            .nav-links li {
                margin: 0 10px;
            }

            .content h1 {
                font-size: 28px;
            }

            table {
                width: 90%;
            }

            th, td {
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
            <li><a href="Instructor_Dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
</header>
<main class="content">
    <h1>Student Progress</h1>

<?php
if (mysqli_num_rows($students_result) > 0) {
    echo "<table>";
    echo "<tr><th>Student Name</th><th>Total Marks</th><th>Quiz Details</th></tr>";
    while ($student = mysqli_fetch_assoc($students_result)) {
        $student_id = $student['id'];
        $username = $student['username'];
        $total_grade = $student['total_grade'];

        echo "<tr>";
        echo "<td>" . htmlspecialchars($username) . "</td>";
        echo "<td>" . htmlspecialchars($total_grade) . "</td>";
        echo "<td>";

        // Fetch quiz results for the student
        $results_query = "
            SELECT q.title, r.marks_obtained
            FROM Student_Quiz_Results r
            JOIN Quizzes q ON r.quiz_id = q.id
            WHERE r.student_id = '$student_id'
        ";
        $results_result = mysqli_query($conn, $results_query);

        if (mysqli_num_rows($results_result) > 0) {
            echo "<ul>";
            while ($result = mysqli_fetch_assoc($results_result)) {
                $quiz_title = $result['title'];
                $marks_obtained = $result['marks_obtained'];
                echo "<li><strong>" . htmlspecialchars($quiz_title) . ":</strong> " . htmlspecialchars($marks_obtained) . " marks</li>";
            }
            echo "</ul>";
        } else {
            echo "No quiz results available.";
        }

        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No students found.";
}

mysqli_close($conn);
?>

</body>
</html>
