<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM feedbacks WHERE feedback_id = $delete_id";
    $conn->query($delete_query);
    header("Location: view_feedbacks.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedbacks</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 1.5rem 0;
            text-align: center;
            margin-bottom: 2rem;
        }
        header h1 {
            margin: 0;
        }
        .feedback-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .feedback-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table td {
            background-color: #f9f9f9;
        }
        table td a {
            color: #e74c3c;
            text-decoration: none;
        }
        table td a:hover {
            text-decoration: underline;
        }
        .action-buttons {
            text-align: center;
            margin-top: 2rem;
        }
        .action-buttons a {
            padding: 0.8rem 2rem;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .action-buttons a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Admin Dashboard</h1>
        </header>

        <div class="feedback-container">
            <h2>Customer Feedbacks</h2>

            <table>
                <thead>
                    <tr>
                        <th>Feedback</th>
                        <th>Customer</th>
                        <th>Submitted On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT feedbacks.feedback_id, feedbacks.feedback_text, feedbacks.created_at, users.name FROM feedbacks
                              JOIN users ON feedbacks.user_id = users.user_id";
                    $result = $conn->query($query);

                    while ($feedback = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$feedback['feedback_text']}</td>
                                <td>{$feedback['name']}</td>
                                <td>{$feedback['created_at']}</td>
                                <td><a href='view_feedbacks.php?delete_id={$feedback['feedback_id']}'>Delete</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <div class="action-buttons">
                <a href="admin_dashboard.php">Back to Dashboard</a>
                
            </div>
        </div>
    </div>
</body>
</html>
