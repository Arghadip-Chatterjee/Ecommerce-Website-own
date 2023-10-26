<?php
include('config.php');

$message = "";
$product = ['product_name' => '', 'product_description' => '', 'product_image1' => '', 'product_image2' => '', 'product_image3' => '', 'product_image4' => '', 'product_price' => '', 'product_quantity' => '', 'product_category' => '', 'product_rating' => ''];

// Fetch product details based on ID
if (isset($_GET['id'])) {
    $productId = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT * FROM products WHERE product_id = '$productId'";
    $result = $conn->query($query);
    if ($result) {
        $product = $result->fetch_assoc();
    }
}

// Update product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = mysqli_real_escape_string($conn, $_POST['product_name']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['product_description']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['product_price']);
    $productQuantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
    $productCategory = mysqli_real_escape_string($conn, $_POST['product_category']);
    $productRating = mysqli_real_escape_string($conn, $_POST['product_rating']);
    $productId = mysqli_real_escape_string($conn, $_POST['product_id']);

    // Handle file uploads
    $uploadDir = "../assets/imgs/"; // Create a directory to store uploaded images
    $uploadedImages = array(); // Store the uploaded image file names

    for ($i = 1; $i <= 4; $i++) {
        $fieldName = "product_image$i";
        $file = $_FILES[$fieldName];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $uploadDir . basename($file['name']);
            move_uploaded_file($file['tmp_name'], $fileName);
            $uploadedImages[] = $fileName;
        } else {
            // Handle upload errors
            $message = "Error uploading $fieldName: " . $file['error'];
        }
    }

    // Get the names of the currently stored images
    $currentImages = [$product['product_image1'], $product['product_image2'], $product['product_image3'], $product['product_image4']];

    // If no new images were uploaded, use the current images
    for ($i = 0; $i < 4; $i++) {
        if (empty($uploadedImages[$i])) {
            $uploadedImages[$i] = $currentImages[$i];
        }
    }

    // Insert data into the database
    $query = "UPDATE products SET product_name = '$productName', product_description = '$productDescription', product_image1 = '$uploadedImages[0]', product_image2 = '$uploadedImages[1]', product_image3 = '$uploadedImages[2]', product_image4 = '$uploadedImages[3]', product_price = '$productPrice', product_quantity = '$productQuantity', product_category = '$productCategory', product_rating = '$productRating' WHERE product_id = '$productId'";

    if ($conn->query($query) === TRUE) {
        $message = "Product updated successfully!";
        header('location:index.php');
    } else {
        $message = "Error: " . $query . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2>Edit Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <span><?php echo $message; ?></span>
            <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" class="form-control" name="product_name" value="<?php echo $product['product_name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Product Description:</label>
                <textarea class="form-control" name="product_description" required><?php echo $product['product_description']; ?></textarea>
            </div>

            <div class="form-group">
                <label>Product Image1:</label>
                <input type="file" class="form-control" name="product_image1">
                <p>Current Image: <?php echo $product['product_image1']; ?></p>
            </div>

            <div class="form-group">
                <label>Product Image2:</label>
                <input type="file" class="form-control" name="product_image2">
                <p>Current Image: <?php echo $product['product_image2']; ?></p>
            </div>

            <div class="form-group">
                <label>Product Image3:</label>
                <input type="file" class="form-control" name="product_image3">
                <p>Current Image: <?php echo $product['product_image3']; ?></p>
            </div>

            <div class="form-group">
                <label>Product Image4:</label>
                <input type="file" class="form-control" name="product_image4">
                <p>Current Image: <?php echo $product['product_image4']; ?></p>
            </div>

            <div class="form-group">
                <label>Product Price:</label>
                <input type="number" step="0.01" class="form-control" name="product_price" value="<?php echo $product['product_price']; ?>" required>
            </div>

            <div class="form-group">
                <label>Product Quantity:</label>
                <input type="number" class="form-control" name="product_quantity" value="<?php echo $product['product_quantity']; ?>" required>
            </div>

            <div class="form-group">
                <label>Product Category:</label>
                <input type="text" class="form-control" name="product_category" value="<?php echo $product['product_category']; ?>" required>
            </div>

            <div class="form-group">
                <label>Product Rating:</label>
                <input type="number" step="0.01" class="form-control" name="product_rating" value="<?php echo $product['product_rating']; ?>" required>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update Product">
            </div>
        </form>

    </div>

</body>

</html>
