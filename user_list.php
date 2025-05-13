<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

// Fetch a list of users to chat with (excluding the logged-in user)
$currentUserId = $_SESSION['user_id'];
$result = $conn->query("SELECT user_id, name FROM user WHERE user_id != $currentUserId");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select a User to Chat With</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        h1 { text-align: center; }
        .user-list { max-width: 600px; margin: 0 auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .user-list a { display: block; padding: 12px; background: #3498db; color: white; text-decoration: none; margin: 8px 0; border-radius: 4px; }
        .user-list a:hover { background: #2980b9; }
    </style>
</head>
<body>
    <h1>Select a User to Chat With</h1>
    <div class="user-list">
        <?php
        while ($user = $result->fetch_assoc()) {
            echo "<a href='chat.php?receiver_id={$user['user_id']}'>Chat with {$user['name']}</a>";
        }
        ?>
    </div>
</body>
</html>
