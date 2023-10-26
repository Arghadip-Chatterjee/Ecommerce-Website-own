<?php
include('config.php');

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    $query = "SELECT * FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id); // i represents integer
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $items = $result->fetch_all(MYSQLI_ASSOC);

        if (count($items) > 0) {
            echo "<ul>";
            foreach ($items as $item) {
                echo "<li>" . $item['product_name'] . " - Quantity: " . $item['product_quantity'] . " - Product ID: " . $item['product_id'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "No items found for this order.";
        }
    } else {
        echo "Failed to fetch order items.";
    }
}
?>
