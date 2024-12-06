<?php
include 'db_connection.php';

// Sanitize inputs
$query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price';

// Validate sort column
$allowedSortColumns = ['price', 'service_name'];
$sort = in_array($sort, $allowedSortColumns) ? $sort : 'price';

$sql = "SELECT * FROM services 
        WHERE service_name LIKE ? OR description LIKE ? 
        ORDER BY $sort ASC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$searchTerm = "%$query%";
$stmt->bind_param('ss', $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($service = $result->fetch_assoc()) {
        echo "<div class='service-item'>
                <img src='" . htmlspecialchars($service['image']) . "' alt='Service Image' class='img-fluid' style='max-height: 100px;'>
                <h4>" . htmlspecialchars($service['service_name']) . "</h4>
                <p>" . htmlspecialchars($service['description']) . "</p>
                <span>Price: $ " . htmlspecialchars($service['price']) . "</span>
                <a href='book_service.php?service_id=" . urlencode($service['id']) . "' class='btn btn-success mt-3'>Book Service</a>
              </div>";
    }
} else {
    echo "<p>No services found.</p>";
}

$stmt->close();
$conn->close();
?>
