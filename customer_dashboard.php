<?php
include 'db_connection.php';
session_start();

// Restrict access to customers only
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'customer') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle search and sorting
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price';

// Handle pagination
$limit = 10; // Number of services per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// SQL Query for services with search, sorting, and pagination
$services_query = "
    SELECT * FROM services 
    WHERE service_name LIKE '%$search_query%' OR description LIKE '%$search_query%' 
    ORDER BY $sort ASC 
    LIMIT $limit OFFSET $offset";
$services_result = $conn->query($services_query);

// Count total services for pagination
$total_query = "
    SELECT COUNT(*) as total 
    FROM services 
    WHERE service_name LIKE '%$search_query%' OR description LIKE '%$search_query%'";
$total_result = $conn->query($total_query);
$total_services = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_services / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  
    <style>
    /* Universal reset and font */
    /* Universal reset and font */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body {
    background: linear-gradient(120deg, #fdfbfb, #ebedee);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    padding: 20px;
}

/* Dashboard Container */
.dashboard-container {
    width: 100%;
    max-width: 1200px;
    background: white;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.6s ease-in-out;
    text-align: center;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.dashboard-container h2 {
    font-size: 32px;
    color: #333;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

/* Buttons Layout */
.button-layout {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-bottom: 30px;
    gap: 15px;
}
.button-layout a {
    padding: 15px 40px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    text-decoration: none;
    border-radius: 50px;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.button-layout a.my-bookings-btn {
    background: linear-gradient(120deg, #6a11cb, #2575fc); /* Purple-Blue Gradient */
}
.button-layout a.feedback-btn {
    background: linear-gradient(120deg, #ff9a9e, #fad0c4); /* Pink-Orange Gradient */
}
.button-layout a.logout-btn {
    background: linear-gradient(120deg, #84fab0, #8fd3f4); /* Green-Blue Gradient */
}
/* Hover Effects */
.button-layout a:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
}
/* Search Bar */
.search-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 30px;
}
.search-bar input[type="text"] {
    width: 300px;
    padding: 12px;
    border: none;
    border-radius: 50px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    font-size: 16px;
    transition: box-shadow 0.3s ease;
}
.search-bar input[type="text"]:focus {
    outline: none;
    box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.2);
}
.search-bar select {
    padding: 12px;
    border: none;
    border-radius: 50px;
    background: #2575fc;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}
.search-bar select:hover {
    background: #1b59c4;
}
.search-bar button {
    padding: 12px 20px;
    border: none;
    border-radius: 50px;
    background: linear-gradient(120deg, #f12711, #f5af19);
    color: white;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.search-bar button:hover {
    transform: translateY(-3px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.3);
}

/* Services Section */
.services-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}
.service-item {
    background: white;
    border-radius: 15px;
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.service-item:hover {
    transform: translateY(-10px);
    box-shadow: 0px 15px 30px rgba(0, 0, 0, 0.2);
}
.service-item img {
    max-width: 100px;
    margin-bottom: 15px;
    border-radius: 50%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}
.service-item h4 {
    font-size: 20px;
    color: #333;
    margin-bottom: 10px;
}
.service-item p {
    font-size: 14px;
    color: #555;
    margin-bottom: 10px;
}
.service-item span {
    font-size: 18px;
    font-weight: bold;
    color: #4caf50;
    display: block;
    margin-bottom: 15px;
}
.service-item .book-btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 50px;
    background: #4caf50;
    color: white;
    font-size: 14px;
    text-decoration: none;
    transition: background 0.3s ease;
}
.service-item .book-btn:hover {
    background: #388e3c;
}

/* Pagination */
.pagination {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 10px;
}
.pagination a {
    padding: 10px 15px;
    border-radius: 50%;
    background: #84fab0;
    color: white;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.3s ease, transform 0.3s ease;
}
.pagination a:hover {
    background: #6ab3e3;
    transform: translateY(-3px);
}
.pagination a.active {
    background: #2575fc;
}

/* Footer Links */
.footer-links {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    gap: 20px;
}
.footer-links a {
    font-size: 14px;
    color: #555;
    text-decoration: none;
    transition: color 0.3s ease;
}
.footer-links a:hover {
    color: #000;
}

</style>


</head>
<>
    <div class="dashboard-container">
        <h2>Welcome to Your Dashboard</h2>

      
        <!-- Search Bar -->
        <div class="search-bar">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search services..." value="<?php echo htmlspecialchars($search_query); ?>">
                <select name="sort">
                    <option value="price" <?php echo $sort == 'price' ? 'selected' : ''; ?>>Sort by Price</option>
                    <option value="service_name" <?php echo $sort == 'service_name' ? 'selected' : ''; ?>>Sort by Name</option>
                </select>
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Available Services -->
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
                                <a href='book_service.php?service_id={$service['id']}' class='book-btn'>Book Service</a>
                            </div>
                          </div>";
                }
            } else {
                echo "<p>No services match your search.</p>";
            }
            ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?search=<?php echo htmlspecialchars($search_query); ?>&sort=<?php echo $sort; ?>&page=<?php echo $page - 1; ?>">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?search=<?php echo htmlspecialchars($search_query); ?>&sort=<?php echo $sort; ?>&page=<?php echo $i; ?>" <?php if ($i == $page) echo 'class="active"'; ?>>
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <?php if ($page < $total_pages): ?>
                <a href="?search=<?php echo htmlspecialchars($search_query); ?>&sort=<?php echo $sort; ?>&page=<?php echo $page + 1; ?>">Next</a>
            <?php endif; ?>
        </div>

        <!-- Footer Links -->
        <div class="footer-links">
              <!-- My Bookings Button -->
        <a href="my_bookings.php" class="my-bookings-btn">My Bookings</a>
            <a href="give_feedback.php">Provide Feedback</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <script>
        function loadServices(page = 1) {
            const search = $('#search').val();
            const sort = $('#sort').val();

            $.ajax({
                url: 'ajax_services.php',
                method: 'GET',
                data: { search, sort, page },
                success: function (response) {
                    const data = JSON.parse(response);
                    $('#servicesContainer').html(data.services);
                    $('#paginationContainer').html(data.pagination);
                },
                error: function () {
                    alert('Error loading services.');
                }
            });
        }

        $(document).ready(function () {
            loadServices();

            $('#searchBtn').on('click', function () {
                loadServices();
            });

            $('#sort').on('change', function () {
                loadServices();
            });

            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                loadServices(page);
            });
        });
    </script>

</body>
</html>
