<?php
include 'db_connection.php';
session_start();

if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Handle image upload
    $image_path = null;
    if (isset($_FILES['service_image']) && $_FILES['service_image']['error'] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_path = $target_dir . time() . '_' . basename($_FILES['service_image']['name']);
        move_uploaded_file($_FILES['service_image']['tmp_name'], $image_path);
    }

    // Insert data into database
    $query = "INSERT INTO services (service_name, description, price, image) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssds", $service_name, $description, $price, $image_path);
    $stmt->execute();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services</title>
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
        .manage-services-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
        }
        .manage-services-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: #333;
        }
        .form-input {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        .form-button {
            width: 100%;
            padding: 1rem;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        .form-button:hover {
            background-color: #218838;
        }
        .services-list {
            margin-top: 2rem;
            list-style-type: none;
            padding: 0;
        }
        .services-list li {
            background-color: #f4f4f4;
            padding: 1rem;
            margin: 0.5rem 0;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .services-list li span {
            font-weight: bold;
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

        <div class="manage-services-container">
            <h2>Manage Services</h2>

            <!-- Add New Service Form -->
            <form method="POST" action="" enctype="multipart/form-data">
    <input class="form-input" type="text" name="service_name" required placeholder="Service Name">
    <textarea class="form-input" name="description" placeholder="Description"></textarea>
    <input class="form-input" type="number" name="price" required placeholder="Price">
    <input class="form-input" type="file" name="service_image" accept="image/*">
    <button class="form-button" type="submit">Add Service</button>
</form>


            <!-- Existing Services List -->
            <h3>Existing Services</h3>
            <ul class="services-list">
    <?php
    $result = $conn->query("SELECT * FROM services");
    while ($service = $result->fetch_assoc()) {
        echo "<li>
                <img src='{$service['image']}' alt='Service Image' style='max-width: 50px; margin-right: 10px;'>
                <span>{$service['service_name']}</span> - {$service['price']}
              </li>";
    }
    ?>
</ul>


            <div class="action-buttons">
                <a href="admin_dashboard.php">Home</a>
            </div>
        </div>
    </div>
</body>
</html>
