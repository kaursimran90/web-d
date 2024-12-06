<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price';

$services_query = "
    SELECT * FROM services 
    WHERE service_name LIKE '%$search_query%' OR description LIKE '%$search_query%' 
    ORDER BY $sort ASC";

$services_result = $conn->query($services_query);

if ($services_result->num_rows > 0) {
    while ($service = $services_result->fetch_assoc()) {
        echo "<div class='service-item'>
                <img src='{$service['image']}' alt='Service Image'>
                <div>
                    <h4>{$service['service_name']}</h4>
                    <p>{$service['description']}</p>
                    <span>Price: ₹{$service['price']}</span>
                    <a href='book_service.php?service_id={$service['id']}' class='book-btn'>Book Service</a>
                </div>
              </div>";
    }
} else {
    echo "<p>No services match your search.</p>";
}
?>
