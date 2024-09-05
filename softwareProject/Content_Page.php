<?php
session_start();
include("Database.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: Student _Login_Register_Page.php");
    exit();
}

$query = "SELECT * FROM Content";
$result = mysqli_query($conn, $query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Content</title>
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

        .content-item {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
            width: 80%;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            text-align: left;
        }

        .content-item h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            text-shadow: 1px 1px #6a0572;
        }

        .content-item a {
            color: #1e90ff;
            text-decoration: none;
            font-size: 18px;
        }

        .content-item a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .nav-links li {
                margin: 0 10px;
            }

            .content h1 {
                font-size: 28px;
            }

            .content-item {
                width: 90%;
            }

            .content-item h2 {
                font-size: 20px;
            }

            .content-item a {
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
    <h1>Uploaded Content</h1>
<?php
if (mysqli_num_rows($result) > 0) {
    while ($content = mysqli_fetch_assoc($result)) {
        echo "<div class='content-item'>";
        echo "<h2>" . htmlspecialchars($content['title']) . "</h2>";
        if (!empty($content['file_path'])) {
            echo "<p><a href='" . htmlspecialchars($content['file_path']) . "' download>Download File</a></p>";
        }
        if (!empty($content['video_link'])) {
            echo "<p><a href='" . htmlspecialchars($content['video_link']) . "' target='_blank'>Watch Video</a></p>";
        }
        echo "</div>";
    }
} else {
    echo "<p>No content available.</p>";
}
?>
</body>
</html>

