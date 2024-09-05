<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
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

        .header-image {
            max-width: 25%;
            height: auto;
            margin-bottom: 20px;
            border: 5px solid white;
            border-radius: 50%;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
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

            .header-image {
                max-width: 50%;
            }
        }
    </style>
</head>
<body>
<main class="content">
    <h1>Welcome to secret tutor</h1>
    <img src="imgs/img1.png" alt="Educational Platform" class="header-image">
    <form action="" method="post">
        <div class="form-group">
            <input type="radio" id="student" name="role" value="student" required>
            <label for="student">Student</label><br>
        </div>
        <div class="form-group">
            <input type="radio" id="instructor" name="role" value="instructor" required>
            <label for="instructor">Instructor</label><br>
        </div>
        <input type="submit" value="Continue" class="submit-button">
    </form>
</main>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    if ($role == 'student') {
        echo '<form id="redirectForm" action="Student%20_Login_Register_Page.php" method="post">
                    <input type="hidden" name="role" value="student">
                  </form>';
    } elseif ($role == 'instructor') {
        echo '<form id="redirectForm" action="Instructor_Password_Page.php" method="post">
                    <input type="hidden" name="role" value="instructor">
                  </form>';
    }
    echo '<script type="text/javascript">
                document.getElementById("redirectForm").submit();
              </script>';
}
?>
</body>
</html>
