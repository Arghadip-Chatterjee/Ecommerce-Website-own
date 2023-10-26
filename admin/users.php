<?php
include('config.php');

$users = [];
$query = "SELECT * FROM users"; // Assuming your table is named users
$result = $conn->query($query);
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<h2>Users</h2>
<table class="table table-striped">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Date Joined</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo $user['user_id']; ?></td>
            <td><?php echo $user['user_name']; ?></td> 
            <td><?php echo $user['user_email']; ?></td>
            <td><?php echo $user['created_at']; ?></td> <!-- Adjust this as per your table structure -->
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
