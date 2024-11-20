<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the customer's feedback
$query = "SELECT feedback_text, created_at FROM feedbacks WHERE user_id = '$user_id'";
$result = $conn->query($query);

// Fetch all services
$services_query = "SELECT * FROM services";
$services_result = $conn->query($services_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            padding: 20px;
        }
        .dashboard-container {
            width: 100%;
            max-width: 800px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        .dashboard-container h2 {
            margin-bottom: 24px;
            font-size: 28px;
            color: #333333;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }
        .dashboard-container a {
            display: inline-block;
            padding: 12px 20px;
            margin: 10px 5px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .dashboard-container a:hover {
            background-color: #45a049;
        }
        .services-container {
            margin-top: 30px;
            text-align: left;
        }
        .services-container h3 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333333;
        }
        .service-item {
            display: flex;
            align-items: center;
            background-color: #f9f9f9;
            padding: 15px;
            margin: 15px 0;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .service-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .service-item img {
            max-width: 80px;
            border-radius: 8px;
            margin-right: 20px;
        }
        .service-item div {
            flex: 1;
        }
        .service-item h4 {
            font-size: 20px;
            color: #333333;
            margin-bottom: 5px;
        }
        .service-item p {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }
        .service-item span {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome to Your Dashboard</h2>
        
        <div class="services-container">
            <h3>Available Services</h3>
            <?php
            if ($services_result->num_rows > 0) {
                while ($service = $services_result->fetch_assoc()) {
                    echo "<div class='service-item'>
                            <img src='{$service['image']}' alt='Service Image'>
                            <div>
                                <h4>{$service['service_name']}</h4>
                                <p>{$service['description']}</p>
                                <span>Price: ₹{$service['price']}</span>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No services are currently available.</p>";
            }
            ?>
        </div>
        
        <a href="give_feedback.php">Give Feedback</a>
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
