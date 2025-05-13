<?php
session_start();

include 'db_connect.php';

$user_id = $_SESSION['user_id'];

// Get and sanitize inputs
$property = trim($_POST['property']);
$rating = intval($_POST['rating']);
$review_text = trim($_POST['review']);
$date = date('Y-m-d');

// You need to fetch the post_id from the property name/address.
// For this example, we'll just assume post_id = 1
// Ideally, you would query for post_id using the property name:
$post_id = 1; // <-- Replace with actual lookup logic

// Insert review
$stmt = $conn->prepare("INSERT INTO Review (user_id, post_id, date, review) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("iiss", $user_id, $post_id, $date, $review_text);

if ($stmt->execute()) {
    echo "✅ Review submitted successfully!";
} else {
    echo "❌ Error submitting review: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
