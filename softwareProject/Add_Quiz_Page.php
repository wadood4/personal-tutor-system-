<?php
include("Database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle visibility change
    if (isset($_POST['toggle_visibility'])) {
        $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);
        $visible = mysqli_real_escape_string($conn, $_POST['visible']);
        $query = "UPDATE Quizzes SET visible = '$visible' WHERE id = '$quiz_id'";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Handle deadline change
    if (isset($_POST['change_deadline'])) {
        $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);
        $new_deadline = mysqli_real_escape_string($conn, $_POST['new_deadline']);
        $query = "UPDATE Quizzes SET deadline = '$new_deadline' WHERE id = '$quiz_id'";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        }
    }

    // Handle quiz deletion
    if (isset($_POST['delete_quiz'])) {
        $quiz_id = mysqli_real_escape_string($conn, $_POST['quiz_id']);
        $query = "DELETE FROM Quizzes WHERE id = '$quiz_id'";
        if (!mysqli_query($conn, $query)) {
            echo "Error: " . mysqli_error($conn);
        } else {
            $query = "DELETE FROM Questions WHERE quiz_id = '$quiz_id'";
            if (!mysqli_query($conn, $query)) {
                echo "Error: " . mysqli_error($conn);
            } else {
                echo "Quiz and related questions deleted successfully.";
            }
        }
    }

    // Handle quiz creation
    if (isset($_POST['title'])) {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
        $total_marks = 0;

        $query = "INSERT INTO Quizzes (title, deadline) VALUES ('$title', '$deadline')";
        if (mysqli_query($conn, $query)) {
            $quiz_id = mysqli_insert_id($conn);
            foreach ($_POST['questions'] as $index => $question_text) {
                $question_text = mysqli_real_escape_string($conn, $question_text);
                $choice1 = mysqli_real_escape_string($conn, $_POST['choices1'][$index]);
                $choice2 = mysqli_real_escape_string($conn, $_POST['choices2'][$index]);
                $choice3 = mysqli_real_escape_string($conn, $_POST['choices3'][$index]);
                $choice4 = mysqli_real_escape_string($conn, $_POST['choices4'][$index]);
                $correct_choice = mysqli_real_escape_string($conn, $_POST['correct_choices'][$index]);
                $marks = mysqli_real_escape_string($conn, $_POST['marks'][$index]);

                $query = "INSERT INTO Questions (quiz_id, question_text, choice1, choice2, choice3, choice4, correct_choice, marks)
                          VALUES ('$quiz_id', '$question_text', '$choice1', '$choice2', '$choice3', '$choice4', '$correct_choice', '$marks')";
                if (mysqli_query($conn, $query)) {
                    $total_marks += $marks;
                } else {
                    echo "add_quiz_Error: " . mysqli_error($conn);
                }
            }
            $query = "UPDATE Quizzes SET total_marks = '$total_marks' WHERE id = '$quiz_id'";
            mysqli_query($conn, $query);
            echo "Quiz added successfully.";
        } else {
            echo "add_quiz_Error: " . mysqli_error($conn);
        }
    }
}

