<?php
session_start();
include("server/connection.php");
if (isset($_POST['add_to_cart'])) {

  if (isset($_SESSION['cart'])) {
    $products_array_ids = array_column($_SESSION['cart'], "product_id");
    if (!in_array($_POST['product_id'], $products_array_ids)) {

      $product_id = $_POST['product_id'];

      $product_array = array(
        'product_id' => $_POST['product_id'],
        'product_name' => $_POST['product_name'],
        'product_price' => $_POST['product_price'],
        'product_image1' => $_POST['product_image1'],
        'product_quantity' => $_POST['product_quantity'],
      );

      $_SESSION['cart'][$product_id] = $product_array;
    } else {
      echo "<script>alert('Product Already Added to Cart')</script>";
    }
  } else {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image1 = $_POST['product_image1'];
    $product_quantity = $_POST['product_quantity'];

    $product_array = array(
      'product_id' => $product_id,
      'product_name' => $product_name,
      'product_price' => $product_price,
      'product_image1' => $product_image1,
      'product_quantity' => $product_quantity,
    );

    $_SESSION['cart'][$product_id] = $product_array;
  }
} elseif (isset($_POST['remove_product'])) {
  $product_id = $_POST['product_id'];
  if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    // count the items in the cart after removing the product
    $itemsInCart = count($_SESSION['cart']);
    if ($itemsInCart == 0) {
      header('location:index.php');
    } else {
      echo "<script>alert('Product Removed from Cart')</script>";
    }
    // echo json_encode(array('status' => 'success', 'message' => 'Product Removed from Cart', 'itemsInCart' => $itemsInCart));

    exit;
  }
} elseif (isset($_POST['product_id']) && isset($_POST['product_quantity'])) {
  $product_id = $_POST['product_id'];
  $product_quantity = $_POST['product_quantity'];
  if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
    echo json_encode(array('status' => 'success', 'message' => 'Product quantity updated successfully.'));
    exit;
  }
}

$errors = [];

foreach ($_SESSION['cart'] as $product) {
  $desired_quantity = $product['product_quantity'];

  $stmt = $conn->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
  $stmt->bind_param('i', $product['product_id']);
  $stmt->execute();
  $result = $stmt->get_result();
  $product_data = $result->fetch_assoc();
  $actual_quantity_in_db = $product_data['product_quantity'];

  if ($desired_quantity > $actual_quantity_in_db) {
    $errors[] = 'Quantity for product ' . $product['product_name'] . ' exceeds availability.';
  }
}

