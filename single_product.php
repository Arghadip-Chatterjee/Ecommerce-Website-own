<?php

include("server/connection.php");

if (isset($_GET['product_id'])) {

  $product_id = $_GET['product_id'];
  $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
  $stmt->bind_param("i", $product_id);

  $stmt->execute();

  $product = $stmt->get_result();
} else {
  header('location:index.php');
}

?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="assets/css/style.css" />
  <title>Single_Product</title>
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
            <a class="nav-link" href="#">Blog</a>
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

  <!-- Single Product -->

  <section class="single-product my-5 pt-5 container">
    <div class="row mt-5">
      <?php while ($row = $product->fetch_assoc()) { ?>

        <div class="col-lg-5 col-md-6 col-sm-12">
          <?php
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" alt="" class="img-fluid w-100 pb-1" id="mainImg">
          <div class="small-img-group">
            <div class="small-img-col">
              <?php
              $imagePath1 = $row['product_image1']; // Assuming 'product_image1' contains the full URL
              $imageName1 = basename($imagePath1); // Extract the file name from the URL
              ?>
              <img src="assets/imgs/<?php echo $imageName1; ?>" alt="" srcset="" width="100%" class="small-img">
            </div>
            <div class="small-img-col">
              <?php
              $imagePath2 = $row['product_image2']; // Assuming 'product_image1' contains the full URL
              $imageName2 = basename($imagePath2); // Extract the file name from the URL
              ?>
              <img src="assets/imgs/<?php echo $imageName2; ?>" alt="" srcset="" width="100%" class="small-img">
            </div>
            <div class="small-img-col">
              <?php
              $imagePath3 = $row['product_image3']; // Assuming 'product_image1' contains the full URL
              $imageName3 = basename($imagePath3); // Extract the file name from the URL
              ?>
              <img src="assets/imgs/<?php echo $imageName3; ?>" alt="" srcset="" width="100%" class="small-img">
            </div>
            <div class="small-img-col">
              <?php
              $imagePath4 = $row['product_image4']; // Assuming 'product_image1' contains the full URL
              $imageName4 = basename($imagePath4); // Extract the file name from the URL
              ?>
              <img src="assets/imgs/<?php echo $imageName4; ?>" alt="" srcset="" width="100%" class="small-img">
            </div>
          </div>
        </div>

        <div class="col-lg-6 col-md-12 col-12">
          <h6>Men/Shoes</h6>
          <h3 class="py-4">Men's <?php echo $row['product_name']; ?></h3>
          <h2>₹ <?php echo $row['product_price']; ?></h2>
          <form action="cart.php" method="post">
            <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
            <input type="hidden" name="product_image1" value="<?php echo $row['product_image1']; ?>" />
            <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>" />
            <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>" />
            <input type="number" name="product_quantity" value="1" />
            <button class="buy-btn" type="submit" name="add_to_cart">Add to cart</button>
          </form>

          <h4 class="mt-5 mb-5">Product Details</h4>
          <span><?php echo $row['product_description']; ?></span>
        </div>

      <?php } ?>
    </div>
  </section>

  <!-- Related Products -->
  <section id="related-products">
  <h3 style="text-align: center; font-weight: bold; text-decoration: underline;">Related Products</h3>
    <?php
    // Assuming you have a $productId variable that contains the product_id of the currently viewed product

    // Retrieve the category of the currently viewed product
    $categoryQuery = "SELECT product_category FROM products WHERE product_id = '$product_id'";
    $categoryResult = $conn->query($categoryQuery);

    if ($categoryResult && $categoryResult->num_rows > 0) {
      $categoryRow = $categoryResult->fetch_assoc();
      $productCategory = $categoryRow['product_category'];

      // Modify your SQL query to fetch related products
      $query = "SELECT * FROM products WHERE product_category = '$productCategory' AND product_id != '$product_id' LIMIT 4";

      $result = $conn->query($query);

      if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          // Your HTML code to display each related product here
          echo '<div class="product text-center col-lg-3 col-md-4 col-sm-12">';
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          echo '<img class="img-fluid mb-3" src="assets/imgs/' . htmlspecialchars($imageName) . '" style="padding-left: 20px;" />';
          echo '<div class="star">';
          for ($i = 0; $i < $row['product_rating']; $i++) {
            echo '<i class="fa-solid fa-star"></i>';
          }
          for ($i = $row['product_rating']; $i < 5; $i++) {
            echo '<i class="fa-regular fa-star"></i>';
          }
          echo '</div>';
          echo '<h5 class="p-name">' . htmlspecialchars($row['product_name']) . '</h5>';
          echo '<h4 class="p-price">₹' . htmlspecialchars($row['product_price']) . '</h4>';
          echo '<button class="buy-button">Buy Now</button>';
          echo '</div>';
        }
      } else {
        echo 'No related products found.';
      }
    } else {
      echo 'Category not found for the currently viewed product.';
    }
    ?>
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
  <script>
    var mainImg = document.getElementById("mainImg");
    var smallImg = document.getElementsByClassName("small-img");

    for (let i = 0; i < 4; i++) {
      smallImg[i].onclick = function() {
        mainImg.src = smallImg[i].src;
      }

    }
  </script>
</body>

</html>