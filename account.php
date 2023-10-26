<?php

session_start();
if (!isset($_SESSION['logged_in'])) {
  header("location:login.php");
  exit;
}

if (isset($_GET['logout'])) {
  if (isset($_SESSION['logged_in'])) {
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    header("location:login.php?logout=You logged out successfully");
    exit;
  }
}

include("server/connection.php");

$message = ""; // For feedback to user

if (isset($_POST['change_password'])) {
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];

  // Check if password and confirm password match
  if ($password === $confirmPassword) {
    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Assuming you have the email stored in session after login
    $email = $_SESSION['user_email'];

    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
    $stmt->bind_param('ss', $passwordHash, $email);

    if ($stmt->execute()) {
      $message = "Password updated successfully!";
    } else {
      $message = "Error updating password!";
    }
  } else {
    $message = "Passwords do not match!";
  }
}

// Assuming you store the logged in user's ID in a session after they log in
$user_id = $_SESSION['user_id'];

// Fetch all orders and their respective order items for the logged in user
$sql = "SELECT 
orders.order_id,
orders.order_cost,
orders.order_status,
orders.order_date,
order_items.product_name,
order_items.product_image
FROM 
orders 
JOIN 
order_items ON orders.order_id = order_items.order_id 
WHERE 
orders.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);
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
    .left-align {
      text-align: left;
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

  <!-- Account -->
  <section class="my-5 py-5">
    <div class="row container mx-auto">
      <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
        <h3 class="font-weight-bold">Account Info</h3>
        <hr class="mx-auto w-25" />
        <div class="account-info">
          <p>Name <span><?php if (isset($_SESSION['user_name'])) {
                          echo $_SESSION['user_name'];
                        } ?></span></p>
          <p>Email <span><?php if (isset($_SESSION['user_email'])) {
                            echo $_SESSION['user_email'];
                          } ?></span></p>
          <p><a href="#orders" id="order-btn">Orders</a></p>
          <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
        </div>
      </div>

      <div class="col-lg-6 col-md-12 col-sm-12">
        <?php
        if (!empty($message)) {
          echo '<div class="alert">' . $message . '</div>'; // Display messages
        }
        ?>
        <form action="account.php" id="account-form" method="post">
          <h3>Change Password</h3>
          <hr class="mx-auto w-25" />
          <div class="form-group">
            <label for="">Password</label>
            <input type="password" class="form-control" id="account-password" placeholder="password" name="password" required>
          </div>

          <div class="form-group">
            <label for="">Confirm Password</label>
            <input type="password" class="form-control" id="account-password-confirm" placeholder="Confirm password" name="confirmPassword" required>
          </div>

          <div class="form-group">
            <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- Orders -->
  <section class="orders container my-2 py-3" id="orders">
    <div class="container mt-3">
      <h2 class="font-weight-bolde text-center">Your Orders</h2>
      <hr class="mx-auto w-25" />
    </div>

    <table class="mt-5 pt-5">
      <tr>
        <th>Product</th>
        <th class="left-align">Date</th>
        <th>Order id</th>
        <th>Order Cost</th>
        <th>Order Status</th>

      </tr>

      <?php foreach ($orders as $order) : ?>
        <tr>
          <td>
            <div class="product-info">
              <?php
              $imagePath = $order['product_image']; // Assuming 'product_image1' contains the full URL
              $imageName = basename($imagePath); // Extract the file name from the URL
              ?>
              <img src="assets/imgs/<?php echo $imageName; ?>" alt="<?= htmlspecialchars($order['product_name']) ?>">
              <div>
                <p class="mt-3"><?= htmlspecialchars($order['product_name']) ?></p>
              </div>
            </div>
          </td>
          <td>
            <span><?= htmlspecialchars($order['order_date']) ?></span>
          </td>
          <td>
            <span><?= htmlspecialchars($order['order_id']) ?></span>
          </td>
          <td>
            <span><?= htmlspecialchars($order['order_cost']) ?></span>
          </td>
          <td>
            <span><?= htmlspecialchars($order['order_status']) ?></span>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
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