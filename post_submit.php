<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    die("User not logged in.");
}

$username = $_SESSION['username'];
$user_query = "SELECT user_id FROM user WHERE name = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_stmt->bind_result($user_id);
$user_stmt->fetch();
$user_stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];

    // Common fields
    $area = $_POST['area'];
    $block = $_POST['block'];
    $road_no = $_POST['road_no'];
    $house_no = $_POST['house_no'];
    $availability = $_POST['availability'];
    $status = $_POST['status'];
    $total_area = $_POST['total_area'];
    $no_of_bedroom = $_POST['no_of_bedroom'];
    $parking = isset($_POST['parking']) ? 1 : 0;
    $rent = $_POST['rent'];
    $washroom = $_POST['washroom'];
    $description = $_POST['description'];

    // Insert into Place
    $place_sql = "INSERT INTO Place (user_id, area, block, road_no, house_no, availability, status, total_area, no_of_bedroom, parking, rent, washroom)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $place_stmt = $conn->prepare($place_sql);
    $place_stmt->bind_param("isssssssdidi", $user_id, $area, $block, $road_no, $house_no, $availability, $status, $total_area, $no_of_bedroom, $parking, $rent, $washroom);
    $place_stmt->execute();
    $place_id = $place_stmt->insert_id;
    $place_stmt->close();

    // Insert into Post
    $post_sql = "INSERT INTO Post (place_id, user_id, post_date, description)
                 VALUES (?, ?, CURDATE(), ?)";
    $post_stmt = $conn->prepare($post_sql);
    $post_stmt->bind_param("iis", $place_id, $user_id, $description);
    $post_stmt->execute();
    $post_stmt->close();

    // Insert category-specific data
    switch ($category) {
        case 'Family':
            $service_charge = $_POST['service_charge'];
            $type = $_POST['type'];
            $family_sql = "INSERT INTO Family (place_id, service_charge, type) VALUES (?, ?, ?)";
            $family_stmt = $conn->prepare($family_sql);
            $family_stmt->bind_param("ids", $place_id, $service_charge, $type);
            $family_stmt->execute();
            $family_stmt->close();
            break;

        case 'Seasonal':
            $cost_per_day = $_POST['cost_per_day'];
            $property_type = $_POST['property_type'];
            $wifi = isset($_POST['wifi']) ? 1 : 0;
            $ac = isset($_POST['ac']) ? 1 : 0;
            $guest_capacity = $_POST['guest_capacity'];
            $seasonal_sql = "INSERT INTO Seasonal (place_id, cost_per_day, property_type, wifi, ac, guest_capacity)
                             VALUES (?, ?, ?, ?, ?, ?)";
            $seasonal_stmt = $conn->prepare($seasonal_sql);
            $seasonal_stmt->bind_param("idsiii", $place_id, $cost_per_day, $property_type, $wifi, $ac, $guest_capacity);
            $seasonal_stmt->execute();
            $seasonal_stmt->close();
            break;

        case 'Shared':
            $no_of_total_room = $_POST['no_of_total_room'];
            $no_of_flatmate = $_POST['no_of_flatmate'];
            $community_type = $_POST['community_type'];
            $room_type = $_POST['room_type'];
            $bathroom = isset($_POST['bathroom']) ? 1 : 0;
            $kitchen = isset($_POST['kitchen']) ? 1 : 0;
            $ac = isset($_POST['ac']) ? 1 : 0;
            $wifi = isset($_POST['wifi']) ? 1 : 0;
            $shared_sql = "INSERT INTO Shared_Living (place_id, no_of_total_room, no_of_flatmate, community_type, room_type, bathroom, kitchen, ac, wifi)
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $shared_stmt = $conn->prepare($shared_sql);
            $shared_stmt->bind_param("iiissiiii", $place_id, $no_of_total_room, $no_of_flatmate, $community_type, $room_type, $bathroom, $kitchen, $ac, $wifi);
            $shared_stmt->execute();
            $shared_stmt->close();
            break;
    }

    // Success message and redirect
    $conn->close();
    echo "<script>
        alert('Post submitted successfully.');
        window.location.href = 'index.php';
    </script>";
    exit();
}
?>
