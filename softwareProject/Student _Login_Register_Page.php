<?php
session_start();
include("Database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = mysqli_real_escape_string($conn, $_POST['login_username']);
        $password = mysqli_real_escape_string($conn, $_POST['login_password']);

        $query = "SELECT * FROM student WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['student_id'] = $row['id'];
                header("Location: Student Dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }
    } elseif (isset($_POST['register'])) {
        $username = mysqli_real_escape_string($conn, $_POST['register_username']);
        $password = mysqli_real_escape_string($conn, $_POST['register_password']);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $query = "SELECT * FROM student WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 0) {
            $query = "INSERT INTO student (username, password) VALUES ('$username', '$password_hash')";
            if (mysqli_query($conn, $query)) {
                $query = "SELECT id FROM student WHERE username = '$username'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $_SESSION['student_id'] = $row['id'];
                echo "Registration successful. Please log in.";
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        } else {
            echo "Username already taken.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login/Register</title>
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

        .forms-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
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
            .forms-container {
                width: 100%;
            }

            form {
                width: 90%;
            }
        }

        @media (orientation: landscape) {
            .content {
                flex-direction: column;
                align-items: center;
            }

            .forms-container {
                display: flex;
                flex-direction: row;
                justify-content: space-around;
                align-items: flex-start;
                width: 100%;
            }

            form {
                width: 40%;
                margin: 0 10px;
            }

            .content h1 {
                font-size: 32px;
                margin-bottom: 20px;
                width: 100%;
                text-align: center;
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
    <h1>Student Login/Register</h1>
    <div class="forms-container">
        <form method="post" action="Student%20_Login_Register_Page.php">
            <h2>Login</h2>
            <div class="form-group">
                <label for="login_username">Username:</label><br>
                <input type="text" id="login_username" name="login_username" required><br>
            </div>
            <div class="form-group">
                <label for="login_password">Password:</label><br>
                <input type="password" id="login_password" name="login_password" required><br>
            </div>
            <input type="submit" name="login" value="Login" class="submit-button">
        </form>

        <form method="post" action="Student%20_Login_Register_Page.php">
            <h2>Register</h2>
            <div class="form-group">
                <label for="register_username">Username:</label><br>
                <input type="text" id="register_username" name="register_username" required><br>
            </div>
            <div class="form-group">
                <label for="register_password">Password:</label><br>
                <input type="password" id="register_password" name="register_password" required><br>
            </div>
            <input type="submit" name="register" value="Register" class="submit-button">
        </form>
    </div>
</main>
</body>
</html>

