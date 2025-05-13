<?php
session_start();
include('db_connect.php'); 

// Build the query
$sql = "SELECT * FROM Post p 
        JOIN Place pl ON p.place_id = pl.place_id 
        WHERE 1";

// Filters
if (!empty($_GET['area'])) {
    $area = $_GET['area'];
    $sql .= " AND pl.area LIKE '%$area%'";
}

if (!empty($_GET['min_price'])) {
    $min = $_GET['min_price'];
    $sql .= " AND pl.rent >= $min";
}

if (!empty($_GET['max_price'])) {
    $max = $_GET['max_price'];
    $sql .= " AND pl.rent <= $max";
}

if (!empty($_GET['bedrooms'])) {
    $bed = $_GET['bedrooms'];
    $sql .= " AND pl.no_of_bedroom = $bed";
}

if (!empty($_GET['sort_by'])) {
    if ($_GET['sort_by'] == 'low') {
        $sql .= " ORDER BY pl.rent ASC";
    } elseif ($_GET['sort_by'] == 'high') {
        $sql .= " ORDER BY pl.rent DESC";
    } elseif ($_GET['sort_by'] == 'newest') {
        $sql .= " ORDER BY p.post_date DESC";
    }
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home Listings</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
    }
    header {
      background-color: #35424a;
      color: white;
      padding: 1rem 2rem;
      text-align: center;
    }
    .search-bar {
      display: flex;
      justify-content: center;
      margin: 1.5rem;
    }
    .search-bar form {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .search-bar input, .search-bar select, .search-bar button {
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
    }
    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      padding: 2rem;
    }
    .card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    .card:hover {
      transform: scale(1.02);
    }
    .card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      cursor: pointer;
    }
    .card-details {
      padding: 1rem;
    }
    .location {
      color: #777;
      font-size: 0.9rem;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.8);
    }
    .modal-content {
      margin: 5% auto;
      display: block;
      max-width: 80%;
      border-radius: 10px;
    }
    .close {
      position: absolute;
      top: 20px; right: 35px;
      color: white;
      font-size: 40px;
      font-weight: bold;
      cursor: pointer;
    }
    @media (max-width: 600px) {
      .modal-content { max-width: 95%; }
    }
  </style>
</head>
<body>

<header>
  <h1>Find Your Dream Home</h1>
</header>

<div class="search-bar">
  <form method="GET" action="">
    <input type="text" name="area" placeholder="Area" value="<?= $_GET['area'] ?? '' ?>">
    <input type="number" name="min_price" placeholder="Min Price" value="<?= $_GET['min_price'] ?? '' ?>">
    <input type="number" name="max_price" placeholder="Max Price" value="<?= $_GET['max_price'] ?? '' ?>">
    <input type="number" name="bedrooms" placeholder="Bedrooms" value="<?= $_GET['bedrooms'] ?? '' ?>">
    <select name="sort_by">
      <option value="">Sort By</option>
      <option value="low" <?= (($_GET['sort_by'] ?? '') == 'low') ? 'selected' : '' ?>>Price: Low to High</option>
      <option value="high" <?= (($_GET['sort_by'] ?? '') == 'high') ? 'selected' : '' ?>>Price: High to Low</option>
      <option value="newest" <?= (($_GET['sort_by'] ?? '') == 'newest') ? 'selected' : '' ?>>Newest First</option>
    </select>
    <button type="submit">Search</button>
  </form>
</div>

<div class="gallery" id="gallery">
  <!-- Static Example Cards -->
  <!-- <div class="card">
    <img src="home2.jpg" onclick="openModal(this.src)">
    <div class="card-details">
      <h3>Modern Living Room</h3>
      <p class="location">New York, NY</p>
    </div>
  </div>
  <div class="card">
    <img src="home1.jpg" onclick="openModal(this.src)">
    <div class="card-details">
      <h3>Luxury Bedroom</h3>
      <p class="location">Los Angeles, CA</p>
    </div>
  </div>
  <div class="card">
    <img src="home3.jpg" onclick="openModal(this.src)">
    <div class="card-details">
      <h3>Stylish Kitchen</h3>
      <p class="location">Chicago, IL</p>
    </div>
  </div>
  <div class="card">
    <img src="home4.jpg" onclick="openModal(this.src)">
    <div class="card-details">
      <h3>Cozy Studio</h3>
      <p class="location">Miami, FL</p>
    </div>
  </div> -->

  <!-- Dynamic Listings -->
  <?php while ($row = $result->fetch_assoc()): ?>
    <div class="card">
      <img src="<?= $row['house_no'] ?>.jpg" alt="Home Image" onclick="openModal(this.src)">
      <div class="card-details">
        <h3><?= htmlspecialchars($row['description']) ?></h3>
        <p class="location"><?= $row['area'] ?>, Block <?= $row['block'] ?>, Road <?= $row['road_no'] ?></p>
        <p>$<?= number_format($row['rent'], 2) ?>/month · <?= $row['no_of_bedroom'] ?> Bed · <?= $row['washroom'] ?> Bath</p>

        <?php if (isset($_SESSION['user_id'])): ?>
          <form method="POST" action="add_to_wishlist.php" style="margin-top: 10px;">
            <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
            <button type="submit" name="save" style="background-color:#28a745; color:white; border:none; padding:8px 12px; border-radius:5px; cursor:pointer;">
              Add to Wishlist
            </button>
          </form>
        <?php endif; ?>

      </div>
    </div>
  <?php endwhile; ?>
</div>

<!-- Modal -->
<div id="imgModal" class="modal" onclick="closeModal()">
  <span class="close" onclick="closeModal()">&times;</span>
  <img class="modal-content" id="modalImg">
</div>

<script>
  function openModal(src) {
    document.getElementById('imgModal').style.display = "block";
    document.getElementById('modalImg').src = src;
  }
  function closeModal() {
    document.getElementById('imgModal').style.display = "none";
  }
</script>

</body>
</html>

<?php $conn->close(); ?>



