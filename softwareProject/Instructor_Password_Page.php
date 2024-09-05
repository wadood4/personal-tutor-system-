<?php
// Sample hashed password for all instructors (password: "instructor123")
$instructor_password_hash = password_hash("123", PASSWORD_DEFAULT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the password key exists in the $_POST array
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
        if (password_verify($password, $instructor_password_hash)) {
            header("Location: Instructor_Dashboard.php");
            exit();
        } else {
            $error = "Invalid password. Please try again.";
        }
    } else {
        $error = "Password field is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Login</title>
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
            margin-bottom: 20px;
            width: 80%;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-group label {
            font-size: 18px;
        }

        .submit-button {
            background-color: #6a0572;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .submit-button:hover {
            background-color: #9d50bb;
        }

        .error-message {
            color: red;
            font-size: 16px;
        }

        @media (max-width: 600px) {
            .nav-links li {
                margin: 0 10px;
            }

            .content h1 {
                font-size: 28px;
            }

            .submit-button {
                padding: 12px 24px;
                font-size: 16px;
            }

            form {
                width: 90%;
            }
        }
    </style>
</head>
<body>
<header class="page-header">
    <nav class="navbar">
        <ul class="nav-links">
            <li><a href="Landing_Page.php">Go Back</a></li>
        </ul>
    </nav>
</header>
<main class="content">
    <h1>Instructor Login</h1>
    <form method="post" action="Instructor_Password_Page.php">
        <div class="form-group">
            <label for="password">Enter Instructor Password:</label><br>
            <input type="password" id="password" name="password" required><br>
        </div>
        <input type="submit" value="Login" class="submit-button">
    </form>
    <?php if (!empty($error)): ?>
        <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
</main>
</body>
</html>
