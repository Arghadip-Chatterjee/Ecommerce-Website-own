<?php
include('server/connection.php');

$errorMessage = '';
$successMessage = '';
$emailExists = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (!isset($_POST['new_password']) && !isset($_POST['confirm_password'])) {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $emailExists = true;
        } else {
            $errorMessage = 'Email not found.';
        }
    } elseif (isset($_POST['new_password'], $_POST['confirm_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the user's password in the database
            $stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
            $stmt->bind_param("ss", $hashed_password, $email);
            $stmt->execute();
            $stmt->close();

            $successMessage = 'Password successfully changed!';
            header('login.php');
        } else {
            $errorMessage = 'Passwords do not match.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        
        <?php
        if (!empty($errorMessage)) {
            echo '<p style="color:red;">' . $errorMessage . '</p>';
        } elseif (!empty($successMessage)) {
            echo '<p style="color:green;">' . $successMessage . '</p>';
        }

        if (isset($_POST['email'])) {
            if ($emailExists) {
                // Display password change form
                echo '
                <form action="forgot_password.php" method="post">
                    <input type="hidden" name="email" value="' . $email . '">
                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" id="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" name="confirm_password" id="confirm_password" required>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Change Password">
                    </div>
                </form>';
            }
        } else {
            // Display email verification form
            echo '
            <form action="forgot_password.php" method="post">
                <div class="form-group">
                    <label for="email">Enter your email address:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Verify Email">
                </div>
            </form>';
        }
        ?>
    </div>
</body>
</html>
