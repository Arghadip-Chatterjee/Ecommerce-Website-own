<?php
session_start();

// Check if totalAmount is set as a URL parameter
$totalAmount = isset($_GET['totalAmount']) ? $_GET['totalAmount'] : '1.00';
$status = isset($_GET['order_status']) ? $_GET['order_status'] : 'None';
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

      <!-- payment -->
      <section class="my-5 py-5">
        <div class="container text-center mt-3 pt-5">
            <h2 class="form-weight-bold">Payment</h2>
            <hr class="mx-auto"/>
        </div>

        <div class="mx-auto container text-center">
            <p>Payment Status: <?php echo $status ;?></p>
            <p>Total Payment: $<?php echo $totalAmount ;?></p>
            <input type="button" class="btn btn-primary" value="Pay Now" <?php echo ($totalAmount == '0.00' || $totalAmount <= 0) ? 'disabled' : ''; ?> onclick="payWithRazorpay()"/>
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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
    function payWithRazorpay() {
    var totalAmount = parseFloat("<?php echo $totalAmount; ?>");
    if (totalAmount <= 0) {
        alert('Cannot proceed with zero amount.');
        return;
    }

    // Fetch orderID from backend
    fetch('generate_order.php?totalAmount=<?php echo $totalAmount; ?>').then(response => response.text()).then((orderID) => {
        var options = {
            "key": "rzp_test_FEgBhdjL8oG1YB",
            "amount": totalAmount * 100,
            "currency": "INR",
            "name": "ECommerce",
            "description": "Test Transaction",
            "order_id": orderID,
            "handler": function (response) {
                // Handle successful payment here
                fetch('update_quantities.php').then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        // Redirect to the desired page after successfully updating quantities
                        window.location.href="index.php";
                    } else {
                        alert(data.message);
                    }
                });
            },
            "theme": {
                "color": "#F37254"
            }
        };
        
        var rzp1 = new Razorpay(options);
        rzp1.open();
    });
}
</script>
</body>
</html>