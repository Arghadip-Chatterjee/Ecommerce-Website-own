<?php

    session_start();

    include("connection.php");
    if(isset($_POST['place_order'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $city = $_POST['city'];
        $address = $_POST['address'];
        $order_cost = $_GET['cost'];
        $order_status = "on_hold";
        $user_id = $_SESSION['user_id'];
        if (!isset($_SESSION['user_id'])) {
            die("User not logged in or session expired.");
        }
        
        $order_date = date('Y-m-d H:i:s');

        // Store the prepared statement in a variable
        $stmt = $conn->prepare("INSERT INTO orders(order_cost, order_status, user_id, user_phone, user_city, user_address, order_date) VALUES (?,?,?,?,?,?,?)");

        // Check if the preparation was successful
        if ($stmt === false) {
            die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        $stmt->bind_param('isiisss', $order_cost,$order_status,$user_id,$phone,$city,$address,$order_date);

        // Execute the statement and check for errors
        if ($stmt->execute() === false) {
            die('execute() failed: ' . htmlspecialchars($stmt->error));
        }

        $order_id = $stmt->insert_id;

        foreach($_SESSION['cart'] as $key => $value){
            $product = $_SESSION['cart'][$key];
            $product_id = $product['product_id'];
            $product_name = $product['product_name'];
            $product_image = $product['product_image1'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];

            // Store product_id in SESSION array
            $_SESSION['product_ids'][] = $product_id;
            $_SESSION['product_quantities'][] = $product_quantity;

            $stmt1 = $conn->prepare("INSERT INTO order_items(order_id, product_id, product_name, product_image,product_price,product_quantity,user_id,order_date) VALUES (?,?,?,?,?,?,?,?)");

            $stmt1->bind_param('iissiiis',$order_id,$product_id,$product_name,$product_image,$product_price,$product_quantity,$user_id,$order_date);

            $stmt1->execute();

        }
        header("location:../payment.php?totalAmount={$order_cost}&order_status=order%20placed%20successfully");
    }

?>