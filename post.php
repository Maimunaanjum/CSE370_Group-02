<?php
$category = $_GET['category'] ?? ''; // Get category from URL
$allowed = ['family', 'seasonal', 'shared_living'];

if (!in_array($category, $allowed)) {
    die("Invalid category.");
}

include 'db_connect.php'; 

// Handle filtering logic
$whereClause = "WHERE 1=1";  // Default WHERE clause

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // General Filters
    if (!empty($_GET['area'])) {
        $area = $_GET['area'];
        $whereClause .= " AND place.area LIKE '%$area%'";
    }
    if (!empty($_GET['min_rent'])) {
        $minRent = $_GET['min_rent'];
        $whereClause .= " AND place.rent >= $minRent";
    }
    if (!empty($_GET['max_rent'])) {
        $maxRent = $_GET['max_rent'];
        $whereClause .= " AND place.rent <= $maxRent";
    }
    if (!empty($_GET['available_from'])) {
        $availableFrom = $_GET['available_from'];
        $whereClause .= " AND place.availability >= '$availableFrom'";
    }

    // Category-specific Filters
    if ($category == 'family') {
        if (!empty($_GET['parking'])) {
            $parking = $_GET['parking'];
            $whereClause .= " AND place.parking = '$parking'";
        }
    } elseif ($category == 'seasonal') {
        if (!empty($_GET['property_type'])) {
            $propertyType = $_GET['property_type'];
            $whereClause .= " AND seasonal.property_type = '$propertyType'";
        }
        if (!empty($_GET['guest_capacity'])) {
            $guestCapacity = $_GET['guest_capacity'];
            $whereClause .= " AND seasonal.guest_capacity >= '$guestCapacity'";
        }
        if (!empty($_GET['ac'])) {
            $ac = $_GET['ac'];
            $whereClause .= " AND seasonal.ac = '$ac'";
        }
        if (!empty($_GET['wifi'])) {
            $wifi = $_GET['wifi'];
            $whereClause .= " AND seasonal.wifi = '$wifi'";
        }
    } elseif ($category == 'shared_living') {
        if (!empty($_GET['community'])) {
            $community = $_GET['community'];
            $whereClause .= " AND shared_living.community_type = '$community'";
        }
        if (!empty($_GET['room_type'])) {
            $roomType = $_GET['room_type'];
            $whereClause .= " AND shared_living.room_type = '$roomType'";
        }
        if (!empty($_GET['bathroom'])) {
            $bathroom = $_GET['bathroom'];
            $whereClause .= " AND shared_living.bathroom = '$bathroom'";
        }
        if (!empty($_GET['kitchen'])) {
            $kitchen = $_GET['kitchen'];
            $whereClause .= " AND shared_living.kitchen = '$kitchen'";
        }
        if (!empty($_GET['ac'])) {
            $ac = $_GET['ac'];
            $whereClause .= " AND shared_living.ac = '$ac'";
        }
        if (!empty($_GET['wifi'])) {
            $wifi = $_GET['wifi'];
            $whereClause .= " AND shared_living.wifi = '$wifi'";
        }
    }
}

// Dynamically choose the join based on category
$categoryJoin = "";
if ($category === 'family') {
    $categoryJoin = "JOIN family ON place.place_id = family.place_id";
} elseif ($category === 'seasonal') {
    $categoryJoin = "JOIN seasonal ON place.place_id = seasonal.place_id";
} elseif ($category === 'shared_living') {
    $categoryJoin = "JOIN shared_living ON place.place_id = shared_living.place_id";
}

// Prepare the final SQL query with filters
$sql = "
    SELECT post.post_id, post.description, post.post_date,
           place.road_no, place.house_no, place.area, place.block, place.rent
    FROM post
    JOIN place ON post.place_id = place.place_id
    $categoryJoin
    $whereClause
