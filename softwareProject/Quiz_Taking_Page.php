<?php
session_start();
include("Database.php");

if (!isset($_SESSION['student_id'])) {
    header("Location: Student _Login_Register_Page.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;

// Fetch the quiz details
$quiz_query = "SELECT * FROM Quizzes WHERE id = '$quiz_id' AND visible = 1";
$quiz_result = mysqli_query($conn, $quiz_query);

if (mysqli_num_rows($quiz_result) == 0) {
    echo "Invalid quiz or the quiz is not visible.";
    exit();
}

$quiz = mysqli_fetch_assoc($quiz_result);

// Fetch the quiz questions
$questions_query = "SELECT * FROM Questions WHERE quiz_id = '$quiz_id'";
$questions_result = mysqli_query($conn, $questions_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $total_marks_obtained = 0;
    $total_marks = 0;
    mysqli_data_seek($questions_result, 0); // Reset the result pointer for questions
    while ($question = mysqli_fetch_assoc($questions_result)) {
        $question_id = $question['id'];
        $correct_choice = $question['correct_choice'];
        $marks = $question['marks'];
        $total_marks += $marks;

        if (isset($_POST['question_' . $question_id])) {
            $student_choice = intval($_POST['question_' . $question_id]);
            if ($student_choice == $correct_choice) {
                $total_marks_obtained += $marks;
            }
        }
    }

    // Insert student quiz results
    $insert_query = "INSERT INTO Student_Quiz_Results (student_id, quiz_id, marks_obtained) VALUES ('$student_id', '$quiz_id', '$total_marks_obtained')";
    if (mysqli_query($conn, $insert_query)) {
        // Update student's total grade
        $update_query = "UPDATE student SET total_grade = total_grade + '$total_marks_obtained' WHERE id = '$student_id'";
        mysqli_query($conn, $update_query);

        // Redirect to quizzes page
        header("Location: Quizzes_Page.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
        exit();
    }
}

mysqli_data_seek($questions_result, 0); // Reset the result pointer for questions
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
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

        .question {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            width: 100%;
            text-align: left;
        }

        .question h3 {
            margin-bottom: 10px;
            font-size: 24px;
            text-shadow: 1px 1px #6a0572;
        }

        .question label {
            display: block;
            margin: 5px 0;
            font-size: 18px;
        }

        form input[type="submit"] {
            background-color: #6a0572;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #9d50bb;
        }

        @media (max-width: 600px) {
            .content h1 {
                font-size: 28px;
            }

            form {
                width: 90%;
            }

            .question h3 {
                font-size: 20px;
            }

            .question label {
                font-size: 16px;
            }

            form input[type="submit"] {
                padding: 8px 16px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
<main class="content">
    <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
    <form method="post" action="Quiz_Taking_Page.php?quiz_id=<?php echo htmlspecialchars($quiz_id); ?>">
        <?php
        while ($question = mysqli_fetch_assoc($questions_result)) {
            echo "<div class='question'>";
            echo "<h3>" . htmlspecialchars($question['question_text']) . "</h3>";
            echo "<label><input type='radio' name='question_" . htmlspecialchars($question['id']) . "' value='1' required> " . htmlspecialchars($question['choice1']) . "</label>";
            echo "<label><input type='radio' name='question_" . htmlspecialchars($question['id']) . "' value='2' required> " . htmlspecialchars($question['choice2']) . "</label>";
            echo "<label><input type='radio' name='question_" . htmlspecialchars($question['id']) . "' value='3' required> " . htmlspecialchars($question['choice3']) . "</label>";
            echo "<label><input type='radio' name='question_" . htmlspecialchars($question['id']) . "' value='4' required> " . htmlspecialchars($question['choice4']) . "</label>";
            echo "</div>";
        }
        ?>
        <input type="submit" value="Submit Quiz">
    </form>
</main>
</body>
</html>
