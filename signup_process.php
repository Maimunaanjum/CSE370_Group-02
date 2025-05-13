<?php
include 'db_connect.php';


// Get POST data and sanitize
$name         = trim($_POST['name']);
$phone        = trim($_POST['phone']);
$gender       = trim($_POST['gender']);
$age          = (int)$_POST['age'];
$nationality  = trim($_POST['nationality']);
$nid          = trim($_POST['nid']);
$email        = trim($_POST['email']);
$password     = $_POST['password'];

// Basic validation
if (empty($name) || empty($phone) || empty($gender) || empty($age) ||
    empty($nationality) || empty($nid) || empty($email) || empty($password)) {
    die("All fields are required.");
}

// Check if National ID already exists


$stmt = $conn->prepare("SELECT national_id FROM User WHERE national_id = ?");
$stmt->bind_param("s", $nid);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Redirect to the signup page with an error message if the National ID is already taken
    header("Location: signup.php?error=User+already+exists");
    exit();
}
$stmt->close();

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$stmt = $conn->prepare("INSERT INTO User (name, national_id, gender, age, nationality, email, phone_number, password)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssissss", $name, $nid, $gender, $age, $nationality, $email, $phone, $hashed_password);


if ($stmt->execute()) {
    // Redirect to login.html after successful signup
    header("Location: login.html");
    exit(); // Ensure no further code is executed after the redirect
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
