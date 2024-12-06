<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .dashboard-container h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
        }
        .dashboard-container .nav-item a {
            color: #495057;
            text-decoration: none;
        }
        .dashboard-container .nav-item a:hover {
            color: #007bff;
        }
        .card {
            border: none;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .logout-btn {
            display: block;
            width: 100%;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        .logout-btn:hover {
            background: #bd2130;
        }
    </style>
</head>
<body>

    <div class="dashboard-container">
        <h2>Admin Dashboard</h2>
        <div class="row">
            <!-- Manage Services -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Manage Services</h5>
                        <a href="manage_services.php" class="btn btn-primary mt-3">Go</a>
                    </div>
                </div>
            </div>
            <!-- Manage Bookings -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-calendar-check fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Manage Bookings</h5>
                        <a href="manage_bookings.php" class="btn btn-info mt-3">Go</a>
                    </div>
                </div>
            </div>
            <!-- View Feedback -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-comments fa-3x text-success mb-3"></i>
                        <h5 class="card-title">View Feedback</h5>
                        <a href="view_feedbacks.php" class="btn btn-success mt-3">Go</a>
                    </div>
                </div>
            </div>
            <!-- Logout -->
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-sign-out-alt fa-3x text-danger mb-3"></i>
                        <h5 class="card-title">Logout</h5>
                        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
