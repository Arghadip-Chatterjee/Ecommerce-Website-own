<?php
include('config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $productId = mysqli_real_escape_string($conn, $_POST['id']);
    $query = "DELETE FROM products WHERE product_id = '$productId'";
    
    if ($conn->query($query) === TRUE) {
        $message = "Product deleted successfully!";
    } else {
        $message = "Error: " . $query . "<br>" . $conn->error;
    }
    header("Location: index.php?message=" . urlencode($message));
    exit;
}
?>