";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo ucfirst($category); ?> Listings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            padding: 20px;
        }
        .search-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .filters {
            text-align: center;
            margin-bottom: 30px;
        }
        .filters select, .filters input {
            padding: 5px;
            margin: 5px;
        }
        .post {
            background: white;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>

<h1><?php echo ucfirst($category); ?> Listings</h1>

<!-- Search Bar -->
<div class="search-bar">
    <form action="" method="GET">
        <input type="hidden" name="category" value="<?php echo $category; ?>">  <!-- Include category as hidden field -->
        <input type="text" name="area" placeholder="Search by area" value="<?php echo $_GET['area'] ?? ''; ?>">
        <input type="number" name="min_rent" placeholder="Min Rent" value="<?php echo $_GET['min_rent'] ?? ''; ?>">
        <input type="number" name="max_rent" placeholder="Max Rent" value="<?php echo $_GET['max_rent'] ?? ''; ?>">
        <input type="date" name="available_from" value="<?php echo $_GET['available_from'] ?? ''; ?>">
        <button type="submit">Apply Filters</button>
    </form>
</div>

<!-- Category-Specific Filters -->
<div class="filters">
    <?php if ($category == 'family'): ?>
        <!-- Family category filter form -->
        <form action="" method="GET">
            <input type="hidden" name="category" value="family">
            <label for="parking">Parking Available</label>
            <select name="parking">
                <option value="">Select</option>
                <option value="1" <?php echo isset($_GET['parking']) && $_GET['parking'] == 1 ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo isset($_GET['parking']) && $_GET['parking'] == 0 ? 'selected' : ''; ?>>No</option>
            </select>
            <button type="submit">Apply Filters</button>
        </form>
    <?php elseif ($category == 'seasonal'): ?>
        <!-- Seasonal category filter form -->
        <form action="" method="GET">
            <input type="hidden" name="category" value="seasonal">
            <!-- Filters for seasonal -->
            <label for="property_type">Property Type</label>
            <select name="property_type">
                <option value="">Select</option>
                <option value="flat" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'flat' ? 'selected' : ''; ?>>Flat</option>
                <option value="bungalow" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'bungalow' ? 'selected' : ''; ?>>Bungalow</option>
                <option value="villa" <?php echo isset($_GET['property_type']) && $_GET['property_type'] == 'villa' ? 'selected' : ''; ?>>Villa</option>
            </select>
            <label for="guest_capacity">Guest Capacity</label>
            <input type="number" name="guest_capacity" value="<?php echo $_GET['guest_capacity'] ?? ''; ?>" placeholder="Guest Capacity">
            <label for="ac">AC</label>
            <select name="ac">
                <option value="">Select</option>
                <option value="1" <?php echo isset($_GET['ac']) && $_GET['ac'] == 1 ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo isset($_GET['ac']) && $_GET['ac'] == 0 ? 'selected' : ''; ?>>No</option>
            </select>
            <label for="wifi">Wi-Fi</label>
            <select name="wifi">
                <option value="">Select</option>
                <option value="1" <?php echo isset($_GET['wifi']) && $_GET['wifi'] == 1 ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo isset($_GET['wifi']) && $_GET['wifi'] == 0 ? 'selected' : ''; ?>>No</option>
            </select>
            <button type="submit">Apply Filters</button>
        </form>
    <?php endif; ?>
</div>

<!-- Display Listings -->
<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="post">
            <p><strong>Post ID:</strong> <?php echo $row['post_id']; ?></p>
            <p><strong>Date:</strong> <?php echo $row['post_date']; ?></p>
            <p><strong>Address:</strong> 
                House <?php echo htmlspecialchars($row['house_no']); ?>, 
                Road <?php echo htmlspecialchars($row['road_no']); ?>, 
                Block <?php echo htmlspecialchars($row['block']); ?>, 
                Area <?php echo htmlspecialchars($row['area']); ?>
            </p>
            <p><strong>Rent:</strong> <?php echo $row['rent']; ?> BDT</p>
            <p><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No results found for the selected filters.</p>
<?php endif; ?>

</body>
</html>
