<?php
session_start();

include("server/connection.php");

$response = ['status' => 'error', 'message' => ''];

if (isset($_SESSION['product_ids']) && isset($_SESSION['product_quantities']) && !empty($_SESSION['product_ids']) && !empty($_SESSION['product_quantities'])) {
    $length = count($_SESSION['product_ids']);
    
    // Ensure both arrays have the same length
    if (count($_SESSION['product_quantities']) != $length) {
        $response['message'] = 'Mismatch between product IDs and quantities.';
        echo json_encode($response);
        exit;
    }
    
    for ($i = 0; $i < $length; $i++) {
        $product_id = $_SESSION['product_ids'][$i];
        $product_quantity = $_SESSION['product_quantities'][$i];

        // Update the products table with the specific quantity for each product_id
        $stmt = $conn->prepare("UPDATE products SET product_quantity = product_quantity - ? WHERE product_id = ?");
        $stmt->bind_param('ii', $product_quantity, $product_id);
        $stmt->execute();
    }

    // Clear the product_ids and product_quantities from the session once done
    unset($_SESSION['product_ids']);
    unset($_SESSION['product_quantities']);
    unset($_SESSION['cart']);
    
    $response['status'] = 'success';
    $response['message'] = 'Quantities updated successfully.';
} else {
    $response['message'] = 'No products or quantities found in the session.';
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
