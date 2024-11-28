<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch bookings
$bookings_query = "
    SELECT b.id, s.service_name, s.description, s.price, b.booking_date, b.status 
    FROM bookings b 
    JOIN services s ON b.service_id = s.id 
    WHERE b.user_id = '$user_id'
";
$bookings_result = $conn->query($bookings_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .dashboard-container {
            width: 70%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        .booking-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .booking-item:last-child {
            border-bottom: none;
        }
        .booking-item h4 {
            margin: 0;
            font-size: 18px;
        }
        .booking-item p {
            margin: 5px 0;
        }
        .booking-item span {
            font-weight: bold;
        }
        .message {
            color: green;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>My Bookings</h2>

        <!-- Show success message if any -->
        <?php if (isset($_GET['message'])): ?>
            <div class="message"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <?php
        if ($bookings_result->num_rows > 0) {
            while ($booking = $bookings_result->fetch_assoc()) {
                echo "<div class='booking-item'>
                        <h4>{$booking['service_name']}</h4>
                        <p>{$booking['description']}</p>
                        <span>Price: ₹{$booking['price']}</span><br>
                        <span>Booking Date: {$booking['booking_date']}</span><br>
                        <span>Status: {$booking['status']}</span>
                      </div>";
            }
        } else {
            echo "<p>You have no bookings yet.</p>";
        }
        ?>
        
        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
