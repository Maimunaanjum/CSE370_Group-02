<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}

include 'db_connect.php';

// Get receiver ID and message from form
$receiver_id = isset($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : 0;
$message = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : '';

// Validate inputs
if ($receiver_id <= 0 || empty($message)) {
    header('Location: chat.php?error=Invalid input');
    exit;
}

// Insert message into the Chat table
$stmt = $conn->prepare("INSERT INTO Chat (sender_id, receiver_id, chat) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $_SESSION['user_id'], $receiver_id, $message);
$stmt->execute();
$stmt->close();

// Redirect back to the chat page
header("Location: chat.php?receiver_id=$receiver_id");
exit;
?>
