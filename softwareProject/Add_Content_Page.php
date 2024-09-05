<?php
include("Database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_id'])) {
        // Handle delete request
        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
        $query = "DELETE FROM Content WHERE id = '$delete_id'";
        if (mysqli_query($conn, $query)) {
            echo "Content deleted successfully.";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Handle add content request
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $file_path = NULL;
        $video_link = NULL;

        if (!empty($_FILES['file']['name'])) {
            $target_dir = "uploads/";

            // Check if the uploads directory exists, if not create it
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_path = $target_dir . basename($_FILES["file"]["name"]);
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $file_path)) {
                echo "File uploaded successfully.";
            } else {
                echo "No file was uploaded";
            }
        }

        if (!empty($_POST['video_link'])) {
            $video_link = mysqli_real_escape_string($conn, $_POST['video_link']);
        }

        $query = "INSERT INTO Content (title, file_path, video_link) VALUES ('$title', '$file_path', '$video_link')";
        if (mysqli_query($conn, $query)) {
            echo "Content added successfully.";
        } else {
            echo "no video was uploaded ";
        }
    }
}

// Fetch all content
$content_query = "SELECT * FROM Content";
$content_result = mysqli_query($conn, $content_query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Content</title>
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

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 80%;
            margin-bottom: 20px;
        }

        form label {
            font-size: 18px;
            margin-bottom: 5px;
        }

        form input[type="text"],
        form input[type="file"],
        form input[type="submit"] {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #6a0572;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #9d50bb;
        }

        .previous-uploads {
            width: 80%;
            margin: 0 auto;
        }

        .previous-uploads h2 {
            font-size: 24px;
            margin-bottom: 10px;
            text-shadow: 1px 1px #6a0572;
        }

        .content-item {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
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

            form {
                width: 90%;
            }

            form input[type="submit"] {
                padding: 10px;
            }

            .previous-uploads {
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
            <li><a href="Instructor_Dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
</header>
<main class="content">
    <h1>Add Content</h1>
    <form method="post" action="Add_Content_Page.php" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="file">Upload File:</label><br>
        <input type="file" id="file" name="file"><br>
        <label for="video_link">Video Link:</label><br>
        <input type="text" id="video_link" name="video_link"><br>
        <input type="submit" value="Add Content">
    </form>
    <div class="previous-uploads">
        <h2>Previous Uploads</h2>
        <?php
        if (mysqli_num_rows($content_result) > 0) {
            while ($row = mysqli_fetch_assoc($content_result)) {
                echo "<div class='content-item'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                if (!empty($row['file_path'])) {
                    echo "<p><a href='" . htmlspecialchars($row['file_path']) . "' download>Download File</a></p>";
                }
                if (!empty($row['video_link'])) {
                    echo "<p><a href='" . htmlspecialchars($row['video_link']) . "' target='_blank'>Watch Video</a></p>";
                }
                echo "<form method='post' action='Add_Content_Page.php' class='delete-form'>";
                echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' value='Delete'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No content uploaded yet.</p>";
        }
        ?>
    </div>
</main>
</body>
</html>
