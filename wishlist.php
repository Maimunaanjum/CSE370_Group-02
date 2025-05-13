
<?php
session_start();
include('db_connect.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist items
$sql = "SELECT p.*, pl.area, pl.block, pl.road_no, pl.rent, pl.no_of_bedroom, pl.washroom
        FROM Wishlist w
        JOIN Post p ON w.post_id = p.post_id
        JOIN Place pl ON p.place_id = pl.place_id
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Wishlist</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS -->
</head>
<body>
    <h2 style="text-align:center;">My Wishlist</h2>

    <div class="wishlist-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="wishlist-item">
                <img src="<?= $row['house_no'] ?>.jpg" alt="House Image">
                <h3><?= htmlspecialchars($row['description']) ?></h3>
                <p><?= $row['area'] ?>, Block <?= $row['block'] ?>, Road <?= $row['road_no'] ?></p>
                <p>৳<?= number_format($row['rent'], 2) ?>/month · <?= $row['no_of_bedroom'] ?> Bed · <?= $row['washroom'] ?> Bath</p>
                
                <form method="POST" action="remove_from_wishlist.php">
                    <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                    <button type="submit" name="remove">Remove</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
