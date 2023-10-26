<?php
// ... usual checks and session stuff ...

include('config.php');

$orders = [];
$query = "SELECT * FROM orders"; // Assuming your table is named orders
$result = $conn->query($query);
if ($result) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<h2>Orders</h2>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Order ID</th>
        <th>Phone Number</th>
        <th>Order Date</th>
        <th>Order Cost</th>
        <th>View Items</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo $order['order_id']; ?></td>
            <td><?php echo $order['user_phone']; ?></td> <!-- Adjust this as per your table structure -->
            <td><?php echo $order['order_date']; ?></td>
            <td><?php echo $order['order_cost']; ?></td>
            <td>
                <!-- Trigger showing order items with AJAX -->
                <button class="btn btn-primary viewOrderItems" data-order-id="<?php echo $order['order_id']; ?>">View Items</button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<!-- Modal for showing order items -->
<div class="modal" tabindex="-1" id="orderItemsModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Items</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderItemsContent">
                <!-- Items will be loaded here dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $(".viewOrderItems").click(function() {
        var orderId = $(this).data('order-id');
        $("#orderItemsContent").load("order_items.php?order_id=" + orderId, function() {
            $("#orderItemsModal").modal('show');
        });
    });
});
</script>
