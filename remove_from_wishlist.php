<?php
session_start();
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = (int)$_POST['post_id'];

    $stmt = $conn->prepare("DELETE FROM Wishlist WHERE user_id = ? AND post_id = ?");
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $stmt->close();
}

header("Location: wishlist.php");
exit;
