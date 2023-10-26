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

  <!-- Home -->
  <section id="home">
    <div class="container">
      <h5>NEW ARRIVALS</h5>
      <h1><span>Best Prices</span> This Session</h1>
      <p>EShop Offers the best Products for the most affordable Prices</p>
      <a href="shop.php"><button class="text-uppercase">Shop Now</button></a>
    </div>
  </section>

  <!-- Brands -->
  <section id="brand" class="container">
    <div class="row py-3">
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand1.jpg" />
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand2.webp" />
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand3.png" />
      <img class="img-fluid col-lg-3 col-md-6 col-sm-12" src="assets/imgs/brand4.png" />
    </div>
  </section>

  <!-- New -->
  <section id="new" class="w-100">
    <!-- One -->
    <div class="row p-0 m-0">
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/1.jpg">
        <div class="details">
          <h2>Extremely Awesome Shoes</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>

      <!-- Two -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/2.jpg">
        <div class="details">
          <h2>Awesome Jacket</h2>
          <button class="text-uppercase">Shop Now</button>
        </div>
      </div>

      <!-- Three -->
      <div class="one col-lg-4 col-md-12 col-sm-12 p-0">
        <img class="img-fluid" src="assets/imgs/3.jpg">
        <div class="details">
          <h2>50% OFF Watches</h2>
          <a href="shop.php"><button class="text-uppercase">Shop Now</button></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured -->
  <section id="featured" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our Featured</h3>
      <hr />
      <p>Here You can Check Out Our Featured Products</p>
    </div>

    <?php include("server/get_featured_products.php"); ?>

    <div class="row mx-auto container-fluid">
      <?php while ($row = $featured_products->fetch_assoc()) { ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <?php
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" />
          <div class="star">
            <?php for ($i = 0; $i < $row['product_rating']; $i++) : ?>
              <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
            <?php for ($i = $row['product_rating']; $i < 5; $i++) : ?>
              <i class="fa-regular fa-star"></i>
            <?php endfor; ?>
          </div>
          <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
          <h4 class="p-price">₹ <?php echo $row['product_price']; ?></h4>

          <!-- Check if the product_quantity is 0 -->
          <?php if ($row['product_quantity'] <= 0) : ?>
            <p class="out-of-stock">Out of Stock</p>
            <button class="buy-button" disabled>Buy Now</button>
          <?php else : ?>
            <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-button">Buy Now</button></a>
          <?php endif; ?>

        </div>
      <?php } ?>
    </div>

  </section>

  <!-- Banner -->
  <section id="banner" class="my-5 py-5">
    <div class="container">
      <h4>MID SEASON'S SALE</h4>
      <h1>Autumn collection <br /> Upto 30% OFF</h1>
      <a href="shop.php"><button class="text-uppercase">Shop Now</button></a>
    </div>
  </section>

  <!-- Clothes -->
  <section id="featured" class="my-5">
    <div class="container text-center mt-5 py-5">
      <h3>Dresses and Coats</h3>
      <hr />
      <p>Here You can Check Out Our Clothes</p>
    </div>

    <div class="row mx-auto container-fluid">
      <?php include("server/get_coats.php"); ?>
      <?php while ($row = $coats_products->fetch_assoc()) { ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <?php
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" />
          <div class="star">
            <?php for ($i = 0; $i < $row['product_rating']; $i++) : ?>
              <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
            <?php for ($i = $row['product_rating']; $i < 5; $i++) : ?>
              <i class="fa-regular fa-star"></i>
            <?php endfor; ?>
          </div>
          <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
          <h4 class="p-price">₹ <?php echo $row['product_price']; ?></h4>
          <!-- Check if the product_quantity is 0 -->
          <?php if ($row['product_quantity'] <= 0) : ?>
            <p class="out-of-stock">Out of Stock</p>
            <button class="buy-button" disabled>Buy Now</button>
          <?php else : ?>
            <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-button">Buy Now</button></a>
          <?php endif; ?>
        </div>
      <?php } ?>


    </div>
  </section>

  <!-- Watches -->
  <section id="watches" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Our Watches</h3>
      <hr />
      <p>Here You can Check Out Our Featured Watches</p>
    </div>

    <div class="row mx-auto container-fluid">
      <?php include("server/get_watches.php"); ?>
      <?php while ($row = $watches_products->fetch_assoc()) { ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
          <?php
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" />
          <div class="star">
            <?php for ($i = 0; $i < $row['product_rating']; $i++) : ?>
              <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
            <?php for ($i = $row['product_rating']; $i < 5; $i++) : ?>
              <i class="fa-regular fa-star"></i>
            <?php endfor; ?>
          </div>
          <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
          <h4 class="p-price">₹ <?php echo $row['product_price']; ?></h4>
          <!-- Check if the product_quantity is 0 -->
          <?php if ($row['product_quantity'] <= 0) : ?>
            <p class="out-of-stock">Out of Stock</p>
            <button class="buy-button" disabled>Buy Now</button>
          <?php else : ?>
            <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-button">Buy Now</button></a>
          <?php endif; ?>
        </div>
      <?php } ?>
    </div>
  </section>

  <!-- Shoes -->
  <section id="shoes" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
      <h3>Shoes</h3>
      <hr />
      <p>Here You can Check Out Our Amazing Shoes</p>
    </div>

    <div class="row mx-auto container-fluid">
      <?php include("server/get_shoes.php"); ?>
      <?php while ($row = $shoes_products->fetch_assoc()) { ?>
        <div class="product text-center col-lg-3 col-md-4 col-sm-12">
        <?php
          $imagePath = $row['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" />
          <div class="star">
            <?php for ($i = 0; $i < $row['product_rating']; $i++) : ?>
              <i class="fa-solid fa-star"></i>
            <?php endfor; ?>
            <?php for ($i = $row['product_rating']; $i < 5; $i++) : ?>
              <i class="fa-regular fa-star"></i>
            <?php endfor; ?>
          </div>
          <h5 class="p-name"><?php echo $row['product_name']; ?></h5>
          <h4 class="p-price">₹ <?php echo $row['product_price']; ?></h4>
          <!-- Check if the product_quantity is 0 -->
          <?php if ($row['product_quantity'] <= 0) : ?>
            <p class="out-of-stock">Out of Stock</p>
            <button class="buy-button" disabled>Buy Now</button>
          <?php else : ?>
            <a href="single_product.php?product_id=<?php echo $row['product_id']; ?>"><button class="buy-button">Buy Now</button></a>
          <?php endif; ?>
        </div>
      <?php } ?>
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