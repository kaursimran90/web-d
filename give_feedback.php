<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_text = $_POST['feedback_text'];

    $query = "INSERT INTO feedbacks (user_id, feedback_text) VALUES ('$user_id', '$feedback_text')";
    if ($conn->query($query) === TRUE) {
        echo "<p>Feedback submitted successfully!</p>";
    } else {
        echo "<p>Error: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Feedback</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .feedback-container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .feedback-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }
        .feedback-container textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            resize: vertical;
            height: 150px;
            margin-bottom: 1rem;
        }
        .feedback-container button {
            width: 100%;
            padding: 0.8rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        .feedback-container button:hover {
            background-color: #0056b3;
        }
        .feedback-container a {
            display: block;
            margin-top: 1rem;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        .feedback-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <h2>Submit Your Feedback</h2>

        <!-- Display Success or Error Message -->
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p style="text-align: center; color: green;"><?php echo $conn->affected_rows > 0 ? 'Feedback submitted successfully!' : 'Error submitting feedback.'; ?></p>
        <?php endif; ?>

        <form method="POST" action="">
            <textarea name="feedback_text" required placeholder="Your feedback..."></textarea>
            <button type="submit">Submit Feedback</button>
        </form>

        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
