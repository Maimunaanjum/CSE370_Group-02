<?php
session_start();
include 'db_connect.php'; 

$query = isset($_GET['query']) ? trim($_GET['query']) : '';

$sql = "
    SELECT Post.post_id, Post.post_date, Post.description, 
           Place.area, Place.block, Place.road_no, Place.house_no, Place.rent
    FROM Post
    INNER JOIN Place ON Post.place_id = Place.place_id
    WHERE Place.area LIKE ?
";

$stmt = $conn->prepare($sql);
$search = "%" . $query . "%";
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; padding: 2em; }
        .card { background: #fff; padding: 1em; margin: 1em 0; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <h1>Search Results for "<?php echo htmlspecialchars($query); ?>"</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="card">
                <h3>Rent: ৳<?php echo htmlspecialchars($row['rent']); ?></h3>
                <p><strong>Area:</strong> <?php echo htmlspecialchars($row['area']); ?>, Block <?php echo htmlspecialchars($row['block']); ?>, Road <?php echo htmlspecialchars($row['road_no']); ?>, House <?php echo htmlspecialchars($row['house_no']); ?></p>
                <p><strong>Posted on:</strong> <?php echo htmlspecialchars($row['post_date']); ?></p>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No listings found in this area.</p>
    <?php endif; ?>

<?php
$stmt->close();
$conn->close();
?>
</body>
</html>