// Fetch all quizzes
$quizzes_query = "SELECT * FROM Quizzes";
$quizzes_result = mysqli_query($conn, $quizzes_query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quiz</title>
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
        form input[type="datetime-local"],
        form input[type="number"],
        form input[type="submit"],
        form button {
            margin-bottom: 15px;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"],
        form button {
            background-color: #6a0572;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover,
        form button:hover {
            background-color: #9d50bb;
        }

        .question {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            width: 100%;
        }

        .existing-quizzes {
            width: 80%;
            margin: 0 auto;
        }

        .existing-quizzes h2 {
            font-size: 24px;
            margin-bottom: 10px;
            text-shadow: 1px 1px #6a0572;
        }

        .quiz-item {
            border: 2px solid white;
            padding: 15px;
            margin: 10px 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            text-align: left;
        }

        .quiz-item h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            text-shadow: 1px 1px #6a0572;
        }

        .quiz-item form {
            display: inline;
        }

        .quiz-item input[type="submit"] {
            background-color: #6a0572;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .quiz-item input[type="submit"]:hover {
            background-color: #9d50bb;
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

            form input[type="submit"],
            form button {
                padding: 10px;
            }

            .existing-quizzes {
                width: 90%;
            }

            .quiz-item h2 {
                font-size: 20px;
            }

            .quiz-item input[type="submit"] {
                padding: 5px;
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
    <h1>Add Quiz</h1>
    <form method="post" action="Add_Quiz_Page.php">
        <label for="title">Quiz Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="deadline">Deadline:</label><br>
        <input type="datetime-local" id="deadline" name="deadline" required><br>
        <div id="questions">
            <h2>Questions</h2>
            <div class="question">
                <label for="questions[]">Question:</label><br>
                <input type="text" name="questions[]" required><br>
                <label for="choices1[]">Choice 1:</label><br>
                <input type="text" name="choices1[]" required><br>
                <label for="choices2[]">Choice 2:</label><br>
                <input type="text" name="choices2[]" required><br>
                <label for="choices3[]">Choice 3:</label><br>
                <input type="text" name="choices3[]" required><br>
                <label for="choices4[]">Choice 4:</label><br>
                <input type="text" name="choices4[]" required><br>
                <label for="correct_choices[]">Correct Choice (1-4):</label><br>
                <input type="number" name="correct_choices[]" min="1" max="4" required><br>
                <label for="marks[]">Marks:</label><br>
                <input type="number" name="marks[]" required><br>
                <button type="button" onclick="removeQuestion(this)">Delete Question</button><br><br>
            </div>
        </div>
        <button type="button" onclick="addQuestion()">Add Another Question</button><br><br>
        <input type="submit" value="Add Quiz">
    </form>

    <div class="existing-quizzes">
        <h2>Existing Quizzes</h2>
        <?php
        if (mysqli_num_rows($quizzes_result) > 0) {
            while ($quiz = mysqli_fetch_assoc($quizzes_result)) {
                echo "<div class='quiz-item'>";
                echo "<h2>" . htmlspecialchars($quiz['title']) . "</h2>";
                echo "<p><strong>Deadline:</strong> " . htmlspecialchars($quiz['deadline']) . "</p>";
                echo "<form method='post' action='Add_Quiz_Page.php'>";
                echo "<input type='hidden' name='quiz_id' value='" . $quiz['id'] . "'>";
                echo "<input type='hidden' name='visible' value='" . ($quiz['visible'] ? 0 : 1) . "'>";
                echo "<input type='submit' name='toggle_visibility' value='" . ($quiz['visible'] ? "Make Invisible" : "Make Visible") . "'>";
                echo "</form>";
                echo "<form method='post' action='Add_Quiz_Page.php'>";
                echo "<input type='hidden' name='quiz_id' value='" . $quiz['id'] . "'>";
                echo "<input type='datetime-local' name='new_deadline' required>";
                echo "<input type='submit' name='change_deadline' value='Change Deadline'>";
                echo "</form>";
                echo "<form method='post' action='Add_Quiz_Page.php'>";
                echo "<input type='hidden' name='quiz_id' value='" . $quiz['id'] . "'>";
                echo "<input type='submit' name='delete_quiz' value='Delete Quiz'>";
                echo "</form>";
                echo "</div><br>";
            }
        } else {
            echo "<p>No quizzes added yet.</p>";
        }
        ?>
    </div>
</main>

<script>
    function addQuestion() {
        var questionDiv = document.createElement('div');
        questionDiv.classList.add('question');
        questionDiv.innerHTML = `
            <label for="questions[]">Question:</label><br>
            <input type="text" name="questions[]" required><br>
            <label for="choices1[]">Choice 1:</label><br>
            <input type="text" name="choices1[]" required><br>
            <label for="choices2[]">Choice 2:</label><br>
            <input type="text" name="choices2[]" required><br>
            <label for="choices3[]">Choice 3:</label><br>
            <input type="text" name="choices3[]" required><br>
            <label for="choices4[]">Choice 4:</label><br>
            <input type="text" name="choices4[]" required><br>
            <label for="correct_choices[]">Correct Choice (1-4):</label><br>
            <input type="number" name="correct_choices[]" min="1" max="4" required><br>
            <label for="marks[]">Marks:</label><br>
            <input type="number" name="marks[]" required><br>
            <button type="button" onclick="removeQuestion(this)">Delete Question</button><br><br>
        `;
        document.getElementById('questions').appendChild(questionDiv);
    }

    function removeQuestion(button) {
        var questionDiv = button.parentElement;
        questionDiv.remove();
    }
</script>

</body>
</html>
