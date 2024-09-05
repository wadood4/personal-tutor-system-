<?php
session_start();
include("Database.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: Student _Login_Register_Page.php");
    exit();
}

$student_id = $_SESSION['student_id'];

$query = "SELECT username, total_grade FROM student WHERE id = '$student_id'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
    $username = $student['username'];
    $total_grade = $student['total_grade'];
} else {
    echo "Error fetching student data.";
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
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

        .dashboard {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .dashboard h1 {
            margin-bottom: 20px;
            font-size: 36px;
            font-family: 'Impact', sans-serif;
            text-shadow: 2px 2px #6a0572;
        }

        .dashboard p {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .dashboard a {
            display: inline-block;
            margin: 10px;
            padding: 15px 30px;
            text-decoration: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid white;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dashboard a:hover {
            background-color: white;
            color: #6a0572;
        }

        @media (max-width: 600px) {
            .nav-links li {
                margin: 0 10px;
            }

            .dashboard h1 {
                font-size: 28px;
            }

            .dashboard p {
                font-size: 20px;
            }

            .dashboard a {
                padding: 12px 24px;
                font-size: 16px;
            }
        }

        @media (orientation: landscape) {
            .button-container {
                flex-direction: row;
            }

            .dashboard a {
                margin: 10px 20px;
            }
        }
    </style>
</head>
<body>
<header class="page-header">
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="Landing_Page.php">Home</a></li>
        </ul>
    </nav>
</header>
<div class="dashboard">
    <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
    <p>Your total grade: <?php echo htmlspecialchars($total_grade); ?></p>
    <div class="button-container">
        <a href="Content_Page.php">View Content</a>
        <a href="Quizzes_Page.php">View Quizzes</a>
    </div>
</div>
</body>
</html>
