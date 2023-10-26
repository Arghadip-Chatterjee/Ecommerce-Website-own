<?php
session_start();
include('config.php');
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Assuming you already connected to the database earlier in the code
$result = $conn->query("SELECT order_date, SUM(order_cost) as total_cost FROM orders GROUP BY order_date ORDER BY order_date ASC");
$salesData = [];
while ($row = $result->fetch_assoc()) {
    $salesData[] = $row;
}
$salesJson = json_encode($salesData);

$result1 = $conn->query("SELECT MONTHNAME(created_at) as month, COUNT(user_id) as total_users FROM users GROUP BY MONTH(created_at) ORDER BY created_at ASC");
$customersData = [];
while ($row = $result1->fetch_assoc()) {
    $customersData[] = $row;
}

$customersJson = json_encode($customersData);

$result2 = $conn->query("SELECT MONTHNAME(created_at) as month, COUNT(product_id) as total_products FROM products GROUP BY MONTH(created_at) ORDER BY created_at ASC");
$productsData = [];
while ($row = $result2->fetch_assoc()) {
    $productsData[] = $row;
}

$productsJson = json_encode($productsData);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-content-link="products.php">
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-content-link="orders.php">
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-content-link="users.php">
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);" data-content-link="logout.php">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main id="mainContent" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <div class="content">
                    <h4>Welcome to the Admin Dashboard!</h4>

                    <div class="row">
                        <!-- Total Sales Graph -->
                        <div class="col-lg-4">
                            <canvas id="salesChart"></canvas>
                            <h4>Sales Chart</h4>
                        </div>

                        <!-- Number of Customers Graph -->
                        <div class="col-lg-4">
                            <canvas id="customersChart"></canvas>
                            <h4>Customers Chart</h4>
                        </div>

                        <!-- Number of Products Graph -->
                        <div class="col-lg-4">
                            <canvas id="productsChart"></canvas>
                            <h4>Products Chart</h4>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Handle sidebar link clicks
            $("[data-content-link]").click(function() {
                var link = $(this).data('content-link');

                // Load content
                $("#mainContent").load(link);
            });

            // Get the PHP data
            var salesData = JSON.parse('<?php echo $salesJson; ?>');

            // Extract labels and data for the chart
            var salesLabels = salesData.map(function(item) {
                return item.order_date;
            });
            var salesValues = salesData.map(function(item) {
                return item.total_cost;
            });

            // Sales graph
            var salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: salesLabels,
                    datasets: [{
                        label: 'Total Sales',
                        data: salesValues,
                        borderColor: 'rgb(75, 192, 192)',
                        fill: false
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Get the PHP data
            var customersData = JSON.parse('<?php echo $customersJson; ?>');

            // Extract labels and data for the chart
            var customerLabels = customersData.map(function(item) {
                return item.month;
            });
            var customerValues = customersData.map(function(item) {
                return item.total_users;
            });

            // Customers graph
            var customersCtx = document.getElementById('customersChart').getContext('2d');
            new Chart(customersCtx, {
                type: 'bar',
                data: {
                    labels: customerLabels,
                    datasets: [{
                        label: 'Number of Customers',
                        data: customerValues,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)'
                    }]
                },
                options: {
                    responsive: true
                }
            });

            // Get the PHP data
            var productsData = JSON.parse('<?php echo $productsJson; ?>');

            // Extract labels and data for the chart
            var productLabels = productsData.map(function(item) {
                return item.month;
            });
            var productValues = productsData.map(function(item) {
                return item.total_products;
            });

            // Products graph
            var productsCtx = document.getElementById('productsChart').getContext('2d');
            new Chart(productsCtx, {
                type: 'bar',
                data: {
                    labels: productLabels,
                    datasets: [{
                        label: 'Number of Products Added',
                        data: productValues,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)' // Different color for distinction
                    }]
                },
                options: {
                    responsive: true
                }
            });

        });
    </script>

</body>

</html>