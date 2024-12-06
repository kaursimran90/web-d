<?php
include 'db_connection.php';
session_start();  // Start the session

// Include the send_email function
include 'includes/send_email.php';

// Ensure the user is logged in and has the 'customer' role
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$service_id = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;

// Fetch the service details
$service_query = "SELECT * FROM services WHERE id = $service_id";
$service_result = $conn->query($service_query);

if ($service_result->num_rows == 0) {
    echo "Service not found!";
    exit();
}

$service = $service_result->fetch_assoc();

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_date = $_POST['booking_date'];

    // Insert the booking into the database
    $booking_query = "INSERT INTO bookings (user_id, service_id, booking_date) VALUES ('$user_id', '$service_id', '$selected_date')";
    if ($conn->query($booking_query)) {
        // Send the email if the session variables are set
        if (isset($_SESSION['email']) && isset($_SESSION['name'])) {
            $toEmail = $_SESSION['email']; // Get the email from the session
            $subject = "Booking Confirmation - Professional Salon";
            $body = "<p>Dear {$_SESSION['name']},</p>
                     <p>Your booking for the service <strong>{$service['service_name']}</strong> has been confirmed on <strong>$selected_date</strong>.</p>
                     <p>Thank you for choosing us!</p>
                     <p>Best regards,<br>Professional Salon Team</p>";

            // Send the booking confirmation email
            $emailStatus = sendBookingEmail($toEmail, $subject, $body);

            if ($emailStatus) {
                // Redirect to the 'my bookings' page after a 2-second delay
                header("Refresh: 2; url=my_bookings.php?message=Service+booked+successfully%21+A+confirmation+email+has+been+sent.");
                exit();
            } else {
                echo "Booking was successful, but we couldn't send an email at the moment.";
            }
        } else {
            echo "Error: Email or name not found in session. Please log in again.";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Service</title>
    <style>
        /* UI styles for booking page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .dashboard-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #007bff;
        }
        p {
            font-size: 16px;
        }
        label {
            font-weight: bold;
        }
        input[type="date"] {
            padding: 8px;
            width: 100%;
            margin-top: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 15px;
        }
        button:hover {
            background-color: #218838;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Book Service: <?php echo htmlspecialchars($service['service_name']); ?></h2>
        <p><?php echo htmlspecialchars($service['description']); ?></p>
        <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($service['price']); ?></p>

        <!-- Booking Form -->
        <form action="book_service.php?service_id=<?php echo $service_id; ?>" method="POST">
            <label for="booking_date">Select Booking Date:</label>
            <input type="date" id="booking_date" name="booking_date" required><br><br>

            <button type="submit">Book Service</button>
        </form>

        <a href="customer_dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>
