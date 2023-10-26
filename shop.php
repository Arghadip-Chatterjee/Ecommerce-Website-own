<?php

include("server/connection.php");

$sql = "SELECT * FROM products"; // you can add a LIMIT clause if necessary
$result = $conn->query($sql);

// Fetch distinct categories from the products table
$sql1 = "SELECT DISTINCT product_category FROM products";
$categoriesResult = $conn->query($sql1);

$categories = [];
while ($row = $categoriesResult->fetch_assoc()) {
  $categories[] = $row;
}

// Setting up the product filters
$whereClauses = [];
$params = [];
$paramTypes = '';

// Filtering by selected categories
if (isset($_GET['categories']) && is_array($_GET['categories'])) {
  $placeholders = implode(',', array_fill(0, count($_GET['categories']), '?'));
  $whereClauses[] = "product_category IN ($placeholders)";
  $params = array_merge($params, $_GET['categories']);
  $paramTypes .= str_repeat('s', count($_GET['categories']));
}

// Filtering by price range
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
  $whereClauses[] = "product_price >= ?";
  $params[] = $_GET['min_price'];
  $paramTypes .= 'd';
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
  $whereClauses[] = "product_price <= ?";
  $params[] = $_GET['max_price'];
  $paramTypes .= 'd';
}

// Construct the SQL for products
$sql = "SELECT * FROM products";
if (!empty($whereClauses)) {
  $sql .= " WHERE " . implode(' AND ', $whereClauses);
}

$stmt = $conn->prepare($sql);
if (count($params) > 0) {
  $stmt->bind_param($paramTypes, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <title>Home</title>
  <style>
    .pagination a {
      color: coral;
    }

    .pagination li:hover a {
      color: white;
      background-color: coral;
    }
    .out-of-stock {
      color: red;
      font-weight: bold;
    }

    .buy-button[disabled] {
      background-color: #ccc;
      /* Gray */
      cursor: not-allowed;
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
    <div class="container">
      <img class="logo" src="assets/imgs/logo.jpg" />
      <h2 class="brand">0range</h2>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="shop.php">Shop</a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact Us</a>
          </li>

          <li class="nav-item">
            <a href="cart.php"><i class="fa-solid fa-cart-shopping"></i></a>
            <a href="account.php"><i class="fa-solid fa-user"></i></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <!-- Featured -->
  <section id="featured" class="my-5 py-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our Products</h3>
      <hr />
      <p>Here You can Check Out Our Featured Products</p>
    </div>

    <div class="row mx-auto container">

      <!-- Filter Section -->
      <div class="col-lg-3 col-md-4 col-sm-12">
        <h5>Filter Products</h5>

        <form action="" method="GET">
          <h6>Categories</h6>
          <?php foreach ($categories as $category) : ?>
            <input type="checkbox" name="categories[]" value="<?= htmlspecialchars($category['product_category']) ?>" <?= in_array($category['product_category'], $_GET['categories'] ?? []) ? 'checked' : '' ?>> <?= htmlspecialchars($category['product_category']) ?><br>
          <?php endforeach; ?>


          <h6 class="mt-3">Price Range</h6>
          <label for="min_price">Min:</label>
          <input type="number" name="min_price" id="min_price" value="<?= $_GET['min_price'] ?? '' ?>"><br>

          <label for="max_price" class="mt-2">Max:</label>
          <input type="number" name="max_price" id="max_price" value="<?= $_GET['max_price'] ?? '' ?>"><br>

          <input type="submit" value="Filter" class="mt-3">
        </form>
      </div>


      <?php while ($product = $result->fetch_assoc()) : ?>
    <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <?php
        $imagePath = $product['product_image1']; // Access 'product_image1' from $product
        $imageName = basename($imagePath); // Extract the file name from the URL
        ?>
        <img class="img-fluid mb-3" src="assets/imgs/<?= htmlspecialchars($imageName) ?>" />

        <div class="star">
            <?php for ($i = 0; $i < $product['product_rating']; $i++) : ?>
                <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
            <?php for ($i = $product['product_rating']; $i < 5; $i++) : ?>
                <i class="fa-regular fa-star"></i> <!-- Assuming "fa-regular fa-star" is an empty star or another representation of non-filled star -->
            <?php endfor; ?>
        </div>

        <h5 class="p-name"><?= htmlspecialchars($product['product_name']) ?></h5>
        <h4 class="p-price">₹<?= htmlspecialchars($product['product_price']) ?></h4>
         <!-- Check if the product_quantity is 0 -->
         <?php if ($product['product_quantity'] <= 0) : ?>
            <p class="out-of-stock">Out of Stock</p>
            <button class="buy-button" disabled>Buy Now</button>
          <?php else : ?>
            <a href="single_product.php?product_id=<?php echo $product['product_id']; ?>"><button class="buy-button">Buy Now</button></a>
          <?php endif; ?>
    </div>
<?php endwhile; ?>


      <!-- <nav aria-label="Page navigation example">
        <ul class="pagination mt-5">
          <li class="page-item"><a href="#" class="page-link">Previous</a></li>
          <li class="page-item"><a href="#" class="page-link">1</a></li>
          <li class="page-item"><a href="#" class="page-link">2</a></li>
          <li class="page-item"><a href="#" class="page-link">3</a></li>
          <li class="page-item"><a href="#" class="page-link">Next</a></li>
        </ul>
      </nav> -->
    </div>
  </section>




  <!-- Footer -->
  <footer class="mt-5 py-5">
    <div class="row container mx-auto pt-5">
      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <img class="logo" src="assets/imgs/logo.jpg" alt="" srcset="">
        <p class="pt-3">We Provide the Best Products For Most Affordable Prices</p>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Featured</h5>
        <ul class="text-uppercase">
          <li><a href="#">Men</a></li>
          <li><a href="#">Women</a></li>
          <li><a href="#">Boys</a></li>
          <li><a href="#">Girls</a></li>
          <li><a href="#">New Arrivals</a></li>
          <li><a href="#">Clothes</a></li>
        </ul>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Contact Us</h5>
        <div>
          <h6 class="text-uppercase">Address</h6>
          <p>1234 Street Name, City</p>
        </div>

        <div>
          <h6 class="text-uppercase">Phone Number</h6>
          <p>9999999</p>
        </div>

        <div>
          <h6 class="text-uppercase">Email</h6>
          <p>Email@email.com</p>
        </div>
      </div>

      <div class="footer-one col-lg-3 col-md-6 col-sm-12">
        <h5 class="pb-2">Instagram</h5>
        <div class="row">
          <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2" />
          <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2" />
        </div>
      </div>
    </div>

    <div class="copyright mt-5">
      <div class="row container mx-auto">
        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
          <img src="assets/imgs/payment.jpg" alt="" srcset="">
        </div>

        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
          <p>Ecommerce@2023 Reserved</p>
        </div>

        <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
          <a href="#"><i class="fa-brands fa-facebook"></i></a>
        </div>
      </div>
    </div>
  </footer>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>