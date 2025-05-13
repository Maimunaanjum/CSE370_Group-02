<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save']) && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $date = date("Y-m-d");

    // Prevent duplicate wishlist entries
    $check = $conn->prepare("SELECT * FROM Wishlist WHERE user_id = ? AND post_id = ?");
    $check->bind_param("ii", $user_id, $post_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO Wishlist (user_id, post_id, date) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $post_id, $date);
        $stmt->execute();
        $stmt->close();
    }

    $check->close();
}

// Redirect back to the page the user came from
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>
