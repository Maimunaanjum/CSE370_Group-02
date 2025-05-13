<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Create a Post</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
    .container { max-width: 800px; margin: 2rem auto; padding: 1rem; background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    h2 { text-align: center; margin-bottom: 2rem; }
    .btn-group { text-align: center; margin-bottom: 2rem; }
    .btn-group button {
      margin: 0 1rem; padding: 0.8rem 1.5rem; font-size: 1rem;
      background: #379683; color: white; border: none; border-radius: 6px;
      cursor: pointer;
    }
    .btn-group button:hover { background-color: #2c7c6c; }
    form { display: none; }
    .form-group { margin-bottom: 1rem; }
    label { display: block; margin-bottom: 0.3rem; }
    input, textarea, select { width: 100%; padding: 0.6rem; border: 1px solid #ccc; border-radius: 6px; }
    button.submit { background-color: #379683; color: white; border: none; padding: 0.7rem 1.5rem; font-size: 1rem; border-radius: 6px; cursor: pointer; margin-top: 1rem; }
  </style>
</head>
<body>

<div class="container">
  <h2>Select Category to Post</h2>
  <div class="btn-group">
    <button onclick="showForm('Family')">Cozy Family Flat</button>
    <button onclick="showForm('Seasonal')">Seasonal Rent</button>
    <button onclick="showForm('Shared')">Shared Living</button>
  </div>

  <form id="postForm" action="post_submit.php" method="POST">
    <input type="hidden" name="category" id="category" value="">

    <div class="form-group"><label>Area</label><input type="text" name="area" required></div>
    <div class="form-group"><label>Block</label><input type="text" name="block" required></div>
    <div class="form-group"><label>Road No</label><input type="text" name="road_no" required></div>
    <div class="form-group"><label>House No</label><input type="text" name="house_no" required></div>
    <div class="form-group"><label>Availability Date</label><input type="date" name="availability" required></div>
    <div class="form-group"><label>Status</label><input type="text" name="status" required></div>
    <div class="form-group"><label>Total Area (sq ft)</label><input type="number" name="total_area" step="0.01" required></div>
    <div class="form-group"><label>Number of Bedrooms</label><input type="number" name="no_of_bedroom" required></div>
    <div class="form-group"><label>Parking</label><input type="checkbox" name="parking"></div>
    <div class="form-group"><label>Rent</label><input type="number" step="0.01" name="rent" required></div>
    <div class="form-group"><label>Number of Washrooms</label><input type="number" name="washroom" required></div>
    <div class="form-group"><label>Description</label><textarea name="description" rows="4" required></textarea></div>

    <!-- FAMILY -->
    <div id="familyFields">
      <div class="form-group"><label>Service Charge</label><input type="number" name="service_charge" step="0.01"></div>
      <div class="form-group"><label>Flat Type</label><input type="text" name="type"></div>
    </div>

    <!-- SEASONAL -->
    <div id="seasonalFields">
      <div class="form-group"><label>Cost Per Day</label><input type="number" step="0.01" name="cost_per_day"></div>
      <div class="form-group"><label>Property Type</label>
        <select name="property_type">
          <option value="">Select</option>
          <option value="flat">Flat</option>
          <option value="bungalow">Bungalow</option>
          <option value="villa">Villa</option>
        </select>
      </div>
      <div class="form-group"><label>WiFi</label><input type="checkbox" name="wifi"></div>
      <div class="form-group"><label>AC</label><input type="checkbox" name="ac"></div>
      <div class="form-group"><label>Guest Capacity</label><input type="number" name="guest_capacity"></div>
    </div>

    <!-- SHARED -->
    <div id="sharedFields">
      <div class="form-group"><label>No. of Total Rooms</label><input type="number" name="no_of_total_room"></div>
      <div class="form-group"><label>No. of Flatmates</label><input type="number" name="no_of_flatmate"></div>
      <div class="form-group"><label>Community Type</label>
        <select name="community_type">
          <option value="">Select</option>
          <option value="student">Student</option>
          <option value="job-holder">Job-holder</option>
          <option value="female">Female</option>
        </select>
      </div>
      <div class="form-group"><label>Room Type</label>
        <select name="room_type">
          <option value="private">Private</option>
          <option value="shared">Shared</option>
          <option value="any">Any</option>
        </select>
      </div>
      <div class="form-group"><label>Washroom</label>
        <select name="bathroom">
          <option value="private">Private</option>
          <option value="shared">Shared</option>
          <option value="any">Any</option>
        </select>
      </div>
      <div class="form-group"><label>Kitchen</label><input type="checkbox" name="kitchen"></div>
      <div class="form-group"><label>AC</label><input type="checkbox" name="ac"></div>
      <div class="form-group"><label>WiFi</label><input type="checkbox" name="wifi"></div>
    </div>

    <button type="submit" class="submit">Submit Post</button>
  </form>
</div>

<script>
  function showForm(category) {
    document.getElementById("postForm").style.display = "block";
    document.getElementById("category").value = category;

    document.getElementById("familyFields").style.display = (category === 'Family') ? 'block' : 'none';
    document.getElementById("seasonalFields").style.display = (category === 'Seasonal') ? 'block' : 'none';
    document.getElementById("sharedFields").style.display = (category === 'Shared') ? 'block' : 'none';
  }
</script>

</body>
</html>
