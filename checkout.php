<?php

  session_start();
  // Check if totalAmount is set as a URL parameter
  $totalAmount = isset($_GET['totalAmount']) ? $_GET['totalAmount'] : '0.00';

  if(!empty($_SESSION['cart']) && isset($_POST['checkout'])){

  }
  else{
    header("location:index.php");
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css"/>
    <title>Home</title>
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

      <!-- checkout -->
      <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Checkout</h2>
            <hr class="mx-auto"/>
        </div>

        <div class="mx-auto container">
            <form action="server/place_order.php?cost=<?php echo $totalAmount; ?>" id="checkout-form" method="post">
                <div class="form-group checkout-small-element">
                    <label for="">Name</label>
                    <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required>
                </div>

                <div class="form-group checkout-small-element">
                    <label for="">Email</label>
                    <input type="text" class="form-control" id="checkout-email" name="email" placeholder="email" required>
                </div>

                <div class="form-group checkout-small-element">
                    <label for="">Phone</label>
                    <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="phone" required>
                </div>

                <div class="form-group checkout-small-element">
                    <label for="">City</label>
                    <input type="text" class="form-control" id="register-city" name="city" placeholder="city" required>
                </div>

                <div class="form-group checkout-large-element">
                    <label for="">Address</label>
                    <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required>
                </div>

                <div class="form-group checkout-btn-container">
                <input type="submit" class="btn" id="checkout-btn" value="Place Order - ₹<?php echo $totalAmount; ?>" name="place_order" />
                </div>
            </form>
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
              <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2"/>
              <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2"/>
              <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2"/>
              <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2"/>
              <img src="assets/imgs/featured1.jpg" alt="" srcset="" class="img-fluid w-25 h-100 m-2"/>
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