<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f0f2f5;
        }
        .dashboard-container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .dashboard-container h2 {
            margin-bottom: 24px;
            font-size: 24px;
            color: #333333;
        }
        .dashboard-container a {
            display: block;
            padding: 12px;
            margin: 10px 0;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .dashboard-container a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <a href="manage_services.php">Manage Services</a>
        <a href="view_feedbacks.php">View Feedbacks</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
