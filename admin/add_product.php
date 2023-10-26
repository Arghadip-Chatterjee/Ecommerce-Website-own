<?php
include('config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = mysqli_real_escape_string($conn, $_POST['product_name']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['product_description']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['product_price']);
    $productQuantity = mysqli_real_escape_string($conn, $_POST['product_quantity']);
    $productCategory = mysqli_real_escape_string($conn, $_POST['product_category']);
    $productRating = mysqli_real_escape_string($conn, $_POST['product_rating']);

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

    // Insert data into the database
    $query = "INSERT INTO products (product_name, product_description, product_image1, product_image2, product_image3, product_image4, product_price, product_quantity, product_category, product_rating) 
    VALUES ('$productName', '$productDescription', '$uploadedImages[0]', '$uploadedImages[1]', '$uploadedImages[2]', '$uploadedImages[3]', '$productPrice', '$productQuantity', '$productCategory', '$productRating')";

    if ($conn->query($query) === TRUE) {
        $message = "Product added successfully!";
    } else {
        $message = "Error: " . $query . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2>Add Product</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <span><?php echo $message; ?></span>
            <div class="form-group">
                <label>Product Name:</label>
                <input type="text" class="form-control" name="product_name" required>
            </div>
            <div class="form-group">
                <label>Product Description:</label>
                <textarea class="form-control" name="product_description" required></textarea>
            </div>

            <div class="form-group">
                <label>Product Image1:</label>
                <input type="file" class="form-control" name="product_image1" required>
            </div>

            <div class="form-group">
                <label>Product Image2:</label>
                <input type="file" class="form-control" name="product_image2" required>
            </div>

            <div class="form-group">
                <label>Product Image3:</label>
                <input type="file" class="form-control" name="product_image3" required>
            </div>

            <div class="form-group">
                <label>Product Image4:</label>
                <input type="file" class="form-control" name="product_image4" required>
            </div>

            <div class="form-group">
                <label>Product Price:</label>
                <input type="number" step="0.01" class="form-control" name="product_price" required>
            </div>

            <div class="form-group">
                <label>Product Quantity:</label>
                <textarea class="form-control" name="product_quantity" required></textarea>
            </div>

            <div class="form-group">
                <label>Product Category:</label>
                <textarea class="form-control" name="product_category" required></textarea>
            </div>

            <div class="form-group">
                <label>Product Rating:</label>
                <textarea class="form-control" name="product_rating" required></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Product">
            </div>
        </form>
    </div>

</body>

</html>