if (!empty($errors)) {
  foreach ($errors as $error) {
    echo $error . "<br>";
  }
  // Prevent checkout and handle the situation appropriately
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


  <!-- Cart -->
  <section class="cart container my-5 py-5">
    <div class="container mt-5">
      <h2 class="font-weight-bolde">Your Cart</h2>
      <hr />
    </div>

    <table class="mt-5 pt-5">
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Subtotal</th>
      </tr>

      <?php
      if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
          // Fetch available quantity from database for this product
          $stmt = $conn->prepare("SELECT product_quantity FROM products WHERE product_id = ?");
          $stmt->bind_param('i', $value['product_id']);
          $stmt->execute();
          $result = $stmt->get_result();
          $product_data = $result->fetch_assoc();
          $max_product_quantity = $product_data['product_quantity'];
      ?>

          <tr>
            <td>
              <div class="product-info">
              <?php
          $imagePath = $value['product_image1']; // Assuming 'product_image1' contains the full URL
          $imageName = basename($imagePath); // Extract the file name from the URL
          ?>
          <img class="img-fluid mb-3" src="assets/imgs/<?php echo $imageName; ?>" />
                <div>
                  <p><?php echo $value['product_name']; ?></p>
                  <small><span>₹</span><?php echo $value['product_price']; ?></small>
                  <br>
                  <form action="cart.php" method="post" class="remove-form">
                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                    <input type="submit" name="remove_product" class="remove-btn" value="remove" />
                  </form>

                </div>
              </div>
            </td>
            <td>
              <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>" data-price="<?php echo $value['product_price']; ?>" max="<?php echo $max_product_quantity; ?>" onchange="updateQuantity(<?php echo $value['product_id']; ?>, this.value)" />
            </td>

            <td>
              <span>₹</span>
              <!-- Store original price for calculation -->
              <span id="original-price-<?php echo $value['product_id']; ?>" style="display: none;">
                <?php echo $value['product_price']; ?>
              </span>
              <span id="product-price-<?php echo $value['product_id']; ?>" class="product-price">
                <?php echo $value['product_price'] * $value['product_quantity']; ?>
              </span>
            </td>



          </tr>
      <?php
        }
      } else {
        echo "<tr><td colspan='3'>No items in cart</td></tr>";
      }
      ?>

    </table>

    <div class="cart-total">
      <table>
        <tr>
          <td>SubTotal</td>
          <td id="subTotal">₹0.00</td>
        </tr>
        <tr>
          <td>Total</td>
          <td id="total">₹0.00</td>
        </tr>
      </table>
    </div>


    <div class="checkout-container">
      <?php
      // Assuming you set $_SESSION['user_id'] or some other session variable when the user logs in
      if (isset($_SESSION['logged_in'])) {
      ?>
        <form action="checkout.php" method="post" id="checkoutForm" onsubmit="return appendTotalAmount();">
          <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout" />
        </form>
      <?php
      } else {
        echo "<a href='login.php' class='btn btn-primary'>Login to Proceed with Checkout</a>";

      }
      ?>
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
  <script>
    function updateQuantity(productId, quantity) {
      // Fetch the original product price
      var originalPrice = parseFloat(document.getElementById('original-price-' + productId).textContent);

      // Calculate the new price based on quantity
      var newPrice = originalPrice * quantity;

      // Update the displayed price
      document.getElementById('product-price-' + productId).textContent = newPrice.toFixed(2);

      // Update the cart on the server
      fetch('cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `product_id=${productId}&product_quantity=${quantity}`
      });

      // Calculate the new subtotal and total
      var allPrices = document.querySelectorAll('.product-price');
      var subTotal = 0;
      allPrices.forEach(function(priceElem) {
        subTotal += parseFloat(priceElem.textContent);
      });

      document.getElementById('subTotal').textContent = '₹' + subTotal.toFixed(2);
      document.getElementById('total').textContent = '₹' + subTotal.toFixed(2); // assuming no taxes and fees
    }
    window.onload = function() {
      // Handle the remove product form submission
      document.querySelectorAll('.remove-form').forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          fetch('cart.php', {
              method: 'POST',
              body: new FormData(form),
            })
            .then(response => response.json())
            .then(data => {
              if (data.status === 'success') {
                // Remove the corresponding row from the cart
                form.closest('tr').remove();
                // Call updateSubTotalAndTotal function to update the SubTotal and Total
                updateSubTotalAndTotal();
                // Alert the user
                alert('Product Removed Successfully');
                // If no items left in the cart, reload the page
                if (data.itemsInCart === 0) {
                  window.location.href = "index.php";
                }
              } else {
                alert(data.message);
              }
            });
        });
      });
    }


    function updateSubTotalAndTotal() {
      var allPrices = document.querySelectorAll('.product-price');
      var subTotal = 0;
      allPrices.forEach(function(priceElem) {
        subTotal += parseFloat(priceElem.textContent);
      });

      document.getElementById('subTotal').textContent = '₹' + subTotal.toFixed(2);
      document.getElementById('total').textContent = '₹' + subTotal.toFixed(2); // assuming no taxes and fees

      // If no items left in the cart, display the message
      if (allPrices.length === 0) {
        document.querySelector('table').innerHTML = "<tr><td colspan='3'>No items in cart</td></tr>";
      }
    }

    // Call the function on page load to ensure the subtotal and total are calculated
    window.onload = updateSubTotalAndTotal;

    function appendTotalAmount() {
      var form = document.getElementById('checkoutForm');
      var totalAmount = document.getElementById('total').textContent; // assuming the total amount is in an element with id 'total'
      totalAmount = totalAmount.replace('₹', ''); // remove the currency symbol
      form.action = 'checkout.php?totalAmount=' + encodeURIComponent(totalAmount);
      return true; // proceed with the form submission
    }
  </script>



</body>

</html>