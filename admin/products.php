<?php
session_start();
include('config.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$products = [];
$query = "SELECT * FROM products"; // assuming you have a table named products
$result = $conn->query($query);
if ($result) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... other meta tags ... -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
    <!-- ... Sidebar code ... -->

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-1" style="margin-left: 0; padding-left: 0;">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
            <a href="add_product.php" class="btn btn-primary">Add Product</a> <!-- assuming you have add_product.php -->
        </div>

        <div class="content">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo $product['product_id']; ?></td>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['product_description']; ?></td>
                            <td>₹<?php echo number_format($product['product_price'], 2); ?></td>
                            <td><?php echo $product['product_quantity']; ?></td>
                            <td class="d-flex">
                                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>" class="btn btn-warning pe-3">Edit</a>
                                <form action="delete_product.php" method="post" class="ps-3">
                                    <input type="hidden" name="id" value="<?php echo $product['product_id']; ?>">
                                    <input type="submit" value="Delete" onclick="return confirm('Are you sure you want to delete this product?');">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- ... end of your content ... -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>