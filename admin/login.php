<?php
session_start();

include('config.php');

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $submittedPassword = $_POST['password']; // don't escape here; we'll not put it into SQL directly

    $query = "SELECT user_id, user_password, user_email FROM users WHERE user_email = '$username'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['user_password'];
        $userEmail = $row['user_email'];

        // Verify submitted password against stored hashed password
        if (password_verify($submittedPassword, $hashedPassword)) {
            $_SESSION['login_user'] = $username;

            // Check if the email is 'admin@gmail.com' and set a session variable
            if ($userEmail === 'admin@gmail.com') {
                $_SESSION['admin_logged_in'] = true;
            } else {
                $error = "Only admin can log in.";
            }

            header("location: index.php");
            exit;
        } else {
            $error = "Your login name or password is invalid";
        }
    } else {
        $error = "Your login name or password is invalid";
    }
}
?>




<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>

    <div class="container mt-5">
        <div class="col-lg-5 mx-auto">
            <h2>Admin Login</h2>
            <form action="" method="post">
                <div class="form-group">
                    <label>User email:</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Login">
                </div>
            </form>
            <span><?php echo $error; ?></span>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>